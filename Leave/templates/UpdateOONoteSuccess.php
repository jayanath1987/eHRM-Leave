    <?php
if ($lockMode=='1') {
        $editMode = false;
        $disabled = '';
    } else {
        $editMode = true;
        $disabled = 'disabled="disabled"';
    }
?>
<script type="text/javascript" src="<?php echo public_path('../../scripts/jquery/jquery.min.js')?>"></script>
<script type="text/javascript" src="<?php echo public_path('../../scripts/jquery/jquery-ui.min.js')?>"></script>
<link href="<?php echo public_path('../../themes/orange/css/jquery/jquery-ui.css')?>" rel="stylesheet" type="text/css"/>
<link href="../../themes/orange/css/style.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="<?php echo public_path('../../scripts/jquery/jquery.validate.js') ?>"></script>
<script type="text/javascript" src="<?php echo public_path('../../scripts/time.js') ?>"></script>
<script type="text/javascript" src="<?php echo public_path('../../scripts/paginator.js') ?>"></script>
<style type="text/css">

label {
    margin: 2px 0 10px;
}
            div.formpage4col select{
                width: 50px;
            }
            .paginator{
                padding-left: 100px;
            }
    
</style>
<div class="formpage4col">
        <div class="navigation">
        	
                 
        </div>
        <div id="status"></div>
        <div class="outerbox">
            <div class="mainHeading"><h2><?php echo __("Out of Office")?></h2></div>
            	<form name="frmSave" id="frmSave" method="post"  action="">
                    <?php echo message()?>
            	<?php echo $form['_csrf_token']; ?>
                
                <br class="clear"/>
