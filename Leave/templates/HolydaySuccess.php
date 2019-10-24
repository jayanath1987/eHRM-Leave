<script type="text/javascript" src="<?php echo public_path('../../scripts/jquery/jquery.autocomplete.js') ?>"></script>

<div class="outerbox">
    <div class="maincontent">

        <div class="mainHeading"><h2><?php echo __("Holiday Summary") ?></h2></div>
        <?php echo message() ?>
        <form name="frmSearchBox" id="frmSearchBox" method="post" action="" onsubmit="return validateform();">
            <input type="hidden" name="mode" value="search">
            <div class="searchbox">
                <label for="searchMode"><?php echo __("Search By") ?></label>


                <select name="searchMode" id="searchMode">
                    <option value="all"><?php echo __("--Select--") ?></option>
                    <option value="leave_holiday_name_"<?php
        if ($searchMode == 'leave_holiday_name_') {
            echo "selected";
        }
        ?>><?php echo __("Holiday Name") ?></option>
                    <option value="leave_holiday"<?php
                    if ($searchMode == 'leave_holiday') {
                        echo "selected";
                    }
        ?>><?php echo __("Date") ?></option>
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
        <form name="standardView" id="standardView" method="post" action="<?php echo url_for('Leave/DeleteHolyday') ?>">
            <input type="hidden" name="mode" id="mode" value=""/>
            <table cellpadding="0" cellspacing="0" class="data-table">
                <thead>
                    <tr>
                        <td width="50">

                            <input type="checkbox" class="checkbox" name="allCheck" value="" id="allCheck" />

                        </td>


                        <td scope="col">
                            <?php
                            if ($Culture == 'en') {
                                $btname = 'b.leave_holiday_name';
                            } else {
                                $btname = 'b.leave_holiday_name_' . $Culture;
                            }
                            ?>
<?php echo $sorter->sortLink($btname, __('Holiday Name'), '@LeaveHolyday', ESC_RAW); ?>
                        </td>
                        <td scope="col">
<?php echo $sorter->sortLink('leave_holiday_date', __('Date'), '@LeaveHolyday', ESC_RAW); ?>

                        </td>
                        <td scope="col">
<?php echo $sorter->sortLink('leave_holiday_fulorhalf', __('Full Day/Half Day'), '@LeaveHolyday', ESC_RAW); ?>

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
                                        <input type='checkbox' class='checkbox innercheckbox' name='chkLocID[]' id="chkLoc" value='<?php echo $reasons->getLeave_holiday_id() ?>' />
                        </td>

                        <td class="">
                            <a href="<?php echo url_for('Leave/UpdateHolyday?id=' . $reasons->getLeave_holiday_id()) ?>"><?php
                                if ($Culture == 'en') {//echo $reasons->BenifitType->getBt_name();
                                    $dd = $reasons->getLeave_holiday_name();
                                    $rest = substr($reasons->getLeave_holiday_name(), 0, 100);
                                    if (strlen($dd) > 100) {
                                        echo $rest ?>.<a href="" title="<?php echo $dd ?>" onclick="javascript:disableAnchor(this, true)">...</a> <?php
                                    } else {
                                        echo $rest;
                                    }
                                } else {
                                    $cc = "getLeave_holiday_name_" . $Culture;
                                    if ($reasons->$cc() == "") {
                                        $dd = $reasons->getLeave_holiday_name();
                                        $rest = substr($reasons->getLeave_holiday_name(), 0, 100);
                                        if (strlen($dd) > 100) {
                                            echo $rest ?>.<a href="" title="<?php echo $dd ?>" onclick="javascript:disableAnchor(this, true)">...</a> <?php
                                        } else {
                                            echo $rest;
                                        }
                                    } else {
                                        $dd = $reasons->$cc();
                                        $rest = substr($reasons->$cc(), 0, 100);
                                        if (strlen($dd) > 100) {
                                            echo $rest ?>.<a href="" title="<?php echo $dd ?>" onclick="javascript:disableAnchor(this, true)">...</a> <?php
                                        } else {
                                            echo $rest;
                                        }
                                    }
                                }
?></a>
                            </td>
                            <td class="">
                            <?php echo LocaleUtil::getInstance()->formatDate($reasons->getLeave_holiday_date()); ?>
                            </td>
                            <td class="">
                            <?php
                                if ($reasons->getLeave_holiday_fulorhalf() == "1") {
                                    echo __("Full Day");
                                } else {
                                    echo __("Half Day");
                                };
                            ?>
                                    </td>
                                </tr>
<?php } ?>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
        <script type="text/javascript">
            function disableAnchor(obj, disable){
                if(disable){
                    var href = obj.getAttribute("href");
                    if(href && href != "" && href != null){
                        obj.setAttribute('href_bak', href);
                    }
                    obj.removeAttribute('href');
                    obj.style.color="gray";
                }
                else{
                    obj.setAttribute('href', obj.attributes
                    ['href_bak'].nodeValue);
                    obj.style.color="blue";
                }
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
                buttonSecurityCommon("buttonAdd","null","null","buttonRemove");
                //When click add button
                $("#buttonAdd").click(function() {
                    location.href = "<?php echo url_for(public_path('../../symfony/web/index.php/Leave/SaveHolyday')) ?>";

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
                    location.href = "<?php echo url_for(public_path('../../symfony/web/index.php/Leave/Holyday')) ?>";
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
