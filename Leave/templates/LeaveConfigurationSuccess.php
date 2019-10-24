<?php
if ($lockMode == '1') {
    $editMode = false;
    $disabled = '';
} else {
    $editMode = true;
    $disabled = 'disabled="disabled"';
}
?>

<script type="text/javascript" src="<?php echo public_path('../../scripts/jquery/jquery.validate.js') ?>"></script>
<div class="formpage4col">
    <div id="status"></div>
    <div class="outerbox">
        <div class="mainHeading"><h2><?php echo __("Leave Type Configuration") ?></h2></div>
        <form name="frmSave" id="frmSave" method="post"  action="">
            <?php echo message() ?>
            <?php echo $form['_csrf_token']; ?>
            <br class="clear"/>
            <div class="leftCol" style="width: 200px; ">
                <label class="" style="margin-top: 0px"><?php echo __("Leave Type Name") ?><span class="required">*</span></label>
            </div>
            <div class="centerCol">

                <select name="cmbbtype" id="cmbbtype" onchange="getbenfittype(this.value);">
                    <option value=""><?php echo __("--Select--") ?></option>
                    <?php foreach ($loadbtype as $btype) {
                    ?>
                        <option value="<?php echo $btype->getLeave_type_id(); ?>"> <?php
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
            <div class="leftCol" style="width: 200px;">
                <label for="txtLocationCode" style="margin-top: 8px"><?php echo __("Description") ?><span class="required">*</span></label>
            </div>
            <div class="centerCol">
                <input id="txtDescription" type="text" name="txtDescription" value="" style="margin-top: 8px" />
            </div>
            <br class="clear"/>

            <div class="leftCol" style="width: 200px;font-weight: bold;">
                <label for="txtLocationCode" ><?php echo __("Leave Rules") ?> </label>
            </div>
            <br class="clear"/>


            <div class="leftCol" style="width: 200px;">
                <label for="txtLocationCode"><?php echo __("Active") ?> </label>
            </div>
            <div class="centerCol" style="padding-top: 8px;width: 50px;">
                <input type="checkbox" id="chkActive" name="chkActive" value="1" />
            </div>

            <div class="leftCol" style="width: 200px;">
                <label for="txtLocationCode" style="width: 200px;"><?php echo __("Covering Employee Required") ?> </label>
            </div>
            <div class="centerCol" style="padding-top: 8px;width: 50px;">
                <input type="checkbox" id="chkEcovering" name="chkEcovering" value="1" />
            </div>

            <div class="leftCol" style="width: 200px;">
                <label for="txtLocationCode"><?php echo __("Allow Half Days") ?> </label>
            </div>
            <div class="centerCol" style="padding-top: 8px;width: 50px;">
                <input type="checkbox" id="chkAllowHD" name="chkAllowHD" value="1"/>
            </div>
            <br class="clear"/>

            <div class="leftCol" style="width: 200px;">
                <label for="txtLocationCode"><?php echo __("Maternity Leave") ?> </label>
            </div>
            <div class="centerCol" style="padding-top: 8px;width: 50px;">
                <input type="checkbox" id="chkMerity" name="chkMerity" value="1"/>
            </div>

            <div class="leftCol" style="width: 200px;">
                <label for="txtLocationCode"><?php echo __("Need Approval") ?> </label>
            </div>
            <div class="centerCol" style="padding-top: 8px;width: 50px;">
                <input type="checkbox" id="chkNApprove" name="chkNApprove" value="1"/>
            </div>
            <div class="leftCol" style="width: 200px;">
                <label for="txtLocationCode"><?php echo __("Short Leave") ?> </label>
            </div>
            <div class="centerCol" style="padding-top: 8px;width: 50px;">
                <input type="checkbox" id="chkSL" name="chkSL" value="1" />
            </div>
            <br class="clear"/>

            <br class="clear"/>
            <div class="leftCol" style="width: 400px;font-weight: bold;">
                <label for="txtLocationCode" style="width: 400px;"><?php echo __("Select the Employment types that are eligible from the list below.") ?> </label>
            </div>
            <br class="clear"/>

            <?php
                    $i = 0;
                    foreach ($loadEtype as $rEtype) {
            ?>
                        <div class="leftCol" style="width: 190px;margin-left: 10px;padding-top: 8px;" >
                <?php
                        if ($Culture == "en") {
                            echo $rEtype['name'];
                        } else {
                            echo $rEtype['estat_name_' . $Culture];
                            if ($rEtype['estat_name_' . $Culture] == null) {
                                echo $rEtype['name'];
                            }
                        }
                ?>
                    </div>
                    <div class="centerCol" style="padding-top: 8px;width: 50px;">
                        <input type="checkbox" id="chk<?php echo $rEtype['id'] ?>" name="chk<?php echo $rEtype['id'] ?>" value="1" title="<?php echo $rEtype['id']; ?>"/>
                    </div>
            <?php if ($i / 2 == 1) {
            ?> <br class="clear"/>
            <?php }$i+=1;
                    } ?>
                    <br class="clear"/>
                    <div class="leftCol" style="width: 200px;">
                        <label for="txtLocationCode" ><?php echo __("Entitle Days") ?><span class="required">*</span> </label>
                    </div>
                    <div class="centerCol" style="padding-top: 8px; width: 190px;">
                        <input id="txtEntitleDays" type="text" name="txtEntitleDays" value="<?php //echo $extfdate;   ?>"/>
                        </div>
                        <div id="divmonth" class="centerCol" style="padding-top: 8px; ">
                            <label style="margin-top: 3px; width: 50px;" for="txtLocationCode"><?php echo __("Monthly")?></label><input type="checkbox" id="chkMonthly" name="chkMonthly" checked="checked" value="1"/>
                    </div>
                    <br class="clear"/>
                    <div class="leftCol" style="width: 200px;">
                        <label style="margin-top: 0px;" for="txtLocationCode"><?php echo __("Maximum Leave Days without medical certificate") ?> </label>
                    </div>
                    <div class="centerCol" style="padding-top: 8px;">
                        <input style="margin-top: 0px;" id="txtMaximumLeaveDay" type="text" name="txtMaximumLeaveDays" value="<?php //echo $extfdate;   ?>"/>
                    </div>
                    <br class="clear"/>
                    <div class="leftCol" style="width: 200px;">
                        <label style="margin-top: 0px;" for="txtLocationCode"><?php echo __("Need to Apply Before Days") ?> </label>
                    </div>
                    <div class="centerCol" >
                        <input style="margin-top: 0px;" id="txtApplyBefore" type="text" name="txtApplyBefore" value="<?php //echo $extfdate;   ?>"/>
                    </div>
                    <br class="clear"/>
                    <div class="leftCol" style="width: 200px;">
                        <label for="txtLocationCode"><?php echo __("Comments") ?> </label>
                    </div>
                    <div class="centerCol" >
                        <textarea rows=""  style="margin-left: 0px;margin-top: 5px;"  id="txtComments" name="txtComments" cols="" ></textarea>
                    </div>
                    <br class="clear"/>

                    <br class="clear"/>
                </form>



                <div class="formbuttons">
                    <input type="button" class="<?php echo $editMode ? 'editbutton' : 'savebutton'; ?>" name="EditMain" id="editBtn"
                           value="<?php echo $editMode ? __("Edit") : __("Save"); ?>"
                           title="<?php echo $editMode ? __("Edit") : __("Save"); ?>"
                           onmouseover="moverButton(this);" onmouseout="moutButton(this);"/>
                    <input type="reset" class="clearbutton" id="btnClear"
                           onmouseover="moverButton(this);" onmouseout="moutButton(this);"	<?php echo $disabled; ?>
                           value="<?php echo __("Reset"); ?>" />
                </div>

            </div>
            <div class="requirednotice"><?php echo __("Fields marked with an asterisk") ?><span class="required"> * </span> <?php echo __("are required") ?></div>
            <br class="clear" />
        </div>

        <script type="text/javascript">
            // <![CDATA[
            function getbenfittype(id){
                //alert(id);
                var btId=id;

                $('#frmSave')[0].reset();
                $.post(

                "<?php echo url_for('Leave/DTConfig') ?>", //Ajax file

                { id: id },  // create an object will all values

                //function that is called when server returns a value.
                function(data){
                    var actionvalue;
                    var ajaxdataarray = new Array();
                    var leavetypeconf= new Array();
                    var lockMode;

                    $.each(data, function(key, value) {

                        switch(key){
                            case 0:
                                if(value!=null){
                                    $('#cmbbtype').val(value);

                                }
                                else{

                                    $('#cmbbtype').val(id);
                                }
                                break;
                            case 1:
                                if(value!=0){
                                    $('#txtDescription').val(value);
                                }
                                break;
                            case 2:
                                if(value==1){
                                    $('#chkActive').attr('checked', 'checked');
                                    break;
                                }
                            case 3:
                                if(value==1){
                                    $('#chkEcovering').attr('checked', 'checked');
                                    break;
                                }
                            case 4:
                                if(value==1){
                                    $('#chkAllowHD').attr('checked', 'checked');
                                    break;
                                }
                            case 5:
                                if(value==1){
                                    $('#chkMerity').attr('checked', 'checked');
                                    break;
                                }
                            case 6:
                                if(value==1){
                                    $('#chkNApprove').attr('checked', 'checked');
                                    break;
                                }
                            case 7:
                                if(value==null){
                                    $('#txtEntitleDays').val('');
                                }else{
                                    $('#txtEntitleDays').val(value);
                                }
                                break;
                            case 8:
                                if(value==null){
                                    $('#txtMaximumLeaveDay').val('');
                                }else{
                                    $('#txtMaximumLeaveDay').val(value);
                                }
                                break;
                            case 9:
                                if(value==null){
                                    $('#txtApplyBefore').val('');
                                }else{
                                    $('#txtApplyBefore').val(value);
                                }
                                break;
                            case 11:
                                if(value==null){
                                    $('#txtComments').val('');
                                }else{
                                    $('#txtComments').val(value);
                                }
                                break;
                            case 12:
                                if(value==1){
                                    $('#chkSL').attr('checked', 'checked');
                                    $('#divmonth').show();
                                    $('#chkMonthly').attr('checked', 'checked');
                                    break;
                                }
                            case 13:
                                $.each(value, function(key,value) {
                                    var chktitle=$('#chk'+value.estat_code).attr('title');
                                    if(value.estat_code== chktitle){
                                        $('#chk'+value.estat_code).attr('checked', 'checked');
                                    }
                                });
                                break;

                                var $lockMode= value;
                                break;

                        }


                    });



                    //alert(<?php echo $leavetypeconf; ?>);

                },

                //How you want the data formated when it is returned from the server.
                "json"

            );

            }		$(document).ready(function() {        

                $("#divmonth").hide();
                buttonSecurityCommon("null","null","editBtn","null");
                $('#chkSL').click(function() {
                    $("#divmonth").toggle();
                });
<?php if ($editMode == true) { ?>
                    $('#frmSave :input').attr('disabled', true);
                    $('#editBtn').removeAttr('disabled');
<?php } ?>


                //Validate the form
                $("#frmSave").validate({

                    rules: {
                        txtDescription: { noSpecialCharsOnly: true, maxlength:100,required: true },
                        cmbbtype: {required: true },
                        txtEntitleDays: {required: true,number: true },
                        txtMaximumLeaveDays: {number: true },
                        txtApplyBefore: {number: true },
                        txtComments: {noSpecialCharsOnly: true, maxlength:200 }
                    },
                    messages: {
                        txtDescription: {maxlength:"<?php echo __("Maximum 100 Characters") ?>",noSpecialCharsOnly:"<?php echo __("Special Characters are not allowed") ?>",required:"<?php echo __("This is required ") ?>"},
                        cmbbtype:{required:"<?php echo __("Leave Type is required ") ?>"},
                        txtEntitleDays:{required:"<?php echo __("This is required ") ?>",number:"<?php echo __("Please Enter Digit") ?>"},
                        txtMaximumLeaveDays:{number:"<?php echo __("Please Enter Digit") ?>"},
                        txtApplyBefore:{number:"<?php echo __("Please Enter Digit") ?>"},
                        txtComments:{maxlength:"<?php echo __("Maximum 200 Characters") ?>",noSpecialCharsOnly:"<?php echo __("Special Characters are not allowed") ?>"}

                    }
                });

                // When click edit button
                $("#frmSave").data('edit', <?php echo $editMode ? '1' : '0' ?>);

                $("#editBtn").click(function() {

                    var editMode = $("#frmSave").data('edit');
                    if (editMode == 1) {
                        // Set lock = 1 when requesting a table lock

                        location.href="<?php echo url_for('Leave/LeaveConfiguration?id=' . $btype->getLeave_type_id() . '&lock=1') ?>";
                    }
                    else {
                        var entdate=parseInt($('#txtEntitleDays').val());
                        var MaximumLeaveDays=parseInt($('#txtMaximumLeaveDays').val());
                        var ApplyBefore=parseInt($('#txtApplyBefore').val());

                        if(entdate<0){
                            alert("<?php echo __("Entitle Days Invalid") ?>");
                            return false;
                        }
                        if(MaximumLeaveDays < 0){
                            alert("<?php echo __("Maximum Leave with out Medical Certificate, Days Invalid") ?>");
                            return false;
                        }
                        if(ApplyBefore < 0){
                            alert("<?php echo __("Apply Before Days Invalid") ?>");
                            return false;
                        }

                        $('#frmSave').submit();
                    }


                });

                //When Click back button
                $("#btnBack").click(function() {
                    location.href = "<?php //echo url_for(public_path('../../symfony/web/index.php/Leave/DocumentType'))    ?>";
                });

                //When click reset buton
                $("#btnClear").click(function() {
                    // Set lock = 0 when resetting table lock
                    location.href="<?php //echo url_for('Leave/UpdateDocumentType?id='.$benifittypelist->getLeave_type_id().'&lock=0')   ?>";
        });
    });
    // ]]>
</script>
