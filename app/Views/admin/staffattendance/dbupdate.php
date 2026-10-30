<?php
$st = $record ? $record['status'] : 'not run';
$colors = array('done' => '#16a34a', 'failed' => '#dc2626', 'running' => '#d97706', 'not run' => '#64748b');
$any_diff = false;
foreach ((array) $compare as $n) { if ((int) $n > 0) { $any_diff = true; } }
?>
<style>
    .du-card { background:#fff; border:1px solid #e2e8f0; border-radius:10px; padding:16px; margin-bottom:16px; }
    .du-badge { display:inline-block; color:#fff; font-weight:700; font-size:12px; padding:3px 10px; border-radius:12px; }
    .du-log { background:#0f172a; color:#e2e8f0; font:12px/1.55 Consolas, monospace; padding:12px; border-radius:8px; max-height:360px; overflow:auto; white-space:pre-wrap; margin:0; }
    .du-kv td { padding:4px 10px 4px 0; font-size:13px; vertical-align:top; }
    .du-kv td:first-child { color:#64748b; white-space:nowrap; }
</style>
<div class="content-wrapper">
    <section class="content">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-database"></i> Database Update: Staff Attendance</h3>
                <div class="box-tools pull-right">
                    <a href="<?php echo site_url('admin/staffattendance'); ?>" class="btn btn-default btn-sm"><i class="fa fa-arrow-left"></i> Staff Attendance</a>
                </div>
            </div>
            <div class="box-body" style="background:#f8fafc;">
                <div class="du-card">
                    <p style="margin-top:0;">
                        This update runs <b>once, automatically</b>, the first time an attendance page is opened after the new files are uploaded.
                        It first <b>backs up</b> the attendance tables, then adds only new columns, new tables, the "Leave" status, shift links and
                        change-log triggers, and finally <b>compares every existing row with the backup</b> to prove nothing changed.
                    </p>
                    <table class="du-kv">
                        <tr><td>Update</td><td><b><?php echo html_escape($title_text); ?></b> <span class="text-muted">(<?php echo html_escape($code); ?>)</span></td></tr>
                        <tr><td>Status</td><td><span class="du-badge" style="background:<?php echo $colors[$st] ?? '#64748b'; ?>;"><?php echo strtoupper(html_escape($st)); ?></span>
                            <?php if ($record) { ?> &nbsp; attempt <?php echo (int) $record['attempts']; ?>, run by <?php echo html_escape($record['run_by']); ?><?php } ?></td></tr>
                        <?php if ($record) { ?>
                            <tr><td>Started / finished</td><td><?php echo html_escape($record['started_at']); ?> &rarr; <?php echo html_escape($record['finished_at'] ?: '…'); ?></td></tr>
                            <tr><td>Backup tables</td><td><code><?php echo html_escape($record['backup_prefix']); ?>*</code></td></tr>
                        <?php } ?>
                        <tr><td>Change-log triggers</td><td><?php echo $triggers_ok ? '<span style="color:#16a34a;font-weight:700;">Installed</span>' : '<span style="color:#dc2626;font-weight:700;">Not installed</span> <span class="text-muted">(the database user needs the TRIGGER permission; everything else works)</span>'; ?></td></tr>
                    </table>
                </div>

                <?php if ($result) { ?>
                    <div class="du-card" style="border-color:<?php echo $result['status'] === 'done' ? '#86efac' : ($result['status'] === 'restored' ? '#93c5fd' : '#fca5a5'); ?>;">
                        <b>Result of this action: <?php echo strtoupper(html_escape($result['status'])); ?></b>
                        <pre class="du-log" style="margin-top:8px;"><?php echo html_escape(implode("\n", $result['log'])); ?></pre>
                    </div>
                <?php } ?>

                <?php if (!empty($compare)) { ?>
                <div class="du-card">
                    <b>Existing data compared with the backup now</b>
                    <p class="text-muted" style="font-size:12px; margin:4px 0 8px;">
                        Right after the update every count must be 0. Later, normal attendance work (syncs, edits, approvals) will change rows,
                        so non-zero counts days later are expected; use the Change Log to see what changed.
                    </p>
                    <table class="table table-condensed table-bordered" style="max-width:520px; margin:0;">
                        <thead><tr><th>Table</th><th>Rows that differ from backup</th></tr></thead>
                        <tbody>
                        <?php foreach ($compare as $t => $n) { ?>
                            <tr><td><?php echo html_escape($t); ?></td><td style="font-weight:700; color:<?php echo $n === null ? '#94a3b8' : ((int) $n === 0 ? '#16a34a' : '#dc2626'); ?>;"><?php echo $n === null ? 'no backup' : (int) $n; ?></td></tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
                <?php } ?>

                <div class="du-card">
                    <form method="post" style="display:inline;" onsubmit="return confirm('Run the database update now? It makes a fresh backup first.');">
                        <input type="hidden" name="action" value="run">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-play"></i> <?php echo $st === 'done' ? 'Run again (re-check)' : 'Run update now'; ?></button>
                    </form>
                    <a href="<?php echo site_url('admin/staffattendance/dbupdate'); ?>" class="btn btn-default"><i class="fa fa-refresh"></i> Verify again</a>
                    <?php if (!empty($record['backup_prefix'])) { ?>
                        <form method="post" style="display:inline;" onsubmit="return confirm('Put back the original attendance values from the backup <?php echo html_escape($record['backup_prefix']); ?>*?\n\nAny attendance changes made after the backup (syncs, edits, approvals) will be undone for rows that existed at backup time. New columns and tables are kept.');">
                            <input type="hidden" name="action" value="restore">
                            <button type="submit" class="btn <?php echo $any_diff ? 'btn-danger' : 'btn-default'; ?>"><i class="fa fa-undo"></i> Restore from backup</button>
                        </form>
                    <?php } ?>
                </div>

                <?php if ($record && !empty($record['log'])) { ?>
                    <div class="du-card">
                        <b>Log of the last run</b>
                        <pre class="du-log" style="margin-top:8px;"><?php echo html_escape($record['log']); ?></pre>
                    </div>
                <?php } ?>

                <?php if (!empty($backups)) { ?>
                    <div class="du-card">
                        <b>Backup tables in the database</b>
                        <span class="text-muted" style="font-size:12px;">(drop them in phpMyAdmin after 2–4 stable weeks)</span>
                        <table class="table table-condensed" style="margin:8px 0 0; font-size:12.5px;">
                            <thead><tr><th>Table</th><th>Rows (approx.)</th><th>Created</th></tr></thead>
                            <tbody>
                            <?php foreach ($backups as $b) { ?>
                                <tr><td><code><?php echo html_escape($b['TABLE_NAME']); ?></code></td><td><?php echo (int) $b['TABLE_ROWS']; ?></td><td><?php echo html_escape($b['CREATE_TIME']); ?></td></tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>
</div>
