<script type="text/javascript" src="<?php echo public_path('../../scripts/jquery/jquery.autocomplete.js') ?>"></script>

<div class="outerbox">
    <div class="maincontent">

        <div class="mainHeading"><h2><?php echo __("Leave Entitlement") ?></h2></div>
        <?php echo message() ?>
        <form name="frmSearchBox" id="frmSearchBox" method="post" action="" onsubmit="return validateform();">
            <input type="hidden" name="mode" value="search">
            <div class="searchbox">
                <label for="searchMode"><?php echo __("Search By") ?></label>


                <select name="searchMode" id="searchMode">
                    <option value="all"><?php echo __("--Select--") ?></option>
                    <option value="employee_id" <?php
        if ($searchMode == 'employee_id') {
            echo "selected";
        }
        ?>><?php echo __("Employee ID") ?></option>
                    <option value="emp_name" <?php
                    if ($searchMode == 'emp_name') {
                        echo "selected";
                    } ?>><?php echo __("Employee Name") ?></option>
                    <option value="leave_type_name_" <?php
                            if ($searchMode == 'leave_type_name_') {
                                echo "selected";
                            }
        ?>><?php echo __("Leave Type") ?></option>
                </select>

                <label for="searchValue"><?php echo __("Search For") ?></label>
                <input type="text" size="20" name="searchValue" id="searchValue" value="<?php echo $searchValue ?>" />
                <input type="submit" class="plainbtn"
                       value="<?php echo __("Search") ?>" />
                <input type="reset" class="plainbtn"
                       value="<?php echo __("Reset") ?>" id="resetBtn"/>
                <br class="clear"/>
            </div>
        </form>
        <div class="actionbar">
            <div class="actionbuttons">

                <input type="button" class="plainbtn" id="buttonAdd"
                       value="<?php echo __("Add") ?>" />


                <input type="button" class="plainbtn" id="buttonRemove"
                       value="<?php echo __("Delete") ?>" />

            </div>
            <div class="noresultsbar"></div>
            <div class="pagingbar"><?php echo is_object($pglay) ? $pglay->display() : ''; ?> </div>
            <br class="clear" />
        </div>
        <br class="clear" />
        <form name="standardView" id="standardView" method="post" action="<?php echo url_for('Leave/DeleteEntitlement') ?>">
            <input type="hidden" name="mode" id="mode" value=""/>
            <table cellpadding="0" cellspacing="0" class="data-table">
                <thead>
                    <tr>
                        <td width="50">

                            <input type="checkbox" class="checkbox" name="allCheck" value="" id="allCheck" />

                        </td>


                        <td scope="col">
                            <?php echo $sorter->sortLink('e.employee_id', __('Employee ID'), '@LeaveEntitlement', ESC_RAW); ?>

                        </td>
                        <td scope="col">
                            <?php
                            if ($Culture == 'en') {
                                $ename = 'e.emp_display_name';
                            } else {
                                $ename = 'e.emp_display_name_' . $Culture;
                            } ?>
                            <?php echo $sorter->sortLink($ename, __('Employee Name'), '@LeaveEntitlement', ESC_RAW); ?>

                        </td>
                        <td scope="col">
<?php
                            if ($Culture == 'en') {
                                $btname = 'c.leave_type_name';
                            } else {
                                $btname = 'c.leave_type_name_' . $Culture;
                            }
?>
<?php echo $sorter->sortLink($btname, __('Leave Type'), '@LeaveEntitlement', ESC_RAW); ?>
                        </td>
                        <td scope="col">
                            <?php echo $sorter->sortLink('b.leave_ent_year', __('Year'), '@LeaveEntitlement', ESC_RAW); ?>

                        </td>
                        <td scope="col">
<?php echo $sorter->sortLink('b.leave_ent_day', __('Entitled'), '@LeaveEntitlement', ESC_RAW); ?>

                        </td>
                        <td scope="col">
<?php echo $sorter->sortLink('b.leave_ent_taken', __('Taken'), '@LeaveEntitlement', ESC_RAW); ?>


                        </td>
                        <td scope="col">
<?php echo $sorter->sortLink('b.leave_ent_sheduled', __('Scheduled'), '@LeaveEntitlement', ESC_RAW); ?>

                        </td>

                        <td scope="col">
                    <?php echo $sorter->sortLink('b.leave_ent_remain', __('Remaining'), '@LeaveEntitlement', ESC_RAW); ?>

                                </td>
                            </tr>
                        </thead>

                        <tbody>
