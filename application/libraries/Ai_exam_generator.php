<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Ai_exam_generator
{
    protected $CI;
    protected $openrouter_api_key;

    public function __construct()
    {
        $this->CI = &get_instance();
        $sch_setting = $this->CI->setting_model->getSetting();
        $this->openrouter_api_key = !empty($sch_setting->ai_openrouter_api_key) ? $sch_setting->ai_openrouter_api_key : (defined('OPENROUTER_API_KEY') ? OPENROUTER_API_KEY : '');
    }

    /**
     * Generate structured CBSE Exam Paper / Sets
     */
    public function generate_paper($params)
    {
        @set_time_limit(60);
        $class_name            = isset($params['class_name']) ? $params['class_name'] : 'Class 10';
        $subject_name          = isset($params['subject_name']) ? $params['subject_name'] : 'Science';
        $chapter               = isset($params['chapter']) ? $params['chapter'] : 'Complete Syllabus';
        $total_marks           = isset($params['total_marks']) ? intval($params['total_marks']) : 80;
        $difficulty            = isset($params['difficulty']) ? $params['difficulty'] : 'Medium';
        $language              = isset($params['language']) ? $params['language'] : 'English';
        $academic_session      = isset($params['academic_session']) ? $params['academic_session'] : '2026-2027';
        $blooms_taxonomy       = isset($params['blooms_taxonomy']) ? $params['blooms_taxonomy'] : null;
        $generate_multi_sets   = !empty($params['generate_multi_sets']) && $params['generate_multi_sets'] == 'yes';
        $question_distribution = isset($params['question_distribution']) ? $params['question_distribution'] : null;
        $custom_api_key        = isset($params['api_key']) ? trim($params['api_key']) : '';

        $active_openrouter_key = !empty($custom_api_key) ? $custom_api_key : $this->openrouter_api_key;

        if (empty($active_openrouter_key)) {
            return ['status' => 'error', 'message' => 'OpenRouter API key is not configured. Please set it in AI Settings.'];
        }

        // Build CBSE Exam Prompt
        $prompt = $this->build_cbse_prompt($class_name, $subject_name, $chapter, $total_marks, $difficulty, $language, $academic_session, $blooms_taxonomy, $question_distribution, $generate_multi_sets);

        // Single provider: OpenRouter stealth/union-alpha
        $response = $this->call_openrouter($prompt, $active_openrouter_key);

        if (!$response || isset($response['error'])) {
            return [
                'status'  => 'error',
                'message' => isset($response['error']) ? $response['error'] : 'Failed to communicate with AI service.'
            ];
        }

        $parsed_data = $this->extract_json($response['raw_text']);
        if (!$parsed_data) {
            return [
                'status'  => 'error',
                'message' => 'Unable to parse AI response into structured CBSE format. Raw preview: ' . substr($response['raw_text'], 0, 200) . '...'
            ];
        }

        return [
            'status' => 'success',
            'data'   => $parsed_data
        ];
    }

    /**
     * Fast Endpoint: Regenerate a Single Question
     */
    public function regenerate_single_question($params)
    {
        $class_name     = isset($params['class_name']) ? $params['class_name'] : 'Class 10';
        $subject_name   = isset($params['subject_name']) ? $params['subject_name'] : 'Science';
        $chapter        = isset($params['chapter']) ? $params['chapter'] : 'General';
        $section_name   = isset($params['section_name']) ? $params['section_name'] : 'Section A';
        $question_type  = isset($params['question_type']) ? $params['question_type'] : 'singlechoice';
        $marks          = isset($params['marks']) ? intval($params['marks']) : 1;
        $difficulty     = isset($params['difficulty']) ? $params['difficulty'] : 'Medium';
        $language       = isset($params['language']) ? $params['language'] : 'English';
        $custom_api_key = isset($params['api_key']) ? trim($params['api_key']) : '';

        $active_openrouter_key = !empty($custom_api_key) ? $custom_api_key : $this->openrouter_api_key;

        $prompt = <<<EOT
You are an expert CBSE paper setter. Generate 1 replacement question for:
- Class: {$class_name} | Subject: {$subject_name} | Chapter: {$chapter}
- Section: {$section_name} | Question Type: {$question_type} | Marks: {$marks}
- Difficulty: {$difficulty} | Language: {$language}

Format LaTeX for Math/Chemistry. Include detailed answer key / explanation.
OUTPUT ONLY 1 VALID JSON OBJECT matching this exact schema with NO markdown code fences:
{
  "question_type": "{$question_type}",
  "marks": {$marks},
  "question_text": "New replacement question text...",
  "options": {
    "A": "Option A",
    "B": "Option B",
    "C": "Option C",
    "D": "Option D"
  },
  "correct_option": "A",
  "or_question_text": "",
  "answer_key": "Step-by-step marking answer key...",
  "explanation": "Detailed explanation..."
}
EOT;

        $response = $this->call_openrouter($prompt, $active_openrouter_key);

        if (!$response || isset($response['error'])) {
            return ['status' => 'error', 'message' => isset($response['error']) ? $response['error'] : 'Failed to regenerate question.'];
        }

        $parsed = $this->extract_json($response['raw_text']);
        if (!$parsed) {
            return ['status' => 'error', 'message' => 'Unable to parse single question replacement.'];
        }

        return ['status' => 'success', 'question' => $parsed];
    }

    /**
     * Check if the selected class is Kindergarten / Pre-Primary
     */
    private function is_preprimary_class($class_name)
    {
        $cl = strtolower(trim($class_name));
        return (
            strpos($cl, 'nursery') !== false ||
            strpos($cl, 'lkg') !== false ||
            strpos($cl, 'ukg') !== false ||
            strpos($cl, 'kg') !== false ||
            strpos($cl, 'prep') !== false ||
            strpos($cl, 'kindergarten') !== false ||
            strpos($cl, 'play') !== false ||
            strpos($cl, 'pre-primary') !== false
        );
    }

    /**
     * Build Prompt with Bloom's Taxonomy, Multi-Sets, and SVG Diagrams
     */
    private function build_cbse_prompt($class_name, $subject_name, $chapter, $total_marks, $difficulty, $language, $academic_session, $blooms_taxonomy, $question_distribution, $generate_multi_sets)
    {
        if ($this->is_preprimary_class($class_name)) {
            return $this->build_preprimary_worksheet_prompt($class_name, $subject_name, $chapter, $total_marks, $difficulty, $language, $academic_session, $generate_multi_sets);
        }

        $distribution_instructions = "";
        if (!empty($question_distribution) && is_array($question_distribution)) {
            $distribution_instructions = "CUSTOM QUESTION TYPE BREAKDOWN REQUESTED BY TEACHER:\n";
            if (!empty($question_distribution['mcq_count'])) $distribution_instructions .= "- Multiple Choice Questions (MCQ): {$question_distribution['mcq_count']} questions (1 Mark each)\n";
            if (!empty($question_distribution['tf_count'])) $distribution_instructions .= "- True / False Questions: {$question_distribution['tf_count']} questions (1 Mark each)\n";
            if (!empty($question_distribution['fib_count'])) $distribution_instructions .= "- Fill in the Blanks: {$question_distribution['fib_count']} questions (1 Mark each)\n";
            if (!empty($question_distribution['ar_count'])) $distribution_instructions .= "- Assertion & Reasoning: {$question_distribution['ar_count']} questions (1 Mark each)\n";
            if (!empty($question_distribution['sa1_count'])) $distribution_instructions .= "- Very Short Answer (VSA / 2 Marks): {$question_distribution['sa1_count']} questions (2 Marks each)\n";
            if (!empty($question_distribution['sa2_count'])) $distribution_instructions .= "- Short Answer (SA / 3 Marks): {$question_distribution['sa2_count']} questions (3 Marks each)\n";
            if (!empty($question_distribution['la_count'])) $distribution_instructions .= "- Long Answer (LA / 5 Marks): {$question_distribution['la_count']} questions with internal choice 'OR' (5 Marks each)\n";
            if (!empty($question_distribution['case_count'])) $distribution_instructions .= "- Case-Study / Passage-based: {$question_distribution['case_count']} units (4 Marks each with sub-questions)\n";
        }

        $blooms_instructions = "";
        if (!empty($blooms_taxonomy) && is_array($blooms_taxonomy)) {
            $blooms_instructions = "BLOOM'S TAXONOMY COGNITIVE WEIGHTAGE DISTRIBUTION:\n" .
                "- Remembering & Understanding (Recall facts, direct definitions): " . (!empty($blooms_taxonomy['remembering']) ? $blooms_taxonomy['remembering'] : 30) . "%\n" .
                "- Application & Problem Solving (Formula execution, direct solutions): " . (!empty($blooms_taxonomy['applying']) ? $blooms_taxonomy['applying'] : 40) . "%\n" .
                "- Analyzing, Evaluating & HOTS (High Order Thinking, multi-step synthesis): " . (!empty($blooms_taxonomy['hots']) ? $blooms_taxonomy['hots'] : 30) . "%\n";
        }

        $sets_instructions = "";
        if ($generate_multi_sets) {
            $sets_instructions = "MULTI-SET GENERATION (Anti-Cheating Sets A, B, and C):\n" .
                "Generate 3 parallel question sets: 'Set A', 'Set B', and 'Set C'.\n" .
                "Maintain identical difficulty and blueprint, but shuffle question order, randomize MCQ options, and vary numerical values in Math/Science calculations across sets.\n";
        }

        $prompt = <<<EOT
You are an expert CBSE Board paper setter and NCERT curriculum specialist.
Generate an authentic, complete CBSE examination question paper for:
- Class: {$class_name}
- Subject: {$subject_name}
- Topics / Chapters: {$chapter}
- Academic Session: {$academic_session}
- Total Marks: {$total_marks}
- Difficulty Level: {$difficulty}
- Language: {$language}

{$distribution_instructions}
{$blooms_instructions}
{$sets_instructions}

DIAGRAM, MAP, CHEMISTRY, BIOLOGY & GEOMETRY RULES:
1. BIOLOGY & HUMAN ANATOMY:
   - For questions on Heart, Nephron, Digestive System, Brain, Plant Cell, or Stomata: Provide a clear labeled inline SVG diagram in 'diagram_svg' (with labeled parts (A), (B), (C), (D) for students to identify) OR a standard framed student drawing schematic.
2. CHEMISTRY:
   - For Chemical Apparatus (Electrolysis, Gas preparation, Titration, Distillation) or Organic Reaction schemes (Benzene ring, Hydrocarbon bonds, Functional groups): Embed valid SVG diagrams or structured chemical skeletal formulas in LaTeX ($CH_3-CH_2-OH$).
3. PHYSICS:
   - For Electric Circuits (Resistors in series/parallel, Voltmeter, Ammeter, Battery), Ray Optics (Concave/Convex mirrors, Lenses, Refraction through prism), or Magnetic field lines: Provide complete, clean inline SVG vectors in 'diagram_svg'.
4. GEOMETRY & MATHEMATICS:
   - For Circles (tangents, secants, cyclic quadrilaterals), Triangles (congruence, Thales theorem, medians), or Coordinate Graphs: Provide high-contrast vector SVG with angle and length markings ($AB=6\text{ cm}$, $\angle ABC=60^\circ$).
5. GEOGRAPHY / MAP WORK:
   - For Map-based identification questions (e.g. Major Soil types, Ports, Thermal Power plants, Rivers, National Parks): Provide an outline SVG Map with tagged identification pointers (i), (ii), (iii).
6. If a question contains a Data Table, Frequency Distribution, or Values Matrix, format using standard markdown table with newlines:
| Column 1 | Column 2 | Column 3 |
|---|---|---|
| Val 1 | Val 2 | Val 3 |
Do NOT run table pipes and dashes together on one single line.

STRICT JSON OUTPUT FORMAT ONLY:
Output MUST be a single valid JSON object strictly matching this schema with NO markdown code fences (no ```json):
{
  "paper_title": "CBSE {$class_name} {$subject_name} Examination",
  "academic_session": "{$academic_session}",
  "subject": "{$subject_name}",
  "class": "{$class_name}",
  "time_allowed": "{$total_marks} marks duration (e.g. 3 Hours)",
  "max_marks": {$total_marks},
  "is_multi_set": {$this->bool_str($generate_multi_sets)},
  "general_instructions": [
    "This question paper contains sections divided logically by question types.",
    "Section A comprises Objective questions (MCQs, True/False, Fill in blanks, Assertion-Reason) carrying 1 mark each.",
    "Section B comprises Short Answer type questions carrying 2 marks each.",
    "Section C comprises Short Answer type questions carrying 3 marks each.",
    "Section D comprises Long Answer type questions carrying 5 marks each with internal choice.",
    "Section E comprises Case-based integrated units of assessment carrying 4 marks each.",
    "All questions are compulsory. Internal choice is provided in some questions."
  ],
  "sets": {
    "Set A": {
      "sections": [
        {
          "section_name": "SECTION A",
          "description": "Objective Type Questions (1 Mark Each)",
          "questions": [
            {
              "q_no": 1,
              "question_type": "singlechoice",
              "marks": 1,
              "question_text": "MCQ Question text...",
              "options": {
                "A": "Option A text",
                "B": "Option B text",
                "C": "Option C text",
                "D": "Option D text"
              },
              "correct_option": "A",
              "diagram_svg": "",
              "explanation": "Explanation..."
            },
            {
              "q_no": 2,
              "question_type": "true_false",
              "marks": 1,
              "question_text": "Statement for True/False...",
              "correct_option": "True",
              "explanation": "Reason why it is True/False..."
            },
            {
              "q_no": 3,
              "question_type": "fill_in_the_blanks",
              "marks": 1,
              "question_text": "The process of ______ is called photosynthesis.",
              "correct_option": "converting light energy to chemical energy",
              "explanation": "Photosynthesis concept..."
            }
          ]
        },
        {
          "section_name": "SECTION B",
          "description": "Short Answer Questions (2 Marks Each)",
          "questions": [
            {
              "q_no": 4,
              "question_type": "descriptive",
              "marks": 2,
              "question_text": "Question text...",
              "answer_key": "Point 1: (1 Mark), Point 2: (1 Mark)"
            }
          ]
        },
        {
          "section_name": "SECTION C",
          "description": "Short Answer Questions (3 Marks Each)",
          "questions": [
            {
              "q_no": 5,
              "question_type": "descriptive",
              "marks": 3,
              "question_text": "Question text...",
              "answer_key": "Detailed marking distribution"
            }
          ]
        },
        {
          "section_name": "SECTION D",
          "description": "Long Answer Questions (5 Marks Each)",
          "questions": [
            {
              "q_no": 6,
              "question_type": "descriptive",
              "marks": 5,
              "question_text": "Main question...",
              "or_question_text": "Alternative OR question...",
              "answer_key": "Step-wise 5-mark distribution"
            }
          ]
        },
        {
          "section_name": "SECTION E",
          "description": "Case-Based / Source-Based Integrated Questions (4 Marks Each)",
          "questions": [
            {
              "q_no": 7,
              "question_type": "descriptive",
              "marks": 4,
              "case_study_context": "Context passage / scenario...",
              "sub_questions": [
                {"sub_q": "(i) Question 1...", "marks": 1, "answer": "Answer 1"},
                {"sub_q": "(ii) Question 2...", "marks": 1, "answer": "Answer 2"},
                {"sub_q": "(iii) Question 3...", "marks": 2, "answer": "Answer 3"}
              ]
            }
          ]
        }
      ]
    }
  }
}
EOT;
        return $prompt;
    }

    /**
     * Specialized Prompt for Kindergarten / Pre-Primary Activity Worksheets (Nursery, LKG, UKG)
     */
    private function build_preprimary_worksheet_prompt($class_name, $subject_name, $chapter, $total_marks, $difficulty, $language, $academic_session, $generate_multi_sets)
    {
        $prompt = <<<EOT
You are an expert Early Childhood Education (ECE / Pre-Primary) curriculum designer.
Generate an engaging, colorful, pictorial activity worksheet exam paper for:
- Class: {$class_name} (Early Childhood / Kindergarten)
- Subject: {$subject_name} (e.g. English, Math / Number Work, EVS / General Awareness, Hindi)
- Scope / Topics: {$chapter} (e.g. Fruits, Vegetables, Animals, Shapes, Colors, Numbers 1-10, Alphabets A-Z, Phonics)
- Academic Session: {$academic_session}
- Total Marks: {$total_marks}

PRE-PRIMARY QUESTION & ACTIVITY FORMATS (Use appropriate mix for Kindergarten):
1. MATCH THE PAIR (Matching column A picture/name with column B color, shadow, or parent):
   - Example: 🍎 Apple -> Red | 🍌 Banana -> Yellow | 🍇 Grapes -> Purple
2. COUNT & WRITE IN THE BOX (Visual counting with emojis or SVG objects):
   - Example: "Count how many stars and write in the box: ⭐ ⭐ ⭐ ⭐ ⭐ ➡️ [____]"
3. CIRCLE THE ODD ONE OUT / CIRCLE THE CORRECT PICTURE:
   - Example: 🍎, 🍌, 🚗, 🥭 -> "Circle the vehicle among fruits!"
4. COLORING & TRACING INSTRUCTIONS (Clean line outline SVG for children to color):
   - Example: "Color the 🥭 Mango Yellow with Green leaf!" (Provide clean line outline in diagram_svg)
5. FILL THE MISSING ALPHABET OR NUMBER:
   - Example: "A, B, __, D, E, __, G" or "1, 2, __, 4, 5, __, 7"
6. RECOGNIZE & NAME:
   - Example: "Look at the picture and tick the first letter: [🦁 Lion] ➡️ (A) L  (B) M  (C) P"

STRICT JSON OUTPUT FORMAT ONLY:
Output MUST be a single valid JSON object strictly matching this schema with NO markdown code fences:
{
  "paper_title": "{$class_name} {$subject_name} Activity Worksheet",
  "academic_session": "{$academic_session}",
  "subject": "{$subject_name}",
  "class": "{$class_name}",
  "time_allowed": "1 Hour",
  "max_marks": {$total_marks},
  "is_multi_set": false,
  "general_instructions": [
    "Dear Parents / Teachers: Please read the instructions to the child gently.",
    "Use crayons / colored pencils for coloring questions.",
    "Attempt all fun activities!"
  ],
  "sets": {
    "Set A": {
      "sections": [
        {
          "section_name": "SECTION A: RECOGNIZE & MATCH",
          "description": "Fun Matching & Identification Activities",
          "questions": [
            {
              "q_no": 1,
              "question_type": "singlechoice",
              "marks": 2,
              "question_text": "Look at the fruit 🍎 and choose the correct starting letter and color:",
              "options": {
                "A": "A for Apple (Red)",
                "B": "M for Mango (Yellow)",
                "C": "O for Orange (Orange)",
                "D": "G for Grapes (Green)"
              },
              "correct_option": "A",
              "diagram_svg": "<svg width='120' height='120' viewBox='0 0 120 120' xmlns='http://www.w3.org/2000/svg'><circle cx='60' cy='65' r='38' fill='#fee2e2' stroke='#ef4444' stroke-width='3'/><path d='M60 27 Q65 15 75 18' stroke='#15803d' stroke-width='4' fill='none'/><ellipse cx='73' cy='22' rx='8' ry='4' fill='#22c55e'/></svg>",
              "explanation": "Apple starts with letter A and is red."
            },
            {
              "q_no": 2,
              "question_type": "fill_in_the_blanks",
              "marks": 2,
              "question_text": "Count the balloons and write the number: 🎈 🎈 🎈 🎈 🎈 ➡️ [ _____ ]",
              "correct_option": "5",
              "explanation": "There are 5 balloons."
            }
          ]
        },
        {
          "section_name": "SECTION B: COLORING & TRACING",
          "description": "Creative Coloring and Missing Sequence",
          "questions": [
            {
              "q_no": 3,
              "question_type": "descriptive",
              "marks": 3,
              "question_text": "Color the Mango 🥭: Fill Yellow color inside the mango and Green color in the leaf!",
              "diagram_svg": "<svg width='140' height='140' viewBox='0 0 140 140' xmlns='http://www.w3.org/2000/svg'><path d='M70,30 C95,30 115,55 105,90 C95,120 55,125 40,95 C25,65 45,30 70,30 Z' fill='#fffbeb' stroke='#f59e0b' stroke-width='3' stroke-dasharray='4,3'/><path d='M70,30 Q75,10 90,15' stroke='#15803d' stroke-width='3' fill='none'/><ellipse cx='86' cy='18' rx='10' ry='5' fill='#f0fdf4' stroke='#16a34a' stroke-width='2'/><text x='52' y='80' font-family='Arial' font-size='11' fill='#94a3b8'>Color Me</text></svg>",
              "answer_key": "Full marks (3M) for neat yellow coloring inside the outline and green on the leaf."
            },
            {
              "q_no": 4,
              "question_type": "descriptive",
              "marks": 3,
              "question_text": "Fill the missing letters in the apple train: A, __ , C, __ , E, __ , G",
              "answer_key": "Missing letters: B, D, F (1 mark each)"
            }
          ]
        }
      ]
    }
  }
}
EOT;
        return $prompt;
    }

    private function bool_str($val) {
        return $val ? 'true' : 'false';
    }

    /**
     * Call OpenRouter API — stealth/union-alpha only
     */
    private function call_openrouter($prompt, $api_key)
    {
        $url = "https://openrouter.ai/api/v1/chat/completions";
        $site_url = defined('base_url') ? base_url() : 'https://sunriseschool.in';

        $payload = [
            'model' => 'stealth/union-alpha',
            'messages' => [
                ['role' => 'system', 'content' => 'You are an expert CBSE examination question author. You MUST output ONLY raw valid JSON adhering exactly to the requested schema without any introductory text, conversational remarks, thinking traces, or markdown explanations.'],
                ['role' => 'user', 'content' => $prompt]
            ],
            'response_format' => ['type' => 'json_object'],
            'temperature' => 0.2,
            'max_tokens' => 6000
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $api_key,
            'HTTP-Referer: ' . $site_url,
            'X-Title: Sunrise ERP AI Studio'
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 25);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $result = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($ch);
        curl_close($ch);

        if ($curl_error) {
            return ['error' => 'OpenRouter cURL Error: ' . $curl_error];
        }

        $res_json = json_decode($result, true);
        if ($http_code === 200 && isset($res_json['choices'][0]['message']['content'])) {
            return ['raw_text' => $res_json['choices'][0]['message']['content']];
        }

        if (isset($res_json['error']['message'])) {
            return ['error' => 'OpenRouter: ' . $res_json['error']['message']];
        }

        return ['error' => 'HTTP ' . $http_code . ' from OpenRouter'];
    }

    /**
     * Clean and extract valid JSON from LLM response
     */
    private function extract_json($raw_text)
    {
        if (empty($raw_text)) {
            return null;
        }

        $text = trim($raw_text);

        // 1. Remove reasoning / thought traces if output by reasoning models (<think>...</think> or similar)
        $text = preg_replace('/<think>.*?<\/think>/is', '', $text);

        // 2. Remove Markdown code block wrappers (```json ... ``` or ``` ...)
        if (preg_match('/```(?:json)?\s*([\s\S]*?)\s*```/i', $text, $matches)) {
            $text = trim($matches[1]);
        } else {
            $text = preg_replace('/^```(?:json)?\s*/i', '', $text);
            $text = preg_replace('/\s*```$/', '', $text);
        }
        $text = trim($text);

        // Direct parse attempt
        $data = json_decode($text, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($data)) {
            return $this->normalize_parsed_paper($data);
        }

        // 3. Fix duplicate leading / trailing curly braces like '{\n{\n "paper_title"...'
        $cleaned_text = preg_replace('/^(\s*\{)+\s*\{/i', '{', $text);
        $cleaned_text = preg_replace('/\}(\s*\})+$/i', '}', $cleaned_text);
        $data = json_decode($cleaned_text, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($data)) {
            return $this->normalize_parsed_paper($data);
        }

        // 4. Extract between first '{' and last '}' with balanced brace scanner
        $first_brace = strpos($text, '{');
        $last_brace  = strrpos($text, '}');
        if ($first_brace !== false && $last_brace !== false && $last_brace > $first_brace) {
            $candidate = substr($text, $first_brace, $last_brace - $first_brace + 1);
            $data = json_decode($candidate, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($data)) {
                return $this->normalize_parsed_paper($data);
            }

            // Strip redundant leading/trailing braces inside candidate
            $candidate_cleaned = preg_replace('/^(\s*\{)+\s*\{/i', '{', $candidate);
            $candidate_cleaned = preg_replace('/\}(\s*\})+$/i', '}', $candidate_cleaned);
            $data = json_decode($candidate_cleaned, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($data)) {
                return $this->normalize_parsed_paper($data);
            }

            // Clean common trailing commas before } or ]
            $no_trailing = preg_replace('/,\s*([\}\]])/m', '$1', $candidate_cleaned);
            $data = json_decode($no_trailing, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($data)) {
                return $this->normalize_parsed_paper($data);
            }

            // Balanced brace parser to find cleanest valid root object
            $len = strlen($candidate);
            $depth = 0;
            $start = -1;
            for ($i = 0; $i < $len; $i++) {
                $ch = $candidate[$i];
                if ($ch === '{') {
                    if ($depth === 0) $start = $i;
                    $depth++;
                } elseif ($ch === '}') {
                    $depth--;
                    if ($depth === 0 && $start !== -1) {
                        $sub = substr($candidate, $start, $i - $start + 1);
                        $d = json_decode($sub, true);
                        if (is_array($d)) {
                            return $this->normalize_parsed_paper($d);
                        }
                    }
                }
            }
        }

        // 5. Truncated JSON recovery: if output was cut off mid-stream, close unclosed strings/arrays/objects
        $balanced = $text;
        $open_braces = substr_count($balanced, '{') - substr_count($balanced, '}');
        $open_brackets = substr_count($balanced, '[') - substr_count($balanced, ']');
        if ($open_braces > 0 || $open_brackets > 0) {
            // Trim any trailing partial string / key / comma
            $balanced = preg_replace('/,\s*"[^"]*"?\s*:\s*[^,}\]]*$/', '', $balanced);
            $balanced = preg_replace('/,\s*$/', '', $balanced);
            // Append missing closing brackets and braces
            for ($k = 0; $k < $open_brackets; $k++) { $balanced .= ']'; }
            for ($k = 0; $k < $open_braces; $k++) { $balanced .= '}'; }
            $data = json_decode($balanced, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($data)) {
                return $this->normalize_parsed_paper($data);
            }
        }

        return null;
    }

    /**
     * Helper to normalize parsed paper structure into standard format
     */
    private function normalize_parsed_paper($data)
    {
        if (!is_array($data)) {
            return null;
        }

        if (!isset($data['sets']) && isset($data['sections'])) {
            $data['sets'] = ['Set A' => ['sections' => $data['sections']]];
        }

        return $data;
    }

    /**
     * Fetch complete NCERT / CBSE Chapter List for any Class & Subject dynamically via AI & Database Cache
     */
    public function fetch_subject_chapters_ai($class_name, $subject_name, $api_engine = 'openrouter', $custom_api_key = '')
    {
        $active_openrouter_key = !empty($custom_api_key) ? $custom_api_key : $this->openrouter_api_key;

        $prompt = <<<EOT
You are an expert CBSE, NCERT, and State Education Curriculum Director.
Provide the standard list of NCERT / CBSE curriculum chapters/units for:
Class: {$class_name}
Subject: {$subject_name}

STRICT JSON OUTPUT FORMAT ONLY:
Output MUST be a single valid JSON object strictly matching this schema with NO markdown fences:
{
  "class_name": "{$class_name}",
  "subject_name": "{$subject_name}",
  "chapters": [
    "Chapter 1 - Chapter Name",
    "Chapter 2 - Chapter Name"
  ]
}
EOT;

        if (empty($active_openrouter_key)) {
            return ['status' => 'error', 'message' => 'OpenRouter API Key is not configured.'];
        }

        $response   = $this->call_openrouter($prompt, $active_openrouter_key, 'stealth/union-alpha');
        $model_used = 'OpenRouter (stealth/union-alpha)';

        if (!$response || isset($response['error'])) {
            return ['status' => 'error', 'message' => isset($response['error']) ? $response['error'] : 'Failed to fetch chapters from AI.'];
        }

        $parsed = $this->extract_json($response['raw_text']);
        $chapters_list = [];

        if (is_array($parsed)) {
            if (isset($parsed['chapters']) && is_array($parsed['chapters'])) {
                $chapters_list = $parsed['chapters'];
            } elseif (isset($parsed['units']) && is_array($parsed['units'])) {
                $chapters_list = $parsed['units'];
            } elseif (isset($parsed['syllabus']) && is_array($parsed['syllabus'])) {
                $chapters_list = $parsed['syllabus'];
            } elseif (isset($parsed[0]) && is_string($parsed[0])) {
                $chapters_list = $parsed;
            }
        }

        if (empty($chapters_list)) {
            return ['status' => 'error', 'message' => 'Invalid chapter structure returned by AI: ' . substr($response['raw_text'], 0, 150)];
        }

        return [
            'status'     => 'success',
            'model_used' => $model_used,
            'chapters'   => $chapters_list
        ];
    }

    /**
     * Generate custom batch of standalone questions for Question Bank
     */
    public function generate_questions_batch($params)
    {
        $class_name     = isset($params['class_name']) ? $params['class_name'] : 'Class 10';
        $subject_name   = isset($params['subject_name']) ? $params['subject_name'] : 'Science';
        $topic          = !empty($params['topic']) ? $params['topic'] : 'Complete Syllabus';
        
        $q_types        = isset($params['question_types']) && is_array($params['question_types']) ? $params['question_types'] : (isset($params['question_type']) ? [$params['question_type']] : ['singlechoice']);
        $levels         = isset($params['levels']) && is_array($params['levels']) ? $params['levels'] : (isset($params['level']) ? [$params['level']] : ['medium']);
        
        $count          = isset($params['count']) ? intval($params['count']) : 5;
        if ($count < 1) $count = 1;
        if ($count > 30) $count = 30;

        $custom_api_key = isset($params['api_key']) ? trim($params['api_key']) : '';
        $active_openrouter_key = !empty($custom_api_key) ? $custom_api_key : $this->openrouter_api_key;

        $types_str = implode(', ', $q_types);
        $levels_str = implode(', ', $levels);

        $prompt = "You are a senior academic question author for Indian CBSE school curriculum.
Generate exactly {$count} unique high-quality exam questions evenly distributed across:
- Class/Grade: {$class_name}
- Subject: {$subject_name}
- Chapters / Syllabus Scope: {$topic}
- Allowed Question Types: {$types_str} (use any from: singlechoice, multichoice, true_false, descriptive)
- Allowed Difficulty Levels: {$levels_str} (use any from: easy, medium, hard)

Requirements:
- For 'singlechoice' and 'multichoice': provide \"options\" object (keys \"A\", \"B\", \"C\", \"D\"), \"correct_option\" (e.g. \"A\"), and \"explanation\".
- For 'true_false': provide \"options\" ({\"A\": \"True\", \"B\": \"False\"}), \"correct_option\" (\"A\" or \"B\"), and \"explanation\".
- For 'descriptive': provide comprehensive question with marking scheme and model answer in \"explanation\".
- Formula & Math Support: If math, physics, or chemistry equations are needed, write them in standard clean LaTeX notation (e.g. `$x^2 + 5x + 6 = 0$`, `$\\frac{a}{b}$`, `$\\sqrt{x}$`, `$H_2SO_4$`) so it seamlessly renders and opens in the LMS CKEditor & WIRIS Math/Chemistry Formula Editor.
- Return ONLY a valid JSON object matching this schema:
{
  \"questions\": [
    {
      \"question_text\": \"Question statement here...\",
      \"question_type\": \"singlechoice\",
      \"level\": \"medium\",
      \"options\": {
        \"A\": \"...\",
        \"B\": \"...\",
        \"C\": \"...\",
        \"D\": \"...\"
      },
      \"correct_option\": \"A\",
      \"explanation\": \"Detailed rationale / marking scheme / step-by-step solution\"
    }
  ]
}";

        if (empty($active_openrouter_key)) {
            return ['status' => 'error', 'message' => 'OpenRouter API Key is not configured. Please set it in AI Engine Configuration.'];
        }

        $response = $this->call_openrouter($prompt, $active_openrouter_key, 'stealth/union-alpha');

        if (!$response || isset($response['error'])) {
            return ['status' => 'error', 'message' => isset($response['error']) ? $response['error'] : 'Failed to generate questions.'];
        }

        $raw = isset($response['raw_text']) ? $response['raw_text'] : '';
        $parsed = $this->extract_json($raw);

        $q_list = [];
        if (is_array($parsed)) {
            if (isset($parsed['questions']) && is_array($parsed['questions'])) {
                $q_list = $parsed['questions'];
            } elseif (isset($parsed['items']) && is_array($parsed['items'])) {
                $q_list = $parsed['items'];
            } elseif (isset($parsed['data']) && is_array($parsed['data'])) {
                $q_list = $parsed['data'];
            } elseif (isset($parsed[0]) && is_array($parsed[0])) {
                $q_list = $parsed;
            }
        }

        // If bracket array extraction needed
        if (empty($q_list)) {
            $start_arr = strpos($raw, '[');
            $end_arr   = strrpos($raw, ']');
            if ($start_arr !== false && $end_arr !== false) {
                $arr_str = substr($raw, $start_arr, $end_arr - $start_arr + 1);
                $arr_data = json_decode($arr_str, true);
                if (is_array($arr_data) && !empty($arr_data)) {
                    $q_list = $arr_data;
                }
            }
        }

        if (empty($q_list)) {
            return ['status' => 'error', 'message' => 'AI returned non-standard JSON format: ' . substr($raw, 0, 180)];
        }

        return [
            'status'    => 'success',
            'questions' => $q_list
        ];
    }
}
