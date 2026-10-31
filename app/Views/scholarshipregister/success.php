<?php
$c = $candidate;
$school_name = !empty($sch_setting[0]['name']) ? $sch_setting[0]['name'] : 'Sunrise International Public School';
$already = !empty($already_registered);

$hindi_days = array('रविवार', 'सोमवार', 'मंगलवार', 'बुधवार', 'गुरुवार', 'शुक्रवार', 'शनिवार');
$hindi_months = array(1 => 'जनवरी', 'फ़रवरी', 'मार्च', 'अप्रैल', 'मई', 'जून', 'जुलाई', 'अगस्त', 'सितंबर', 'अक्टूबर', 'नवंबर', 'दिसंबर');
$ts = !empty($c['exam_date']) ? strtotime($c['exam_date']) : false;
$exam_day = $ts ? date('j', $ts) . ' ' . $hindi_months[(int) date('n', $ts)] . ' ' . date('Y', $ts) . ' (' . $hindi_days[(int) date('w', $ts)] . ')' : '';
$exam_time = $ts ? date('g:i', $ts) . ' बजे से ' . date('g:i', $ts + 7200) . ' बजे तक' : '';
$class_txt = !empty($c['class_label']) ? $c['class_label'] : '—';
$father = !empty($c['father_label']) ? $c['father_label'] : '';
$status_url = site_url('scholarshipregister/check_status') . '?roll_no=' . urlencode($c['roll_no']);
$wa_text = "SSAT Registration ✅\nName: {$c['firstname']}\nRoll No: {$c['roll_no']}\nExam: " . ($exam_day ? $exam_day : $c['exam_title']) . "\nCheck admit card: " . site_url('scholarshipregister');
?>
<!DOCTYPE html>
<html lang="hi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registration Slip - <?php echo htmlspecialchars($c['roll_no']); ?></title>
    <link rel="stylesheet" href="<?php echo base_url('backend/dist/font-awesome-4.5.0/css/font-awesome.min.css'); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Devanagari:wght@500;700;800&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root { --blue: #1b4f9c; --blue-d: #12356f; --red: #e5262c; --pink: #ee3b5f; --yellow: #ffc61a; --green: #16a34a; --line: #dbe5f1; --muted: #5b6b86; --sky: #e8f3fd; }
        * { box-sizing: border-box; }
        body { margin: 0; background: linear-gradient(180deg, var(--sky), #fff 60%); font-family: 'Poppins', 'Noto Sans Devanagari', system-ui, sans-serif; color: #132038; padding: 22px 14px 60px; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        .hi { font-family: 'Noto Sans Devanagari', 'Poppins', sans-serif; }
        .page { max-width: 760px; margin: 0 auto; }

        .ok { text-align: center; margin-bottom: 18px; }
        .ok .tick { width: 66px; height: 66px; border-radius: 50%; background: var(--green); color: #fff; display: inline-grid; place-items: center; font-size: 34px; box-shadow: 0 10px 24px rgba(22,163,74,.35); }
        .ok h1 { margin: 12px 0 2px; font-size: 24px; font-weight: 800; color: #0f5132; }
        .ok p { margin: 0; color: var(--muted); font-size: 14.5px; }

        .slip { background: #fff; border-radius: 22px; box-shadow: 0 16px 44px rgba(18,53,111,.15); border: 1px solid var(--line); overflow: hidden; }
        .slip-head { background: linear-gradient(120deg, var(--blue-d), var(--blue)); color: #fff; padding: 18px 24px; display: flex; justify-content: space-between; align-items: center; gap: 14px; }
        .slip-head b { display: block; font-size: 17px; font-weight: 800; letter-spacing: .3px; }
        .slip-head span { font-size: 12.5px; opacity: .85; }
        .slip-head .tag { background: var(--yellow); color: #0b2150; font-weight: 800; font-size: 12px; padding: 6px 12px; border-radius: 999px; white-space: nowrap; }

        .roll { text-align: center; padding: 24px 16px 10px; }
        .roll small { display: block; font-size: 12px; text-transform: uppercase; letter-spacing: 1.2px; color: var(--muted); font-weight: 600; }
        .roll .no { display: inline-flex; align-items: center; gap: 12px; margin-top: 8px; font-size: clamp(30px, 8vw, 46px); font-weight: 800; letter-spacing: 3px; color: var(--blue-d); border: 3px dashed var(--blue); border-radius: 18px; padding: 8px 26px; background: #f3f8ff; }
        .roll .copy { border: 0; background: var(--blue); color: #fff; width: 38px; height: 38px; border-radius: 10px; cursor: pointer; font-size: 16px; }
        .roll .keep { margin-top: 10px; color: var(--red); font-weight: 700; font-size: 13.5px; }

        .details { display: grid; grid-template-columns: 1fr 1fr; gap: 0; margin: 14px 24px 6px; border: 1px solid var(--line); border-radius: 14px; overflow: hidden; }
        .d { padding: 12px 16px; border-bottom: 1px solid var(--line); }
        .d:nth-child(odd) { border-right: 1px solid var(--line); }
        .d:nth-last-child(-n+2) { border-bottom: 0; }
        .d small { display: block; font-size: 11px; text-transform: uppercase; letter-spacing: .6px; color: var(--muted); font-weight: 600; }
        .d b { font-size: 15px; font-weight: 600; word-break: break-word; }

        .when { margin: 14px 24px 0; background: linear-gradient(120deg, #fff7e0, #fff1c2); border: 1px solid #f6d77a; border-radius: 14px; padding: 14px 18px; display: flex; gap: 14px; align-items: center; }
        .when .ic { font-size: 28px; color: #d98a00; }
        .when b { display: block; font-size: 17px; color: #5a3b00; }
        .when span { font-size: 13.5px; color: #7a5600; }

        .next { margin: 20px 24px 0; }
        .next h3 { margin: 0 0 10px; font-size: 15px; font-weight: 800; color: var(--blue-d); text-transform: uppercase; letter-spacing: .5px; }
        .next ol { margin: 0; padding: 0; list-style: none; display: grid; gap: 10px; counter-reset: s; }
        .next li { counter-increment: s; display: flex; gap: 12px; align-items: flex-start; font-size: 14.5px; }
        .next li::before { content: counter(s); flex: none; width: 26px; height: 26px; border-radius: 50%; background: var(--red); color: #fff; font-weight: 700; font-size: 13px; display: grid; place-items: center; margin-top: 1px; }

        .help { margin: 20px 24px 24px; border-top: 1px dashed var(--line); padding-top: 14px; display: flex; justify-content: space-between; gap: 10px 20px; flex-wrap: wrap; font-size: 13px; color: var(--muted); }
        .help b { color: var(--ink, #132038); }

        .actions { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; margin-top: 18px; }
        .btn { display: flex; align-items: center; justify-content: center; gap: 8px; border: 0; cursor: pointer; font: inherit; font-weight: 700; font-size: 15px; padding: 14px 12px; border-radius: 14px; text-decoration: none; transition: transform .15s; }
        .btn:hover { transform: translateY(-2px); }
        .btn-print { background: linear-gradient(135deg, var(--pink), var(--red)); color: #fff; box-shadow: 0 8px 20px rgba(229,38,44,.3); }
        .btn-wa { background: var(--green); color: #fff; }
        .btn-chk { background: #fff; color: var(--blue-d); border: 2px solid var(--blue); }
        .back { text-align: center; margin-top: 16px; font-size: 14px; }
        .back a { color: var(--blue); font-weight: 600; }
        .toast { position: fixed; left: 50%; bottom: 24px; transform: translateX(-50%) translateY(80px); background: #132038; color: #fff; padding: 10px 18px; border-radius: 999px; font-size: 14px; transition: transform .25s; }
        .toast.show { transform: translateX(-50%) translateY(0); }

        @media (max-width: 560px) {
            .details { grid-template-columns: 1fr; margin-left: 14px; margin-right: 14px; }
            .d:nth-child(odd) { border-right: 0; }
            .d:nth-last-child(2) { border-bottom: 1px solid var(--line); }
            .when, .next, .help { margin-left: 14px; margin-right: 14px; }
            .slip-head { padding: 16px 14px; }
            .actions { grid-template-columns: 1fr; }
        }
        @media print {
            @page { size: A4; margin: 12mm; }
            body { background: #fff; padding: 0; }
            .ok, .actions, .back, .toast, .roll .copy { display: none !important; }
            .slip { box-shadow: none; border: 1.5px solid #1b4f9c; }
            .page { max-width: none; }
        }
    </style>
</head>
<body>
<div class="page">

    <div class="ok">
        <div class="tick"><i class="fa fa-check"></i></div>
        <h1 class="hi"><?php echo $already ? 'आप पहले से रजिस्टर्ड हैं' : 'रजिस्ट्रेशन सफल हुआ!'; ?></h1>
        <p><?php echo $already ? 'This mobile number is already registered. Your details are below.' : 'Registration successful! Please save or print this slip.'; ?></p>
    </div>

    <div class="slip" id="slip">
        <div class="slip-head">
            <div>
                <b><?php echo htmlspecialchars($school_name); ?></b>
                <span>S-SAT · <?php echo htmlspecialchars($c['exam_title']); ?></span>
            </div>
            <span class="tag">REGISTRATION SLIP</span>
        </div>

        <div class="roll">
            <small class="hi">आपका रोल नंबर · Your Roll Number</small>
            <div class="no"><span id="rollNo"><?php echo htmlspecialchars($c['roll_no']); ?></span><button type="button" class="copy" onclick="copyRoll()" aria-label="Copy roll number"><i class="fa fa-clone"></i></button></div>
            <div class="keep hi"><i class="fa fa-exclamation-circle"></i> इसे संभालकर रखें — प्रवेश पत्र और रिजल्ट के लिए ज़रूरी है</div>
        </div>

        <div class="details">
            <div class="d"><small>Student Name</small><b><?php echo htmlspecialchars($c['firstname']); ?></b></div>
            <div class="d"><small>Class</small><b><?php echo htmlspecialchars($class_txt); ?></b></div>
            <?php if ($father) { ?><div class="d"><small>Father's Name</small><b><?php echo htmlspecialchars($father); ?></b></div><?php } ?>
            <div class="d"><small>Mobile</small><b><?php echo htmlspecialchars($c['mobile']); ?></b></div>
            <div class="d"><small>School</small><b><?php echo htmlspecialchars($c['school_name']); ?></b></div>
            <div class="d"><small>Exam</small><b><?php echo htmlspecialchars($c['exam_title']); ?></b></div>
        </div>

        <?php if ($exam_day) { ?>
            <div class="when">
                <div class="ic"><i class="fa fa-calendar-check-o"></i></div>
                <div class="hi"><b><?php echo $exam_day; ?></b><span><?php echo $exam_time; ?> · भीलवाड़ा, जोधपुर, बाड़मेर, नेछवा (सीकर)</span></div>
            </div>
        <?php } ?>

        <div class="next hi">
            <h3>अब आगे क्या करें?</h3>
            <ol>
                <li>इस स्लिप का प्रिंट लें या स्क्रीनशॉट / PDF सेव करें।</li>
                <li>प्रवेश पत्र जारी होने पर वेबसाइट पर <b>रोल नंबर या मोबाइल नंबर</b> से डाउनलोड करें।</li>
                <li>परीक्षा के दिन प्रवेश पत्र व स्कूल ID साथ लाएँ।</li>
            </ol>
        </div>

        <div class="help hi">
            <span>सहायता: <b>98285 12821</b> · <b>99820 85111</b> · <b>97832 00821</b></span>
            <span>www.sunriseeduhub.com · नेछवा, सीकर (राज.)</span>
        </div>
    </div>

    <div class="actions">
        <button class="btn btn-print" onclick="window.print()"><i class="fa fa-print"></i> Print / Save PDF</button>
        <a class="btn btn-wa" target="_blank" rel="noopener" href="https://wa.me/?text=<?php echo rawurlencode($wa_text); ?>"><i class="fa fa-whatsapp"></i> Share on WhatsApp</a>
        <a class="btn btn-chk" href="<?php echo $status_url; ?>"><i class="fa fa-id-card-o"></i> Check Admit Card</a>
    </div>
    <div class="back"><a href="<?php echo site_url('scholarshipregister'); ?>"><i class="fa fa-arrow-left"></i> Back to SSAT page</a></div>
</div>
<div class="toast" id="toast">Roll number copied</div>
<script>
function copyRoll() {
    var t = document.getElementById('rollNo').textContent.trim(), toast = document.getElementById('toast');
    var done = function () { toast.classList.add('show'); setTimeout(function () { toast.classList.remove('show'); }, 1800); };
    if (navigator.clipboard) { navigator.clipboard.writeText(t).then(done); }
    else { var i = document.createElement('input'); i.value = t; document.body.appendChild(i); i.select(); document.execCommand('copy'); i.remove(); done(); }
}
</script>
</body>
</html>
