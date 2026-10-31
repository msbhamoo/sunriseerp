<?php
/**
 * Public SSAT (Sunrise Scholarship & Admission Test) landing + registration page.
 * Visual language follows the printed pamphlet: royal blue, signal red, sunrise orange/yellow on a sky backdrop.
 */
$fields_map = array();
if (!empty($field_settings)) {
    foreach ($field_settings as $fs) {
        $fields_map[$fs['field_name']] = $fs;
    }
}
if (!function_exists('isFieldVisible')) {
    function isFieldVisible($key, $map)
    {
        return (isset($map[$key]) && $map[$key]['is_visible'] == 1);
    }
}
if (!function_exists('isFieldRequired')) {
    function isFieldRequired($key, $map)
    {
        return (isset($map[$key]) && $map[$key]['is_required'] == 1);
    }
}

$school_name = !empty($sch_setting[0]['name']) ? $sch_setting[0]['name'] : 'Sunrise International Public School';
$school_logo = !empty($sch_setting[0]['image']) ? base_url('uploads/school_content/logo/' . $sch_setting[0]['image']) : '';
$exam_title  = !empty($selected_exam['title']) ? $selected_exam['title'] : 'Sunrise Scholarship & Admission Test';

// Exam date: first scheduled date if configured, otherwise the date printed on the pamphlet
$exam_ts = strtotime('2026-12-20 11:00:00');
if (!empty($selected_exam['schedules'])) {
    foreach ($selected_exam['schedules'] as $sc) {
        if (!empty($sc['exam_date']) && strtotime($sc['exam_date'])) {
            $exam_ts = strtotime($sc['exam_date']);
            break;
        }
    }
}
$hindi_days = array('रविवार', 'सोमवार', 'मंगलवार', 'बुधवार', 'गुरुवार', 'शुक्रवार', 'शनिवार');
$hindi_months = array(1 => 'जनवरी', 'फ़रवरी', 'मार्च', 'अप्रैल', 'मई', 'जून', 'जुलाई', 'अगस्त', 'सितंबर', 'अक्टूबर', 'नवंबर', 'दिसंबर');
$exam_hi_date = date('j', $exam_ts) . ' ' . $hindi_months[(int) date('n', $exam_ts)] . ' ' . date('Y', $exam_ts) . ', ' . $hindi_days[(int) date('w', $exam_ts)];

