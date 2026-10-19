<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();

$fullname = $this->customlib->getFullName($student['firstname'], $student['middlename'], $student['lastname'], $sch_setting->middlename, $sch_setting->lastname);

$badge_text         = !empty($story['badge_text']) ? $story['badge_text'] : 'CLASS OF ' . date('Y');
$subtitle           = !empty($story['subtitle']) ? $story['subtitle'] : (!empty($alumni_detail['occupation']) ? $alumni_detail['occupation'] : '');
$story_intro        = !empty($story['story_intro']) ? $story['story_intro'] : '';
$higher_edu_summary = !empty($story['higher_edu_summary']) ? $story['higher_edu_summary'] : '';
$location_summary   = !empty($story['location_summary']) ? $story['location_summary'] : (!empty($alumni_detail['address']) ? $alumni_detail['address'] : '');
$section1_title     = !empty($story['section1_title']) ? $story['section1_title'] : 'The Sunrise Foundation';
$section1_content   = !empty($story['section1_content']) ? $story['section1_content'] : '';
$quote_text         = !empty($story['quote_text']) ? $story['quote_text'] : '';
$quote_author       = !empty($story['quote_author']) ? $story['quote_author'] : '— ' . $fullname;
$section2_title     = !empty($story['section2_title']) ? $story['section2_title'] : 'Going Above and Beyond';
$section2_content   = !empty($story['section2_content']) ? $story['section2_content'] : '';

// Fallbacks from education & work history if empty
if (empty($higher_edu_summary) && !empty($education_list)) {
    $first_edu = $education_list[0];
    $higher_edu_summary = trim($first_edu['degree_name'] . ' ' . $first_edu['college_name']);
}
if (empty($location_summary) && !empty($work_list)) {
    foreach ($work_list as $w) {
        if ($w['is_current'] == 1 && !empty($w['location'])) {
            $location_summary = $w['location'];
            break;
        }
    }
}
?>

<style type="text/css">
.alumni-story-header {
    background: #0f172a;
    color: #ffffff;
    padding: 45px 30px 90px 30px;
    position: relative;
    border-radius: 4px 4px 0 0;
}
.alumni-story-header .btn-back {
    color: #cbd5e1;
    font-size: 13px;
    font-weight: 600;
    text-transform: uppercase;
    text-decoration: none;
    letter-spacing: 0.5px;
    display: inline-block;
    margin-bottom: 15px;
    transition: color 0.2s;
}
.alumni-story-header .btn-back:hover {
    color: #f59e0b;
}
.alumni-badge {
    background: #f59e0b;
    color: #0f172a;
    font-size: 11px;
    font-weight: 800;
    padding: 4px 12px;
    border-radius: 12px;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    display: inline-block;
    margin-left: 10px;
    vertical-align: middle;
}
.alumni-story-name {
    font-size: 42px;
    font-weight: 800;
    line-height: 1.1;
    margin: 10px 0 6px 0;
    color: #ffffff;
    letter-spacing: -0.5px;
}
.alumni-story-subtitle {
    font-size: 18px;
    color: #94a3b8;
    font-weight: 500;
    margin: 0;
}
.alumni-story-container {
    max-width: 820px;
    margin: -60px auto 40px auto;
    position: relative;
    z-index: 10;
    padding: 0 15px;
}
.alumni-story-card {
    background: #ffffff;
    border-radius: 20px;
    box-shadow: 0 20px 30px -10px rgba(15, 23, 42, 0.12), 0 10px 15px -5px rgba(0, 0, 0, 0.04);
    padding: 45px 50px;
    border: 1px solid #e2e8f0;
}
.story-intro-p {
    font-size: 17px;
    line-height: 1.8;
    color: #334155;
    margin-bottom: 30px;
}
.story-highlights-grid {
    display: flex;
    gap: 20px;
    margin-bottom: 35px;
}
.story-highlight-card {
    flex: 1;
    border-radius: 14px;
    padding: 20px 24px;
    display: flex;
    align-items: flex-start;
    gap: 16px;
}
.story-highlight-card.edu {
    background: #f0f9ff;
    border: 1px solid #bae6fd;
}
.story-highlight-card.loc {
    background: #fff7ed;
    border: 1px solid #fed7aa;
}
.story-highlight-icon {
    font-size: 24px;
    line-height: 1;
}
.story-highlight-card.edu .story-highlight-icon {
    color: #0284c7;
}
.story-highlight-card.loc .story-highlight-icon {
    color: #d97706;
}
.story-highlight-label {
    font-size: 13px;
    font-weight: 700;
    margin-bottom: 4px;
}
.story-highlight-card.edu .story-highlight-label {
    color: #0369a1;
}
.story-highlight-card.loc .story-highlight-label {
    color: #c2410c;
}
.story-highlight-val {
    font-size: 14px;
    font-weight: 600;
    color: #1e293b;
    margin: 0;
}
.story-section-title {
    font-size: 22px;
    font-weight: 700;
    color: #0f172a;
    margin: 35px 0 16px 0;
    display: flex;
    align-items: center;
    gap: 10px;
}
.story-section-p {
    font-size: 15px;
    line-height: 1.85;
    color: #475569;
    margin-bottom: 20px;
}
.story-quote-box {
    background: #f8fafc;
    border-left: 4px solid #f59e0b;
    border-radius: 0 14px 14px 0;
    padding: 25px 30px;
    margin: 35px 0;
    position: relative;
}
.story-quote-box::after {
    content: "”";
    position: absolute;
    right: 25px;
    bottom: 10px;
    font-size: 80px;
    color: #e2e8f0;
    font-family: Georgia, serif;
    line-height: 1;
    pointer-events: none;
}
.story-quote-text {
    font-size: 17px;
    font-style: italic;
    color: #334155;
    line-height: 1.7;
    margin-bottom: 12px;
    position: relative;
    z-index: 2;
}
.story-quote-author {
    font-size: 14px;
    font-weight: 700;
    color: #0f172a;
    margin: 0;
    position: relative;
    z-index: 2;
}
@media (max-width: 768px) {
    .alumni-story-card { padding: 25px 20px; }
    .story-highlights-grid { flex-direction: column; }
    .alumni-story-name { font-size: 30px; }
}
</style>