<!--                <div class="leftCol">
                <label class="controlLabel" for="txtLocationCode"><?php echo __("Employee Name")?> <span class="required">*</span></label>
                </div>
                
                <div class="centerCol">
                    <input type="text" name="txtEmployeeName" disabled="disabled"
               id="txtEmployee" value="<?php if($OfficeOut->oo_id){ echo $OfficeOut->Employee->emp_display_name; }else{ echo $EmpDisplayName; }  ?>" readonly="readonly" style="color: #222222"/>
                </div>
                 <div class="centerCol">
                <input class="button" type="button" value="..." id="empRepPopBtn" name="empRepPopBtn" <?php echo $disabled; ?> />
            </div>-->
                <div class="centerCol">
                 </div>
                
                 <div class="leftCol">&nbsp;</div>
                 <div class="centerCol">
                 <input  type="hidden" name="txtEmpId" id="txtEmpId" value="<?php if($OfficeOut->oo_id){ echo $OfficeOut->oo_emp_number; }else{ echo $EmployeeNumber; }?>"/>
                 <input  type="hidden" name="txtBenID" id="txtEmpId" value="<?php echo $OfficeOut->oo_id;?>"/>
                 </div>

                <br class="clear"/>
                <div class="leftCol">
                <label class=""><?php echo __(" Reason Type")?><span class="required">*</span></label>
                </div>
              <div class="centerCol">
                  <select  class=""  name="cmbbtype" id="cmbbtype"  style="width:160px">
	            <option value="all"><?php echo __("--Select--")?></option>
                    
                    <option value="<?php echo "1";?>" <?php if("1"==$OfficeOut->oo_category) echo"selected"; ?>> <?php echo "Official"; ?></option>
                    <option value="<?php echo "2"?>" <?php if("2"==$OfficeOut->oo_category) echo"selected"; ?>> <?php echo "Personal"; ?></option>
                    <option value="<?php echo "3"?>" <?php if("3"==$OfficeOut->oo_category) echo"selected"; ?>> <?php echo "Lunch"; ?></option>    
                    </select>
                </div>
                <br class="clear"/>
                 <div class="leftCol">
                     <label for="txtLocationCode"><?php echo __("Date")?> <span class="required">*</span></label>
                 </div>
                 <div class="centerCol">
                    <input id="disbdate" type="text" name="txtdisbdate" value="<?php echo LocaleUtil::getInstance()->formatDate($OfficeOut->oo_date);?>">
                 </div>
                
                
                
                <br class="clear"/>
                <div class="leftCol">
                    <label class="controlLabel" for="txtcomment"><?php echo __("Time from") ?></label>
                </div>
                <div class="centerCol">
                                
                    <select name="timefromHR" id="timefrom" class="" style="width: 50px;" tabindex="4">
                                    
                        <option value="" <?php if ($fromtimehrs == ""
                )
                    echo "selected"
                    ?>><?php echo __("HH"); ?></option>
                        <option value="00" <?php if ($fromtimehrs == "00")
                                    echo "selected"
                        ?>><?php echo __("00") ?></option>
                        <option value="01" <?php if ($fromtimehrs == "01")
                                echo "selected"
                                ?>><?php echo __("01") ?></option>
                        <option value="02" <?php if ($fromtimehrs == "02")
                                    echo "selected"
                        ?>><?php echo __("02") ?></option>
                        <option value="03" <?php if ($fromtimehrs == "03")
                                echo "selected"
                                ?>><?php echo __("03") ?></option>
                        <option value="04" <?php if ($fromtimehrs == "04")
                                    echo "selected"
                        ?>><?php echo __("04") ?></option>
                        <option value="05" <?php if ($fromtimehrs == "05")
                                echo "selected"
                                ?>><?php echo __("05") ?></option>
                        <option value="06" <?php if ($fromtimehrs == "06")
                                    echo "selected"
                        ?>><?php echo __("06") ?></option>
                        <option value="07" <?php if ($fromtimehrs == "07")
                                echo "selected"
                                ?>><?php echo __("07") ?></option>
                        <option value="08" <?php if ($fromtimehrs == "08")
                                    echo "selected"
                        ?>><?php echo __("08") ?></option>
                        <option value="09" <?php if ($fromtimehrs == "09")
                                echo "selected"
                                ?>><?php echo __("09") ?></option>
                        <option value="10" <?php if ($fromtimehrs == "10")
                                    echo "selected"
                        ?>><?php echo __("10") ?></option>
                        <option value="11" <?php if ($fromtimehrs == "11")
                                echo "selected"
                                ?>><?php echo __("11") ?></option>
                        <option value="12" <?php if ($fromtimehrs == "12")
                                    echo "selected"
                        ?>><?php echo __("12") ?></option>
                        <option value="13" <?php if ($fromtimehrs == "13")
                                echo "selected"
                                ?>><?php echo __("13") ?></option>
                        <option value="14" <?php if ($fromtimehrs == "14")
                                    echo "selected"
                        ?>><?php echo __("14") ?></option>
                        <option value="15" <?php if ($fromtimehrs == "15")
                                echo "selected"
                        ?>><?php echo __("15") ?></option>
                        <option value="16" <?php if ($fromtimehrs == "16")
                            echo "selected"
                            ?>><?php echo __("16") ?></option>
                        <option value="17" <?php if ($fromtimehrs == "17")
                        echo "selected"
                        ?>><?php echo __("17") ?></option>
                        <option value="18" <?php if ($fromtimehrs == "18")
                    echo "selected"
                    ?>><?php echo __("18") ?></option>
                        <option value="19" <?php if ($fromtimehrs == "19")
                                    echo "selected"
                        ?>><?php echo __("19") ?></option>
                        <option value="20" <?php if ($fromtimehrs == "20")
                                echo "selected"
                                ?>><?php echo __("20") ?></option>
                        <option value="21" <?php if ($fromtimehrs == "21")
                                    echo "selected"
                        ?>><?php echo __("21") ?></option>
                        <option value="22" <?php if ($fromtimehrs == "22")
                                echo "selected"
                                ?>><?php echo __("22") ?></option>
                        <option value="23" <?php if ($fromtimehrs == "23")
                                    echo "selected"
                        ?>><?php echo __("23") ?></option>
                                                                                                                                            
                                                                                                                                            
                                                                                                                                            
                    </select>
                                                                                                                                        
                                                                                                                                        
                                                                                                                                        
                    <select name="timefromMM" id="timeto" class="" style="width: 50px;" tabindex="4">
                                                                                                                                        
                        <option value="" <?php if ($fromtimemins == ""
                            )
                                echo "selected"
                                ?>><?php echo __("MM"); ?></option>
                        <option value="00" <?php if ($fromtimemins == "00")
                                echo "selected"
                                ?>><?php echo __("00") ?></option>
                        <option value="05" <?php if ($fromtimemins == "05")
                                echo "selected"
                                ?>><?php echo __("05") ?></option>
                        <option value="10" <?php if ($fromtimemins == "10")
                            echo "selected"
                        ?>><?php echo __("10") ?></option>
                        <option value="15" <?php if ($fromtimemins == "15")
                                echo "selected"
                                ?>><?php echo __("15") ?></option>
                        <option value="20" <?php if ($fromtimemins == "20")
                                    echo "selected"
                        ?>><?php echo __("20") ?></option>
                        <option value="25" <?php if ($fromtimemins == "25")
                                echo "selected"
                                ?>><?php echo __("25") ?></option>
                        <option value="30" <?php if ($fromtimemins == "30")
                                    echo "selected"
                        ?>><?php echo __("30") ?></option>
                        <option value="35" <?php if ($fromtimemins == "35")
                                echo "selected"
                                ?>><?php echo __("35") ?></option>
                        <option value="40" <?php if ($fromtimemins == "40")
                                    echo "selected"
                        ?>><?php echo __("40") ?></option>
                        <option value="45" <?php if ($fromtimemins == "45")
                                echo "selected"
                                ?>><?php echo __("45") ?></option>
                        <option value="50" <?php if ($fromtimemins == "50")
                                    echo "selected"
                        ?>><?php echo __("50") ?></option>
                        <option value="55" <?php if ($fromtimemins == "55")
                                echo "selected"
                                ?>><?php echo __("55") ?></option>
                                                                                                                                                                                                
                                                                                                                                                                                                
                    </select>
                </div>
                
                <br class="clear"/>
                 <div class="leftCol">
                     <label for="txtLocationCode"><?php echo __("To Date")?> <span class="required">*</span></label>
                 </div>
                 <div class="centerCol">
                    <input id="todate" type="text" name="todate" value="<?php echo LocaleUtil::getInstance()->formatDate($OfficeOut->oo_to_date);?>">
                 </div>
                
                
                <br class="clear">
                <div class="leftCol">
                    <label class="controlLabel" for="txtcomment"><?php echo __("Time To") ?></label>
                </div>
                <div class="centerCol">
                    <select name="timetoHR" id="timetohrs" class="" style="width: 50px;" tabindex="4">
                                                                                                                                                                                            
                        <option value="" <?php if ($totimehrs == ""
                                )
                                    echo "selected"
                                    ?>><?php echo __("HH"); ?></option>
                        <option value="00" <?php if ($totimehrs == "00")
                                    echo "selected"
                        ?>><?php echo __("00") ?></option>
                        <option value="01" <?php if ($totimehrs == "01")
                                echo "selected"
                                ?>><?php echo __("01") ?></option>
                        <option value="02" <?php if ($totimehrs == "02")
                                    echo "selected"
                        ?>><?php echo __("02") ?></option>
                        <option value="03" <?php if ($totimehrs == "03")
                            echo "selected"
                        ?>><?php echo __("03") ?></option>
                        <option value="04" <?php if ($totimehrs == "04")
                        echo "selected"
                        ?>><?php echo __("04") ?></option>
                        <option value="05" <?php if ($totimehrs == "05")
                            echo "selected"
                        ?>><?php echo __("05") ?></option>
                        <option value="06" <?php if ($totimehrs == "06")
                                echo "selected"
                                ?>><?php echo __("06") ?></option>
                        <option value="07" <?php if ($totimehrs == "07")
                                    echo "selected"
                        ?>><?php echo __("07") ?></option>
                        <option value="08" <?php if ($totimehrs == "08")
                                echo "selected"
                                ?>><?php echo __("08") ?></option>
                        <option value="09" <?php if ($totimehrs == "09")
                                    echo "selected"
                        ?>><?php echo __("09") ?></option>
                        <option value="10" <?php if ($totimehrs == "10")
                                echo "selected"
                                ?>><?php echo __("10") ?></option>
                        <option value="11" <?php if ($totimehrs == "11")
                                    echo "selected"
                        ?>><?php echo __("11") ?></option>
                        <option value="12" <?php if ($totimehrs == "12")
                                echo "selected"
                                ?>><?php echo __("12") ?></option>
                        <option value="13" <?php if ($totimehrs == "13")
                                    echo "selected"
                        ?>><?php echo __("13") ?></option>
                        <option value="14" <?php if ($totimehrs == "14")
                            echo "selected"
                            ?>><?php echo __("14") ?></option>
                        <option value="15" <?php if ($totimehrs == "15")
                            echo "selected"
                            ?>><?php echo __("15") ?></option>
                        <option value="16" <?php if ($totimehrs == "16")
                    echo "selected"
                    ?>><?php echo __("16") ?></option>
                        <option value="17" <?php if ($totimehrs == "17")
                    echo "selected"
                    ?>><?php echo __("17") ?></option>
                        <option value="18" <?php if ($totimehrs == "18")
                    echo "selected"
                    ?>><?php echo __("18") ?></option>
                        <option value="19" <?php if ($totimehrs == "19")
                    echo "selected"
                    ?>><?php echo __("19") ?></option>
                        <option value="20" <?php if ($totimehrs == "20")
                    echo "selected"
                    ?>><?php echo __("20") ?></option>
                        <option value="21" <?php if ($totimehrs == "21")
                    echo "selected"
                    ?>><?php echo __("21") ?></option>
                        <option value="22" <?php if ($totimehrs == "22")
                    echo "selected"
                    ?>><?php echo __("22") ?></option>
                        <option value="23" <?php if ($totimehrs == "23")
                    echo "selected"
                    ?>><?php echo __("23") ?></option>
                                                                                                                                                                                                                                                                                                    
                                                                                                                                                                                                                                                                                                    
                                                                                                                                                                                                                                                                                                    
                    </select>
                                                                                                                                                                                                                                                                                                
                                                                                                                                                                                                                                                                                                
                                                                                                                                                                                                                                                                                                
                    <select name="timetoMM" id="timetoMM" class="" style="width: 50px;" tabindex="4">
                                                                                                                                                                                                                                                                                                
                        <option value="" <?php if ($totimemins == ""
                )
                    echo "selected"
                    ?>><?php echo __("MM"); ?></option>
                        <option value="00" <?php if ($totimemins == "00")
                    echo "selected"
                    ?>><?php echo __("00") ?></option>
                        <option value="05" <?php if ($totimemins == "05")
                    echo "selected"
                    ?>><?php echo __("05") ?></option>
                        <option value="10" <?php if ($totimemins == "10")
                    echo "selected"
                    ?>><?php echo __("10") ?></option>
                        <option value="15" <?php if ($totimemins == "15")
                    echo "selected"
                    ?>><?php echo __("15") ?></option>
                        <option value="20" <?php if ($totimemins == "20")
                    echo "selected"
                    ?>><?php echo __("20") ?></option>
                        <option value="25" <?php if ($totimemins == "25")
                    echo "selected"
                    ?>><?php echo __("25") ?></option>
                        <option value="30" <?php if ($totimemins == "30")
                    echo "selected"
                    ?>><?php echo __("30") ?></option>
                        <option value="35" <?php if ($totimemins == "35")
                    echo "selected"
                    ?>><?php echo __("35") ?></option>
                        <option value="40" <?php if ($totimemins == "40")
                    echo "selected"
                    ?>><?php echo __("40") ?></option>
                        <option value="45" <?php if ($totimemins == "45")
                    echo "selected"
                    ?>><?php echo __("45") ?></option>
                        <option value="50" <?php if ($totimemins == "50")
                    echo "selected"
                    ?>><?php echo __("50") ?></option>
                        <option value="55" <?php if ($totimemins == "55")
                    echo "selected"
                    ?>><?php echo __("55") ?></option>
                                                                                                                                                                                                                                                                                                                                                        
                                                                                                                                                                                                                                                                                                                                                        
                    </select>
                </div>
                <br class="clear"/>
                 <div class="leftCol">
                     <label for="txtLocationCode"><?php echo __("Institute")?> </label>
                 </div>
                 <div class="centerCol">
                    <input id="txtauthority" type="text" name="txtauthority" value="<?php echo $OfficeOut->oo_authority;?>">
                 </div>
                <br class="clear"/>
                
                <div class="leftCol">
                <label for="txtLocationCode"><?php echo __("Vehicle Required") ?> </label>
            </div>
            <div class="centerCol" style="width: 10px;">
                <input type="checkbox" id="chkActive" name="chkActive" value="1" <?php if($OfficeOut->oo_vehicle_flg=="1" ){ echo "checked"; } ?> />
            </div><!-- <div class="centerCol"><a style="padding-left: 25px;" onclick="VRT()" href="#">Vehicle Request System</a></div> -->
                <br class="clear">
                
                 <div class="leftCol">
                 <label for="txtLocationCode"><?php echo __("Comment")?></label>
                </div>
                 <div class="centerCol">
                     <textarea cols="" rows=""  id="txtcom" maxlength="200" name="txtcomment" type="text" style="margin-left: 0px; margin-top: 0px; height: 80px; width: 320px;"  class="formTextArea" value="" tabindex="1" ><?php echo $OfficeOut->oo_comment;?></textarea>
                 </div> 

                <br class="clear"/>
                
                            <br class="clear"/>
            
            
              <div class="leftCol">
                    <label id="lblemp" class="controlLabel" for="txtLocationCode"><?php echo __("Add Employee") ?> <span class="required">*</span></label>
                </div>
                            
                <div class="centerCol" style="padding-top: 8px;">
                    <input class="button" type="button" value="..." id="empRepPopBtn" name="empRepPopBtn" <?php echo $disabled; ?> /><br>
                    <input  type="hidden" name="txtEmpId" id="txtEmpId" value="<?php echo $etid; ?>"/>
                </div>
            <br class="clear"/>
                <div id="employeeGrid1" class="centerCol" style="margin-left:10px; margin-top: 8px; width: 380px; border-style:  solid; border-color: #FAD163; ">
                    <div style="">
                        <div class="centerCol" style='width:100px; background-color:#FAD163;'>
                            <label class="languageBar" style="padding-left:2px; padding-top:2px;padding-bottom: 1px; background-color:#FAD163; margin-top: 0px;  color:#444444;"><?php echo __("Emp Id") ?></label>
                        </div>
                        <div class="centerCol" style='width:220px;  background-color:#FAD163;'>
                            <label class="languageBar" style="padding-left:2px; padding-top:2px;padding-bottom: 1px; background-color:#FAD163; margin-top: 0px; color:#444444; text-align:inherit"><?php echo __("Employee Name") ?></label>
                        </div>
                        <div class="centerCol" style='width:60px;   background-color:#FAD163;'>
                            <label class="languageBar" style="width:50px; padding-left: 0px; padding-top:2px;padding-bottom: 1px; background-color:#FAD163; margin-top: 0px; color:#444444; text-align:inherit"><?php echo __("Remove") ?></label>
                        </div>

                    </div>
                    <br class="clear"/>
                    <div id="tohide" >
                    

                    </div>
                    <br class="clear"/>
                   
                </div>
            
             <br class="clear"/>

            </form>

	

                <div class="formbuttons">
        <input type="button" class="<?php echo $editMode ? 'editbutton' : 'savebutton';?>" name="EditMain" id="editBtn"
        	value="<?php echo $editMode ? __("Edit") : __("Submit");?>"
        	title="<?php echo $editMode ? __("Edit") : __("Submit");?>"
        	onmouseover="moverButton(this);" onmouseout="moutButton(this);"/>
        <input type="reset" class="clearbutton" id="btnClear" tabindex="5"
                onmouseover="moverButton(this);" onmouseout="moutButton(this);"	<?php echo $disabled;?>
                value="<?php echo __("Reset");?>" />
        <input type="button" class="backbutton" id="btnBack"
                value="<?php echo __("Back")?>" tabindex="18"  onclick="goBack();"/>
        <?php if($OfficeOut->oo_id){ ?>
        <input type="button" class="clearbutton" id="btnCancel" tabindex="5" onclick="cancelNote();"
               
                value="<?php echo __("Cancel");?>" />
        <?php } ?>
            </div>

        </div>
 <div class="requirednotice"><?php echo __("Fields marked with an asterisk")?><span class="required"> * </span> <?php   echo __("are required") ?></div>
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
       
       var myArray2= new Array();
            var empstatArray= new Array();
            var k;
            var pagination = 0;
            var existemp= new Array();

            //Pagination variable
            itemsPerPage = 20;
            paginatorStyle = 2;
            paginatorPosition = 'both';
            enableGoToPage = true;
            currentPage = 1;
            
    function VRT(){
            var empid = $("#txtEmpId").val();
            var fromdate = $("#disbdate").val();
            var fromtime = $("#timefrom").find('option:selected').text()+":"+$("#timeto").find('option:selected').text();
            var totime = $("#timetohrs").find('option:selected').text()+":"+$("#timetoMM").find('option:selected').text();
            var url="<?php echo $sysConf->VRTURL; ?>";
            
            //alert(empid+fromdate+fromtime+totime+url);
            if(empid != ""){
                url+="?empid="+empid;
            }
            if(fromdate != "" ){
                url+="&fromdate="+fromdate;
            }
            if(fromtime != "" || fromtime != "HH:MM"){
                url+="&fromtime="+fromtime;
            }
            if(totime != "" || totime != "HH:MM"){
                url+="&totime="+totime;
            }
            window.open(url);
            
    }   
       
    function cancelNote(){ 
       <?php  if($OfficeOut->oo_id){  ?>
        var ID = <?php echo $OfficeOut->oo_id; ?>;
        if(ID == null){
            alert("<?php echo __("Please Enter OONote"); ?>");
        }else{
            var day;
            $.ajax({
                type: "POST",
                async:false,
                url: "<?php echo url_for('Leave/AjaxAOOCancel') ?>",
                data: { ID: ID },
                dataType: "json",
                success: function(data){
                    alert(data);
                }
            });
        }
      
      <?php } ?>
        
    }                    
                        
                        function SelectEmployee(data){

//                            myArr = data.split('|');
//                            $("#txtEmpId").val("");
//                            $("#txtEmpId").val(myArr[0]);
//                            $("#txtEmployee").val(myArr[1]);
                                myArr=new Array();
                                lol=new Array();
                                myArr = data.split('|');
                                addtoGrid(myArr);
                        }
                        
                        function addtoGrid(empid){

                var arraycp=new Array();

                var arraycp = $.merge([], myArray2);

                var items= new Array();
                for(i=0;i<empid.length;i++){

                    items[i]=empid[i];
                }

                var u=1;
                $.each(items,function(key, value){

                    if(jQuery.inArray(value, arraycp)!=-1)
                    {

                        // ie of array index find bug sloved here//
                        if(!Array.indexOf){
                            Array.prototype.indexOf = function(obj){
                                for(var i=0; i<this.length; i++){
                                    if(this[i]==obj){
                                        return i;
                                    }
                                }
                                return -1;
                            }
                        }

                        var idx = arraycp.indexOf(value);
                        if(idx!=-1) arraycp.splice(idx, 1); // Remove it if really found!
                        u=0;

                    }
                    else{

                        arraycp.push(value);

                    }


                }


            );

                $.each(myArray2,function(key, value){
                    if(jQuery.inArray(value, arraycp)!=-1)
                    {

                        // ie of array index find bug sloved here//
                        if(!Array.indexOf){
                            Array.prototype.indexOf = function(obj){
                                for(var i=0; i<this.length; i++){
                                    if(this[i]==obj){
                                        return i;
                                    }
                                }
                                return -1;
                            }
                        }

                        var idx = arraycp.indexOf(value); // Find the index
                        if(idx!=-1) arraycp.splice(idx, 1); // Remove it if really found!
                        u=0;

                    }
                    else{


                    }


                }


            );
                $.each(arraycp,function(key, value){
                    myArray2.push(value);
                }


            );if(u==0){

                }
                var courseId1=$('#courseid').val();
                $.post(

                "<?php echo url_for('pim/LoadGrid') ?>", //Ajax file



                { 'empid[]' : arraycp },  // create an object will all values

                //function that is c    alled when server returns a value.
                function(data){
                    //alert(data);

                    //var childDiv;
                    var childdiv="";
                    var i=0;

                    $.each(data, function(key, value) {
                        var word=value.split("|");

                                    childdiv="<div style = 'width:380px; height:30px;' class='pagin' id='row_"+word[2]+"' style='padding-top:5px; '>";
                                    childdiv+="<div class='centerCol' id='master' style='width:100px;'>";
                                    childdiv+="<div id='employeename' style='height:30px; padding-left:3px;'>"+word[0]+"</div>";
                                    childdiv+="</div>";

                                    childdiv+="<div class='centerCol' id='master' style='width:220px;'>";
                                    childdiv+="<div id='employeename' style='height:30px; padding-left:3px;'>"+word[1]+"</div>";
                                    childdiv+="</div>";
 

                                    childdiv+="<div class='centerCol' id='master' style='width:60px;'>";
                                    childdiv+="<div id='employeename' style='height:30px; padding-left:3px;'><a href='#' style='width:50px;' onclick='deleteCRow("+i+","+word[2]+")'><?php echo __('Remove') ?></a><input type='hidden' name='hiddenEmpNumber[]' value="+word[2]+" ></div>";
                                    childdiv+="</div>";
                                    childdiv+="</div>";
                                    //

                                    $('#tohide').append(childdiv);


                        k=i;
                        i++;
                    });
                    pagination++;


$('.paginator').remove();

                    $(function () {

                       if(pagination > 1){
                       $("#tohide").depagination();
                       }
                        $("#tohide").pagination();
                    });

                },

                //How you want the data formated when it is returned from the server.
                "json"

            );


            }
            function removeByValue(arr, val) {
                for(var i=0; i<arr.length; i++) {
                    if(arr[i] == val) {

                        arr.splice(i, 1);

                        break;

                    }
                }
            }
            function deleteCRow(id,value){

                answer = confirm("<?php echo __("Do you really want to Delete?") ?>");

                if (answer !=0)
                {

                    $("#row_"+value).remove();
                    removeByValue(myArray2, value);

                    $('#hiddeni').val(Number($('#hiddeni').val())-1);
                    

//            $.ajax({
//            type: "POST",
//            async:false,
//            url: "<?php //echo url_for('performance/AjaxDeleteAssignEmployee') ?>",
//            data: { EVid: $('#cmbbtype').val() , ETid: $('#cmbEtype').val() , Empno:value },
//            dataType: "json",
//            success: function(data){
//            }
//            });
                    $(function () {
                        $("#tohide").depagination();
                        $("#tohide").pagination();
                    });

                }
                else{
                    return false;
                }

            }

