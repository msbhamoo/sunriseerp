<style>
/* Modern Material Register UX */
.modern-stat-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 16px;
    margin-bottom: 20px;
}
.modern-stat-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 16px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
    transition: all 0.2s ease;
}
.modern-stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.08);
}
.modern-stat-info .stat-label {
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    color: #64748b;
    letter-spacing: 0.5px;
    margin-bottom: 4px;
}
.modern-stat-info .stat-value {
    font-size: 22px;
    font-weight: 800;
    color: #0f172a;
    line-height: 1.2;
}
.modern-stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}
.chapter-pill-tag {
    display: inline-block;
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
    color: #334155;
    padding: 3px 8px;
    border-radius: 6px;
    font-size: 11px;
    margin: 2px;
}
</style>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-book" style="color: #6366f1;"></i> Curriculum & Syllabus Catalog
            <small>AI-Generated & Cached Chapter Blueprints</small>
        </h1>
    </section>

    <section class="content">
        <!-- KPI Summary Stat Cards -->
        <div class="modern-stat-grid">
            <div class="modern-stat-card">
                <div class="modern-stat-info">
                    <div class="stat-label">Saved Subject Syllabi</div>
                    <div class="stat-value"><?php echo count($syllabus_list); ?></div>
                </div>
                <div class="modern-stat-icon" style="background: rgba(99, 102, 241, 0.12); color: #6366f1;">
                    <i class="fa fa-database"></i>
                </div>
            </div>

            <div class="modern-stat-card">
                <div class="modern-stat-info">
                    <div class="stat-label">Total Classes Covered</div>
                    <div class="stat-value text-success" style="color: #059669;">
                        <?php 
                        $distinct_classes = [];
                        foreach ($syllabus_list as $s) {
                            $distinct_classes[$s['class_name']] = true;
                        }
                        echo count($distinct_classes);
                        ?>
                    </div>
                </div>
                <div class="modern-stat-icon" style="background: rgba(16, 185, 129, 0.12); color: #10b981;">
                    <i class="fa fa-graduation-cap"></i>
                </div>
            </div>

            <div class="modern-stat-card">
                <div class="modern-stat-info">
                    <div class="stat-label">Cache Engine</div>
                    <div class="stat-value" style="color: #0284c7; font-size: 18px;">Instant 0ms</div>
                </div>
                <div class="modern-stat-icon" style="background: rgba(14, 165, 233, 0.12); color: #0284c7;">
                    <i class="fa fa-bolt"></i>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary" style="border-radius: 12px; overflow: hidden; border-top: 3px solid #6366f1;">
                    <div class="box-header ptbnull" style="padding: 14px 18px;">
                        <h3 class="box-title titlefix">
                            <i class="fa fa-list text-muted" style="margin-right: 6px;"></i> NCERT & CBSE Subject Chapter Syllabi
                        </h3>
                        <div class="box-tools pull-right" style="display: flex; gap: 8px;">
                            <a href="<?php echo base_url(); ?>admin/aiexamgenerator" class="btn btn-primary btn-sm" style="background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%); border: none; font-weight: 700; padding: 6px 14px; border-radius: 6px;">
                                <i class="fa fa-bolt"></i> AI Exam Studio
                            </a>
                        </div>
                    </div>
                    <div class="box-body" style="padding: 15px 18px;">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped table-bordered example">
                                <thead>
                                    <tr style="background: #f8fafc;">
                                        <th style="width: 50px;">#</th>
                                        <th style="width: 140px;">Class</th>
                                        <th style="width: 160px;">Subject</th>
                                        <th>Cached Chapter Syllabus & Scope</th>
                                        <th style="width: 120px;">Last Synced</th>
                                        <th style="width: 130px;" class="text-right noExport">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($syllabus_list)) { 
                                        $i = 1;
                                        foreach ($syllabus_list as $row) {
                                            $chapters = json_decode($row['chapters_json'], true);
                                            if (!is_array($chapters)) $chapters = [];
                                    ?>
                                        <tr>
                                            <td><?php echo $i++; ?></td>
                                            <td>
                                                <span class="label label-primary" style="font-size: 12px; background: #e0e7ff; color: #4338ca; border: 1px solid #c7d2fe;">
                                                    <?php echo htmlspecialchars($row['class_name']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <strong style="color: #0f172a; font-size: 13px;">
                                                    <?php echo htmlspecialchars($row['subject_name']); ?>
                                                </strong>
                                            </td>
                                            <td>
                                                <div style="margin-bottom: 4px;">
                                                    <span class="badge" style="background: #e2e8f0; color: #334155; font-size: 11px;">
                                                        <?php echo count($chapters); ?> Chapters / Units
                                                    </span>
                                                </div>
                                                <div style="display: flex; flex-wrap: wrap; gap: 3px;">
                                                    <?php 
                                                    $ch_limit = 6;
                                                    $rendered_count = 0;
                                                    foreach ($chapters as $ch) { 
                                                        if ($rendered_count < $ch_limit) { ?>
                                                            <span class="chapter-pill-tag"><?php echo htmlspecialchars($ch); ?></span>
                                                        <?php }
                                                        $rendered_count++;
                                                    } 
                                                    if (count($chapters) > $ch_limit) { ?>
                                                        <span class="chapter-pill-tag" style="background: #e0e7ff; color: #4338ca; font-weight: 600;">+<?php echo count($chapters) - $ch_limit; ?> more</span>
                                                    <?php } ?>
                                                </div>
                                            </td>
                                            <td style="font-size: 12px; color: #64748b; white-space: nowrap;">
                                                <?php echo !empty($row['updated_at']) ? date('d M Y, h:i A', strtotime($row['updated_at'])) : '-'; ?>
                                            </td>
                                            <td class="text-right white-space-nowrap">
                                                <button type="button" class="btn btn-default btn-xs" onclick='openEditSyllabusModal(<?php echo htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8'); ?>)' title="Edit Chapter List" style="border-color: #cbd5e1; color: #4338ca;">
                                                    <i class="fa fa-pencil"></i> Edit
                                                </button>
                                                <button type="button" class="btn btn-default btn-xs" onclick="reSyncSingleSyllabus('<?php echo htmlspecialchars($row['class_name'], ENT_QUOTES); ?>', '<?php echo htmlspecialchars($row['subject_name'], ENT_QUOTES); ?>')" title="Re-fetch via AI" style="border-color: #cbd5e1; color: #059669;">
                                                    <i class="fa fa-refresh"></i> AI Sync
                                                </button>
                                                <button type="button" class="btn btn-default btn-xs text-danger" onclick="deleteSyllabus(<?php echo $row['id']; ?>)" title="Remove Entry" style="border-color: #cbd5e1;">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php } } else { ?>
                                        <tr>
                                            <td colspan="6" class="text-center" style="padding: 40px 20px; color: #64748b;">
                                                <i class="fa fa-book" style="font-size: 32px; color: #cbd5e1; margin-bottom: 10px; display: block;"></i>
                                                <strong>No cached curriculum syllabi found yet.</strong>
                                                <p style="margin-top: 6px; font-size: 13px;">Generate a question paper or click "Sync All Syllabi via AI" in the AI Exam Studio to auto-populate chapter lists.</p>
                                                <a href="<?php echo base_url(); ?>admin/aiexamgenerator" class="btn btn-primary btn-sm" style="margin-top: 10px;">
                                                    <i class="fa fa-bolt"></i> Go to AI Exam Studio
                                                </a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal: Edit Chapters List -->
<div class="modal fade" id="modalEditSyllabus" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header" style="background: #ffffff; color: #0f172a; border-bottom: 1px solid #e2e8f0; padding: 16px 20px;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" style="font-weight: 700;">
                    <i class="fa fa-edit text-primary"></i> Edit Curriculum Chapters: <span id="edit_syllabus_title"></span>
                </h4>
            </div>
            <div class="modal-body" style="padding: 20px;">
                <input type="hidden" id="edit_class_name">
                <input type="hidden" id="edit_subject_name">
                <div class="form-group">
                    <label style="font-weight: 600;">Chapters / Units List (1 per line):</label>
                    <textarea id="edit_chapters_raw" class="form-control" rows="12" style="font-family: inherit; font-size: 13px;"></textarea>
                    <small class="text-muted">Enter or edit chapter names. Each line will become a selectable chapter badge in the AI Exam Generator.</small>
                </div>
            </div>
            <div class="modal-footer" style="background: #f8fafc; border-top: 1px solid #e2e8f0;">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="btnSaveSyllabus" onclick="saveEditedSyllabus()"><i class="fa fa-save"></i> Save Changes</button>
            </div>
        </div>
    </div>
</div>

<script>
function openEditSyllabusModal(row) {
    $('#edit_class_name').val(row.class_name);
    $('#edit_subject_name').val(row.subject_name);
    $('#edit_syllabus_title').text(`${row.class_name} - ${row.subject_name}`);

    let chapters = [];
    try {
        chapters = JSON.parse(row.chapters_json);
    } catch(e) {}

    $('#edit_chapters_raw').val(chapters.join("\n"));
    $('#modalEditSyllabus').modal('show');
}

function saveEditedSyllabus() {
    const className = $('#edit_class_name').val();
    const subjectName = $('#edit_subject_name').val();
    const chaptersRaw = $('#edit_chapters_raw').val();

    const btn = $('#btnSaveSyllabus');
    btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');

    $.ajax({
        url: '<?php echo base_url(); ?>admin/aiexamsyllabus/save_chapters_ajax',
        type: 'POST',
        dataType: 'json',
        data: {
            class_name: className,
            subject_name: subjectName,
            chapters_raw: chaptersRaw
        },
        success: function(res) {
            btn.prop('disabled', false).html('<i class="fa fa-save"></i> Save Changes');
            if (res.status === 'success') {
                $('#modalEditSyllabus').modal('hide');
                location.reload();
            } else {
                alert('Error: ' + res.message);
            }
        },
        error: function() {
            btn.prop('disabled', false).html('<i class="fa fa-save"></i> Save Changes');
            alert('Failed to save syllabus.');
        }
    });
}

function reSyncSingleSyllabus(className, subjectName) {
    if (!confirm(`Re-fetch standard curriculum syllabus for "${className} - ${subjectName}" from AI?`)) return;

    $.ajax({
        url: '<?php echo base_url(); ?>admin/aiexamgenerator/get_or_fetch_chapters_ajax',
        type: 'POST',
        dataType: 'json',
        data: {
            class_name: className,
            subject_name: subjectName,
            api_engine: 'gemini',
            force_reload: 1
        },
        success: function(res) {
            if (res.status === 'success') {
                location.reload();
            } else {
                alert('Sync Error: ' + res.message);
            }
        },
        error: function() {
            alert('Failed to re-sync syllabus.');
        }
    });
}

function deleteSyllabus(id) {
    if (!confirm('Remove this syllabus entry from the cache?')) return;

    $.ajax({
        url: '<?php echo base_url(); ?>admin/aiexamsyllabus/delete_syllabus_ajax',
        type: 'POST',
        dataType: 'json',
        data: { id: id },
        success: function(res) {
            if (res.status === 'success') {
                location.reload();
            } else {
                alert('Error: ' + res.message);
            }
        }
    });
}
</script>
