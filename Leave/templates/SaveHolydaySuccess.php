<script type="text/javascript" src="<?php echo public_path('../../scripts/jquery/jquery.min.js') ?>"></script>
<script type="text/javascript" src="<?php echo public_path('../../scripts/jquery/jquery-ui.min.js') ?>"></script>
<link href="<?php echo public_path('../../themes/orange/css/jquery/jquery-ui.css') ?>" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="<?php echo public_path('../../scripts/jquery/jquery.validate.js') ?>"></script>
<link href="../../themes/orange/css/style.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="<?php echo public_path('../../scripts/time.js') ?>"></script>
<script type="text/javascript" src="<?php echo public_path('../../scripts/jquery.placeholder.js') ?>"></script>
<?php

                    $sysConf = OrangeConfig::getInstance()->getSysConf();
                    $inputDate = $sysConf->getDateInputHint();
                    $dateDisplayHint = $sysConf->dateDisplayHint;
                    $format = LocaleUtil::convertToXpDateFormat($sysConf->getDateFormat());
?>
<div class="formpage4col" >
    <div class="navigation">

    </div>
    <div id="status"></div>
    <div class="outerbox">
        <div class="mainHeading"><h2><?php echo __("Define Holiday") ?></h2></div>
        <form name="frmSave" id="frmSave" method="post"  action="">
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
                <label for="txtLocationCode"><?php echo __("Holiday Name") ?> <span class="required">*</span></label>
            </div>
            <div class="centerCol">
                <textarea id="txtName"  name="txtName" type="text"  class="formTextArea" value=""  ></textarea>
            </div>


            <div class="centerCol">
                <textarea id="txtJobTitleDesc" class="formTextArea"  name="txtNamesi" type="text"></textarea>

            </div>
            <div class="centerCol">
                <textarea id="txtJobTitleComments" class="formTextArea" name="txtNameta" type="text"></textarea>

            </div>
            <br class="clear"/>
            <div class="leftCol" >
                <label for="txtLocationCode" ><?php echo __("Date") ?><span class="required">*</span></label>
            </div>
            <div class="centerCol" style="padding-top: 8px;padding-left: 8px;">
                <input id="txtLeaveStartDate" type="text" placeholder="<?php echo  $dateDisplayHint; ?>" name="txtLeaveStartDate" value="<?php //echo $extfdate;   ?>" onchange="txtenable(this.value);">
            </div>

            <br class="clear"/>
            <div class="leftCol" >
                <label for="txtLocationCode"><?php echo __("Full Day/Half Day") ?><span class="required">*</span></label>
            </div>
            <div class="centerCol" style="padding-top: 8px;padding-left: 8px;">

                <select name="cmbHalfDay" id="cmbHalfDay"  style="width: 160px;">
                    <option value=""><?php echo __("--Select--") ?></option>
                    <option value="1"><?php echo __("FullDay"); ?></option>
                    <option value="0"><?php echo __("HalfDay"); ?></option>
                </select>
            </div>
            <br class="clear"/>


            <br class="clear"/>
            <div class="formbuttons">
                <input type="button" class="savebutton" id="editBtn"

                       value="<?php echo __("Save") ?>" />
                <input type="button" class="clearbutton"  id="resetBtn"
                       value="<?php echo __("Reset") ?>" />
                <input type="button" class="backbutton" id="btnBack"
                       value="<?php echo __("Back") ?>" />
            </div>
        </form>
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

        $("#txtLeaveStartDate").placeholder();
        buttonSecurityCommon("null","editBtn","null","null");
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
        $("#editBtn").click(function() {
            $('#frmSave').submit();
        });

        //When click reset buton
        $("#resetBtn").click(function() {
            document.forms[0].reset('');
        });

        //When Click back button
        $("#btnBack").click(function() {
            location.href = "<?php echo url_for(public_path('../../symfony/web/index.php/Leave/Holyday')) ?>";
        });


        $("#txtLeaveStartDate").datepicker({ dateFormat:'<?php echo $inputDate; ?>' });
    });
</script>
