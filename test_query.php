<?php require "index.php"; $CI =& get_instance(); $CI->load->model("studentcall_model"); print_r($CI->studentcall_model->get_students_call_status(null, null, 0, 10));