function getbenfittype(id){

           btId=id;


           $.post(

    "<?php echo url_for('wbm/Checkbtype') ?>", //Ajax file

    { id: id },  // create an object will all values

    //function that is called when server returns a value.
    function(data){
         var selectbox="<option value='-1'><?php echo __('--Select--') ?></option>";
   $.each(data, function(key, value) {
        selectbox=selectbox +"<option value="+key+">"+value+"</option>";
    });
        selectbox=selectbox +"</select>";
   $('#cmbbstype').html(selectbox);

},
    //How you want the data formated when it is returned from the server.
   "json"
    );


       }

		$(document).ready(function() {
                buttonSecurityCommon("null","null","editBtn","null");
              <?php  if($editMode == true){ ?>
                $('#frmSave :input').attr('disabled', true);
                  $('#editBtn').removeAttr('disabled');
                      $('#btnBack').removeAttr('disabled');
                  <?php } ?>
                      
                      $('#empRepPopBtn').click(function() {

                                //var popup=window.open('<?php echo public_path('../../symfony/web/index.php/pim/searchEmployee?type=single&method=SelectEmployee'); ?>','Locations','height=450,width=800,resizable=1,scrollbars=1');
                                var popup=window.open("<?php echo public_path('../../symfony/web/index.php/pim/searchEmployee?type=multiple&method=SelectEmployee') ?>",'Locations','height=450,width=800,resizable=1,scrollbars=1');
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
                                        cmbbtype: { required: true },
                                        cmbbstype: { required: true },
				 	//txtName: { required: true },
                                        txtdisbdate: { required: true ,orange_date:true},
                                        todate: { required: true ,orange_date:true},
                                        txtcomment: { maxlength:200, noSpecialCharsOnly: true }
			 	 },
			 	 messages: {
                                        cmbbtype: "<?php echo __("Please select Benefit Type")?>",
                                        cmbbstype: "<?php echo __("Please select Benefit")?>",
			 		//txtName: "<?php echo __("Job Title Name is required")?>",
                                         txtdisbdate: {required:"<?php echo __("Please Enter Date")?>",orange_date: "<?php echo __("Please specify valid  date");?>"},
                                         todate: {required:"<?php echo __("Please Enter Date")?>",orange_date: "<?php echo __("Please specify valid  date");?>"},
                                        txtcomment:{maxlength:"<?php echo __("Maximum 200 Characters")?>",noSpecialCharsOnly:"<?php echo __("Special Characters are not allowed")?>"}
			 	 }
			 });

 // When click edit button
         $("#frmSave").data('edit', <?php echo $editMode ? '1' : '0' ?>);
         
        $("#editBtn").click(function() {
            
            var editMode = $("#frmSave").data('edit');
            
           var start = $("#disbdate").datepicker('getDate');
           var endd = $("#todate").datepicker('getDate');
           var days   = (endd - start)/1000/60/60/24;
           
           if(start > endd){
                alert("<?php echo __('Invalid Date') ?>");
                end;
           }
           else if(start == endd) { 
            
            var timeFromHrstoSec=$('#timefrom').val()*3600;


           var timeToHrstoSec=$('#timetohrs').val()*3600;

           var timeFromMM=$('#timeto').val()*60;

           var timeToMM=$('#timetoMM').val()*60;

           var totalTimetoSec=timeToHrstoSec+timeToMM;

           var totalTimeFromSec=timeFromHrstoSec+timeFromMM;

           if(totalTimetoSec==0 && totalTimeFromSec==0){
               if($('#timefrom').val()=="00" || $('#timetohrs').val()=="00" || $('#timeto').val()=="00" || $('#timetoMM').val()=="00"){
                   alert("<?php echo __('Invalid Time') ?>");
                   end;
               }
           }else{

               if($('#timefrom').val()=="" ||  $('#timeto').val()=="" ){

                   alert("<?php echo __('Invalid Time') ?>");
                   end;
               }else{

                   if(totalTimetoSec<=totalTimeFromSec){

                       alert("<?php echo __('Invalid Time') ?>");
                       end;

                   }
                   else{

                       if($('#timetohrs').val()=="" || $('#timetoMM').val()==""){
                           alert("<?php echo __('Invalid Time') ?>");
                           end;
                       }
                   }

               }


           }
           }

       


            
            
            if (editMode == 1) {
                // Set lock = 1 when requesting a table lock
                 
                location.href="<?php echo url_for('Leave/UpdateOONote?id='.$OfficeOut->oo_id.'&lock=1')?>";
            }
            else {
               
    		$('#frmSave').submit();
            }

           
        });

        //When Click back button
        $("#btnBack").click(function() {
                              location.href = "<?php echo url_for(public_path('../../symfony/web/index.php/Leave/OONote')) ?>";
                             });
                             
        $("#goCancel").click(function() {
                              location.href = "<?php echo url_for(public_path('../../symfony/web/index.php/Leave/OONoteCancel?id='.$OfficeOut->oo_id)) ?>";
                             });                     
                             

       //When click reset buton
	$("#btnClear").click(function() {
            // Set lock = 0 when resetting table lock
             location.href="<?php echo url_for('Leave/UpdateOONote?id='.$OfficeOut->oo_id.'&lock=0')?>";
	});

        $("#disbdate").datepicker({ dateFormat:'<?php echo $inputDate; ?>',onSelect: function(dateText, inst) {
        var date = $(this).val();
        $("#todate").datepicker("setDate", date );

        } });
        $("#todate").datepicker({ dateFormat:'<?php echo $inputDate; ?>' });

		 });
</script>
       