<?php
if ($lockMode == '1') {
    $editMode = false;
    $disabled = '';
} else {
    $editMode = true;
    $disabled = 'disabled="disabled"';
}
?>
<script type="text/javascript" src="<?php echo public_path('../../scripts/jquery/jquery.min.js') ?>"></script>
<script type="text/javascript" src="<?php echo public_path('../../scripts/jquery/jquery-ui.min.js') ?>"></script>
<link href="<?php echo public_path('../../themes/orange/css/jquery/jquery-ui.css') ?>" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="<?php echo public_path('../../scripts/jquery/jquery.validate.js') ?>"></script>
<link href="../../themes/orange/css/style.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="<?php echo public_path('../../scripts/time.js') ?>"></script>
<style>
    abc { color: #000; }
</style>
<div class="formpage4col">
    <div class="navigation">


    </div>
    <div id="status"></div>
    <div class="outerbox">

        <?php echo $form['_csrf_token']; ?>

        <div class="mainHeading"><h2><?php echo __("Leave") ?></h2></div>
        <form enctype="multipart/form-data" name="frmSave" id="frmSave" method="post"  action="">
            <?php echo message() ?>
            <div class="leftCol">
                &nbsp;
            </div>
            <br class="clear"/>
            <div class="leftCol">
                <label class="controlLabel" for="txtLocationCode"><?php echo __("Employee Name") ?></label>
            </div>
            <div class="centerCol" style="padding-top: 8px;">
                <input type="text" name="txtEmployeeName" disabled="disabled" id="txtEmployee" value="<?php
            if ($Culture == 'en') {
                $abcd = "getEmp_display_name";
            } else {
                $abcd = "getEmp_display_name_" . $Culture;
            }

            if ($EData[0]->$abcd() == " ") {
                echo $EData[0]->getEmp_display_name();
            } else {
                echo $EData[0]->$abcd();
            } ?>" readonly="readonly" style="color: #000"/>
                <input type="hidden" name="txtEmpId" id="txtEmpId" value="<?php echo $EData[0]->getEmp_number(); ?>"/>&nbsp;
            </div>
            <br class="clear"/>
            <div class="leftCol" >
                <label for="txtLocationCode" ><?php echo __("Designation") ?></label>
            </div>
            <div class="centerCol" style="padding-top: 8px;">
                <input id="txtDesignation" type="text" name="txtDesignation" value="<?php
                       if ($Culture == 'en') {
                           $abc = "getName";
                       } else {
                           $abc = "getName_" . $Culture;
                       }

                       if ($EData[0]->jobTitle->$abc() == null) {
                           echo $EData[0]->jobTitle->getName();
                       } else {
                           echo $EData[0]->jobTitle->$abc();
                       }
            ?>" readonly="readonly" style="color: #000">
                <input type="hidden" name="txtDesignationId" id="txtDesignationId" value="<?php echo $EData[0]->getJob_title_code(); ?>" readonly="readonly"/>
            </div>
            <br class="clear"/>
            <div class="leftCol" >
                <label for="txtLocationCode"><?php echo __("Department") ?></label>
            </div>
            <div class="centerCol" style="padding-top: 8px;">
                <input id="txtDepartment" type="text" name="txtDepartment"  value="<?php
                       if ($Culture == 'en') {
                           $ab = "getTitle";
                       } else {
                           $ab = "getTitle_" . $Culture;
                       }

                       if ($EData[0]->subDivision->$ab() == null) {
                           echo $EData[0]->subDivision->getTitle();
                       } else {
                           echo $EData[0]->subDivision->$ab();
                       }
            ?>" readonly="readonly" style="color: #000">
                <input type="hidden" name="txtDepartmentId" id="txtDepartmentId" value="<?php echo $EData[0]->getWork_station(); ?>" />
            </div>
            <br class="clear"/>
            <div class="leftCol" >
                <label for="txtLocationCode"><?php echo __("Approving Person Name") ?></label>
            </div>
            <div class="centerCol" style="padding-top: 8px;">
                <input id="txtActingPerson" type="text" name="txtActingPerson" value="<?php
                       //echo $Sup[0]['supervisorId'];
                       if ($Culture == 'en') {
                           $ad = "getEmp_display_name";
                       } else {
                           $ad = "getEmp_display_name_" . $Culture;
                       }

                       if ($ESupData[0]->$ad() == " ") {
                           echo $ESupData[0]->getEmp_display_name();
                       } else {
                           echo $ESupData[0]->$ad();
                       }
            ?>" readonly="readonly" style="color: #000">
                <input type="hidden" name="txtActingPersonId" id="txtActingPersonId" value="<?php echo $ESupData[0]->getEmp_number(); ?>" />
            </div>
            <br class="clear"/>
            <div class="leftCol" >
                <label class=""><?php echo __("Leave Type Name") ?><span class="required">*</span></label>
            </div>
            <div class="centerCol" style="padding-top: 8px;" >

                <input  type="hidden" name="cmbbtype" id="cmbbtype" value="<?php echo $Entitle->getLeave_type_id(); ?>" />
                <?php foreach ($loadbtype as $btype) {
 ?>

<?php if ($Entitle->getLeave_type_id() == $btype->getLeave_type_id()) { ?>
                               <input  name="dcmbbtype" id="dcmbbtype" value="<?php
                               if ($Culture == 'en') {
                                   echo $btype->getLeave_type_name();
                               } elseif ($Culture == 'si') {
                                   if (($btype->getLeave_type_name_si()) == null) {
                                       echo $btype->getLeave_type_name();
                                   } else {
                                       echo $btype->getLeave_type_name_si();
                                   }
                               } elseif ($Culture == 'ta') {
                                   if (($btype->getLeave_type_name_ta()) == null) {
                                       echo $btype->getLeave_type_name();
                                   } else {
                                       echo $btype->getLeave_type_name_ta();
                                   }
                               }
?>" />
                        <?php }
                       } ?>
            </div>
            <br class="clear"/>
            <div class="leftCol" >
                <label for="txtLocationCode" ><?php echo __("Leave Start Date") ?><span class="required">*</span></label>
            </div>
            <div class="centerCol" style="padding-top: 8px;">
                <input id="txtLeaveStartDate" type="text" style="color: #000" name="txtLeaveStartDate" value="<?php echo LocaleUtil::getInstance()->formatDate($Entitle->getLeave_app_start_date()); ?>" onchange="txtenable(this.value);" readonly="readonly">
            </div>
            <br class="clear"/>
            <div class="leftCol" >
                <label for="txtLocationCode"><?php echo __("Leave End Date") ?><span class="required">*</span></label>
            </div>
            <div class="centerCol" style="padding-top: 8px;">
                <input id="txtLeaveEndDate" type="text" style="color: #000" name="txtLeaveEndDate" value="<?php echo LocaleUtil::getInstance()->formatDate($Entitle->getLeave_app_end_date()); ?>" onchange="getday(this.value);" readonly="readonly">
            </div>
            <br class="clear"/>
            <div class="leftCol" >
                <label for="txtLocationCode"><?php echo __("Number of Days Applied") ?></label>
            </div>
            <div class="centerCol" style="padding-top: 8px;color: #000">
                <input id="txtnofodays" type="text" style="color: #000" name="txtnofodays" value="<?php echo $Entitle->getLeave_app_workdays(); ?>" readonly="readonly">
            </div>
            <br class="clear"/>
            <div class="leftCol" >
                <label for="txtLocationCode"><?php echo __("Leave Reason") ?><span class="required">*</span></label>
            </div>

            <div class="centerCol" style="padding-top: 8px;" >


                <input style="width: 150px;" name="LeaveReason" id="LeaveReason" value="<?php
                       switch ($Entitle->getLeave_app_reason()) {
                           case 1:
                               echo __("Sick");
                               break;
                           case 2:
                               echo __("Alms Giving");
                               break;
                           case 3:
                               echo __("Funeral");
                               break;
                           case 4:
                               echo __("Study");
                               break;
                           case 5:
                               echo __("Personal");
                               break;
                       }
                        ?>" readonly="readonly"/>

                <input type="hidden" name="cmbLeaveReason" id="cmbLeaveReason" value="<?php echo $Entitle->getLeave_app_reason(); ?>" readonly="readonly"/>
            </div>
            <br class="clear"/>

            <div class="leftCol">
                <label class="controlLabel" for="txtLocationCode"><?php echo __("Acting Person") ?> <span class="required">*</span></label>
            </div>
            <div class="centerCol" style="padding-top: 8px;">
                <input type="text" name="txtAppEmployee" disabled="disabled" id="txtAppEmployee" value="<?php
                       //echo $Entitle->getLeave_app_covemp_number()->Employee->;
                       if ($Culture == 'en') {
                           $ad = "getEmp_display_name";
                       } else {
                           $ad = "getEmp_display_name_" . $Culture;
                       }

                       if ($ECov[0]->$ad() == " ") {
                           echo $ECov[0]->getEmp_display_name();
                       } else {
                           echo $ECov[0]->$ad();
                       }
                        ?>" readonly="readonly" style="color: #000"/>
                <input type="hidden" name="txtAppEmpId" id="txtAppEmpId" value="<?php echo $Entitle->getLeave_app_covemp_number(); ?>" readonly="readonly"/>&nbsp;
            </div>
            
            
            <div class="centerCol" style="padding-top: 8px;">
                <input class="button" type="button" value="..." id="empRepPopBtn" name="empRepPopBtn" <?php echo "disabled=disabled"; ?> />
            </div>
            
            <br class="clear"/>
                        <div class="leftCol" style="width: 150px;">
                            <label class="controlLabel" for="txtLocationCode"><?php echo __("Attachment") ?> </label></div>
            <div class="centerCol"><INPUT style="" TYPE="file" class="" VALUE="Upload" name="txtletter" <?php echo $disabled; ?> style="margin-top: 5px"></div>
                        
            <br class="clear"/>
            <div class="leftCol" style="width: 150px;">
                <label for="txtLocationCode"><?php echo __("Comments") ?> </label>
            </div>
            <div class="centerCol" style="margin-top: 5px;">
                <textarea style="margin-left: 0px;margin-top: 5px;color: #000"  id="txtComments" name="txtComments"  ><?php echo $Entitle->getLeave_app_comment(); ?></textarea>
            </div>
            <br class="clear"/>


            <br class="clear"/>
            <input type="hidden" name="txtLeaveID" id="txtLeaveID" value="<?php echo $Entitle->leave_app_id; ?>" readonly="readonly"/>
        </form>



        <div class="formbuttons">
            <input type="button" class="<?php echo $editMode ? 'editbutton' : 'savebutton'; ?>" name="EditMain" id="editBtn"
                   value="<?php echo $editMode ? __("Edit") : __("Leave Cancel"); ?>"
                   title="<?php echo $editMode ? __("Edit") : __("Leave Cancel"); ?>"
                   onmouseover="moverButton(this);" onmouseout="moutButton(this);"/>
            <input type="reset" class="clearbutton" id="btnClear"
                   onmouseover="moverButton(this);" onmouseout="moutButton(this);"	<?php echo $disabled; ?>
                   value="<?php echo __("Reset"); ?>" />
            <input type="button" class="backbutton" id="btnBack"
                   value="<?php echo __("Back") ?>" onclick="goBack();"/>
            <input type="button" class="backbutton" id="saveAttachment" <?php echo $disabled; ?>
                   value="<?php echo __("Save") ?>" onclick="saveAttachment();"/>
        </div>

    </div>
    <div class="requirednotice"><?php echo __("Fields marked with an asterisk") ?><span class="required"> * </span> <?php echo __("are required") ?></div>
    <br class="clear" />
</div>
<?php
                       require_once '../../lib/common/LocaleUtil.php';
                       $sysConf = OrangeConfig::getInstance()->getSysConf();
                       $sysConf = new sysConf();
                       $inputDate = $sysConf->dateInputHint;
                       $format = LocaleUtil::convertToXpDateFormat($sysConf->getDateFormat());
?>
                       <script type="text/javascript">
                           function saveAttachment(){

                                document.getElementById('frmSave').action = "<?php echo public_path('../../symfony/web/index.php/Leave/AttachementUpdate'); ?>";
                                document.getElementById('frmSave').submit();
                           }
                           
                           function txtenable(id){
                               if($('#txtLeaveStartDate').val()!= null){
                                   $('#txtLeaveEndDate').show(500);
                               }
                               if($('#txtLeaveEndDate').val()!= null){
                                   getday(1);
                               }
                           }
                           function getday(id){

                           }
                           function SelectEmployee(data){

                               myArr = data.split('|');
                               $("#txtAppEmpId").val(myArr[0]);
                               $("#txtAppEmployee").val(myArr[1]);

                           }
                           $(document).ready(function() {

                               var show="<?php echo $show; ?>";

                               if(show == 1){
                                   $('#editBtn').hide(100);
                                   $('#btnClear').hide(100);
                                   $('#saveAttachment').hide(100);
                               }else{
                                   $('#editBtn').show(100);
                                   $('#btnClear').show(100);
                                   $('#saveAttachment').show(100);
                               }



<?php if ($editMode == true) { ?>
                                   $('#frmSave :input').attr('disabled', true);
                                   $('#editBtn').removeAttr('disabled');
                                   $('#btnBack').removeAttr('disabled');
<?php } else { ?>

<?php } ?>


                               $('#empRepPopBtn').click(function() {

                                   var popup=window.open('<?php echo public_path('../../symfony/web/index.php/pim/searchEmployee?type=single&method=SelectEmployee'); ?>','Locations','height=450,width=800,resizable=1,scrollbars=1');

                                   if(!popup.opener) popup.opener=self;
                                   popup.focus();
                               });

                               jQuery.validator.addMethod("orange_date",
                               function(value, element, params) {

                                   var format = params[0];

                                   // date is not required
                                   if (value == '') {

                                       return true;
                                   }
                                   var d = strToDate(value, "<?php echo $format ?>");


                                   return (d != false);

                               }, ""
                           );


                               //Validate the form
                               $("#frmSave").validate({

                                   rules: {

                                   },
                                   messages: {
                                   }
                               });

                               // When click edit button
                               $("#frmSave").data('edit', <?php echo $editMode ? '1' : '0' ?>);

                               $("#editBtn").click(function() {

                                   var editMode = $("#frmSave").data('edit');
                                   if (editMode == 1) {
                                       // Set lock = 1 when requesting a table lock

                                       location.href="<?php echo url_for('Leave/UpdateLeave?id=' . $Entitle->getLeave_app_id() . '&lock=1') ?>";
                                   }
                                   else {

                                       $('#frmSave').submit();

                                   }


                               });

                               //When Click back button
                               $("#btnBack").click(function() {
                                   location.href = "<?php echo url_for(public_path('../../symfony/web/index.php/Leave/Leave')) . "?eid=" . $EData[0]->getEmp_number() ?>";
                               });

                               //When click reset buton
                               $("#btnClear").click(function() {
                                   // Set lock = 0 when resetting table lock
                                   location.href="<?php echo url_for('Leave/UpdateLeave?id=' . $Entitle->getLeave_app_id() . '&lock=0') ?>";
        });

    });
</script>
