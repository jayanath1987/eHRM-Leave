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
<div class="formpage4col">
    <div class="navigation">


    </div>
    <div id="status"></div>
    <div class="outerbox">
        <div class="mainHeading"><h2><?php echo __("Edit Holiday") ?></h2></div>
        <form name="frmSave" id="frmSave" method="post"  action="">
            <?php echo message() ?>
            <?php echo $form['_csrf_token']; ?>
            <div class="leftCol">
                &nbsp;
            </div>
            <div class="centerCol">
                <label class="languageBar"><?php echo __("English") ?></label>
            </div>
            <div class="centerCol">
                <label class="languageBar"><?php echo __("Sinhala") ?></label>
            </div>
            <div class="centerCol">
                <label class="languageBar"><?php echo __("Tamil") ?></label>
            </div>
            <br class="clear"/>
            <div class="leftCol">
                <label for="txtLocationCode"><?php echo __("Leave Type Name") ?><span class="required">*</span></label>
                <input name="txtbtid" type="hidden" value="<?php echo $benifittypelist->getLeave_holiday_id() ?>"/>
            </div>
            <div class="centerCol">
                <textarea id="txtJobTitleDesc" class="formTextArea" tabindex="1" name="txtName" type="text"><?php echo $benifittypelist->getLeave_holiday_name() ?></textarea>
            </div>
            <div class="centerCol">
                <textarea id="txtJobTitleDesc" class="formTextArea" tabindex="2" name="txtNamesi" type="text"><?php echo $benifittypelist->getLeave_holiday_name_si() ?></textarea>
            </div>
            <div class="centerCol">
                <textarea id="txtJobTitleComments" class="formTextArea" tabindex="3" name="txtNameta" type="text"><?php echo $benifittypelist->getLeave_holiday_name_ta() ?></textarea>
            </div>
            <br class="clear"/>
            <div class="leftCol" >
                <label for="txtLocationCode" ><?php echo __("Date") ?><span class="required">*</span></label>
            </div>
            <div class="centerCol" style="padding-top: 8px;padding-left: 8px;">
                <input id="txtLeaveStartDate" type="text" name="txtLeaveStartDate" value="<?php echo LocaleUtil::getInstance()->formatDate($benifittypelist->getLeave_holiday_date()); ?>" onchange="txtenable(this.value);">
            </div>

            <br class="clear"/>
            <div class="leftCol" >
                <label for="txtLocationCode"><?php echo __("FullDay/HalfDay") ?><span class="required">*</span></label>
            </div>
            <div class="centerCol" style="padding-top: 8px;padding-left: 8px;">

                <select name="cmbHalfDay" id="cmbHalfDay"  style="width: 160px;">
                    <option value="" <?php if ($benifittypelist->getLeave_holiday_fulorhalf() == " ") {
                echo "selected";
            } ?>><?php echo __("--Select--") ?></option>
                    <option value="1" <?php if ($benifittypelist->getLeave_holiday_fulorhalf() == "1") {
                echo "selected";
            } ?>><?php echo __("FullDay"); ?></option>
                    <option value="0" <?php if ($benifittypelist->getLeave_holiday_fulorhalf() == "0") {
                echo "selected";
            } ?>><?php echo __("HalfDay"); ?></option>
                </select>
            </div>
            <br class="clear"/>

        </form>



        <div class="formbuttons">
            <input type="button" class="<?php echo $editMode ? 'editbutton' : 'savebutton'; ?>" name="EditMain" id="editBtn"
                   value="<?php echo $editMode ? __("Edit") : __("Save"); ?>"
                   title="<?php echo $editMode ? __("Edit") : __("Save"); ?>"
                   onmouseover="moverButton(this);" onmouseout="moutButton(this);"/>
            <input type="reset" class="clearbutton" id="btnClear" tabindex="5"
                   onmouseover="moverButton(this);" onmouseout="moutButton(this);"	<?php echo $disabled; ?>
                   value="<?php echo __("Reset"); ?>" />
            <input type="button" class="backbutton" id="btnBack"
                   value="<?php echo __("Back") ?>" tabindex="18"  onclick="goBack();"/>
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

                $(document).ready(function() {
                    buttonSecurityCommon("null","null","editBtn","null");
<?php if ($editMode == true) { ?>
                                          $('#frmSave :input').attr('disabled', true);
                                          $('#editBtn').removeAttr('disabled');
                                          $('#btnBack').removeAttr('disabled');
<?php } ?>

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


                                   $("#frmSave").validate({

                                       rules: {
                                           txtName: { required: true,noSpecialCharsOnly: true, maxlength:100 },
                                           txtNamesi: {noSpecialCharsOnly: true, maxlength:100 },
                                           txtNameta: {noSpecialCharsOnly: true, maxlength:100 },
                                           txtLeaveStartDate: { required: true ,orange_date:true},
                                           cmbHalfDay: { required: true },
                                           cmbannual: { required: true }
                                       },
                                       messages: {
                                           txtName: {required:"<?php echo __("Holiday is required in English") ?>",maxlength:"<?php echo __("Maximum 100 Characters") ?>",noSpecialCharsOnly:"<?php echo __("Special Characters are not allowed") ?>"},
                                           txtNamesi:{maxlength:"<?php echo __("Maximum 100 Characters") ?>",noSpecialCharsOnly:"<?php echo __("Special Characters are not allowed") ?>"},
                                           txtNameta:{maxlength:"<?php echo __("Maximum 100 Characters") ?>",noSpecialCharsOnly:"<?php echo __("Special Characters are not allowed") ?>"},
                                           txtLeaveStartDate:{ required:"<?php echo __("This is required ") ?>",orange_date: "<?php echo __("Please specify valid date") ?>"},
                                           cmbHalfDay: "<?php echo __("This is required ") ?>",
                                           cmbannual: "<?php echo __("This is required ") ?>"
                                       }
                                   });

                                   // When click edit button
                                   $("#frmSave").data('edit', <?php echo $editMode ? '1' : '0' ?>);

                                   $("#editBtn").click(function() {

                                       var editMode = $("#frmSave").data('edit');
                                       if (editMode == 1) {
                                           // Set lock = 1 when requesting a table lock

                                           location.href="<?php echo url_for('Leave/UpdateHolyday?id=' . $benifittypelist->getLeave_holiday_id() . '&lock=1') ?>";
                                       }
                                       else {

                                           $('#frmSave').submit();
                                       }


                                   });

                                   //When Click back button
                                   $("#btnBack").click(function() {
                                       location.href = "<?php echo url_for(public_path('../../symfony/web/index.php/Leave/Holyday')) ?>";
                                   });

                                   //When click reset buton
                                   $("#btnClear").click(function() {
                                       // Set lock = 0 when resetting table lock
                                       location.href="<?php echo url_for('Leave/UpdateHolyday?id=' . $benifittypelist->getLeave_holiday_id() . '&lock=0') ?>";
                                   });

                                   $("#txtLeaveStartDate").datepicker({ dateFormat:'<?php echo $inputDate; ?>' });

                   });
</script>
