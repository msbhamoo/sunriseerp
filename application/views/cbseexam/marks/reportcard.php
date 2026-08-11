<?php
$exam = $card['exam'];
$grades = $card['grades'];
$report = $card['report'];
// $school may arrive as an object (getSetting) or an array of rows; normalize.
$sch = $school;
if (is_array($sch)) { $sch = isset($sch[0]) ? $sch[0] : null; }
$sch = (object) ($sch ? (array) $sch : array());
$sname = isset($sch->name) ? $sch->name : '';
$saddr = isset($sch->address) ? $sch->address : '';
$semail = isset($sch->email) ? $sch->email : '';
$sphone = isset($sch->phone) ? $sch->phone : '';
$slogo = !empty($sch->image) ? base_url('uploads/school_content/logo/' . $sch->image) : '';
function rc_fmt($n) { return rtrim(rtrim(number_format((float)$n, 2, '.', ''), '0'), '.'); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Report Card — <?php echo htmlspecialchars($exam['name']); ?></title>
<style>
  :root{ --ink:#1a1a1a; --muted:#555; --line:#c9c9c9; --line-strong:#111; --band:#f2f4f7; --accent:#0b3d6b; }
  *{box-sizing:border-box}
  body{margin:0;background:#e9ecf1;font-family:"Segoe UI",Roboto,Helvetica,Arial,sans-serif;color:var(--ink);font-size:13px}
  .toolbar{position:sticky;top:0;background:#0b3d6b;color:#fff;padding:10px 16px;display:flex;gap:12px;align-items:center;justify-content:space-between}
  .toolbar button{background:#fff;color:#0b3d6b;border:none;border-radius:6px;padding:8px 16px;font-weight:700;cursor:pointer;font-size:13px}
  .toolbar .meta{font-size:13px;opacity:.9}
  .card{background:#fff;width:210mm;min-height:296mm;margin:16px auto;padding:14mm 12mm;box-shadow:0 2px 10px rgba(0,0,0,.15);page-break-after:always}
  .card:last-child{page-break-after:auto}
  .hdr{display:flex;align-items:center;gap:14px;border-bottom:3px double var(--line-strong);padding-bottom:10px}
  .hdr img{width:74px;height:74px;object-fit:contain}
  .hdr .school{flex:1;text-align:center}
  .hdr .school h1{margin:0;font-size:23px;letter-spacing:.4px;color:var(--accent);text-transform:uppercase}
  .hdr .school .addr{font-size:11.5px;color:var(--muted);margin-top:3px}
  .rc-title{text-align:center;font-weight:700;font-size:14px;margin:12px 0 14px;text-transform:uppercase;letter-spacing:.5px}
  .rc-title span{border:1px solid var(--line-strong);padding:4px 16px;border-radius:4px}
  .info{display:grid;grid-template-columns:1fr 1fr;gap:2px 24px;margin-bottom:14px}
  .info .row{display:flex;font-size:12.5px;padding:3px 0;border-bottom:1px dotted var(--line)}
  .info .row .l{width:120px;color:var(--muted);font-weight:600}
  .info .row .v{flex:1;font-weight:600}
  table{width:100%;border-collapse:collapse;font-size:12.5px}
  th,td{border:1px solid var(--line);padding:7px 8px}
  thead th{background:var(--band);text-align:left;font-size:11.5px;text-transform:uppercase;letter-spacing:.3px}
  td.num,th.num{text-align:center;font-variant-numeric:tabular-nums}
  tfoot td{font-weight:700;background:var(--band)}
  .absent{color:#b23b3b;font-weight:700}
  .summary{display:grid;grid-template-columns:repeat(5,1fr);gap:10px;margin:14px 0}
  .summary .box{border:1px solid var(--line);border-radius:6px;padding:10px 12px;text-align:center}
  .summary .box .k{font-size:11px;color:var(--muted);text-transform:uppercase;letter-spacing:.4px}
  .summary .box .v{font-size:18px;font-weight:800;margin-top:2px;color:var(--accent)}
  .summary .box.pass .v{color:#1f7a4d}.summary .box.fail .v{color:#b23b3b}
  .legend{margin-top:12px;font-size:11px;color:var(--muted)}
  .legend b{color:var(--ink)}
  .legend .g{display:inline-block;margin-right:12px;white-space:nowrap}
  .sign{display:flex;justify-content:space-between;margin-top:44px;font-size:12px}
  .sign div{text-align:center;border-top:1px solid var(--line-strong);padding-top:5px;width:150px;color:var(--muted)}
  .empty{padding:40px;text-align:center;color:#888}
  @media print{
    body{background:#fff}
    .toolbar{display:none}
    .card{width:auto;min-height:auto;margin:0;box-shadow:none;padding:8mm 10mm}
    @page{size:A4;margin:8mm}
  }
</style>
</head>
<body>
<div class="toolbar">
  <span class="meta"><?php echo htmlspecialchars($exam['name']); ?> — <?php echo count($report); ?> student(s)</span>
  <button onclick="window.print()">&#128424; Print / Save PDF</button>
</div>

<?php if (empty($report)) { ?>
  <div class="card"><div class="empty">No students found for the selected exam and class.</div></div>
<?php } foreach ($report as $rc) {
    $s = $rc['student'];
    $full = trim($s['firstname'] . ' ' . (isset($s['middlename']) ? $s['middlename'] . ' ' : '') . (isset($s['lastname']) ? $s['lastname'] : ''));
    $roll = ($exam['use_exam_roll_no'] != 0 && !empty($s['exam_roll_no'])) ? $s['exam_roll_no'] : (isset($s['roll_no']) ? $s['roll_no'] : '');
?>
<div class="card">
  <div class="hdr">
    <?php if ($slogo) { ?><img src="<?php echo $slogo; ?>" alt=""><?php } ?>
    <div class="school">
      <h1><?php echo htmlspecialchars($sname); ?></h1>
      <div class="addr"><?php echo htmlspecialchars($saddr); ?><?php if ($sphone) echo ' &nbsp;|&nbsp; Ph: ' . htmlspecialchars($sphone); ?><?php if ($semail) echo ' &nbsp;|&nbsp; ' . htmlspecialchars($semail); ?></div>
    </div>
    <?php if ($slogo) { ?><img src="<?php echo $slogo; ?>" alt="" style="visibility:hidden"><?php } ?>
  </div>

  <div class="rc-title"><span>Report Card &nbsp;·&nbsp; <?php echo htmlspecialchars($exam['name']); ?></span></div>

  <div class="info">
    <div class="row"><span class="l">Student Name</span><span class="v"><?php echo htmlspecialchars($full); ?></span></div>
    <div class="row"><span class="l">Class / Section</span><span class="v"><?php echo htmlspecialchars($s['class_name'] . ' - ' . $s['section_name']); ?></span></div>
    <div class="row"><span class="l">Roll No.</span><span class="v"><?php echo htmlspecialchars($roll); ?></span></div>
    <div class="row"><span class="l">Admission No.</span><span class="v"><?php echo htmlspecialchars(isset($s['admission_no']) ? $s['admission_no'] : ''); ?></span></div>
    <div class="row"><span class="l">Father's Name</span><span class="v"><?php echo htmlspecialchars(isset($s['father_name']) ? $s['father_name'] : ''); ?></span></div>
    <div class="row"><span class="l">Mother's Name</span><span class="v"><?php echo htmlspecialchars(isset($s['mother_name']) ? $s['mother_name'] : ''); ?></span></div>
    <div class="row"><span class="l">Date of Birth</span><span class="v"><?php echo htmlspecialchars(isset($s['dob']) ? $s['dob'] : ''); ?></span></div>
    <div class="row"><span class="l">Attendance</span><span class="v"><?php echo htmlspecialchars((isset($s['total_present_days']) ? $s['total_present_days'] : 0) . (isset($s['total_working_days']) && $s['total_working_days'] ? ' / ' . $s['total_working_days'] : '') . ' days'); ?></span></div>
  </div>

  <table>
    <thead>
      <tr>
        <th style="width:34px" class="num">#</th>
        <th>Subject</th>
        <th class="num" style="width:90px">Max Marks</th>
        <th class="num" style="width:90px">Marks Obtained</th>
        <th class="num" style="width:70px">Grade</th>
      </tr>
    </thead>
    <tbody>
      <?php $i = 1; foreach ($rc['subjects'] as $sub) { ?>
      <tr>
        <td class="num"><?php echo $i++; ?></td>
        <td><?php echo htmlspecialchars($sub['subject_name']); ?><?php echo $sub['subject_code'] ? ' <span style="color:#888">(' . htmlspecialchars($sub['subject_code']) . ')</span>' : ''; ?></td>
        <td class="num"><?php echo rc_fmt($sub['max']); ?></td>
        <td class="num">
          <?php if ($sub['absent']) { echo '<span class="absent">AB</span>'; }
                elseif ($sub['obtained'] === null) { echo '<span style="color:#aaa">—</span>'; }
                else { echo rc_fmt($sub['obtained']); } ?>
        </td>
        <td class="num"><?php echo htmlspecialchars($sub['grade']); ?></td>
      </tr>
      <?php } ?>
      <?php if (empty($rc['subjects'])) { ?>
      <tr><td colspan="5" class="empty">No subjects mapped to this student's class.</td></tr>
      <?php } ?>
    </tbody>
    <?php if (!empty($rc['subjects'])) { ?>
    <tfoot>
      <tr>
        <td colspan="2">Total</td>
        <td class="num"><?php echo rc_fmt($rc['grand_max']); ?></td>
        <td class="num"><?php echo rc_fmt($rc['grand_obtained']); ?></td>
        <td class="num"><?php echo htmlspecialchars($rc['overall_grade']); ?></td>
      </tr>
    </tfoot>
    <?php } ?>
  </table>

  <div class="summary">
    <div class="box"><div class="k">Total</div><div class="v"><?php echo rc_fmt($rc['grand_obtained']) . ' / ' . rc_fmt($rc['grand_max']); ?></div></div>
    <div class="box"><div class="k">Percentage</div><div class="v"><?php echo rc_fmt($rc['overall_percent']); ?>%</div></div>
    <div class="box"><div class="k">Grade</div><div class="v"><?php echo htmlspecialchars($rc['overall_grade'] ?: '—'); ?></div></div>
    <div class="box"><div class="k">Class Rank</div><div class="v"><?php echo !empty($rc['rank']) ? $rc['rank'] : '—'; ?></div></div>
    <div class="box <?php echo $rc['result'] === 'PASS' ? 'pass' : 'fail'; ?>"><div class="k">Result</div><div class="v"><?php echo $rc['result']; ?></div></div>
  </div>

  <?php if (!empty($grades)) { ?>
  <div class="legend">
    <b>Grading:</b>
    <?php foreach ($grades as $g) { ?>
      <span class="g"><b><?php echo htmlspecialchars($g['name']); ?></b> (<?php echo rc_fmt($g['minimum_percentage']); ?>–<?php echo rc_fmt($g['maximum_percentage']); ?>%)</span>
    <?php } ?>
  </div>
  <?php } ?>

  <div class="sign">
    <div>Class Teacher</div>
    <div>Examination In-charge</div>
    <div>Principal</div>
  </div>
</div>
<?php } ?>
</body>
</html>
