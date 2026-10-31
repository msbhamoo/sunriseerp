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
    <title>SSAT – Laakho ki Scholarship – <?php echo htmlspecialchars($exam_title); ?> | Sunrise Career Academy</title>
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
        .roman { display: block; margin-top: 2px; font-size: 14px; font-weight: 600; letter-spacing: .4px; color: var(--muted); font-family: 'Poppins', sans-serif; }
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

        .perk-wrap { width: 100%; padding: 26px 24px 96px; }
        .perk-h { margin: 0 0 16px; font-family: 'Noto Sans Devanagari', sans-serif; font-weight: 700; font-size: 17px; color: var(--yellow); text-align: center; letter-spacing: .3px; }
        .perks { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        .perks div { background: rgba(255,255,255,.1); border: 1px solid rgba(255,255,255,.2); border-radius: 16px; padding: 16px 10px; text-align: center; color: #fff; }
        .perks i { font-size: 28px; color: var(--yellow); display: block; margin-bottom: 6px; }
        .perks b { display: block; font-size: 15px; line-height: 1.3; }
        .perks span { font-size: 12px; opacity: .8; }
        .hero-photo.fallback { aspect-ratio: auto; min-height: 300px; }

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

        /* ===== interactive layer ===== */
        .scrollbar { position: fixed; left: 0; top: 0; height: 4px; width: 0; z-index: 100; background: linear-gradient(90deg, var(--yellow), var(--red)); transition: width .1s linear; }
        .rv { opacity: 0; transform: translateY(22px); transition: opacity .6s ease, transform .6s ease; }
        .rv.in { opacity: 1; transform: none; }
        .float-a { animation: floaty 5s ease-in-out infinite; }
        @keyframes floaty { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-9px); } }
        .pulse { animation: pulse 2.2s infinite; }
        @keyframes pulse { 0% { box-shadow: 0 10px 26px rgba(229,38,44,.38), 0 0 0 0 rgba(229,38,44,.45); } 70% { box-shadow: 0 10px 26px rgba(229,38,44,.38), 0 0 0 16px rgba(229,38,44,0); } 100% { box-shadow: 0 10px 26px rgba(229,38,44,.38), 0 0 0 0 rgba(229,38,44,0); } }
        .proof { display: inline-flex; align-items: center; gap: 10px; background: #fff; border: 1px solid var(--line); border-radius: 999px; padding: 7px 16px 7px 8px; font-size: 14px; font-weight: 600; color: var(--blue-d); box-shadow: 0 6px 18px rgba(18,53,111,.1); margin-top: 16px; }
        .proof .dot { width: 30px; height: 30px; border-radius: 50%; background: #e2f7e9; color: #16a34a; display: grid; place-items: center; }
        .proof b { color: var(--red); font-size: 17px; }
        .share-row { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 14px; }
        .btn-sm { padding: 10px 18px; font-size: 14px; }
        .btn-ghost { background: transparent; color: var(--blue-d); border: 1.5px dashed var(--blue); }
        .btn-ghost:hover { background: #fff; }

        /* eligibility checker */
        .elig { display: grid; grid-template-columns: 1.2fr 1fr; gap: 0; background: #fff; border-radius: 26px; box-shadow: var(--shadow); border: 1px solid var(--line); overflow: hidden; }
        .elig-q { padding: 34px; }
        .eyebrow-b { display: inline-block; background: #fff1c2; color: #7a5600; font-weight: 700; font-size: 12px; padding: 5px 12px; border-radius: 999px; margin-bottom: 10px; }
        .elig-q h2 { margin: 0 0 6px; font-size: clamp(22px, 3vw, 28px); font-weight: 800; color: var(--blue-d); }
        .elig-q p { color: var(--muted); margin: 0 0 18px; }
        .cls-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; }
        .cls-grid button { font: inherit; font-weight: 700; font-size: 15px; padding: 14px 6px; border-radius: 14px; border: 2px solid var(--line); background: #f9fbfe; color: var(--blue-d); cursor: pointer; transition: all .15s; }
        .cls-grid button:hover { border-color: var(--blue); transform: translateY(-2px); }
        .cls-grid button.on { background: var(--blue); border-color: var(--blue); color: #fff; box-shadow: 0 8px 18px rgba(27,79,156,.3); }
        .cls-grid button:first-child, .cls-grid button:last-child { font-size: 12.5px; }
        .elig-a { background: linear-gradient(160deg, var(--sky), #fff); padding: 30px; display: grid; place-items: center; text-align: center; min-height: 260px; border-left: 1px solid var(--line); }
        .elig-ph { color: #9fb2cf; } .elig-ph i { font-size: 44px; } .elig-ph p { margin: 8px 0 0; font-weight: 600; }
        .ans { animation: popin .35s ease; }
        @keyframes popin { from { opacity: 0; transform: scale(.94); } to { opacity: 1; transform: none; } }
        .ans .big { width: 64px; height: 64px; border-radius: 50%; display: inline-grid; place-items: center; font-size: 30px; color: #fff; margin-bottom: 8px; }
        .ans .big.ok { background: #16a34a; box-shadow: 0 10px 22px rgba(22,163,74,.35); } .ans .big.no { background: var(--orange); }
        .ans h3 { margin: 4px 0; font-size: 20px; font-weight: 800; color: var(--blue-d); }
        .ans p { margin: 0 0 14px; color: var(--muted); font-size: 14.5px; }

        /* wizard */
        .wiz { margin-bottom: 22px; }
        .wiz-bar { height: 8px; background: #e6eef8; border-radius: 99px; overflow: hidden; }
        .wiz-bar i { display: block; height: 100%; width: 33%; background: linear-gradient(90deg, var(--yellow), var(--red)); border-radius: 99px; transition: width .4s ease; }
        .wiz-steps { list-style: none; margin: 12px 0 0; padding: 0; display: flex; justify-content: space-between; }
        .wiz-steps li { display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 600; color: #9fb2cf; }
        .wiz-steps li b { width: 26px; height: 26px; border-radius: 50%; background: #e6eef8; display: grid; place-items: center; font-size: 12px; color: #6a7f9f; transition: all .25s; }
        .wiz-steps li.on { color: var(--blue-d); } .wiz-steps li.on b { background: var(--blue); color: #fff; }
        .wiz-steps li.done b { background: #16a34a; color: #fff; } .wiz-steps li.done { color: #16a34a; }
        .pane { display: none; } .pane.on { display: block; animation: slidein .35s ease; }
        @keyframes slidein { from { opacity: 0; transform: translateX(18px); } to { opacity: 1; transform: none; } }
        .fs-title span i { font-size: 12px; }
        .seg3 { grid-template-columns: repeat(3, 1fr); } .seg5 { grid-template-columns: repeat(5, 1fr); }
        .seg5 span { padding: 11px 4px; font-size: 13px; }
        .wiz-nav { display: flex; gap: 10px; margin-top: 26px; }
        .btn-next { background: var(--blue); color: #fff; flex: 1; justify-content: center; box-shadow: 0 8px 20px rgba(27,79,156,.3); }
        .btn-next:hover { background: var(--blue-d); }
        .btn-back { background: #eef3fa; color: var(--blue-d); }
        .wiz-nav .btn-free { flex: 1; justify-content: center; font-size: 16px; }
        .review { margin-top: 24px; background: #f6f9fd; border: 1px dashed var(--blue); border-radius: 16px; padding: 16px 18px; }
        .review .fs-title { margin-top: 0; }
        .review dl { margin: 0; display: grid; grid-template-columns: auto 1fr; gap: 6px 14px; font-size: 14px; }
        .review dt { color: var(--muted); } .review dd { margin: 0; font-weight: 600; word-break: break-word; }
        .draft-note { background: #fff7e0; border: 1px solid #f6d77a; color: #7a5600; border-radius: 10px; padding: 8px 12px; font-size: 13px; margin-bottom: 14px; }
        .draft-note a { color: var(--red); font-weight: 700; }
        .in.bad { border-color: var(--red); animation: shake .35s; }
        @keyframes shake { 25% { transform: translateX(-5px); } 75% { transform: translateX(5px); } }

        .to-top { position: fixed; right: 16px; bottom: 90px; width: 44px; height: 44px; border-radius: 50%; border: 0; background: var(--blue); color: #fff; font-size: 18px; cursor: pointer; box-shadow: var(--shadow); opacity: 0; pointer-events: none; transition: opacity .25s; z-index: 55; }
        .to-top.show { opacity: 1; pointer-events: auto; }
        @media (min-width: 601px) { .to-top { bottom: 24px; } }
        @media (max-width: 960px) { .elig { grid-template-columns: 1fr; } .elig-a { border-left: 0; border-top: 1px solid var(--line); min-height: 200px; } }
        @media (max-width: 600px) {
            .elig-q { padding: 24px 18px; } .cls-grid { grid-template-columns: repeat(3, 1fr); }
            .wiz-steps li span { display: none; } .wiz-steps li.on span { display: inline; }
            .wiz-nav { position: sticky; bottom: 70px; background: #fff; padding: 10px 0; z-index: 5; }
            .seg5 { grid-template-columns: repeat(3, 1fr); }
            .proof { font-size: 13px; }
        }
        @media (prefers-reduced-motion: reduce) { .rv { opacity: 1; transform: none; } .float-a, .pulse, .pane.on, .ans { animation: none; } }
        /* "just registered" popup (real sign-ups only, names partly hidden) */
        .live-toast { position: fixed; left: 16px; bottom: 24px; z-index: 70; max-width: 320px; display: flex; gap: 12px; align-items: center; background: #fff; border: 1px solid var(--line); border-left: 5px solid #16a34a; border-radius: 14px; padding: 12px 16px; box-shadow: var(--shadow); font-size: 13.5px; transform: translateY(140%); opacity: 0; transition: transform .45s ease, opacity .45s ease; pointer-events: none; }
        .live-toast.show { transform: none; opacity: 1; }
        .live-toast .lt-ic { width: 34px; height: 34px; border-radius: 50%; background: #e2f7e9; color: #16a34a; display: grid; place-items: center; flex: none; }
        .live-toast b { color: var(--blue-d); } .live-toast small { display: block; color: var(--muted); font-size: 12px; }
        .live-toast .lt-dot { display: inline-block; width: 8px; height: 8px; border-radius: 50%; background: #16a34a; margin-right: 5px; animation: pulseDot 1.6s infinite; }
        @keyframes pulseDot { 50% { opacity: .3; } }
        @media (max-width: 600px) { .live-toast { left: 10px; right: 10px; max-width: none; bottom: 78px; } }
    </style>
</head>
<body>
<div class="scrollbar" id="scrollbar"></div>
<button type="button" class="to-top" id="toTop" aria-label="Back to top"><i class="fa fa-chevron-up"></i></button>

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
                <img src="<?php echo $school_logo; ?>" alt="" onerror="this.outerHTML='<span class=&quot;brand-ico&quot;>&#9728;</span>'">
            <?php } else { ?>
                <span class="brand-ico">&#9728;</span>
            <?php } ?>
            <span class="brand-t"><b>SUNRISE</b><span>Career Academy · S-SAT</span></span>
        </a>
        <nav class="menu" aria-label="Main">
            <a href="#check">Am I eligible?</a>
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
                <a href="#register" class="btn btn-free pulse"><i class="fa fa-pencil-square-o"></i> रजिस्टर करें <span class="tag">FREE</span></a>
                <a href="#status" class="btn btn-line"><i class="fa fa-id-card-o"></i> Admit Card / Status</a>
            </div>
            <div class="hero-note">
                <span><i class="fa fa-check-circle"></i> कक्षा 5 से 11 के सभी विद्यार्थी पात्र</span>
                <span><i class="fa fa-check-circle"></i> हिंदी एवं अंग्रेजी माध्यम</span>
                <span><i class="fa fa-check-circle"></i> Online &amp; Offline</span>
            </div>
            <div class="proof" id="proof" style="<?php echo (!empty($registered_count) && $registered_count >= 10) ? '' : 'display:none'; ?>"><span class="dot"><i class="fa fa-users"></i></span><span><b id="proofNum" data-count="<?php echo (int) $registered_count; ?>">0</b> students have already registered</span></div>
        </div>

        <div class="hero-card">
            <?php if ($hero_img) { ?>
                <div class="hero-photo"><img src="<?php echo $hero_img; ?>" alt="Sunrise School campus"></div>
            <?php } else { ?>
                <div class="hero-photo fallback">
                    <div class="perk-wrap">
                        <p class="perk-h">S-SAT में आपको मिलेगा</p>
                        <div class="perks">
                            <div><i class="fa fa-graduation-cap"></i><b class="hi">स्कॉलरशिप</b><span>प्रवेश पर</span></div>
                            <div><i class="fa fa-gift"></i><b class="hi">नकद पुरस्कार</b><span>एवं उपहार</span></div>
                            <div><i class="fa fa-certificate"></i><b class="hi">प्रमाण पत्र</b><span>भागीदारी का</span></div>
                            <div><i class="fa fa-compass"></i><b class="hi">कैरियर गाइडेंस</b><span>काउंसलिंग</span></div>
                        </div>
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

<!-- ================= ELIGIBILITY CHECKER ================= -->
<section class="sec" id="check" style="padding-top:64px">
    <div class="wrap">
        <div class="elig">
            <div class="elig-q">
                <span class="eyebrow-b">30-second check</span>
                <h2 class="hi">आपका बच्चा किस कक्षा में पढ़ता है?</h2>
                <p>Tap the class your child is in right now — we'll tell you if they can appear.</p>
                <div class="cls-grid" id="clsGrid" role="group" aria-label="Choose present class">
                    <button type="button" data-c="4">Class 4 &amp; below</button>
                    <?php for ($n = 5; $n <= 11; $n++) { ?><button type="button" data-c="<?php echo $n; ?>">Class <?php echo $n; ?></button><?php } ?>
                    <button type="button" data-c="12">Class 12</button>
                </div>
            </div>
            <div class="elig-a" id="eligAns" aria-live="polite">
                <div class="elig-ph"><i class="fa fa-hand-pointer-o"></i><p>Your result will appear here</p></div>
            </div>
        </div>
    </div>
</section>

<!-- ================= WHY ================= -->
<section class="sec" id="why">
    <div class="wrap">
        <div class="pill-title">Why the Exam</div>
        <p class="sec-sub">Every participant walks away with more than a score — recognition, guidance and a real chance at a scholarship.</p>
        <div class="why-grid">
            <div class="why" style="--c: var(--red)">
                <div class="ic"><i class="fa fa-certificate"></i></div>
                <h3>Certificate of Participation</h3><p class="hi">भागीदारी प्रमाण पत्र</p>
                <p>A certificate of participation for every student who takes part.</p>
            </div>
            <div class="why" style="--c: var(--blue)">
                <div class="ic"><i class="fa fa-graduation-cap"></i></div>
                <h3>Scholarship for Admission</h3><p class="hi">प्रवेश पर स्कॉलरशिप</p>
                <p>Lakhs in scholarships for admission to Class 6 to 12.</p>
            </div>
            <div class="why" style="--c: var(--orange)">
                <div class="ic"><i class="fa fa-compass"></i></div>
                <h3>Career Counselling &amp; Guidance</h3><p class="hi">कैरियर काउंसलिंग एवं मार्गदर्शन</p>
                <p>Career counselling and guidance from the Sunrise Career Academy team.</p>
            </div>
            <div class="why" style="--c: #16a34a">
                <div class="ic"><i class="fa fa-gift"></i></div>
                <h3>Cash Award &amp; Gift</h3><p class="hi">नकद पुरस्कार एवं उपहार</p>
                <p>Cash awards and gifts for successful students.</p>
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

                    <?php $has_family = isFieldVisible('father_name', $fields_map) || isFieldVisible('mother_name', $fields_map) || isFieldVisible('village', $fields_map) || isFieldVisible('city', $fields_map) || isFieldVisible('address', $fields_map); ?>
                    <div class="wiz" id="wiz" data-total="<?php echo $has_family ? 3 : 2; ?>">
                        <div class="wiz-bar"><i id="wizFill"></i></div>
                        <ol class="wiz-steps">
                            <li class="on" data-s="1"><b>1</b><span>Student</span></li>
                            <li data-s="2"><b>2</b><span>School &amp; Contact</span></li>
                            <?php if ($has_family) { ?><li data-s="3"><b>3</b><span>Family</span></li><?php } ?>
                        </ol>
                    </div>
                    <div class="draft-note" id="draftNote" style="display:none"><i class="fa fa-history"></i> We restored your earlier answers. <a href="#" id="draftClear">Start fresh</a></div>

                    <!-- STEP 1 -->
                    <div class="pane on" data-step="1">
                        <div class="fs-title hi-t"><span><i class="fa fa-user"></i></span> Student Details</div>
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
                                <div class="full">
                                    <label class="l">Gender <?php echo isFieldRequired('gender', $fields_map) ? '<i>*</i>' : ''; ?></label>
                                    <div class="seg seg3" role="radiogroup" aria-label="Gender">
                                        <?php foreach (array('Male' => 'fa-male', 'Female' => 'fa-female', 'Other' => 'fa-user') as $g => $gi) { ?>
                                            <label><input type="radio" name="gender" value="<?php echo $g; ?>" <?php echo isFieldRequired('gender', $fields_map) ? 'required' : ''; ?>><span><i class="fa <?php echo $gi; ?>"></i> <?php echo $g; ?></span></label>
                                        <?php } ?>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if (isFieldVisible('category', $fields_map)) { ?>
                                <div class="full">
                                    <label class="l">Category <?php echo isFieldRequired('category', $fields_map) ? '<i>*</i>' : ''; ?></label>
                                    <div class="seg seg5" role="radiogroup" aria-label="Category">
                                        <?php foreach (array('General', 'OBC', 'SC', 'ST', 'SBC') as $k => $cat) { ?>
                                            <label><input type="radio" name="category" value="<?php echo $cat; ?>" <?php echo $k === 0 ? 'checked' : ''; ?>><span><?php echo $cat; ?></span></label>
                                        <?php } ?>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>

                    <!-- STEP 2 -->
                    <div class="pane" data-step="2">
                        <?php if (isFieldVisible('school_name', $fields_map)) { ?>
                            <div class="fs-title hi-t"><span><i class="fa fa-university"></i></span> Present School</div>
                            <div class="seg" role="radiogroup" aria-label="School type">
                                <label><input type="radio" name="school_type" value="internal" checked onchange="toggleSchool(this.value)"><span><i class="fa fa-university"></i> <?php echo htmlspecialchars($school_name); ?></span></label>
                                <label><input type="radio" name="school_type" value="external" onchange="toggleSchool(this.value)"><span><i class="fa fa-building-o"></i> Another School</span></label>
                            </div>
                            <div id="other_school_box" style="display:none;margin-top:12px;">
                                <label class="l" for="other_school_name">School Name <?php echo isFieldRequired('school_name', $fields_map) ? '<i>*</i>' : ''; ?></label>
                                <input type="text" id="other_school_name" name="other_school_name" class="in" placeholder="Type the student's current school" maxlength="150">
                            </div>
                        <?php } ?>

                        <div class="fs-title hi-t"><span><i class="fa fa-phone"></i></span> Contact</div>
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
                        </div>
                    </div>

                    <!-- STEP 3 -->
                    <?php if ($has_family) { ?>
                    <div class="pane" data-step="3">
                        <div class="fs-title hi-t"><span><i class="fa fa-home"></i></span> Family &amp; Address</div>
                        <div class="grid2">
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
                    </div>
                    <?php } ?>

                    <!-- Review (filled by script on the last step) -->
                    <div class="review" id="review" style="display:none">
                        <div class="fs-title hi-t"><span><i class="fa fa-check"></i></span> Please check your details</div>
                        <dl id="reviewList"></dl>
                    </div>

                    <div class="wiz-nav">
                        <button type="button" class="btn btn-back" id="btnBack" style="display:none"><i class="fa fa-arrow-left"></i> Back</button>
                        <button type="button" class="btn btn-next" id="btnNext">Next <i class="fa fa-arrow-right"></i></button>
                        <button type="submit" class="btn btn-free" id="submitBtn" style="display:none"><i class="fa fa-check-circle"></i> Submit &amp; Get Roll Number <span class="tag">FREE</span></button>
                    </div>
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

        <div class="share-row" style="justify-content:center;margin-top:26px"><a class="btn btn-ghost btn-sm" target="_blank" rel="noopener" href="https://wa.me/?text=<?php echo rawurlencode("लाखों में स्कॉलरशिप! S-SAT - Sunrise Scholarship & Admission Test. Free registration: " . $reg_url); ?>"><i class="fa fa-whatsapp" style="color:#16a34a"></i> Share with friends</a></div>

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
            <details><summary>How do I get my admit card and result?</summary><div class="ans">The school will inform you when admit cards and results are released. You can then search with your Roll Number or mobile number in "Already registered?" on this page. For help call 98285 12821.</div></details>
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
<script>
// ===== interactive layer =====
(function () {
    var $ = function (s, r) { return (r || document).querySelector(s); };
    var $$ = function (s, r) { return [].slice.call((r || document).querySelectorAll(s)); };

    // Scroll progress + back-to-top
    var bar = $('#scrollbar'), top = $('#toTop');
    var onScroll = function () {
        var h = document.documentElement, max = h.scrollHeight - h.clientHeight;
        if (bar) bar.style.width = (max > 0 ? (h.scrollTop / max) * 100 : 0) + '%';
        if (top) top.classList.toggle('show', h.scrollTop > 600);
    };
    window.addEventListener('scroll', onScroll, { passive: true }); onScroll();
    if (top) top.addEventListener('click', function () { window.scrollTo({ top: 0, behavior: 'smooth' }); });

    // Reveal on scroll
    var targets = $$('.why, .step, .fact, .faq details, .alt-card, .elig, .status, .date-card, .school .aff');
    if ('IntersectionObserver' in window) {
        var io = new IntersectionObserver(function (es) {
            es.forEach(function (e) { if (e.isIntersecting) { e.target.classList.add('in'); io.unobserve(e.target); } });
        }, { threshold: .12 });
        targets.forEach(function (t, i) { t.classList.add('rv'); t.style.transitionDelay = ((i % 4) * 80) + 'ms'; io.observe(t); });
    }

    // Animated counter
    $$('[data-count]').forEach(function (el) {
        var to = parseInt(el.getAttribute('data-count'), 10) || 0, done = false;
        var run = function () {
            if (done) return; done = true;
            var t0 = performance.now();
            (function step(t) {
                var p = Math.min(1, (t - t0) / 1200);
                el.textContent = Math.round(to * (1 - Math.pow(1 - p, 3)));
                if (p < 1) requestAnimationFrame(step);
            })(t0);
        };
        if ('IntersectionObserver' in window) {
            new IntersectionObserver(function (es, o) { if (es[0].isIntersecting) { run(); o.disconnect(); } }).observe(el);
        } else { run(); }
    });

    // ----- Eligibility checker -----
    var romans = { 5: 'V', 6: 'VI', 7: 'VII', 8: 'VIII', 9: 'IX', 10: 'X', 11: 'XI' };
    var ans = $('#eligAns');
    $$('#clsGrid button').forEach(function (b) {
        b.addEventListener('click', function () {
            $$('#clsGrid button').forEach(function (x) { x.classList.remove('on'); });
            b.classList.add('on');
            var c = parseInt(b.getAttribute('data-c'), 10);
            if (c >= 5 && c <= 11) {
                ans.innerHTML = '<div class="ans"><div class="big ok"><i class="fa fa-check"></i></div><h3 class="hi">बधाई हो! Class ' + c + ' पात्र है</h3>' +
                    '<p class="hi">आपका बच्चा S-SAT में बैठ सकता है। स्कॉलरशिप कक्षा 6 से 12 में प्रवेश के लिए है।</p>' +
                    '<button type="button" class="btn btn-free btn-sm pulse" id="elReg"><i class="fa fa-pencil-square-o"></i> Class ' + c + ' के लिए रजिस्टर करें</button></div>';
                $('#elReg').addEventListener('click', function () { preselectClass(c); });
            } else {
                ans.innerHTML = '<div class="ans"><div class="big no"><i class="fa fa-info"></i></div><h3 class="hi">यह परीक्षा कक्षा 5 से 11 के लिए है</h3>' +
                    '<p class="hi">' + (c < 5 ? 'छोटी कक्षाओं के लिए' : 'कक्षा 12 के लिए') + ' हमारी टीम से बात करें — हम सही मार्गदर्शन देंगे।</p>' +
                    '<a class="btn btn-line btn-sm" href="tel:+919828512821"><i class="fa fa-phone"></i> 98285 12821</a></div>';
            }
        });
    });
    function preselectClass(c) {
        var sel = $('#class_id');
        document.getElementById('register').scrollIntoView({ behavior: 'smooth' });
        if (!sel) return;
        var want = 'Class ' + romans[c], hit = null, multi = [];
        $$('option', sel).forEach(function (o) {
            var t = o.textContent.trim();
            if (t === want) hit = o;
            else if (t.indexOf(want + ' ') === 0 || t.indexOf(want + '-') === 0) multi.push(o);
        });
        if (hit) { sel.value = hit.value; sel.dispatchEvent(new Event('change', { bubbles: true })); }
        else if (multi.length) { sel.focus(); }
        setTimeout(function () { var n = $('#candidate_name'); if (n) n.focus({ preventScroll: true }); }, 700);
    }

    // ----- Multi-step form -----
    var form = $('#regForm');
    if (!form) return;
    var wiz = $('#wiz'), total = parseInt(wiz.getAttribute('data-total'), 10), step = 1;
    var panes = $$('.pane', form), nextB = $('#btnNext'), backB = $('#btnBack'), subB = $('#submitBtn'), review = $('#review');

    function show(n, scroll) {
        step = n;
        panes.forEach(function (p) { p.classList.toggle('on', parseInt(p.getAttribute('data-step'), 10) === n); });
        $$('.wiz-steps li').forEach(function (li) {
            var s = parseInt(li.getAttribute('data-s'), 10);
            li.classList.toggle('on', s === n); li.classList.toggle('done', s < n);
        });
        $('#wizFill').style.width = (n / total * 100) + '%';
        backB.style.display = n > 1 ? '' : 'none';
        nextB.style.display = n < total ? '' : 'none';
        subB.style.display = n === total ? '' : 'none';
        review.style.display = n === total ? '' : 'none';
        if (n === total) buildReview();
        if (scroll) { var box = $('.reg-box'); window.scrollTo({ top: box.getBoundingClientRect().top + window.pageYOffset - 70, behavior: 'smooth' }); }
    }
    function valid(n) {
        var pane = panes.filter(function (p) { return parseInt(p.getAttribute('data-step'), 10) === n; })[0];
        var fields = $$('input, select, textarea', pane);
        for (var i = 0; i < fields.length; i++) {
            if (!fields[i].checkValidity()) {
                fields[i].classList.add('bad'); fields[i].reportValidity && fields[i].reportValidity();
                setTimeout(function (f) { return function () { f.classList.remove('bad'); }; }(fields[i]), 800);
                return false;
            }
        }
        return true;
    }
    nextB.addEventListener('click', function () { if (valid(step)) show(step + 1, true); });
    backB.addEventListener('click', function () { show(step - 1, true); });
    form.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' && step < total && e.target.tagName !== 'TEXTAREA') { e.preventDefault(); nextB.click(); }
    });

    function val(name) {
        var el = form.elements[name]; if (!el) return '';
        if (el.length && el[0] && el[0].type === 'radio') { var c = $$('input[name="' + name + '"]:checked', form)[0]; return c ? c.value : ''; }
        if (el.tagName === 'SELECT') return el.selectedIndex >= 0 ? el.options[el.selectedIndex].text.replace('Select class', '') : '';
        return el.value;
    }
    function buildReview() {
        var rows = [['Student', 'candidate_name'], ['Class', 'class_id'], ['Date of birth', 'dob'], ['Gender', 'gender'], ['School', null], ['Mobile', 'mobile'], ['Email', 'email'], ['Father', 'father_name'], ['Mother', 'mother_name'], ['Village', 'village'], ['District', 'city'], ['Address', 'address']];
        var html = '';
        rows.forEach(function (r) {
            var v = r[1] ? val(r[1]) : (val('school_type') === 'external' ? val('other_school_name') : (document.querySelector('input[name="school_type"]:checked') ? document.querySelector('input[name="school_type"]:checked').parentNode.textContent.trim() : ''));
            if (v) html += '<dt>' + r[0] + '</dt><dd>' + v.replace(/[<>&]/g, function (m) { return { '<': '&lt;', '>': '&gt;', '&': '&amp;' }[m]; }) + '</dd>';
        });
        $('#reviewList').innerHTML = html;
    }

    // Draft autosave (so a parent who gets interrupted does not lose answers)
    var KEY = 'ssat_draft_v1', store = null;
    try { store = window.localStorage; } catch (e) {}
    function save() {
        if (!store) return;
        var d = {};
        $$('input, select, textarea', form).forEach(function (f) {
            if (!f.name || f.name === 'exam_id') return;
            if (f.type === 'radio') { if (f.checked) d[f.name] = f.value; } else d[f.name] = f.value;
        });
        try { store.setItem(KEY, JSON.stringify(d)); } catch (e) {}
    }
    function restore() {
        if (!store) return;
        var d; try { d = JSON.parse(store.getItem(KEY) || 'null'); } catch (e) {}
        if (!d || !d.candidate_name) return;
        Object.keys(d).forEach(function (k) {
            var els = form.elements[k]; if (!els) return;
            if (els.length && els[0] && els[0].type === 'radio') { $$('input[name="' + k + '"]', form).forEach(function (r) { r.checked = (r.value === d[k]); }); }
            else els.value = d[k];
        });
        if (d.school_type) window.toggleSchool(d.school_type);
        $('#draftNote').style.display = '';
    }
    form.addEventListener('input', save); form.addEventListener('change', save);
    $('#draftClear').addEventListener('click', function (e) { e.preventDefault(); try { store.removeItem(KEY); } catch (x) {} form.reset(); window.toggleSchool('internal'); $('#draftNote').style.display = 'none'; show(1, false); });
    form.addEventListener('submit', function () { if (form.checkValidity()) { try { store.removeItem(KEY); } catch (x) {} } });
    restore();
    show(1, false);
})();
</script>
<div class="live-toast" id="liveToast" role="status" aria-live="polite"><span class="lt-ic"><i class="fa fa-user-plus"></i></span><div><span id="ltText"></span><small id="ltAgo"></small></div></div>
<script>
// Real activity only: polls the server for the true count and the latest sign-ups (names partly hidden)
(function () {
    var url = '<?php echo site_url('scholarshipregister/activity/' . (!empty($selected_exam['exam_code']) ? urlencode($selected_exam['exam_code']) : '')); ?>';
    var proof = document.getElementById('proof'), num = document.getElementById('proofNum');
    var toast = document.getElementById('liveToast'), tText = document.getElementById('ltText'), tAgo = document.getElementById('ltAgo');
    var recent = [], idx = 0, shown = 0;
    function esc(t) { return String(t).replace(/[<>&"]/g, function (m) { return { '<': '&lt;', '>': '&gt;', '&': '&amp;', '"': '&quot;' }[m]; }); }
    function poll() {
        fetch(url, { cache: 'no-store' }).then(function (r) { return r.json(); }).then(function (d) {
            if (d.count >= 10 && proof) { proof.style.display = ''; if (num && String(d.count) !== num.textContent) num.textContent = d.count; }
            recent = d.recent || [];
        }).catch(function () {});
    }
    function next() {
        if (!recent.length || shown >= 6) return;
        var r = recent[idx++ % recent.length]; shown++;
        tText.innerHTML = '<span class="lt-dot"></span><b>' + esc(r.name) + '</b> ' + (r.place ? 'from ' + esc(r.place) + ' ' : '') + 'registered' + (r.cls ? ' for ' + esc(r.cls) : '');
        tAgo.textContent = r.ago;
        toast.classList.add('show');
        setTimeout(function () { toast.classList.remove('show'); }, 5000);
    }
    poll();
    setTimeout(function () { next(); setInterval(next, 14000); }, 6000);
    setInterval(poll, 30000);
})();
</script>
</body>
</html>