<div class="content-wrapper">
    <div class="container-fluid no-padding">
        <!-- Dark Header -->
        <div class="alumni-story-header text-center">
            <div style="max-width: 820px; margin: 0 auto; text-align: left;">
                <a href="<?php echo site_url('admin/alumni/alumnilist'); ?>" class="btn-back">
                    <i class="fa fa-arrow-left"></i> &nbsp;Back to Alumni
                </a>
                <span class="alumni-badge"><?php echo htmlspecialchars($badge_text); ?></span>
                <h1 class="alumni-story-name"><?php echo htmlspecialchars($fullname); ?></h1>
                <?php if (!empty($subtitle)) { ?>
                    <p class="alumni-story-subtitle"><?php echo htmlspecialchars($subtitle); ?></p>
                <?php } ?>
            </div>
        </div>

        <!-- Floating Card View -->
        <div class="alumni-story-container">
            <div class="alumni-story-card">

                <!-- Story Intro -->
                <?php if (!empty($story_intro)) { ?>
                    <div class="story-intro-p">
                        <?php echo nl2br(htmlspecialchars($story_intro)); ?>
                    </div>
                <?php } ?>

                <!-- Highlights Grid -->
                <div class="story-highlights-grid">
                    <div class="story-highlight-card edu">
                        <div class="story-highlight-icon"><i class="fa fa-graduation-cap"></i></div>
                        <div>
                            <div class="story-highlight-label">Higher Education</div>
                            <div class="story-highlight-val">
                                <?php echo !empty($higher_edu_summary) ? htmlspecialchars($higher_edu_summary) : 'Not Specified'; ?>
                            </div>
                        </div>
                    </div>

                    <div class="story-highlight-card loc">
                        <div class="story-highlight-icon"><i class="fa fa-map-marker"></i></div>
                        <div>
                            <div class="story-highlight-label">Current Location</div>
                            <div class="story-highlight-val">
                                <?php echo !empty($location_summary) ? htmlspecialchars($location_summary) : 'Not Specified'; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <hr style="border-top: 1px solid #f1f5f9; margin: 30px 0;">

                <!-- Section 1 -->
                <?php if (!empty($section1_title) || !empty($section1_content)) { ?>
                    <div class="story-section-title">
                        <i class="fa fa-book text-primary" style="font-size: 18px; color: #3b82f6;"></i>
                        <span><?php echo htmlspecialchars($section1_title); ?></span>
                    </div>
                    <?php if (!empty($section1_content)) { ?>
                        <div class="story-section-p">
                            <?php echo nl2br(htmlspecialchars($section1_content)); ?>
                        </div>
                    <?php } ?>
                <?php } ?>

                <!-- Quote Box -->
                <?php if (!empty($quote_text)) { ?>
                    <div class="story-quote-box">
                        <div class="story-quote-text">
                            "<?php echo htmlspecialchars($quote_text); ?>"
                        </div>
                        <div class="story-quote-author">
                            <?php echo htmlspecialchars($quote_author); ?>
                        </div>
                    </div>
                <?php } ?>

                <!-- Section 2 -->
                <?php if (!empty($section2_title) || !empty($section2_content)) { ?>
                    <div class="story-section-title">
                        <i class="fa fa-heart" style="font-size: 18px; color: #ec4899;"></i>
                        <span><?php echo htmlspecialchars($section2_title); ?></span>
                    </div>
                    <?php if (!empty($section2_content)) { ?>
                        <div class="story-section-p">
                            <?php echo nl2br(htmlspecialchars($section2_content)); ?>
                        </div>
                    <?php } ?>
                <?php } ?>

            </div>
        </div>
    </div>
</div>