<?php
                            $row = 0;
                            foreach ($knwdoctype as $reasons) {
                                $cssClass = ($row % 2) ? 'even' : 'odd';
                                $row = $row + 1;
?>
                    <tr class="<?php echo $cssClass ?>">
                        <td >
                            <input type='checkbox' class='checkbox innercheckbox' name='chkLocID[]' id="chkLoc" value='<?php echo ($reasons->getEmp_number() . '|' . $reasons->getLeave_type_id() . '|' . $reasons->getLeave_ent_year()); ?>' />
                        </td>
                        <td class=""><a href="<?php echo url_for('Leave/UpdateEntitlement?eid=' . $reasons->getEmp_number() . '&lt=' . $reasons->getLeave_type_id() . '&yr=' . $reasons->getLeave_ent_year()) ?>">
                                <?php echo $reasons->Employee->getEmployee_id(); ?></a>
                        </td>
                        <td class="">
                            <a href="<?php echo url_for('Leave/UpdateEntitlement?eid=' . $reasons->getEmp_number() . '&lt=' . $reasons->getLeave_type_id() . '&yr=' . $reasons->getLeave_ent_year()) ?>">
                                <?php
                                if ($Culture == 'en') {
                                    $abcd = "getEmp_display_name";
                                } else {
                                    $abcd = "getEmp_display_name_" . $Culture;
                                }

                                $dd = $reasons->Employee->$abcd();
                                $rest = substr($reasons->Employee->$abcd(), 0, 100);

                                if ($reasons->Employee->$abcd() == null) {
                                    $dd = $reasons->Employee->getEmp_display_name();
                                    $rest = substr($reasons->Employee->getEmp_display_name(), 0, 100);
                                    //echo $list->getDis_offence_name();
                                    if (strlen($dd) > 100) {
                                        echo $rest ?>.<a href="" title="<?php echo $dd ?>" onclick="javascript:disableAnchor(this, true)">...</a> <?php
                                    } else {
                                        echo $rest;
                                    }
                                } else {
                                    //$dd=$reasons->Employee->getEmp_display_name();
                                    if (strlen($dd) > 100) {
                                        echo $rest
                                ?>.<a href="" title="<?php echo $dd ?>" onclick="javascript:disableAnchor(this, true)">...</a> <?php
                                    } else {
                                        echo $rest;
                                    }
                                } ?>
                                </a>



                            </td>
                            <td class="">
                            <?php
                                if ($Culture == 'en') {
                                    echo $reasons->LeaveType->getLeave_type_name();
                                } else {
                                    $abc = 'getLeave_type_name_' . $Culture;
                                    echo $reasons->LeaveType->$abc();
                                    if ($reasons->LeaveType->$abc() == null) {
                                        echo $reasons->LeaveType->getLeave_type_name();
                                    }
                                }
                            ?>                            </td>
                            <td class="">
<?php echo $reasons->getLeave_ent_year(); ?>
                            </td>
                            <td class="">
<?php echo $reasons->getLeave_ent_day(); ?>
                            </td>
                            <td class="">
<?php echo $reasons->getLeave_ent_taken(); ?>
                            </td>
                            <td class="">
<?php echo $reasons->getLeave_ent_sheduled(); ?>
                                    </td>
                                    <td class="">
<?php echo $reasons->getLeave_ent_remain(); ?>
                                    </td>

                                </tr>
<?php } ?>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
        <script type="text/javascript">
            function validateform(){

                if($("#searchValue").val()=="")
                {

                    alert("<?php echo __('Please enter search value') ?>");
                    return false;

                }
                if($("#searchMode").val()=="all"){
                    alert("<?php echo __('Please select the search mode') ?>");
                    return false;
                }
                else{
                    $("#frmSearchBox").submit();
                }

            }
            $(document).ready(function() {
                $("#searchMode:visible:first").focus();
                buttonSecurityCommon("buttonAdd","null","null","buttonRemove");

                //When click add button
                $("#buttonAdd").click(function() {
                    location.href = "<?php echo url_for(public_path('../../symfony/web/index.php/Leave/SaveEntitlement')) ?>";

                });

                // When Click Main Tick box
                $("#allCheck").click(function() {
                    if ($('#allCheck').attr('checked')){

                        $('.innercheckbox').attr('checked','checked');
                    }else{
                        $('.innercheckbox').removeAttr('checked');
                    }
                });

                $(".innercheckbox").click(function() {
                    if($(this).attr('checked'))
                    {

                    }else
                    {
                        $('#allCheck').removeAttr('checked');
                    }
                });

                //When click reset buton
                $("#resetBtn").click(function() {
                    location.href = "<?php echo url_for(public_path('../../symfony/web/index.php/Leave/Entitlement')) ?>";
                });
                $("#buttonRemove").click(function() {
                    $("#mode").attr('value', 'delete');
                    if($('input[name=chkLocID[]]').is(':checked')){
                        answer = confirm("<?php echo __("Do you really want to Delete?") ?>");
                    }


                    else{
                        alert("<?php echo __("select at least one check box to delete") ?>");

            }

            if (answer !=0)
            {

                $("#standardView").submit();

            }
            else{
                return false;
            }

        });

        //When click Save Button
        $("#buttonRemove").click(function() {
            $("#mode").attr('value', 'save');
            //		$("#standardView").submit();
        });



    });


</script>
