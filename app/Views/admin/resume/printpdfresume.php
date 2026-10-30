<?php /* AUTO_INJECT_SAFETY_START */
if (empty($student) || !is_array($student)) { $student = []; }
     $student = new class($student) implements ArrayAccess {
         private $d;
         public function __construct($d) { $this->d = $d; }
         public function offsetExists($k): bool { return true; }
         public function offsetGet($k): mixed { return $this->d[$k] ?? ""; }
         public function offsetSet($k, $v): void { $this->d[$k] = $v; }
         public function offsetUnset($k): void { unset($this->d[$k]); }
     };
/* AUTO_INJECT_SAFETY_END */ ?>
<?php 
if (empty($student) || !is_array($student)) { $student = []; }
$default_keys = ["admission_no","roll_no","firstname","middlename","lastname","mobileno","email","dob","gender","religion","cast","about","category_id","designation","image","current_address","permanent_address"];
foreach ($default_keys as $k) { if (!isset($student[$k])) { $student[$k] = ""; } }
?>
<?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? []));
if (empty($student_data)) {
    $student_data = [
        "image" => "",
        "firstname" => "",
        "lastname" => "",
        "gender" => "",
        "dob" => "",
        "mobileno" => "",
        "email" => "",
        "current_address" => "",
        "permanent_address" => "",
        "father_name" => "",
        "mother_name" => "",
        "guardian_name" => "",
        "category" => "",
        "religion" => "",
        "cast" => "",
        "blood_group" => "",
        "height" => "",
        "weight" => "",
        "measurement_date" => "",
        "admission_no" => "",
        "roll_no" => "",
        "class" => "",
        "section" => ""
    ];
}
$student = $student_data;
?>
<?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? []));  
	$student=$student_data; 

?>
<div style="width: 100%; margin: 0 auto;">
 <table cellpadding="0" cellspacing="0" width="100%">
  <tr>
   <td valign="top" style="background:#f6f6f6; padding: 6px 10px 12px">
    <table cellpadding="0" cellspacing="0" width="100%">
    <tr>
    <?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? []));
    if ($this->customlib->getfieldcustomstatus('student_photo')) {
        if (!empty($student["image"])) {
            $image_url = $this->media_storage->getImageURL($student["image"]);
        } else {
            if ((($student['gender'] ?? '') ?? '') == 'Female') {
                $image_url = $this->media_storage->getImageURL("uploads/student_images/default_female.jpg");
            } else {
                $image_url = $this->media_storage->getImageURL("uploads/student_images/default_male.jpg");
            }
        } 
        ?>
      <td valign="top" width="120" style="padding-top:5px;">
       <img src="<?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo $image_url; ?>" width="100" height="100">
      </td>
      <?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); } ?>
      <td valign="top" >
       <table cellpadding="0" cellspacing="0" width="100%">

        <tr>
         <td valign="top">
          <h1><?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? []));
if ($this->customlib->getfieldcustomstatus('lastname')) {
$lastname=($student['lastname'] ?? '') ?? '';
$sch_setting_lastname=$sch_setting->lastname;
}else{
$lastname="";
$sch_setting_lastname="";   
} 
 
echo $student_name=$this->customlib->getFullName(($student['firstname'] ?? '') ?? '',($student['middlename'] ?? '') ?? '',$lastname,$sch_setting->middlename,$sch_setting_lastname);

