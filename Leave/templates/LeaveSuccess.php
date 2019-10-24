<script type="text/javascript" src="<?php echo public_path('../../scripts/jquery/jquery.autocomplete.js') ?>"></script>

<div class="outerbox">
    <div class="maincontent">

        <div class="mainHeading"><h2><?php echo __("My Leave List") ?></h2></div>
        <?php echo message() ?>
        <form name="frmSearchBox" id="frmSearchBox" method="post" action="" onsubmit="return validateform();">
            <input type="hidden" name="mode" value="search">
            <div class="searchbox">
                <label for="searchMode"><?php echo __("Search By") ?></label>


                <select name="searchMode" id="searchMode">
                    <option value="all"><?php echo __("--Select--") ?></option>

                    <option value="emp_name" ><?php echo __("Employee Name") ?></option>
                    <option value="leave_type_name_"<?php //$Culture  ?>><?php echo __("Leave Type") ?></option>
                </select>

                <label for="searchValue"><?php echo __("Search For") ?></label>
                <input type="text" size="20" name="searchValue" id="searchValue" value="<?php echo $searchValue ?>" />
                <input type="submit" class="plainbtn"
                       value="<?php echo __("Search") ?>" />
                <input type="reset" class="plainbtn"
                       value="<?php echo __("Reset") ?>" id="resetBtn" name="resetBtn" />
                <br class="clear"/>
            </div>
        </form>
        <div class="actionbar">
            <div class="actionbuttons">

                <input type="button" class="plainbtn" id="buttonAdd"
                       value="<?php echo __("Apply Leave") ?>" />


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

                        </td>



                        <td scope="col">
                            <?php if ($Culture == 'en') {
                                $ename = 'e.emp_display_name';
                            } else {
                                $ename = 'e.emp_display_name_' . $Culture;
                            } ?>
<?php echo $sorter->sortLink($ename, __('Employee Name'), '@Leave', ESC_RAW); ?>

                        </td>

                        <td scope="col">
                            <?php echo $sorter->sortLink('b.leave_app_start_date', __('From'), '@Leave', ESC_RAW); ?>

                        </td>
                        <td scope="col">
                            <?php echo $sorter->sortLink('b.leave_app_end_date', __('To'), '@Leave', ESC_RAW); ?>

                        </td>
                        <td scope="col">
                            <?php echo __('No of Days'); ?>

                        </td>
                        <td scope="col">
                            <?php if ($Culture == 'en') {
                                $btname = 'c.leave_type_name';
                            } else {
                                $btname = 'c.leave_type_name_' . $Culture;
                            } ?>
<?php echo $sorter->sortLink($btname, __('Leave Type'), '@Leave', ESC_RAW); ?>
                        </td>
                        <td scope="col">
                    <?php echo $sorter->sortLink('b.leave_app_status', __('Status'), '@Leave', ESC_RAW); ?>

                                </td>
                        <td scope="col" style="width: 50px;" >

                            <?php echo __('Attachment') ?>
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
                        </td>

                        <td class="">
                            <a href="<?php echo url_for('Leave/UpdateLeave?id=' . $reasons->getLeave_app_id()) ?>">
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
                                    if (strlen($dd) > 100) {
                                        echo $rest ?>.<a href="" title="<?php echo $dd ?>" onclick="javascript:disableAnchor(this, true)">...</a> <?php
                                    } else {
                                        echo $rest;
                                    }
                                } else {
                                    if (strlen($dd) > 100) {
                                        echo $rest ?>.<a href="" title="<?php echo $dd ?>" onclick="javascript:disableAnchor(this, true)">...</a> <?php
                                    } else {
                                        echo $rest;
                                    }
                                }
                                ?>
                            </a>


                        </td>
                        <td class="">
<?php echo LocaleUtil::getInstance()->formatDate($reasons->getLeave_app_start_date()); ?>
                            </td>
                            <td class="">
                            <?php echo LocaleUtil::getInstance()->formatDate($reasons->getLeave_app_end_date()); ?>
                            </td>
                            <td class="">
                            <?php echo $reasons->leave_app_workdays; ?>
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
                            <?php
                                $reasons->getLeave_app_status();
                                switch ($reasons->getLeave_app_status()) {
                                    case "0":
                                        echo __('Canceled');
                                        break;
                                    case "1":
                                        echo __('Pending');
                                        break;
                                    case "2":
                                        echo __('Approved');
                                        break;
                                    case "3":
                                        echo __('Rejected');
                                        break;
                                }
                            ?>
                            </td>
                            <td class="">
                            </td>
                            <td class="" style="text-align: center">
                                <a href="#" onclick="popuimage(link='<?php echo url_for('Leave/imagepop?id='); ?><?php echo $reasons->leave_app_id ?>')"><?php
                                $kk = $leaveDao->readattach($reasons->leave_app_id);
                                foreach ($kk as $rowa) {
                                    if ($rowa['count'] == 1) {
                                        echo __("View");
                                    }
                                }
                            ?></a>

                        </td>

                        </tr>
<?php } ?>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
        <script type="text/javascript">
             function popuimage(link){
                window.open(link, "myWindow",
                "status = 1, height = 300, width = 300, resizable = 0" )
            }
            
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
                buttonSecurityCommon(null,"buttonAdd",null,null);
                var btnactive="<?php echo $btnactive;?>";
                if(btnactive==1){
                    $("#buttonAdd").show();
                }else{
                    $("#buttonAdd").hide();
                }
                //When click add button
                $("#buttonAdd").click(function() {
                    location.href = "<?php echo url_for(public_path('../../symfony/web/index.php/Leave/SaveLeave')) ?>";

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
                    location.href = "<?php echo url_for(public_path('../../symfony/web/index.php/Leave/Leave')) ?>";
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
