<?php
if ($lockMode == '1') {
    $editMode = false;
    $disabled = '';
} else {
    $editMode = true;
    $disabled = 'disabled="disabled"';
}
?>
<?php //echo $Entitlemax."|". $Entitlemin."|".$EntitleTS; ?>

<script type="text/javascript" src="<?php echo public_path('../../scripts/jquery/jquery.validate.js') ?>"></script>
<div class="formpage4col">
    <div class="navigation">


    </div>
    <div id="status"></div>
    <div class="outerbox">
        <div class="mainHeading"><h2><?php echo __("Edit Leave Entitlement") ?></h2></div>
        <form name="frmSave" id="frmSave" method="post"  action="">
            <?php echo message() ?>
            <?php echo $form['_csrf_token']; ?>
            <div class="leftCol">
                <label class="controlLabel" for="txtLocationCode"><?php echo __("Employee Name") ?> <span class="required">*</span></label>
            </div>
            <div class="centerCol" style="padding-top: 8px;">
                <input type="text" name="txtEmployeeName" disabled="disabled" id="txtEmployee" value="<?php
            if ($Culture == 'en') {
                $abcd = "emp_display_name";
            } else {
                $abcd = "emp_display_name_" . $Culture;
            }
            echo $empname[0][$abcd]; ?>" readonly="readonly"/>
                <input type="hidden" name="txtEmpId" id="txtEmpId" value="<?php echo $Entitle[0]['emp_number']; ?>"/>&nbsp;
            </div>
            <div class="centerCol" style="padding-top: 8px;">
                <input class="button" type="button" value="..." id="empRepPopBtn" name="empRepPopBtn" <?php echo $disabled; ?> />
            </div>
            <br class="clear"/>
            <div class="leftCol" >
                <label class=""><?php echo __("Leave Type Name") ?><span class="required">*</span></label>
            </div>
            <div class="centerCol" style="padding-top: 8px;" >

                <select name="cmbbtype" id="cmbbtype"  <?php echo readonly; ?>>
                    <option value=""><?php echo __("--Select--") ?></option>
                    <?php foreach ($loadbtype as $btype) {
                    ?>
                           <option value="<?php echo $btype->getLeave_type_id(); ?>" <?php if ($Entitle[0]['leave_type_id'] == $btype->getLeave_type_id()
                           
                               )echo "selected"; ?> <?php echo readonly; ?> > <?php
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
                    ?></option>
<?php } ?>
                        </select>
                    </div>
                    <br class="clear"/>
                    <div class="leftCol" >
                        <label for="txtLocationCode" ><?php echo __("Year") ?></label>
                    </div>
                    <div class="centerCol" style="padding-top: 8px;">
                        <input id="txtYear" type="text" name="txtYear" value="<?php echo $Entitle[0]['leave_ent_year']; ?>" maxlength="4" <?php echo readonly; ?>>
                    </div>
                    <br class="clear"/>
                    <div class="leftCol" >
                        <label for="txtLocationCode" ><?php echo __("Entitle Days") ?><span class="required">*</span></label>
                    </div>
                    <div class="centerCol" style="padding-top: 8px;">
                        <input id="txtEntitleDays" type="text" name="txtEntitleDays" value="<?php echo $Entitle[0]['leave_ent_day']; ?>" maxlength="4" >
                    </div>
                    <br class="clear"/>
                    <div class="leftCol" >
                        <label for="txtLocationCode" ><?php echo __("Shedule Days") ?></label>
                    </div>
                    <div class="centerCol" style="padding-top: 8px;">
                        <input id="txtSheduleDays" type="text" name="txtSheduleDays" value="<?php echo $Entitle[0]['leave_ent_sheduled']; ?>" maxlength="4" readonly="readonly">
                    </div>
                    <br class="clear"/>
                    <div class="leftCol" >
                        <label for="txtLocationCode"><?php echo __("Entitle Taken") ?> </label>
                    </div>
                    <div class="centerCol" style="padding-top: 8px;">
                        <input id="txtEnTakenDays" type="text" name="txtEnTakenDays" value="<?php echo $Entitle[0]['leave_ent_taken']; ?>"maxlength="4" readonly="readonly">
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

        <script type="text/javascript">

            $(document).ready(function() {
                buttonSecurityCommon("null","null","editBtn","null");
<?php if ($editMode == true) { ?>
                    $('#frmSave :input').attr('disabled', true);
                    $('#editBtn').removeAttr('disabled');
                    $('#btnBack').removeAttr('disabled');
<?php } ?>

                //Validate the form
                $("#frmSave").validate({

                    rules: {

                        txtEmpId: { required: true},
                        cmbbtype: { required: true},
                        txtEntitleDays: {required: true, number: true }
                    },
                    messages: {
                        txtEmpId: "<?php echo __("This is required ") ?>",
                        cmbbtype: { required:"<?php echo __("Leave Type is required ") ?>"},
                        txtEntitleDays:{ required:"<?php echo __("This is required") ?>",number:"<?php echo __("Please Enter Digit") ?>"}

                    }
                });

                // When click edit button
                $("#frmSave").data('edit', <?php echo $editMode ? '1' : '0' ?>);

                $("#editBtn").click(function() {

                    var editMode = $("#frmSave").data('edit');
                    if (editMode == 1) {
                        // Set lock = 1 when requesting a table lock

                        location.href="<?php echo url_for('Leave/UpdateEntitlement?eid=' . $Entitle[0]['emp_number'] . '&lt=' . $Entitle[0]['leave_type_id'] . '&yr=' . $Entitle[0]['leave_ent_year'] . '&lock=1') ?>";
                    }
                    else {

                        var entdate=parseFloat($('#txtEntitleDays').val());
                        var enttaken=parseFloat($('#txtEnTakenDays').val());
                        var SheduleDays=parseFloat($('#txtSheduleDays').val());
                        if(entdate < 0){
                            alert("<?php echo __("Entitle Days Invalid") ?>");
                            return false;
                        }
                        
                        var Entitlemax=parseFloat("<?php echo $Entitlemax; ?>");
                        var Entitlemin=parseFloat("<?php echo $Entitlemin; ?>");
                        var EntitleTS=parseFloat("<?php echo $EntitleTS; ?>");
                        if(enttaken < 0 || enttaken > Entitlemax ){
                            alert("<?php echo __("Entitle Taken Days Invalid") ?>");
                            return false;
                        }
                        
                        if(entdate < EntitleTS){
                            alert("<?php echo __("Entitle Days Minimum Execeed") ?>");
                            return false;
                        }
                        if(entdate > Entitlemax){
                            alert("<?php echo __("Entitle Days Maximum Execeed") ?>");
                            return false;
                        }else{
                            if((entdate-SheduleDays) < enttaken){
                               alert("<?php echo __("Entitle Taken Days Maximum Execeed") ?>");
                            return false; 
                            }   
                            
                            $('#frmSave').submit();
                        }
                    }


                });

                //When Click back button
                $("#btnBack").click(function() {
                    location.href = "<?php echo url_for(public_path('../../symfony/web/index.php/Leave/Entitlement')) ?>";
                });

                //When click reset buton
                $("#btnClear").click(function() {
                    // Set lock = 0 when resetting table lock
                    location.href="<?php echo url_for('Leave/UpdateEntitlement?eid=' . $Entitle[0]['emp_number'] . '&lt=' . $Entitle[0]['leave_type_id'] . '&yr=' . $Entitle[0]['leave_ent_year'] . '&lock=0') ?>";
        });
    });
</script>