?></h1>

		<?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); if($this->customlib->get_additional_field_status('other_details')) { if(!empty(($student['designation'] ?? ''))) { ?>
          <h4 style="line-height:normal;margin-bottom: 5px;"><?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo ($student['designation'] ?? ''); ?></h4>
		<?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); } } ?>  
		  
          <hr style="background:#ddd;margin-bottom: 5px; height: 1px; margin-top: 6px;" />
         </td>
        </tr>
        <tr>
         <td valign="top">
          <table cellpadding="0" cellspacing="0" width="100%">
           <tr>
            <?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); if ($this->customlib->getfieldcustomstatus('mobile_no')) {?>
            <td width="30"><img src="<?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo base_url("backend/images/student_resume/phone-icon.png");?>" width="17" height="17"></td>
            <td><?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo ($student['mobileno'] ?? '');?></td>
            <?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? []));  } ?>
            <?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); if ($this->customlib->getfieldcustomstatus('student_email')) {?>
          	
            <td width="30"><img src="<?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo base_url("backend/images/student_resume/email-icon.png");?>" width="19" height="19"></td>
            <td><a href="<?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo ($student['email'] ?? '');?>" style="color:#444; text-decoration: none;"><?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo ($student['email'] ?? '');?></a></td>
            <?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); } ?>
        	</tr>
        	<tr>
            <?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); if ($this->customlib->getfieldcustomstatus('guardian_address')) {?>
            <td style="padding-top: 6px"><img src="<?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo base_url("backend/images/student_resume/location-icon.png");?>" width="20" height="20"></td>
            <td style="padding-top: 6px"><?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo ($student['guardian_address'] ?? '');?></td>
            <?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); } ?>
           </tr>
          </table>
         </td>
        </tr>
       </table>
      </td>
     </tr>
    </table>
   </td>
  </tr>
  
  <?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); if($this->customlib->get_additional_field_status('other_details')) { if((($student['about'] ?? '') ?? '')) { ?>
  <tr>
   <td valign="top" style="padding: 20px 10px 10px">
    <h2 class="heading-h2"><?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo $this->lang->line('about_me'); ?></h2>
    <hr />
    <p><?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo (($student['about'] ?? '') ?? ''); ?></p>
   </td>
  </tr>
  <?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); } } ?>
  
 <?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); if ($this->customlib->getfieldcustomstatus('personal_details')){ ?>
  <tr>
   <td valign="top" style="padding: 10px 10px 10px;">
    <table cellpadding="0" cellspacing="0" width="100%">
     <tr>
      <td valign="top" class="pb-5">
       <h2 class="heading-h2"><?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo $this->lang->line('personal_details'); ?></h2>
      </td>
     </tr>
     <tr>
      <td valign="top">
       <table cellpadding="0" cellspacing="0" width="100%" class="denifittable">   
        <tr>
			 <?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); if ($this->customlib->getfieldcustomstatus('dob')) {?>
				<th width="30%" style="border-bottom: 0"><?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo $this->lang->line('date_of_birth'); ?></th>
				<td width="20%" style="border-bottom: 0">
					<?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); if (!empty(($student['dob'] ?? '')) && ($student['dob'] ?? '') != '0000-00-00') {
						echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat(($student['dob'] ?? '')));
					} ?></td>        
			<?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); } ?>
			<?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); if ($this->customlib->getfieldcustomstatus('gender')) {?>        
				<th width="30%" style="border-bottom:0"><?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo $this->lang->line('gender'); ?></th>
				<td width="20%" style="border-bottom:0"><?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo ($student['gender'] ?? '');?></td>        
			<?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); } ?>
		<tr>

			<tr>			
			<?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); if ($this->customlib->getfieldcustomstatus('category')) {?>         
				<th width="30%"><?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo $this->lang->line('category'); ?></th>
				<td width="20%">
					<?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? []));
					foreach ($category_list as $value) {
						if ((($student['category_id'] ?? '') ?? 0) == $value['id']) {
							echo $value['category'];
						}
					}
					?>
				</td>         
			<?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); } ?>

			<?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); if ($this->customlib->getfieldcustomstatus('religion')) {?>        
				<th width="30%"><?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo $this->lang->line('religion'); ?></th>
				<td width="20%"><?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo ($student['religion'] ?? ''); ?></td>       
			<?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); } ?>
			
		</tr>
       
		<tr>
		<?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); if ($this->customlib->getfieldcustomstatus('cast')) {?>         
				<th width="30%"><?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo $this->lang->line('caste'); ?></th>
				<td width="20%"><?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo ($student['cast'] ?? '');  ?></td>         
			<?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); } ?>

			<?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); if ($this->customlib->getfieldcustomstatus('is_blood_group')) {?>         
				<th width="30%"><?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo $this->lang->line('blood_group'); ?></th>
				<td width="20%"><?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo ($student['blood_group'] ?? '');?></td>       
			<?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); } ?>
			
		</tr>	
        
		<tr>	
		<?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); if ($this->customlib->getfieldcustomstatus('height')) {?>        
				<th width="30%"><?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo $this->lang->line('height'); ?></th>
				<td width="20%"><?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo ($student['height'] ?? '');?></td>         
			<?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); } ?>		
			<?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); if ($this->customlib->getfieldcustomstatus('weight')) {?>        
				<th width="30%"><?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo $this->lang->line('weight'); ?></th>
				<td width="20%"><?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo ($student['weight'] ?? '');?></td>         
			<?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); } ?>	
		</tr>		
		<tr>			
			<?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); if ($this->customlib->getfieldcustomstatus('rte')) {?>        
				<th width="30%"><?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo $this->lang->line('rte'); ?></th>
				<td width="20%"><?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo ($student['rte'] ?? '');?></td>         
			<?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); } ?>
			<?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); if ($this->customlib->getfieldcustomstatus('previous_school_details')) {?>       
				<th width="30%"><?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo $this->lang->line('previous_school_details'); ?></th>
				<td width="20%"><?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo ($student['previous_school'] ?? '');?></td>        
			<?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); } ?>
		</tr>

			 <tr>
			<?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); if ($this->customlib->getfieldcustomstatus('national_identification_no')) {?>        
				<th width="30%"><?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo $this->lang->line('national_identification_number'); ?></th>
				<td width="20%"><?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo ($student['adhar_no'] ?? '');?></td>        
			<?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); } ?>
			<?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); if ($this->customlib->getfieldcustomstatus('local_identification_no')) {?>         
				<th width="30%"><?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo $this->lang->line('local_identification_number'); ?></th>
				<td width="20%"><?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo ($student['samagra_id'] ?? '');?></td>
			<?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); } ?>	
								
		</tr>		
					
        <?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); 
			//***student custom fields data***//
			$cutom_fields_data = get_custom_table_values(($student['id'] ?? ''), 'students');
			if (!empty($cutom_fields_data)) {
				
				foreach ($cutom_fields_data as $field_key => $field_value) {
					if ($this->customlib->getfieldcustomstatus($field_value->name)) {
					 ?>	
					<tr>
						<th width="20%" colspan="1"><?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo $field_value->name; ?></th>
						<td colspan="3"><?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? []));
						if (is_string($field_value->field_value) && is_array(json_decode($field_value->field_value, true)) && (json_last_error() == JSON_ERROR_NONE)) {
						$field_array = json_decode($field_value->field_value);
							echo "<ul class='student_custom_field'>";
							foreach ($field_array as $each_key => $each_value) {
								echo "<li>" . $each_value . "</li>";
							}
							echo "</ul>";
						} else {
							$display_field = $field_value->field_value;
							if ($field_value->type == "link") {
								$display_field = "<a href=" . $field_value->field_value . " target='_blank'>" . $field_value->field_value . "</a>";
						}
							echo $display_field;
						}
						?>
						</td>
					</tr>
						<?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); 
						 ?>
       			<?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); 
					} 
				}
			} 
		?>


       </table>
      </td>
     </tr>
    </table>
   </td>
  </tr>

