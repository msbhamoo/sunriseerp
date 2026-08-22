<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Ai_vision_evaluator
{
    protected $CI;
    protected $gemini_api_key;
    protected $groq_api_key;

    public function __construct()
    {
        $this->CI = &get_instance();
        $sch_setting = $this->CI->setting_model->getSetting();
        $this->gemini_api_key = !empty($sch_setting->ai_gemini_api_key) ? $sch_setting->ai_gemini_api_key : (defined('GEMINI_API_KEY') ? GEMINI_API_KEY : '');
        $this->groq_api_key   = !empty($sch_setting->ai_groq_api_key) ? $sch_setting->ai_groq_api_key : (defined('GROQ_API_KEY') ? GROQ_API_KEY : '');
    }

    /**
     * Evaluate Handwritten Answer Sheets using Multimodal Vision API
     */
    public function evaluate_submission($params)
    {
        $paper_data       = isset($params['paper_data']) ? $params['paper_data'] : [];
        $image_paths      = isset($params['image_paths']) ? $params['image_paths'] : [];
        $custom_solution  = isset($params['custom_solution']) ? $params['custom_solution'] : '';
        $student_name     = isset($params['student_name']) ? $params['student_name'] : 'Student';
        $class_name       = isset($params['class_name']) ? $params['class_name'] : 'Class';
        $subject_name     = isset($params['subject_name']) ? $params['subject_name'] : 'Subject';
        $custom_api_key   = isset($params['api_key']) ? trim($params['api_key']) : '';

        $active_gemini_key = !empty($custom_api_key) ? $custom_api_key : $this->gemini_api_key;

        if (empty($active_gemini_key)) {
            return [
                'status'  => 'error',
                'message' => 'Google Gemini Vision API key is required for handwritten image evaluation. Please provide or configure it in Settings.'
            ];
        }

        if (empty($image_paths)) {
            return [
                'status'  => 'error',
                'message' => 'No answer sheet images provided for evaluation.'
            ];
        }

        // Build Vision Evaluation Prompt
        $prompt = $this->build_evaluation_prompt($paper_data, $custom_solution, $student_name, $class_name, $subject_name);

        // Call Gemini Multimodal Vision API with Base64 Images
        $response = $this->call_gemini_vision($prompt, $image_paths, $active_gemini_key);

        if (!$response || isset($response['error'])) {
            return [
                'status'  => 'error',
                'message' => isset($response['error']) ? $response['error'] : 'Failed to evaluate answer sheet images.'
            ];
        }

        $parsed = $this->extract_json($response['raw_text']);
        if (!$parsed) {
            return [
                'status'  => 'error',
                'message' => 'Could not parse structured evaluation output. Preview: ' . substr($response['raw_text'], 0, 250) . '...'
            ];
        }

        return [
            'status' => 'success',
            'data'   => $parsed
        ];
    }

    /**
     * Construct CBSE Step-by-Step Marking Evaluation Prompt
     */
    private function build_evaluation_prompt($paper_data, $custom_solution, $student_name, $class_name, $subject_name)
    {
        $paper_json_str = json_encode($paper_data, JSON_PRETTY_PRINT);
        
        $custom_sol_text = "";
        if (!empty($custom_solution)) {
            $custom_sol_text = "TEACHER'S SPECIAL MARKING INSTRUCTIONS / CUSTOM SOLUTION:\n" . $custom_solution . "\n";
        }

        $prompt = <<<EOT
You are an expert CBSE Senior Board Head Examiner and AI Handwriting Analysis Specialist.
You are evaluating the handwritten physical answer sheet pages submitted for student '{$student_name}' ({$class_name} - {$subject_name}).

OFFICIAL QUESTION PAPER & MARKING SCHEME:
{$paper_json_str}

{$custom_sol_text}

MISSION-CRITICAL EVALUATION PROTOCOL (ZERO-MISTAKE HUMAN-IN-THE-LOOP):
1. Transcribe the student's handwritten responses for each question identified across the uploaded pages.
2. If handwriting contains mathematical derivations or chemical formulas, transcribe them accurately in LaTeX ($...$).
3. Score each question strictly following CBSE Step-by-Step Marking principles:
   - Award partial credit for formulas, correct substitution, and valid methodology even if the final calculation has minor arithmetic errors.
   - For MCQs / Objective, verify option letter and statement text.
   - For Biology/Physics diagrams, check if essential labeled parts are drawn.
4. Calculate an OCR & Semantic Readability Confidence Score (0 to 100) for each question:
   - 90-100: Clear, highly legible handwriting.
   - 70-89: Moderate legibility.
   - Below 70: Ambiguous/blurry handwriting or heavy strike-throughs (will be flagged for teacher review).
5. Provide a constructive, encouraging feedback tip for the student on where they can improve.

STRICT JSON OUTPUT FORMAT ONLY:
Output MUST be a single valid JSON object strictly matching this schema with NO markdown code fences (no ```json):
{
  "student_name": "{$student_name}",
  "total_max_marks": 80,
  "total_obtained_marks": 0.0,
  "overall_accuracy_percentage": 0.0,
  "average_confidence": 92,
  "general_examiner_remarks": "Summary of student's overall performance...",
  "evaluated_questions": [
    {
      "q_no": 1,
      "section_name": "SECTION A",
      "question_type": "singlechoice",
      "max_marks": 1.0,
      "obtained_marks": 1.0,
      "confidence_score": 95,
      "page_number": 1,
      "student_answer_transcription": "Student wrote...",
      "step_marking_breakdown": [
        {
          "step_description": "Correct Option Selection (A)",
          "marks_allocated": 1.0,
          "marks_awarded": 1.0,
          "step_status": "correct",
          "comment": "Accurate option and reasoning."
        }
      ],
      "examiner_feedback": "Well attempted."
    },
    {
      "q_no": 21,
      "section_name": "SECTION B",
      "question_type": "descriptive",
      "max_marks": 2.0,
      "obtained_marks": 1.5,
      "confidence_score": 88,
      "page_number": 2,
      "student_answer_transcription": "Student wrote formula and calculation...",
      "step_marking_breakdown": [
        {
          "step_description": "Formula / Identification",
          "marks_allocated": 1.0,
          "marks_awarded": 1.0,
          "step_status": "correct",
          "comment": "Correct formula stated."
        },
        {
          "step_description": "Calculation & Units",
          "marks_allocated": 1.0,
          "marks_awarded": 0.5,
          "step_status": "partial",
          "comment": "Correct numerical value but omitted unit 'cm^2'."
        }
      ],
      "examiner_feedback": "Remember to always write units with final numerical answers."
    }
  ]
}
EOT;
        return $prompt;
    }

    /**
     * Fetch all available models from Google ModelService dynamically
     */
    private function get_available_gemini_models($api_key)
    {
        $url = "https://generativelanguage.googleapis.com/v1beta/models?key=" . urlencode($api_key);
        $ch  = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $res = curl_exec($ch);
        curl_close($ch);

        $models = [];
        if ($res) {
            $json = json_decode($res, true);
            if (!empty($json['models']) && is_array($json['models'])) {
                foreach ($json['models'] as $m) {
                    $methods = isset($m['supportedGenerationMethods']) ? $m['supportedGenerationMethods'] : [];
                    if (in_array('generateContent', $methods) && !empty($m['name'])) {
                        $name = str_replace('models/', '', $m['name']);
                        $models[] = $name;
                    }
                }
            }
        }
        return $models;
    }

    /**
     * Call Google Gemini Vision REST API with Multimodal Image Parts
     */
    private function call_gemini_vision($prompt, $image_paths, $api_key)
    {
        $image_parts = [];
        foreach ($image_paths as $idx => $path) {
            if (file_exists($path)) {
                $mime_type = mime_content_type($path);
                if (empty($mime_type)) {
                    $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                    $mime_type = ($ext === 'png') ? 'image/png' : 'image/jpeg';
                }
                $base64_data = base64_encode(file_get_contents($path));
                $image_parts[] = [
                    'inlineData' => [
                        'mimeType' => $mime_type,
                        'data'     => $base64_data
                    ]
                ];
            }
        }

        if (empty($image_parts)) {
            return ['error' => 'Unable to read or encode uploaded answer sheet images.'];
        }

        $parts = array_merge([['text' => $prompt]], $image_parts);

        $payload = [
            'contents' => [
                [
                    'parts' => $parts
                ]
            ],
            'generationConfig' => [
                'temperature'      => 0.2,
                'responseMimeType' => 'application/json'
            ]
        ];

        // 1. Discover models supported by user's specific API key
        $available_models = $this->get_available_gemini_models($api_key);

        $priority_order = [
            'gemini-2.5-flash',
            'gemini-2.0-flash',
            'gemini-2.0-flash-exp',
            'gemini-1.5-flash',
            'gemini-1.5-flash-latest',
            'gemini-1.5-flash-8b',
            'gemini-1.5-pro',
            'gemini-1.5-pro-latest'
        ];

        $models_to_test = [];
        foreach ($priority_order as $p) {
            if (in_array($p, $available_models)) {
                $models_to_test[] = $p;
            }
        }
        foreach ($available_models as $am) {
            if (!in_array($am, $models_to_test)) {
                $models_to_test[] = $am;
            }
        }

        // Fallback default list if ModelService list endpoint was restricted
        if (empty($models_to_test)) {
            $models_to_test = [
                'gemini-2.0-flash',
                'gemini-1.5-flash',
                'gemini-1.5-flash-latest',
                'gemini-1.5-pro'
            ];
        }

        $last_error = 'Unknown error';

        foreach ($models_to_test as $model) {
            foreach (['v1beta', 'v1'] as $ver) {
                $url = "https://generativelanguage.googleapis.com/{$ver}/models/{$model}:generateContent?key=" . urlencode($api_key);

                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json'
                ]);
                curl_setopt($ch, CURLOPT_TIMEOUT, 120);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

                $result     = curl_exec($ch);
                $http_code  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                $curl_error = curl_error($ch);
                curl_close($ch);

                if ($curl_error) {
                    $last_error = 'cURL Error: ' . $curl_error;
                    continue;
                }

                $res_json = json_decode($result, true);
                if ($http_code === 200 && isset($res_json['candidates'][0]['content']['parts'][0]['text'])) {
                    return ['raw_text' => $res_json['candidates'][0]['content']['parts'][0]['text']];
                }

                if (isset($res_json['error']['message'])) {
                    $last_error = $res_json['error']['message'];
                }
            }
        }

        return ['error' => 'Gemini Vision API Error: ' . $last_error];
    }

    /**
     * Clean and extract valid JSON from LLM response
     */
    private function extract_json($raw_text)
    {
        $text = trim($raw_text);
        $text = preg_replace('/^```(?:json)?\s*/i', '', $text);
        $text = preg_replace('/\s*```$/', '', $text);
        $text = trim($text);

        $data = json_decode($text, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($data)) {
            return $data;
        }

        $start = strpos($text, '{');
        $end   = strrpos($text, '}');
        if ($start !== false && $end !== false) {
            $json_str = substr($text, $start, $end - $start + 1);
            $data = json_decode($json_str, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($data)) {
                return $data;
            }
        }

        return null;
    }
}
