<?php $currency_symbol = $this->customlib->getSchoolCurrencyFormat();?>
<style type="text/css">
    .dashboard2-wrapper {
        background-color: #f4f6f9;
        font-family: 'Inter', sans-serif;
    }
    .d2-card {
        background: #fff;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        border: 1px solid #eaeaea;
    }
    .d2-title {
        font-size: 12px;
        font-weight: 600;
        color: #8a8a8a;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 15px;
    }
    .d2-stat-value {
        font-size: 28px;
        font-weight: 700;
        color: #2c2c2c;
    }
    .d2-stat-sub {
        font-size: 12px;
        color: #8a8a8a;
        margin-top: 5px;
    }
    .d2-box {
        border-radius: 6px;
        padding: 15px;
        margin-bottom: 15px;
        border: 1px solid #f0f0f0;
    }
    .d2-box-title {
        font-size: 11px;
        text-transform: uppercase;
        font-weight: 600;
        margin-bottom: 5px;
    }
    .d2-box-val {
        font-size: 22px;
        font-weight: 700;
    }
    .d2-box-sub {
        font-size: 11px;
        color: #888;
    }
    .d2-box.students { background: #fdfaf6; border-color: #f7eedf; }
    .d2-box.students .d2-box-title { color: #d68940; }
    
    .d2-box.staff { background: #fdfaff; border-color: #f4e8fb; }
    .d2-box.staff .d2-box-title { color: #9d50ce; }

    .d2-box.attendance { background: #f6fffa; border-color: #dcf2e6; }
    .d2-box.attendance .d2-box-title { color: #3b9b65; }

    .d2-header-actions {
        display: flex;
        gap: 10px;
    }
    .d2-btn {
        padding: 6px 12px;
        border-radius: 4px;
        font-size: 13px;
        font-weight: 500;
        border: 1px solid #ccc;
        background: #fff;
        color: #333;
        transition: all 0.2s;
    }
    .d2-btn:hover { background: #f5f5f5; }
    .d2-btn.primary { background: #007bff; color: #fff; border-color: #007bff; }
    .d2-btn.primary:hover { background: #0069d9; }
    .d2-btn.success { background: #28a745; color: #fff; border-color: #28a745; }
    .d2-btn.success:hover { background: #218838; }
</style>
<div class="content-wrapper dashboard2-wrapper">
    <section class="content-header">
        <div class="row" style="display:flex; align-items:center; justify-content:space-between; margin-bottom: 20px;">
            <div class="col-md-6">
                <h1 style="margin:0; font-size: 24px; font-weight:700;"><?php echo $this->lang->line('cbse_exam'); ?> Dashboard</h1>
                <small style="color:#888;">CBSE Examination / Dashboard</small>
            </div>
            <div class="col-md-6 text-right d2-header-actions" style="justify-content: flex-end;">
                <a href="<?php echo site_url('cbseexam/result/marksheet') ?>" class="d2-btn success"><i class="fa fa-print"></i> Print Marksheet</a>
                <a href="<?php echo site_url('cbseexam/exam') ?>" class="d2-btn primary"><i class="fa fa-plus"></i> Create Exam</a>
                <a href="<?php echo site_url('cbseexam/template') ?>" class="d2-btn"><i class="fa fa-paint-brush"></i> Design Template</a>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="row">
            <!-- Session Overview -->
            <div class="col-md-8">
                <div class="d2-card">
                    <div class="d2-title">Examination Overview</div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="d2-stat-value"><?php echo $this->setting_model->getCurrentSessionName(); ?></div>
                            <div class="d2-stat-sub">Current Academic Session</div>
                            
                            <div class="row" style="margin-top:20px;">
                                <div class="col-md-6">
                                    <div class="d2-box students">
                                        <div class="d2-box-title">Total Exams</div>
                                        <div class="d2-box-val"><?php echo $total_exams; ?></div>
                                        <div class="d2-box-sub">Active Exams</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d2-box staff">
                                        <div class="d2-box-title">Marksheet Templates</div>
                                        <div class="d2-box-val"><?php echo $total_templates; ?></div>
                                        <div class="d2-box-sub">Designed Templates</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Upcoming Exams -->
            <div class="col-md-4">
                <div class="d2-card" style="height: calc(100% - 20px);">
                    <div class="d2-title" style="color:#007bff;">Upcoming Exams (CBSE)</div>
                    <?php if(!empty($upcoming_cbse_exams)): ?>
                        <ul style="list-style:none; padding:0; margin:0;">
                        <?php foreach($upcoming_cbse_exams as $ex): ?>
                            <li style="margin-bottom:15px; border-bottom:1px solid #e0e7ff; padding-bottom:10px;">
                                <div style="font-weight:600; font-size:14px; color:#222;"><?php echo $ex['exam_title']; ?></div>
                                <div style="font-size:12px; color:#888; margin-top:3px;">
                                    <i class="fa fa-calendar-check-o"></i> <?php echo date('d M Y', strtotime($ex['start_date'])); ?>
                                    <?php if($ex['start_date'] != $ex['end_date'] && !empty($ex['end_date']) && $ex['end_date'] != '0000-00-00') echo " - " . date('d M Y', strtotime($ex['end_date'])); ?>
                                </div>
                                <?php if(!empty($ex['exam_description'])): ?>
                                <div style="font-size:12px; color:#666; margin-top:5px; line-height:1.4;">
                                    <?php echo strip_tags($ex['exam_description']); ?>
                                </div>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <div style="font-size:13px; color:#888;">No upcoming exams</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Recent Templates -->
            <div class="col-md-8">
                <div class="d2-card" style="height: 100%;">
                    <div class="d2-title" style="color:#28a745;">Recently Designed Templates</div>
                    <?php if(!empty($recent_templates)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover" style="font-size: 13px;">
                                <thead>
                                    <tr>
                                        <th>Template Name</th>
                                        <th>Marksheet Type</th>
                                        <th>Created At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach($recent_templates as $tmpl): ?>
                                    <tr>
                                        <td style="font-weight: 600;"><?php echo $tmpl['name']; ?></td>
                                        <td>
                                            <?php 
                                            if ($tmpl['marksheet_type'] == 'term_wise') {
                                                echo '<span class="label bg-blue">Term Wise</span>';
                                            } else if ($tmpl['marksheet_type'] == 'exam_wise') {
                                                echo '<span class="label bg-green">Exam Wise</span>';
                                            } else if ($tmpl['marksheet_type'] == 'without_term') {
                                                echo '<span class="label bg-red">Without Term</span>';
                                            } else if ($tmpl['marksheet_type'] == 'all_term') {
                                                echo '<span class="label bg-yellow">All Term</span>';
                                            } else {
                                                echo '<span class="label bg-gray">Standard</span>';
                                            }
                                            ?>
                                        </td>
                                        <td><?php echo date('d M Y', strtotime($tmpl['date'])); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div style="font-size:13px; color:#888;">No templates designed yet</div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="d2-card" style="height:100%; display: flex; flex-direction: column; justify-content: center; align-items: center; background: linear-gradient(135deg, #fdf4ff 0%, #fae8ff 100%); border-left: 4px solid #c026d3;">
                    <div class="d2-title" style="color:#c026d3;">Need Help?</div>
                    <div style="text-align:center; padding: 20px;">
                        <i class="fa fa-book" style="font-size: 48px; color: #c026d3; margin-bottom: 15px;"></i>
                        <h4 style="font-weight: 700; margin-bottom: 10px; color: #1e1e2f;">CBSE User Guide</h4>
                        <p style="color: #666; font-size: 13px;">Follow the step-by-step guide to configure the CBSE module correctly.</p>
                        <button type="button" data-toggle="modal" data-target="#guideModal" class="d2-btn primary" style="margin-top: 10px; display: inline-block; border:none; padding:8px 16px;">View Documentation</button>
                    </div>
                </div>
            </div>
        </div>

    </section>
</div>

<!-- Guide Modal -->
<div class="modal fade" id="guideModal" tabindex="-1" role="dialog" aria-labelledby="guideModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content" style="border-radius: 8px; border: none;">
      <div class="modal-header" style="background: linear-gradient(135deg, #c026d3 0%, #9333ea 100%); color: white; border-radius: 8px 8px 0 0;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white; opacity: 0.8;"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="guideModalLabel"><i class="fa fa-graduation-cap"></i> CBSE Examination Module - User Guide</h4>
      </div>
      <div class="modal-body" style="padding: 30px; font-size: 14px; line-height: 1.6; color: #444;">
        <p style="font-size: 15px;">Welcome to the CBSE Examination Module! Setting up exams can seem complicated at first, but if you follow this step-by-step guide, you'll have your exams running and beautiful report cards printing in no time.</p>
        <p style="font-size: 15px; margin-bottom: 25px;">Think of this process like building a house: you need a foundation (<strong>Categories</strong>), walls (<strong>Exams & Subjects</strong>), a roof (<strong>Marks</strong>), and finally paint and decoration (<strong>Templates and Printing</strong>).</p>

        <h4 style="color: #c026d3; font-weight: 700; margin-top: 25px;"><i class="fa fa-folder-open-o"></i> Step 1: Create Categories (The Folders)</h4>
        <p><em>Where to go: <strong>CBSE Examination ➔ Category</strong></em></p>
        <p>Categories are just like folders that help you organize your exams.</p>
        <ul>
            <li><strong>Example:</strong> You might create a category called "Annual Exams" or "Monthly Class Tests".</li>
            <li><strong>Action:</strong> Click "Add" and simply give your category a name.</li>
        </ul>

        <h4 style="color: #c026d3; font-weight: 700; margin-top: 25px;"><i class="fa fa-pencil-square-o"></i> Step 2: Create Exams</h4>
        <p><em>Where to go: <strong>CBSE Examination ➔ Exam</strong></em></p>
        <p>Now you need to create the actual exams that the students will take.</p>
        <ul>
            <li><strong>Example:</strong> "Mid-Term Exam 2024" or "Final Term Exam 2024".</li>
            <li><strong>Action:</strong> Click "Add", name your exam, and assign it to one of the Categories you created in Step 1.</li>
        </ul>

        <h4 style="color: #c026d3; font-weight: 700; margin-top: 25px;"><i class="fa fa-book"></i> Step 3: Assign Subjects & Set Timetable</h4>
        <p><em>Where to go: <strong>CBSE Examination ➔ Exam ➔ Assign Subjects</strong></em></p>
        <p>You have an exam, but what subjects are in it?</p>
        <ul>
            <li><strong>Action:</strong> Click the <strong>Assign/View Subjects</strong> button next to your exam.</li>
            <li>Select the Class and Section.</li>
            <li>Add the subjects (like Math, Science, English) that belong to this exam.</li>
            <li>Here, you also define the <strong>Max Marks</strong> (e.g., 100), <strong>Passing Marks</strong> (e.g., 33), and the date and time of the test.</li>
        </ul>

        <h4 style="color: #c026d3; font-weight: 700; margin-top: 25px;"><i class="fa fa-list-ol"></i> Step 4: Enter Student Marks</h4>
        <p><em>Where to go: <strong>CBSE Examination ➔ Exam ➔ Marks Register</strong></em></p>
        <p>After the exam is finished, it's time to enter the students' scores.</p>
        <ul>
            <li><strong>Action:</strong> Click the <strong>Marks Register</strong> button next to your exam.</li>
            <li>Select the Class and Section.</li>
            <li>A list of students will appear. Type their marks into the boxes for each subject and click <strong>Save</strong>.</li>
        </ul>

        <h4 style="color: #c026d3; font-weight: 700; margin-top: 25px;"><i class="fa fa-paint-brush"></i> Step 5: Create a Marksheet Template (The Design)</h4>
        <p><em>Where to go: <strong>CBSE Examination ➔ Template</strong></em></p>
        <p>How do you want the final report card to look?</p>
        <ul>
            <li><strong>Action:</strong> Click "Add Template".</li>
            <li>This is where you design the report card. You can set the School Name, upload your school Logo, upload the Principal's Signature, and choose what details (like the student's photo or roll number) should show up on the paper.</li>
            <li>Make sure to select which Classes and Sections this template belongs to.</li>
        </ul>

        <h4 style="color: #c026d3; font-weight: 700; margin-top: 25px;"><i class="fa fa-link"></i> Step 6: Link Exams to Your Template (The Magic Step)</h4>
        <p><em>Where to go: <strong>CBSE Examination ➔ Template ➔ Link Exam</strong></em></p>
        <p>This is the most important step! You need to tell the system <em>which</em> exams should be printed on the template you just designed.</p>
        <ul>
            <li><strong>Action:</strong> Find your template and click the <strong>Link Exam</strong> button.</li>
            <li>Choose your <strong>Marksheet Type</strong>:
                <ul>
                    <li><em>Single Exam:</em> Just print one exam (like the Mid-Term).</li>
                    <li><em>Combined Exams:</em> Print multiple exams together on one sheet to calculate a final yearly grade.</li>
                </ul>
            </li>
            <li style="margin-top:10px;"><strong>What is Weightage?</strong> If you are combining exams, the system will ask for "Weightage". This just means "how much does this exam matter?".
                <ul>
                    <li><em>Example:</em> If Mid-Term matters 40% and Final-Term matters 60% for the final grade, you type "40" next to Mid-Term and "60" next to Final-Term. The total must equal 100.</li>
                </ul>
            </li>
        </ul>

        <h4 style="color: #c026d3; font-weight: 700; margin-top: 25px;"><i class="fa fa-print"></i> Step 7: Generate and Print Results! 🎉</h4>
        <p><em>Where to go: <strong>CBSE Examination ➔ Generate Result</strong></em></p>
        <p>You are done! Now you just generate the report cards.</p>
        <ul>
            <li><strong>Action:</strong> Select your Exam, Class, and Section.</li>
            <li>The system will calculate all the marks, apply any weightage, and generate beautiful, printable PDF report cards for your students!</li>
        </ul>

        <hr style="margin: 30px 0;">
        <div style="background: #fdf4ff; border-left: 4px solid #c026d3; padding: 15px; border-radius: 4px;">
            <strong style="color: #c026d3; font-size: 15px;"><i class="fa fa-lightbulb-o"></i> Rule of Thumb:</strong>
            <p style="margin: 5px 0 0 0;">Always go top-to-bottom on the menu. Create the Category ➔ Create the Exam ➔ Add Subjects ➔ Add Marks ➔ Create Template ➔ Link them ➔ Print!</p>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close Guide</button>
      </div>
    </div>
  </div>
</div>