<?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); } ?>
  <?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); if ($this->customlib->getfieldcustomstatus('parent_guardian_detail')) {?>
  <tr>
   <td valign="top" style="padding: 10px 10px 10px;">
    <table cellpadding="0" cellspacing="0" width="100%">
     <tr>
      <td valign="top" class="pb-5">
       <h2 class="heading-h2"><?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo $this->lang->line('parent_guardian_detail'); ?></h2>
      </td>
     </tr>
     <tr>
      <td valign="top">
       <table cellpadding="0" cellspacing="0" width="100%" class="denifittable">
		<tr>
			<?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); if ($this->customlib->getfieldcustomstatus('father_name')) {?>        
				<th width="25%"><?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo $this->lang->line('father_name'); ?></th>
				<td><?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo ($student['father_name'] ?? ''); ?></td>         
			<?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); } ?>
			<?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); if ($this->customlib->getfieldcustomstatus('mother_name')) {?>         
				<th><?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo $this->lang->line('mother_name'); ?></th>
				<td width=""><?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo ($student['mother_name'] ?? '');  ?></td>        
			<?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); } ?>
		</tr>
		<tr>
			<?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); if ($this->customlib->getfieldcustomstatus('father_occupation')) {?>       
				<th><?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo $this->lang->line('father_occupation'); ?></th>
				<td><?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo ($student['father_occupation'] ?? '');  ?></td>        
			<?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); } ?>
			<?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); if ($this->customlib->getfieldcustomstatus('mother_occupation')) {?>        
				<th><?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo $this->lang->line('mother_occupation'); ?></th>
				<td><?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo ($student['mother_occupation'] ?? ''); ?></td>        
			<?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); } ?>
			
		</tr>
		<tr>
			<?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); if ($this->customlib->getfieldcustomstatus('father_phone')) {?>        
				<th><?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo $this->lang->line('father_phone'); ?></th>
				<td><?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo ($student['father_phone'] ?? '');?></td>        
			<?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); } ?>
			<?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); if ($this->customlib->getfieldcustomstatus('mother_phone')) {?>        
				<th><?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo $this->lang->line('mother_phone'); ?></th>
				<td><?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo ($student['mother_phone'] ?? '');?></td>        
			<?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); } ?>
			
		</tr>
        <?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); //if ($this->customlib->getfieldcustomstatus('if_guardian_is')) {?>
		<tr>
			<?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); if ($this->customlib->getfieldcustomstatus('guardian_name')) {?>       
				<th><?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo $this->lang->line('guardian_name'); ?></th>
				<td><?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo ($student['guardian_name'] ?? '');  ?></td>        
			<?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); } ?>
			<?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); if ($this->customlib->getfieldcustomstatus('guardian_relation')) {?>         
				<th><?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo $this->lang->line('guardian_relation'); ?></th>
				<td><?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? []));  echo ($student['guardian_relation'] ?? ''); ?></td>       
			<?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); } ?> 
		</tr>
		<tr>
			<?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); if ($this->customlib->getfieldcustomstatus('guardian_phone')) {?>        
				<th><?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo $this->lang->line('guardian_phone'); ?></th>
				<td><?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo ($student['guardian_phone'] ?? '');?></td>        
			<?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); } ?>
			<?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); if ($this->customlib->getfieldcustomstatus('guardian_email')) {?>         
				<th><?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo $this->lang->line('guardian_email'); ?></th>
				<td><?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo ($student['guardian_email'] ?? '');?></td>        
			<?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); } ?>
		</tr>
		<tr>
			<?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); if ($this->customlib->getfieldcustomstatus('guardian_occupation')) {?>        
				<th><?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo $this->lang->line('guardian_occupation'); ?></th>
				<td><?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo ($student['guardian_occupation'] ?? ''); ?></td>       
			<?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); } ?>
			<?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); if ($this->customlib->getfieldcustomstatus('guardian_address')) {?>        
				<th><?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo $this->lang->line('guardian_address'); ?></th>
				<td><?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo ($student['guardian_address'] ?? '');  ?></td>       
			<?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); } ?>
		</tr>
        <?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); //} ?>
       </table>
      </td>
     </tr>
    </table>
   </td>
  </tr>
  <?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); } ?>
  <?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? []));

