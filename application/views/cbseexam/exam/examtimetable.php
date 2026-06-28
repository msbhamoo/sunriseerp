<div class="content-wrapper"> 
    <!-- Main content -->
    <section class="content">
        <div class="row">           
            <div class="col-md-12">             
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('exam_schedule'); ?></h3>  
                    </div>
                    <div class="box-body">                        
                        <form role="form" id="timetable_form" method="post" class="row class_search_form">
                            <?php echo $this->customlib->getCSRF(); ?>                           
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('exam'); ?></label><small class="req"> *</small>
                                    <select id="exam_id" name="exam_id" class="form-control" >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>  
                                        <?php
                                        foreach ($exams as $exam_key => $exam_value) {
                                            ?>
                                            <option value="<?php echo $exam_value['id'] ?>" ><?php echo $exam_value['name'] ?></option>
                                            <?php
                                        }
                                        ?>                                      
                                    </select>
                                    <span class="text-danger" id="error_exam"></span>
                                </div>
                            </div> 
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('class'); ?></label>
                                    <select id="class_id" name="class_id" class="form-control" >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>  
                                        <?php
                                        foreach ($classes as $class) {
                                            ?>
                                            <option value="<?php echo $class['id'] ?>"><?php echo $class['class'] ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div> 
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary pull-right btn-sm checkbox-toggle"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                </div>
                            </div>
                        </form>

                        <div id="timetable_result"></div>
                    </div>
                </div>
            </div> 
        </div> 
    </section>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('#timetable_form').on('submit', function (e) {
            e.preventDefault();
            var exam_id = $('#exam_id').val();
            if (exam_id == "") {
                $('#error_exam').html('<?php echo $this->lang->line('the_exam_field_is_required'); ?>');
                return false;
            } else {
                $('#error_exam').html('');
            }
            var $this = $(this).find("button[type=submit]:focus");
            $.ajax({
                type: "POST",
                url: baseurl + "cbseexam/exam/get_examtimetable_matrix",
                data: $(this).serialize(),
                dataType: "json",
                beforeSend: function () {
                    $this.button('loading');
                },
                success: function (data) {
                    if (data.status == 1) {
                        $('#timetable_result').html(data.page);
                    }
                    $this.button('reset');
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    $this.button('reset');
                }
            });
        });
    });

    function printDiv(tagid) {
        let hashid = "#"+ tagid;
        var tagname =  $(hashid).prop("tagName").toLowerCase() ;
        var attributes = ""; 
        var attrs = document.getElementById(tagid).attributes;
        $.each(attrs,function(i,elem){
            attributes +=  " "+  elem.name+" ='"+elem.value+"' " ;
        });
        var divToPrint= $(hashid).html() ;
        var head = "<html><head>"+ $("head").html() + "</head>" ;
        var allcontent = head + "<body>"+ "<" + tagname + attributes + ">" +  divToPrint + "</" + tagname + ">" +  "</body></html>"  ;
        var frame1 = $('<iframe />');
        frame1[0].name = "frame1";
        frame1.css({ "position": "absolute", "top": "-1000000px" });
        $("body").append(frame1);
        var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
        frameDoc.document.open();
        frameDoc.document.write(allcontent);
        frameDoc.document.close();
        setTimeout(function () {
            window.frames["frame1"].focus();
            window.frames["frame1"].print();
            frame1.remove();
        }, 500);
    }
</script>