$hero_img = (defined('FCPATH') && file_exists(FCPATH . 'uploads/scholarship/hero.jpg')) ? base_url('uploads/scholarship/hero.jpg') : '';
$wa_link = 'https://wa.me/919828512821?text=' . rawurlencode('Hello, I want to register for the Sunrise Scholarship & Admission Test (SSAT).');
$reg_url = site_url('scholarshipregister/apply/' . (!empty($selected_exam['exam_code']) ? urlencode($selected_exam['exam_code']) : ''));
?>
<!DOCTYPE html>
<html lang="hi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SSAT – <?php echo htmlspecialchars($exam_title); ?> | Sunrise Career Academy</title>
    <meta name="description" content="S-SAT: Where Talent Meets Opportunity. Free registration for the Sunrise Scholarship & Admission Test – lakhs in scholarships for admission to Class 6–12. Classes 5 to 11 can apply. Sunrise International Public School, Nechhwa, Sikar.">
    <meta name="theme-color" content="#14397a">
    <link rel="stylesheet" href="<?php echo base_url('backend/dist/font-awesome-4.5.0/css/font-awesome.min.css'); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Devanagari:wght@500;700;800&family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --blue: #1b4f9c;
            --blue-d: #12356f;
            --blue-dd: #0b2150;
            --red: #e5262c;
            --red-d: #c11a20;
            --pink: #ee3b5f;
            --orange: #f7941d;
            --yellow: #ffc61a;
            --sky: #e8f3fd;
            --sky-2: #cfe6fa;
            --ink: #132038;
            --muted: #5b6b86;
            --line: #dbe5f1;
            --white: #fff;
            --radius: 18px;
            --shadow: 0 14px 40px rgba(18, 53, 111, .14);
        }
        * { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            margin: 0; font-family: 'Poppins', 'Noto Sans Devanagari', system-ui, sans-serif;
            color: var(--ink); background: #fff; line-height: 1.55; -webkit-font-smoothing: antialiased;
        }
        .hi { font-family: 'Noto Sans Devanagari', 'Poppins', sans-serif; }
        a { color: inherit; text-decoration: none; }
        img { max-width: 100%; }
        .wrap { width: 100%; max-width: 1120px; margin: 0 auto; padding: 0 20px; }
        .sr-only { position: absolute; width: 1px; height: 1px; overflow: hidden; clip: rect(0 0 0 0); }

        /* ---------- top bar ---------- */
        .topbar { background: var(--blue-dd); color: #cfe0ff; font-size: 13px; }
        .topbar .wrap { display: flex; justify-content: space-between; align-items: center; gap: 12px; padding-top: 8px; padding-bottom: 8px; flex-wrap: wrap; }
        .topbar b { color: var(--yellow); }
        .topbar a:hover { color: #fff; }
        .topbar .tb-right { display: flex; gap: 18px; }

        /* ---------- nav ---------- */
        .nav { position: sticky; top: 0; z-index: 50; background: rgba(255,255,255,.96); backdrop-filter: blur(8px); border-bottom: 1px solid var(--line); }
        .nav .wrap { display: flex; align-items: center; justify-content: space-between; height: 66px; gap: 16px; }
        .brand { display: flex; align-items: center; gap: 10px; min-width: 0; }
        .brand img { height: 42px; width: auto; }
        .brand-ico { width: 42px; height: 42px; border-radius: 50%; background: var(--blue); color: #fff; display: grid; place-items: center; font-size: 20px; flex: none; }
        .brand-t { line-height: 1.15; min-width: 0; }
        .brand-t b { display: block; font-weight: 800; font-size: 17px; color: var(--red); letter-spacing: .3px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .brand-t span { font-size: 11.5px; color: var(--blue); font-weight: 600; }
        .menu { display: flex; align-items: center; gap: 4px; }
        .menu a { padding: 8px 13px; border-radius: 999px; font-weight: 600; font-size: 14px; color: var(--blue-d); }
        .menu a:hover { background: var(--sky); }
        .menu a.cta { background: var(--red); color: #fff; margin-left: 6px; box-shadow: 0 6px 16px rgba(229,38,44,.3); }
        .menu a.cta:hover { background: var(--red-d); }

        /* ---------- hero ---------- */
        .hero { position: relative; overflow: hidden; background: linear-gradient(180deg, #fff 0%, var(--sky) 55%, var(--sky-2) 100%); padding: 44px 0 120px; }
        .hero::before { content: ""; position: absolute; width: 520px; height: 520px; right: -140px; top: -180px; border-radius: 50%; background: radial-gradient(circle, rgba(255,198,26,.35), rgba(255,198,26,0) 70%); }
        .hero-grid { position: relative; display: grid; grid-template-columns: 1.1fr .9fr; gap: 36px; align-items: center; }
        .logo-ssat { display: flex; align-items: flex-end; gap: 2px; font-weight: 900; font-size: 84px; line-height: .9; letter-spacing: -2px; color: var(--blue); }
        .logo-ssat .dash { font-size: 40px; margin: 0 2px 10px; opacity: .7; }
        .logo-ssat .a { color: var(--orange); position: relative; }
        .logo-ssat .a i { position: absolute; left: 50%; top: -22px; transform: translateX(-50%); font-size: 22px; color: var(--yellow); }
        .tagline { margin: 10px 0 0; font-weight: 600; color: var(--blue-d); font-size: 16.5px; }
        .hero h1 { margin: 22px 0 6px; font-size: clamp(20px, 3vw, 26px); font-weight: 700; color: var(--ink); }
        .hero h1 small { display: block; font-size: 15px; font-weight: 600; color: var(--muted); margin-bottom: 4px; }
        .hl { font-size: clamp(34px, 6vw, 58px); font-weight: 800; line-height: 1.12; color: var(--blue); margin: 4px 0 6px; text-shadow: 0 2px 0 #fff, 0 6px 22px rgba(27,79,156,.18); }
        .hl em { font-style: normal; color: var(--red); }
        .hero-sub { font-weight: 700; font-size: clamp(18px, 2.4vw, 24px); color: var(--ink); margin: 0 0 22px; }
        .hero-actions { display: flex; gap: 12px; flex-wrap: wrap; align-items: center; }
        .btn { display: inline-flex; align-items: center; gap: 10px; border: 0; cursor: pointer; font: inherit; font-weight: 700; border-radius: 999px; padding: 15px 28px; font-size: 16px; transition: transform .15s, box-shadow .15s, background .15s; }
        .btn:active { transform: translateY(1px); }
        .btn-free { background: linear-gradient(135deg, var(--pink), var(--red)); color: #fff; box-shadow: 0 10px 26px rgba(229,38,44,.38); }
        .btn-free:hover { transform: translateY(-2px); box-shadow: 0 14px 30px rgba(229,38,44,.45); }
        .btn-free .tag { background: #fff; color: var(--red); font-size: 11px; font-weight: 800; padding: 3px 8px; border-radius: 6px; letter-spacing: .6px; }
        .btn-line { background: #fff; color: var(--blue-d); border: 2px solid var(--blue); }
        .btn-line:hover { background: var(--blue); color: #fff; }
        .hero-note { margin-top: 18px; display: flex; flex-wrap: wrap; gap: 8px 18px; font-size: 13.5px; color: var(--muted); font-weight: 500; }
        .hero-note i { color: #16a34a; margin-right: 5px; }

        .hero-card { position: relative; }
        .hero-photo { border-radius: 26px; overflow: hidden; box-shadow: var(--shadow); border: 6px solid #fff; background: var(--blue); aspect-ratio: 4/3; position: relative; }
        .hero-photo img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .hero-photo.fallback { background: linear-gradient(145deg, var(--blue) 0%, var(--blue-dd) 100%); display: grid; place-items: center; color: #fff; text-align: center; }
        .hero-photo.fallback .cap { font-size: 120px; line-height: 1; color: var(--yellow); filter: drop-shadow(0 10px 20px rgba(0,0,0,.3)); }
        .hero-photo.fallback p { margin: 8px 0 0; font-weight: 600; letter-spacing: 1px; font-size: 14px; color: #cfe0ff; }
        .badge-cbse { position: absolute; right: -14px; bottom: -34px; width: 150px; height: 150px; border-radius: 50%; background: var(--blue-d); color: #fff; border: 5px solid #fff; display: grid; place-content: center; text-align: center; box-shadow: var(--shadow); padding: 10px; }
        .badge-cbse small { font-size: 9.5px; opacity: .9; }
        .badge-cbse b { font-size: 28px; font-weight: 900; line-height: 1.05; color: var(--yellow); }
        .badge-cbse span { font-size: 11px; font-weight: 600; line-height: 1.25; }

        /* swoosh like the pamphlet */
        .swoosh { position: absolute; left: 0; right: 0; bottom: -1px; height: 90px; line-height: 0; }
        .swoosh svg { width: 100%; height: 100%; display: block; }

        /* ---------- key facts ribbon ---------- */
        .facts { margin-top: -62px; position: relative; z-index: 5; }
        .facts-card { background: #fff; border-radius: 22px; box-shadow: var(--shadow); display: grid; grid-template-columns: repeat(4, 1fr); border: 1px solid var(--line); overflow: hidden; }
        .fact { padding: 22px 20px; border-right: 1px solid var(--line); display: flex; gap: 14px; align-items: flex-start; }
        .fact:last-child { border-right: 0; }
        .fact .ic { flex: none; width: 46px; height: 46px; border-radius: 14px; display: grid; place-items: center; font-size: 20px; color: #fff; }
        .ic.b { background: var(--blue); } .ic.r { background: var(--red); } .ic.o { background: var(--orange); } .ic.g { background: #16a34a; }
        .fact small { display: block; font-size: 11.5px; letter-spacing: .6px; text-transform: uppercase; color: var(--muted); font-weight: 600; }
        .fact b { font-size: 15.5px; line-height: 1.3; display: block; }

        /* ---------- sections ---------- */
        section { scroll-margin-top: 78px; }
        .sec { padding: 84px 0 20px; }
        .pill-title { display: table; margin: 0 auto 10px; border: 2px solid var(--blue); border-radius: 999px; padding: 8px 34px; font-weight: 800; font-size: 20px; color: var(--blue); letter-spacing: 1px; text-transform: uppercase; background: #fff; }
        .sec-sub { text-align: center; color: var(--muted); max-width: 640px; margin: 0 auto 34px; }
        .why-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 18px; }
        .why { background: #fff; border: 1px solid var(--line); border-radius: var(--radius); padding: 26px 22px; text-align: center; transition: transform .2s, box-shadow .2s; position: relative; overflow: hidden; }
        .why::after { content: ""; position: absolute; left: 0; right: 0; top: 0; height: 5px; background: var(--c, var(--blue)); }
        .why:hover { transform: translateY(-5px); box-shadow: var(--shadow); }
        .why .ic { width: 66px; height: 66px; margin: 0 auto 14px; border-radius: 50%; background: var(--c, var(--blue)); color: #fff; display: grid; place-items: center; font-size: 28px; }
        .why h3 { margin: 0 0 2px; font-size: 16px; font-weight: 800; color: var(--ink); text-transform: uppercase; letter-spacing: .4px; }
        .why .hi { color: var(--c, var(--blue)); font-weight: 700; margin: 0 0 8px; font-size: 15px; }
        .why p { margin: 0; font-size: 13.5px; color: var(--muted); }

        /* ---------- exam details ---------- */
        .exam { margin-top: 70px; background: linear-gradient(135deg, var(--blue-d), var(--blue-dd)); color: #fff; position: relative; overflow: hidden; }
        .exam::before { content: ""; position: absolute; right: -80px; top: -80px; width: 320px; height: 320px; border-radius: 50%; background: rgba(255,198,26,.12); }
        .exam .wrap { position: relative; display: grid; grid-template-columns: 1fr 1fr; gap: 42px; padding-top: 58px; padding-bottom: 58px; align-items: center; }
        .eyebrow { display: inline-block; background: var(--pink); color: #fff; font-weight: 700; font-size: 13px; padding: 6px 14px; border-radius: 8px; margin-bottom: 14px; }
        .exam h2 { margin: 0 0 14px; font-size: clamp(24px, 3.4vw, 34px); font-weight: 800; line-height: 1.25; }
        .exam ul { list-style: none; padding: 0; margin: 0 0 20px; display: grid; gap: 11px; }
        .exam li { display: flex; gap: 12px; align-items: flex-start; font-size: 15.5px; }
        .exam li i { color: var(--yellow); margin-top: 5px; }
        .chips { display: flex; flex-wrap: wrap; gap: 8px; }
        .chip { background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.25); padding: 7px 14px; border-radius: 999px; font-size: 14px; font-weight: 600; }
        .date-card { background: #fff; color: var(--ink); border-radius: 24px; overflow: hidden; box-shadow: 0 24px 60px rgba(0,0,0,.35); }
        .date-card .top { background: var(--blue); color: #cfe0ff; text-align: center; padding: 14px; font-weight: 600; }
        .date-card .big { text-align: center; padding: 22px 16px 8px; }
        .date-card .big b { display: block; font-size: clamp(26px, 4vw, 36px); font-weight: 800; color: var(--blue-d); }
        .date-card .big span { display: inline-block; margin-top: 6px; background: var(--yellow); color: var(--blue-dd); font-weight: 800; padding: 6px 18px; border-radius: 999px; font-size: 15px; }
        .count { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; padding: 18px 20px 24px; }
        .count div { background: var(--sky); border-radius: 14px; text-align: center; padding: 12px 4px; }
        .count b { display: block; font-size: 28px; font-weight: 800; color: var(--blue-d); line-height: 1.1; font-variant-numeric: tabular-nums; }
        .count small { font-size: 11px; text-transform: uppercase; letter-spacing: .6px; color: var(--muted); font-weight: 600; }

        /* ---------- how to register ---------- */
        .steps { display: grid; grid-template-columns: repeat(3, 1fr); gap: 18px; }
        .step { background: var(--sky); border-radius: var(--radius); padding: 24px; position: relative; }
        .step .n { position: absolute; top: -14px; left: 22px; width: 34px; height: 34px; border-radius: 50%; background: var(--red); color: #fff; font-weight: 800; display: grid; place-items: center; box-shadow: 0 6px 14px rgba(229,38,44,.35); }
        .step h4 { margin: 8px 0 4px; font-size: 16px; font-weight: 800; }
        .step p { margin: 0; color: var(--muted); font-size: 14px; }
        .step a.lnk { color: var(--red); font-weight: 700; }

        /* ---------- registration form ---------- */
        .reg { padding: 76px 0 30px; }
        .reg-box { background: #fff; border-radius: 26px; box-shadow: var(--shadow); border: 1px solid var(--line); overflow: hidden; max-width: 860px; margin: 0 auto; }
        .reg-head { background: linear-gradient(120deg, var(--red), var(--pink)); color: #fff; padding: 26px 30px; display: flex; gap: 16px; align-items: center; }
        .reg-head .ic { width: 54px; height: 54px; border-radius: 16px; background: rgba(255,255,255,.2); display: grid; place-items: center; font-size: 24px; flex: none; }
        .reg-head h2 { margin: 0; font-size: 22px; font-weight: 800; }
        .reg-head p { margin: 2px 0 0; font-size: 14px; opacity: .95; }
        .reg-body { padding: 28px 30px 34px; }
        .fs-title { display: flex; align-items: center; gap: 10px; font-weight: 800; color: var(--blue-d); font-size: 15px; margin: 26px 0 14px; text-transform: uppercase; letter-spacing: .5px; }
        .fs-title:first-child { margin-top: 0; }
        .fs-title span { width: 26px; height: 26px; border-radius: 50%; background: var(--blue); color: #fff; font-size: 13px; display: grid; place-items: center; }
        .fs-title::after { content: ""; flex: 1; height: 1px; background: var(--line); }
        .grid2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px 18px; }
        .full { grid-column: 1 / -1; }
        label.l { display: block; font-size: 13px; font-weight: 600; color: var(--blue-d); margin-bottom: 6px; }
        label.l i { color: var(--red); font-style: normal; }
        .in, select.in, textarea.in { width: 100%; font: inherit; font-size: 15.5px; padding: 13px 15px; border-radius: 12px; border: 1.5px solid var(--line); background: #f9fbfe; color: var(--ink); outline: none; transition: border-color .15s, box-shadow .15s, background .15s; appearance: none; -webkit-appearance: none; }
        select.in { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='8' viewBox='0 0 14 8'%3E%3Cpath d='M1 1l6 6 6-6' fill='none' stroke='%231b4f9c' stroke-width='2' stroke-linecap='round'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 15px center; padding-right: 40px; }
        .in:focus { border-color: var(--blue); background: #fff; box-shadow: 0 0 0 4px rgba(27,79,156,.14); }
        .in:user-invalid { border-color: var(--red); }
        .hint { font-size: 12px; color: var(--muted); margin-top: 5px; }
        .seg { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
        .seg label { cursor: pointer; }
        .seg input { position: absolute; opacity: 0; pointer-events: none; }
        .seg span { display: flex; align-items: center; justify-content: center; gap: 8px; text-align: center; padding: 12px 10px; border: 1.5px solid var(--line); border-radius: 12px; font-weight: 600; font-size: 14px; color: var(--muted); background: #f9fbfe; transition: all .15s; height: 100%; }
        .seg input:checked + span { border-color: var(--blue); background: #eaf2ff; color: var(--blue-d); box-shadow: 0 0 0 3px rgba(27,79,156,.12); }
        .seg input:focus-visible + span { outline: 2px solid var(--blue); outline-offset: 2px; }
        .submit { margin-top: 26px; width: 100%; justify-content: center; font-size: 17px; padding: 17px 24px; }
        .submit[disabled] { opacity: .75; cursor: progress; }
        .secure { text-align: center; font-size: 12.5px; color: var(--muted); margin-top: 12px; }
        .secure i { color: #16a34a; }
        .closed { text-align: center; padding: 40px 24px; }
        .closed i { font-size: 44px; color: var(--red); }
        .closed h3 { margin: 10px 0 6px; color: #991b1b; }

        .alt { max-width: 860px; margin: 22px auto 0; display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .alt-card { border-radius: var(--radius); padding: 20px 22px; display: flex; gap: 14px; align-items: center; border: 1px solid var(--line); background: #fff; }
        .alt-card .ic { font-size: 30px; flex: none; }
        .alt-card b { display: block; font-size: 15px; }
        .alt-card span { font-size: 13px; color: var(--muted); }
        .alt-card.wa { background: #effcf3; border-color: #bbf0cb; } .alt-card.wa .ic { color: #16a34a; }
        .alt-card.ph .ic { color: var(--blue); }

        /* ---------- status ---------- */
        .status { max-width: 860px; margin: 40px auto 0; background: var(--sky); border-radius: 22px; padding: 26px 30px; display: grid; grid-template-columns: 1fr 1.2fr; gap: 22px; align-items: center; border: 1px solid var(--sky-2); }
        .status h3 { margin: 0 0 4px; font-size: 18px; font-weight: 800; color: var(--blue-d); }
        .status p { margin: 0; font-size: 13.5px; color: var(--muted); }
        .status form { display: flex; gap: 10px; }
        .status .in { background: #fff; }
        .status .btn { padding: 13px 22px; font-size: 15px; background: var(--blue); color: #fff; flex: none; }
        .status .btn:hover { background: var(--blue-d); }

        /* ---------- school band ---------- */
        .school { margin-top: 70px; background: var(--sky); padding: 56px 0; }
        .school .wrap { display: grid; grid-template-columns: 1.3fr 1fr; gap: 36px; align-items: center; }
        .school h2 { margin: 0 0 6px; font-size: clamp(22px, 3vw, 30px); font-weight: 800; color: var(--blue-d); text-transform: uppercase; letter-spacing: .5px; }
        .school p { margin: 0 0 14px; color: var(--muted); }
        .school .academy { display: inline-flex; flex-direction: column; background: #fff; border-radius: 16px; padding: 16px 22px; box-shadow: var(--shadow); border-left: 6px solid var(--red); }
        .school .academy b { font-size: 22px; font-weight: 900; color: var(--red); line-height: 1.1; }
        .school .academy b span { color: var(--ink); display: block; letter-spacing: 1px; font-size: 17px; }
        .school .academy small { margin-top: 6px; background: var(--blue); color: #fff; font-weight: 600; padding: 3px 10px; border-radius: 4px; display: inline-block; font-size: 12px; }
        .aff { background: var(--blue-d); color: #fff; border-radius: 50%; width: 220px; height: 220px; margin: 0 auto; display: grid; place-content: center; text-align: center; box-shadow: var(--shadow); border: 6px solid #fff; padding: 20px; }
        .aff small { font-size: 12px; opacity: .85; }
        .aff b { font-size: 46px; font-weight: 900; line-height: 1; color: #fff; }
        .aff span { font-weight: 600; font-size: 15px; margin-top: 4px; }
        .aff em { font-style: normal; font-size: 13px; opacity: .85; }

        /* ---------- faq ---------- */
        .faq { max-width: 820px; margin: 0 auto; display: grid; gap: 12px; }
        .faq details { border: 1px solid var(--line); border-radius: 14px; background: #fff; padding: 0 20px; }
        .faq details[open] { border-color: var(--blue); box-shadow: 0 8px 24px rgba(27,79,156,.1); }
        .faq summary { cursor: pointer; list-style: none; padding: 17px 0; font-weight: 700; display: flex; justify-content: space-between; gap: 14px; align-items: center; }
        .faq summary::-webkit-details-marker { display: none; }
        .faq summary::after { content: "\f107"; font-family: FontAwesome; font-size: 22px; color: var(--blue); transition: transform .2s; }
        .faq details[open] summary::after { transform: rotate(180deg); }
        .faq .ans { padding: 0 0 18px; color: var(--muted); font-size: 14.5px; }

        /* ---------- footer ---------- */
        footer { margin-top: 70px; background: var(--blue-dd); color: #b9c9ea; }
        footer .main { display: flex; flex-wrap: wrap; justify-content: space-between; gap: 26px; padding: 42px 0 30px; }
        footer h4 { color: #fff; margin: 0 0 6px; font-size: 20px; font-weight: 800; letter-spacing: .5px; }
        footer .c { display: grid; gap: 8px; font-size: 14.5px; }
        footer .c i { color: var(--yellow); width: 20px; }
        footer .c a:hover { color: #fff; }
        footer .copy { border-top: 1px solid rgba(255,255,255,.1); padding: 16px 0; font-size: 12.5px; text-align: center; }
        .bar { background: var(--blue); color: #fff; text-align: center; padding: 12px; font-weight: 600; font-size: 15px; }

        .m-cta { display: none; }

        @media (max-width: 960px) {
            .hero-grid, .exam .wrap, .school .wrap { grid-template-columns: 1fr; }
            .hero-card { max-width: 520px; margin: 0 auto; width: 100%; }
            .facts-card { grid-template-columns: 1fr 1fr; }
            .fact:nth-child(2) { border-right: 0; }
            .fact:nth-child(-n+2) { border-bottom: 1px solid var(--line); }
            .why-grid { grid-template-columns: 1fr 1fr; }
            .steps { grid-template-columns: 1fr; gap: 26px; }
            .status { grid-template-columns: 1fr; }
            .menu a:not(.cta) { display: none; }
        }
        @media (max-width: 600px) {
            .wrap { padding: 0 16px; }
            .topbar .tb-right { display: none; }
            .topbar .wrap { justify-content: center; text-align: center; }
            .hero { padding: 26px 0 100px; }
            .logo-ssat { font-size: 64px; }
            .badge-cbse { width: 112px; height: 112px; right: -4px; bottom: -26px; padding: 6px; }
            .badge-cbse b { font-size: 21px; } .badge-cbse span { font-size: 9px; } .badge-cbse small { font-size: 8px; }
            .btn { width: 100%; justify-content: center; }
            .facts-card { grid-template-columns: 1fr; }
            .fact { border-right: 0 !important; border-bottom: 1px solid var(--line); }
            .fact:last-child { border-bottom: 0; }
            .why-grid { grid-template-columns: 1fr; }
            .pill-title { font-size: 16px; padding: 7px 22px; }
            .grid2, .alt { grid-template-columns: 1fr; }
            .reg-head { padding: 20px; } .reg-body { padding: 22px 18px 28px; }
            .status form { flex-direction: column; }
            .count b { font-size: 22px; }
            .m-cta { display: flex; position: fixed; left: 0; right: 0; bottom: 0; z-index: 60; gap: 10px; padding: 10px 14px calc(10px + env(safe-area-inset-bottom)); background: rgba(255,255,255,.97); border-top: 1px solid var(--line); box-shadow: 0 -8px 24px rgba(18,53,111,.12); }
            .m-cta .btn { padding: 13px 14px; font-size: 15px; }
            .m-cta .btn-free { flex: 1; } .m-cta .wa { background: #16a34a; color: #fff; width: 54px; flex: none; padding: 0; justify-content: center; font-size: 24px; }
            footer { padding-bottom: 70px; }
        }
        @media (prefers-reduced-motion: reduce) { * { transition: none !important; } html { scroll-behavior: auto; } }
    </style>
</head>
<body>

<div class="topbar">
    <div class="wrap">
        <span><b>SUNRISE CAREER ACADEMY</b> &nbsp;·&nbsp; For NEET, IIT-JEE &amp; NDA, CLAT</span>
        <div class="tb-right">
            <a href="tel:+919828512821"><i class="fa fa-phone"></i> 98285 12821</a>
            <a href="tel:+919783200821"><i class="fa fa-phone"></i> 97832 00821</a>
        </div>
    </div>
</div>

<header class="nav">
    <div class="wrap">
        <a href="#top" class="brand">
            <?php if ($school_logo) { ?>
                <img src="<?php echo $school_logo; ?>" alt="" onerror="this.outerHTML='<span class=&quot;brand-ico&quot;><i class=&quot;fa fa-sun-o&quot;></i></span>'">
            <?php } else { ?>
                <span class="brand-ico"><i class="fa fa-sun-o"></i></span>
            <?php } ?>
            <span class="brand-t"><b>SUNRISE</b><span>Career Academy · S-SAT</span></span>
        </a>
        <nav class="menu" aria-label="Main">
            <a href="#why">Why SSAT</a>
            <a href="#exam">Exam Details</a>
            <a href="#how">How to Register</a>
            <a href="#faq">FAQ</a>
            <a href="#register" class="cta"><i class="fa fa-pencil"></i> Free Register</a>
        </nav>
    </div>
</header>

<main id="top">
<!-- ================= HERO ================= -->
<section class="hero">
    <div class="wrap hero-grid">
        <div>
            <div class="logo-ssat" role="img" aria-label="S-SAT">
                <span>S</span><span class="dash">-</span><span>S</span><span class="a"><i class="fa fa-star"></i>A</span><span>T</span>
            </div>
            <p class="tagline">Where Talent Meets Opportunity<br>&amp; Gets Platform to Excel</p>

            <h1 class="hi"><small>कक्षा 6 से 12 में प्रवेश के लिए</small></h1>
            <div class="hl hi">लाखों में <em>स्कॉलरशिप</em></div>
            <p class="hero-sub hi">जीतने का सुनहरा मौका</p>

            <div class="hero-actions">
                <a href="#register" class="btn btn-free"><i class="fa fa-pencil-square-o"></i> रजिस्टर करें <span class="tag">FREE</span></a>
                <a href="#status" class="btn btn-line"><i class="fa fa-id-card-o"></i> Admit Card / Status</a>
            </div>
            <div class="hero-note">
                <span><i class="fa fa-check-circle"></i> कक्षा 5 से 11 के सभी विद्यार्थी पात्र</span>
                <span><i class="fa fa-check-circle"></i> हिंदी एवं अंग्रेजी माध्यम</span>
                <span><i class="fa fa-check-circle"></i> Online &amp; Offline</span>
            </div>
        </div>

        <div class="hero-card">
            <?php if ($hero_img) { ?>
                <div class="hero-photo"><img src="<?php echo $hero_img; ?>" alt="Sunrise School campus"></div>
            <?php } else { ?>
                <div class="hero-photo fallback">
                    <div>
                        <div class="cap"><i class="fa fa-graduation-cap"></i></div>
                        <p>SUNRISE INTERNATIONAL PUBLIC SCHOOL</p>
                    </div>
                </div>
            <?php } ?>
            <div class="badge-cbse">
                <small>Affiliation No. : 1730833</small>
                <b>CBSE</b>
                <span>Nursery to 12<sup>th</sup><br>Science · Arts · Commerce</span>
            </div>
        </div>
    </div>
    <div class="swoosh" aria-hidden="true">
        <svg viewBox="0 0 1440 90" preserveAspectRatio="none">
            <path d="M0,50 C300,110 900,-10 1440,45 L1440,90 L0,90 Z" fill="#ffc61a"/>
            <path d="M0,62 C320,120 920,5 1440,58 L1440,90 L0,90 Z" fill="#1b4f9c"/>
            <path d="M0,78 C340,118 940,28 1440,72 L1440,90 L0,90 Z" fill="#fff"/>
        </svg>
    </div>
</section>

<!-- ================= KEY FACTS ================= -->
<div class="facts">
    <div class="wrap">
        <div class="facts-card">
            <div class="fact"><span class="ic r"><i class="fa fa-calendar"></i></span><div><small>परीक्षा तिथि</small><b class="hi"><?php echo $exam_hi_date; ?></b></div></div>
            <div class="fact"><span class="ic b"><i class="fa fa-clock-o"></i></span><div><small>समय</small><b class="hi"><?php echo date('g:i', $exam_ts) . ' बजे से ' . date('g:i', $exam_ts + 7200) . ' बजे तक'; ?></b></div></div>
            <div class="fact"><span class="ic o"><i class="fa fa-laptop"></i></span><div><small>परीक्षा माध्यम</small><b class="hi">ऑनलाइन और ऑफलाइन</b></div></div>
            <div class="fact"><span class="ic g"><i class="fa fa-language"></i></span><div><small>भाषा</small><b class="hi">हिंदी एवं अंग्रेजी</b></div></div>
        </div>
    </div>
</div>

<!-- ================= WHY ================= -->
<section class="sec" id="why">
    <div class="wrap">
        <div class="pill-title">Why the Exam</div>
        <p class="sec-sub">Every participant walks away with more than a score — recognition, guidance and a real chance at a scholarship.</p>
        <div class="why-grid">
            <div class="why" style="--c: var(--red)">
                <div class="ic"><i class="fa fa-certificate"></i></div>
                <h3>Certificate of Participation</h3><p class="hi">भागीदारी प्रमाण पत्र</p>
                <p>Every student who appears receives an official certificate.</p>
            </div>
            <div class="why" style="--c: var(--blue)">
                <div class="ic"><i class="fa fa-graduation-cap"></i></div>
                <h3>Scholarship for Admission</h3><p class="hi">प्रवेश पर स्कॉलरशिप</p>
                <p>Lakhs in scholarships for admission to Class 6 to 12.</p>
            </div>
            <div class="why" style="--c: var(--orange)">
                <div class="ic"><i class="fa fa-compass"></i></div>
                <h3>Career Counselling &amp; Guidance</h3><p class="hi">कैरियर काउंसलिंग एवं मार्गदर्शन</p>
                <p>Expert guidance towards NEET, IIT-JEE, NDA and CLAT.</p>
            </div>
            <div class="why" style="--c: #16a34a">
                <div class="ic"><i class="fa fa-gift"></i></div>
                <h3>Cash Award &amp; Gift</h3><p class="hi">नकद पुरस्कार एवं उपहार</p>
                <p>Cash awards and gifts for the top performers.</p>
            </div>
        </div>
    </div>
</section>

<!-- ================= EXAM DETAILS ================= -->
<section class="exam" id="exam">
    <div class="wrap">
        <div>
            <span class="eyebrow hi">सत्र 2026-27</span>
            <h2 class="hi">कक्षा 5 से 11 में पढ़ने वाले सभी छात्र/छात्राएं शामिल हो सकते हैं।</h2>
            <ul class="hi">
                <li><i class="fa fa-check-circle"></i> परीक्षा का माध्यम: <b>हिंदी एवं अंग्रेजी</b></li>
                <li><i class="fa fa-check-circle"></i> परीक्षा <b>ऑनलाइन और ऑफलाइन</b> दोनों तरीके से होगी</li>
                <li><i class="fa fa-check-circle"></i> रजिस्ट्रेशन <b>बिल्कुल निःशुल्क</b></li>
            </ul>
            <div class="hi" style="font-weight:700;margin-bottom:10px;">परीक्षा केंद्र</div>
            <div class="chips hi">
                <span class="chip"><i class="fa fa-map-marker"></i> भीलवाड़ा</span>
                <span class="chip"><i class="fa fa-map-marker"></i> जोधपुर</span>
                <span class="chip"><i class="fa fa-map-marker"></i> बाड़मेर</span>
                <span class="chip"><i class="fa fa-map-marker"></i> नेछवा (सीकर)</span>
            </div>
        </div>
        <div class="date-card">
            <div class="top hi">परीक्षा का दिन व दिनांक</div>
            <div class="big hi">
                <b><?php echo $exam_hi_date; ?></b>
                <span>प्रातः: <?php echo date('H:i', $exam_ts); ?> बजे से दोपहर <?php echo date('g:i', $exam_ts + 7200); ?> तक</span>
            </div>
            <div class="count" id="countdown" data-ts="<?php echo $exam_ts * 1000; ?>">
                <div><b id="cd_d">--</b><small>Days</small></div>
                <div><b id="cd_h">--</b><small>Hours</small></div>
                <div><b id="cd_m">--</b><small>Mins</small></div>
                <div><b id="cd_s">--</b><small>Secs</small></div>
            </div>
        </div>
    </div>
</section>

<!-- ================= HOW ================= -->
<section class="sec" id="how">
    <div class="wrap">
        <div class="pill-title hi">रजिस्ट्रेशन करने का आसान तरीका</div>
        <p class="sec-sub">Three easy ways to register — pick whichever suits you.</p>
        <div class="steps">
            <div class="step">
                <span class="n"><i class="fa fa-globe"></i></span>
                <h4 class="hi">ऑनलाइन</h4>
                <p class="hi">नीचे दिए गए फॉर्म को भरें एवं जानकारी दर्ज करें, या QR कोड स्कैन करें। <a class="lnk" href="#register">अभी रजिस्टर करें →</a></p>
            </div>
            <div class="step">
                <span class="n"><i class="fa fa-whatsapp"></i></span>
                <h4 class="hi">WhatsApp Message द्वारा</h4>
                <p class="hi">98285 12821 / 97832 00821 पर विद्यार्थी का नाम, कक्षा, पिता का नाम, स्कूल का नाम एवं स्थायी पता भेजें। <a class="lnk" href="<?php echo $wa_link; ?>" target="_blank" rel="noopener">WhatsApp खोलें →</a></p>
            </div>
            <div class="step">
                <span class="n"><i class="fa fa-qrcode"></i></span>
                <h4>Scan to Register</h4>
                <p>Scan the QR code on our pamphlet, poster or hoarding with your phone camera to open this page instantly.</p>
            </div>
        </div>
    </div>
</section>

<!-- ================= REGISTER ================= -->
<section class="reg" id="register">
    <div class="wrap">
        <div class="pill-title">Free Register</div>
        <p class="sec-sub">Takes about 2 minutes. You get your Roll Number instantly — keep it safe for your admit card and result.</p>

        <div class="reg-box">
            <div class="reg-head">
                <div class="ic"><i class="fa fa-pencil-square-o"></i></div>
                <div>
                    <h2>Candidate Registration</h2>
                    <p><?php echo htmlspecialchars($exam_title); ?> · Free of cost</p>
                </div>
            </div>

            <div class="reg-body">
            <?php if (isset($registration_closed) && $registration_closed) { ?>
                <div class="closed">
                    <i class="fa fa-ban"></i>
                    <h3>Registrations Closed</h3>
                    <p>Online registration for <strong><?php echo htmlspecialchars($exam_title); ?></strong> has closed.
                        <?php echo !empty($selected_exam['registration_stopped_at']) ? '<br>Closed on <strong>' . date('d M Y, h:i A', strtotime($selected_exam['registration_stopped_at'])) . '</strong>.' : ''; ?></p>
                    <p>Questions? Call <a href="tel:+919828512821"><b>98285 12821</b></a>.</p>
                </div>
            <?php } else { ?>
                <form action="<?php echo site_url('scholarshipregister/submit'); ?>" method="POST" id="regForm" autocomplete="on">
                    <?php if (!empty($selected_exam) && !empty($is_direct_link)) { ?>
                        <input type="hidden" name="exam_id" value="<?php echo (int) $selected_exam['id']; ?>">
                    <?php } else { ?>
                        <div class="full" style="margin-bottom:18px;">
                            <label class="l" for="exam_id">Examination <i>*</i></label>
                            <select name="exam_id" id="exam_id" class="in" required onchange="location.href='<?php echo site_url('scholarshipregister/apply/'); ?>' + this.options[this.selectedIndex].getAttribute('data-code')">
                                <?php if (!empty($exams)) {
                                    foreach ($exams as $e) { ?>
                                        <option value="<?php echo (int) $e['id']; ?>" data-code="<?php echo htmlspecialchars(urlencode($e['exam_code'])); ?>" <?php echo ($selected_exam_id == $e['id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($e['title']); ?> (<?php echo htmlspecialchars($e['exam_code']); ?>) – <?php echo ($e['is_paid'] == 1) ? '₹' . number_format($e['registration_fee'], 2) : 'FREE'; ?>
                                        </option>
                                    <?php }
                                } ?>
                            </select>
                        </div>
                    <?php } ?>

                    <div class="fs-title"><span>1</span> Student Details</div>
                    <div class="grid2">
                        <div class="full">
                            <label class="l" for="candidate_name">Student Full Name <i>*</i></label>
                            <input type="text" id="candidate_name" name="candidate_name" class="in" placeholder="As per school records" autocomplete="name" required maxlength="120">
                        </div>
                        <div>
                            <label class="l" for="class_id">Present Class <i>*</i></label>
                            <select name="class_id" id="class_id" class="in" required>
                                <option value="">Select class</option>
                                <?php foreach ($classList as $c) { ?>
                                    <option value="<?php echo (int) $c['id']; ?>">Class <?php echo htmlspecialchars($c['class']); ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <?php if (isFieldVisible('dob', $fields_map)) { ?>
                            <div>
                                <label class="l" for="dob">Date of Birth <?php echo isFieldRequired('dob', $fields_map) ? '<i>*</i>' : ''; ?></label>
                                <input type="date" id="dob" name="dob" class="in" max="<?php echo date('Y-m-d'); ?>" <?php echo isFieldRequired('dob', $fields_map) ? 'required' : ''; ?>>
                            </div>
                        <?php } ?>
                        <?php if (isFieldVisible('gender', $fields_map)) { ?>
                            <div>
                                <label class="l" for="gender">Gender <?php echo isFieldRequired('gender', $fields_map) ? '<i>*</i>' : ''; ?></label>
                                <select name="gender" id="gender" class="in" <?php echo isFieldRequired('gender', $fields_map) ? 'required' : ''; ?>>
                                    <option value="">Select</option>
                                    <option>Male</option><option>Female</option><option>Other</option>
                                </select>
                            </div>
                        <?php } ?>
                        <?php if (isFieldVisible('category', $fields_map)) { ?>
                            <div>
                                <label class="l" for="category">Category <?php echo isFieldRequired('category', $fields_map) ? '<i>*</i>' : ''; ?></label>
                                <select name="category" id="category" class="in" <?php echo isFieldRequired('category', $fields_map) ? 'required' : ''; ?>>
                                    <option>General</option><option>OBC</option><option>SC</option><option>ST</option><option>SBC</option>
                                </select>
                            </div>
                        <?php } ?>
                    </div>

                    <?php if (isFieldVisible('school_name', $fields_map)) { ?>
                        <div class="fs-title"><span>2</span> Present School</div>
                        <div class="seg" role="radiogroup" aria-label="School type">
                            <label><input type="radio" name="school_type" value="internal" checked onchange="toggleSchool(this.value)"><span><i class="fa fa-university"></i> <?php echo htmlspecialchars($school_name); ?></span></label>
                            <label><input type="radio" name="school_type" value="external" onchange="toggleSchool(this.value)"><span><i class="fa fa-building-o"></i> Another School</span></label>
                        </div>
                        <div id="other_school_box" style="display:none;margin-top:12px;">
                            <label class="l" for="other_school_name">School Name <?php echo isFieldRequired('school_name', $fields_map) ? '<i>*</i>' : ''; ?></label>
                            <input type="text" id="other_school_name" name="other_school_name" class="in" placeholder="Type the student's current school" maxlength="150">
                        </div>
                    <?php } ?>

                    <div class="fs-title"><span><?php echo isFieldVisible('school_name', $fields_map) ? '3' : '2'; ?></span> Contact &amp; Family</div>
                    <div class="grid2">
                        <div>
                            <label class="l" for="mobile">Mobile (WhatsApp) <i>*</i></label>
                            <input type="tel" id="mobile" name="mobile" class="in" inputmode="numeric" pattern="[0-9]{10}" maxlength="10" placeholder="10-digit mobile number" autocomplete="tel-national" required title="Enter a 10-digit mobile number">
                            <div class="hint">Admit card &amp; result updates will be sent here.</div>
                        </div>
                        <div>
                            <label class="l" for="email">Email <small style="font-weight:500;color:var(--muted)">(optional)</small></label>
                            <input type="email" id="email" name="email" class="in" placeholder="name@example.com" autocomplete="email">
                        </div>
                        <?php if (isFieldVisible('father_name', $fields_map)) { ?>
                            <div>
                                <label class="l" for="father_name">Father's Name <?php echo isFieldRequired('father_name', $fields_map) ? '<i>*</i>' : ''; ?></label>
                                <input type="text" id="father_name" name="father_name" class="in" <?php echo isFieldRequired('father_name', $fields_map) ? 'required' : ''; ?> maxlength="120">
                            </div>
                        <?php } ?>
                        <?php if (isFieldVisible('mother_name', $fields_map)) { ?>
                            <div>
                                <label class="l" for="mother_name">Mother's Name <?php echo isFieldRequired('mother_name', $fields_map) ? '<i>*</i>' : ''; ?></label>
                                <input type="text" id="mother_name" name="mother_name" class="in" <?php echo isFieldRequired('mother_name', $fields_map) ? 'required' : ''; ?> maxlength="120">
                            </div>
                        <?php } ?>
                        <?php if (isFieldVisible('village', $fields_map)) { ?>
                            <div>
                                <label class="l" for="village">Village / Town <?php echo isFieldRequired('village', $fields_map) ? '<i>*</i>' : ''; ?></label>
                                <input type="text" id="village" name="village" class="in" <?php echo isFieldRequired('village', $fields_map) ? 'required' : ''; ?> maxlength="120">
                            </div>
                        <?php } ?>
                        <?php if (isFieldVisible('city', $fields_map)) { ?>
                            <div>
                                <label class="l" for="city">City / District <?php echo isFieldRequired('city', $fields_map) ? '<i>*</i>' : ''; ?></label>
                                <input type="text" id="city" name="city" class="in" <?php echo isFieldRequired('city', $fields_map) ? 'required' : ''; ?> maxlength="120">
                            </div>
                        <?php } ?>
                        <?php if (isFieldVisible('address', $fields_map)) { ?>
                            <div class="full">
                                <label class="l" for="address">Permanent Address <?php echo isFieldRequired('address', $fields_map) ? '<i>*</i>' : ''; ?></label>
                                <textarea id="address" name="address" rows="2" class="in" <?php echo isFieldRequired('address', $fields_map) ? 'required' : ''; ?> maxlength="300"></textarea>
                            </div>
                        <?php } ?>
                    </div>

                    <button type="submit" class="btn btn-free submit" id="submitBtn"><i class="fa fa-check-circle"></i> Submit &amp; Get Roll Number <span class="tag">FREE</span></button>
                    <div class="secure"><i class="fa fa-lock"></i> Your details are used only for this examination.</div>
                </form>
            <?php } ?>
            </div>
        </div>

        <div class="alt">
            <a class="alt-card wa" href="<?php echo $wa_link; ?>" target="_blank" rel="noopener">
                <span class="ic"><i class="fa fa-whatsapp"></i></span>
                <div><b class="hi">WhatsApp पर रजिस्टर करें</b><span>98285 12821 / 97832 00821</span></div>
            </a>
            <a class="alt-card ph" href="tel:+919982085111">
                <span class="ic"><i class="fa fa-phone-square"></i></span>
                <div><b class="hi">मदद चाहिए? कॉल करें</b><span>99820 85111 · 98285 12821</span></div>
            </a>
        </div>

        <!-- Status lookup -->
        <div class="status" id="status">
            <div>
                <h3><i class="fa fa-search"></i> Already registered?</h3>
                <p>Enter your Roll Number or registered mobile number to get your admit card and result.</p>
            </div>
            <form action="<?php echo site_url('scholarshipregister/check_status'); ?>" method="POST">
                <label class="sr-only" for="search_term">Roll number or mobile</label>
                <input type="text" id="search_term" name="search_term" class="in" placeholder="Roll No. or Mobile Number" required>
                <button type="submit" class="btn">Check</button>
            </form>
        </div>
    </div>
</section>

<!-- ================= SCHOOL ================= -->
<section class="school">
    <div class="wrap">
        <div>
            <h2>Sunrise International Public School</h2>
            <p>A Co-Ed English Medium CBSE School · Nechhwa, Sikar (Raj)</p>
            <div class="academy">
                <b>SUNRISE<span>CAREER ACADEMY</span></b>
                <small>For NEET, IIT-JEE &amp; NDA, CLAT — A New Venture for Competitive Exams</small>
            </div>
        </div>
        <div class="aff">
            <small>Affiliation No. : 1730833</small>
            <b>CBSE</b>
            <span>Nursery to 12<sup>th</sup></span>
            <em>(Science, Arts, Commerce)</em>
        </div>
    </div>
</section>

<!-- ================= FAQ ================= -->
<section class="sec" id="faq" style="padding-top:70px">
    <div class="wrap">
        <div class="pill-title">FAQ</div>
        <p class="sec-sub">Quick answers to what parents ask us most.</p>
        <div class="faq">
            <details open><summary>Who can appear for SSAT?</summary><div class="ans">Any student studying in Class 5 to 11 during the 2026-27 session can take part. The scholarships are for admission to Class 6 to 12 at Sunrise International Public School.</div></details>
            <details><summary>Is there any registration fee?</summary><div class="ans">No. Registration is completely free — online, on WhatsApp or by phone.</div></details>
            <details><summary>In which language is the exam?</summary><div class="ans">The exam is available in Hindi and English.</div></details>
            <details><summary>Is the exam online or offline? Where are the centres?</summary><div class="ans">Both modes are available. Exam centres: Bhilwara, Jodhpur, Barmer and Nechhwa (Sikar).</div></details>
            <details><summary>I lost my Roll Number. What do I do?</summary><div class="ans">Use <a href="#status" style="color:var(--blue);font-weight:700">"Already registered?"</a> above and search with your registered mobile number.</div></details>
            <details><summary>How do I get my admit card and result?</summary><div class="ans">Admit cards and results are published on this page by the school. Search with your Roll Number or mobile number once they are released.</div></details>
        </div>
    </div>
</section>
</main>

<footer>
    <div class="bar hi">Sunrise International Public School · नेछवा, सीकर (राज.)</div>
    <div class="wrap">
        <div class="main">
            <div>
                <h4>SUNRISE INTERNATIONAL PUBLIC SCHOOL</h4>
                <div>(A Co. Ed English Medium CBSE School)</div>
            </div>
            <div class="c">
                <span><i class="fa fa-map-marker"></i> Nechhwa, Sikar (Raj)</span>
                <a href="tel:+919828512821"><i class="fa fa-phone"></i> 98285 12821, 99820 85111, 97832 00821</a>
                <a href="https://www.sunriseeduhub.com" target="_blank" rel="noopener"><i class="fa fa-globe"></i> www.sunriseeduhub.com</a>
            </div>
        </div>
        <div class="copy">&copy; <?php echo date('Y'); ?> Sunrise International Public School. All rights reserved.</div>
    </div>
</footer>

<div class="m-cta">
    <a href="#register" class="btn btn-free"><i class="fa fa-pencil"></i> Free Register</a>
    <a href="<?php echo $wa_link; ?>" target="_blank" rel="noopener" class="btn wa" aria-label="WhatsApp"><i class="fa fa-whatsapp"></i></a>
</div>

<script>
(function () {
    // Current-school toggle
    window.toggleSchool = function (val) {
        var box = document.getElementById('other_school_box'), inp = document.getElementById('other_school_name');
        if (!box || !inp) return;
        var ext = val === 'external';
        box.style.display = ext ? 'block' : 'none';
        inp.required = ext;
        if (ext) { inp.focus(); } else { inp.value = ''; }
    };

    // Mobile: digits only
    var mob = document.getElementById('mobile');
    if (mob) mob.addEventListener('input', function () { this.value = this.value.replace(/\D/g, '').slice(0, 10); });

    // Prevent double submit
    var form = document.getElementById('regForm'), btn = document.getElementById('submitBtn');
    if (form && btn) form.addEventListener('submit', function () {
        if (!form.checkValidity()) return;
        btn.disabled = true;
        btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Submitting…';
    });

    // Countdown
    var cd = document.getElementById('countdown');
    if (cd) {
        var target = parseInt(cd.getAttribute('data-ts'), 10);
        var els = { d: document.getElementById('cd_d'), h: document.getElementById('cd_h'), m: document.getElementById('cd_m'), s: document.getElementById('cd_s') };
        var pad = function (n) { return (n < 10 ? '0' : '') + n; };
        var tick = function () {
            var diff = target - Date.now();
            if (diff <= 0) { cd.innerHTML = '<div style="grid-column:1/-1;padding:10px;font-weight:700;color:#12356f">परीक्षा आरंभ / सम्पन्न</div>'; return false; }
            els.d.textContent = pad(Math.floor(diff / 864e5));
            els.h.textContent = pad(Math.floor(diff % 864e5 / 36e5));
            els.m.textContent = pad(Math.floor(diff % 36e5 / 6e4));
            els.s.textContent = pad(Math.floor(diff % 6e4 / 1e3));
            return true;
        };
        if (tick()) { var t = setInterval(function () { if (!tick()) clearInterval(t); }, 1000); }
    }
})();
</script>
</body>
</html>