if (!empty($get_student_education) && $this->customlib->get_additional_field_status('education_qalification')) { ?>
  <tr>
   <td valign="top" style="padding: 10px 10px 10px;">
    <table cellpadding="0" cellspacing="0" width="100%">
     <tr>
      <td valign="top" class="pb-5">
       <h2 class="heading-h2"><?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo $this->lang->line('education_qalification'); ?></h2>
      </td>
     </tr>
     <tr>
      <td valign="top">
       <table cellpadding="0" cellspacing="0" width="100%" class="denifittable">
        <?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? []));
        foreach($get_student_education as $key=>$value){ ?>
        <tr>
         <td valign="top">
          <h3><?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo $value['course'];?></h3>
          <p class="font-italic"><?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo $value['university'];?>&nbsp;<span style="float: right;"><?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo $value['education_year'];?></span></p>
          <p style="padding-bottom:30px"><?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo $value['education_detail'];?></p>
         </td>
        </tr>
        <?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); } ?>
       </table>
      </td>
     </tr>
    </table>
   </td>
  </tr>
  <?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? []));   
}  ?>
  <?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); if (!empty($get_student_work_experience) && $this->customlib->get_additional_field_status('work_experience')) { ?>
  <tr>
   <td valign="top" style="padding: 10px 10px 0;">
    <table cellpadding="0" cellspacing="0" width="100%">
     <tr>
      <td valign="top" class="pb-5">
       <h2 class="heading-h2"><?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo $this->lang->line('work_experience'); ?></h2>
      </td>
     </tr>
     <tr>
      <td valign="top">
       <table cellpadding="0" cellspacing="0" width="100%" class="denifittable">
        <?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? []));
         foreach($get_student_work_experience as $key=>$value){
                ?>
        <tr>
         <td valign="top">
          <h3><?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo $value['designation'];?></h3>
          <p class="font-italic"><?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo $value['institute'];?> (<?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo $value['location'];?>) <span style="float: right;"><?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo $value['year'];?></span></p>
          <p style="padding-bottom:30px"><?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo $value['detail'];?></p>
         </td>
        </tr>
        <?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); } ?>
       </table>
      </td>
     </tr>
    </table>
   </td>
  </tr>
  <?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); } ?>
  <tr>
   <td valign="top" style="padding: 10px 10px 0;">
    <table cellpadding="0" cellspacing="0" width="100%">
     <tr>
      <?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); if (!empty($get_student_skills) && $this->customlib->get_additional_field_status('technical_skills')) {  ?>
      <td valign="top" style="width: 48%; padding-top:1rem">
       <h2 class="heading-h2"><?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo $this->lang->line('technical_skills'); ?></h2>
       <hr />
       <ul class="hlaful">
        <?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? []));
                foreach($get_student_skills as $key=>$value){  ?>
        <li><b><?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo $value['skill_category'];?></b>:-<?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo $value['skill_detail'];?> </li>
        <?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); } ?>
       </ul>
      </td>
      <?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); }  ?>
      </tr>
    </table>
   </td>
  </tr>

  <?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); if (!empty($get_student_reference) && $this->customlib->get_additional_field_status('reference')){ ?>
  <tr>
   <td valign="top" style="padding: 20px 10px 0;">
    <table cellpadding="0" cellspacing="0" width="100%">
     <tr>
      <td valign="top" class="pb-5">
       <h2 class="heading-h2"><?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo $this->lang->line('reference'); ?></h2>
      </td>
     </tr>
     <tr>
      <td valign="top">
       <table cellpadding="0" cellspacing="0" width="100%" class="denifittable">
        <?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); foreach($get_student_reference as $key=>$value){ ?>
        <tr>
         <td valign="top">
          <h3><?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo $value['name'];?></h3>
          <p class="font-italic"><?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo $value['profession'];?> <span style="float: right;"> </span></p>
          <p style="padding-bottom:30px"><?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo $this->lang->line('contact'); ?>: <?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo $value['contact'];?>, &nbsp;<?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo $this->lang->line('age'); ?>: <?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo $value['age'];?> <?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo $this->lang->line('year'); ?>, &nbsp; <?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo $this->lang->line('relation'); ?>: <?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); echo $value['relation'];?>
          </p>
         </td>
        </tr>
        <?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); } ?>
       </table>
      </td>
     </tr>
    </table>
   </td>
  </tr>
  <?php
$student = array_merge(['admission_no' => '', 'roll_no' => '', 'firstname' => '', 'middlename' => '', 'lastname' => '', 'mobileno' => '', 'email' => '', 'dob' => '', 'gender' => '', 'current_address' => '', 'permanent_address' => '', 'father_name' => '', 'mother_name' => '', 'guardian_name' => '', 'guardian_relation' => '', 'guardian_phone' => '', 'guardian_address' => '', 'class' => '', 'section' => '', 'image' => '', 'designation' => ''], (array)($student ?? [])); } ?>
 </table>
</div>