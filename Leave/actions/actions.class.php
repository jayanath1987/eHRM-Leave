<?php

/**
 * Leave actions.
 *
 * @package    orangehrm
 * @subpackage Leave
 * @author     JBL
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
//require_once '../../lib/common/LocaleUtil.php';
include ('../../lib/common/LocaleUtil.php');

class LeaveActions extends sfActions {

    public function executeAjaxADateConvert(sfWebRequest $request) {

        $date = $request->getParameter('date');
        $this->value1 = LocaleUtil::getInstance()->convertToStandardDateFormat(($date));
        echo json_encode(array("date" => $this->value1));
        die;
    }

    public function executeAjaxADateConvertSet(sfWebRequest $request) {

        $date = $request->getParameter('date');
        $this->value1 = LocaleUtil::getInstance()->formatDate($date);
        echo json_encode(array("date" => $this->value1));
        die;
    }

    public function executeLoadGrid(sfWebRequest $request) {
        $this->culture = $this->getUser()->getCulture();
        $secDao = new LeaveDao();
        $empId = $request->getParameter('empid');

        $this->emplist = $secDao->getEmployee($empId);
        $arr = Array();
    }

    public function executeDates(sfWebRequest $request) {

        $SDate = $request->getParameter('SDate');
        $EDate = $request->getParameter('EDate');
        $this->SDate = strtotime($SDate);
        $this->EDate = strtotime($EDate);
        echo json_encode(array("sdate" => $this->SDate, "edate" => $this->EDate));
        die;
    }

    public function executeFormatDates(sfWebRequest $request) {

        $date = $request->getParameter('date');
        $day = getdate($date);
        $langDay = $this->getContext()->getI18N()->__($day['weekday'], $args, 'messages');
        $this->Date = $day['year'] . "-" . $day['mon'] . "-" . $day['mday'] . " : " . $langDay;

        echo json_encode($this->Date);
        die;
    }

    public function executeDocumentType(sfWebRequest $request) {

        try {
            $this->Culture = $this->getUser()->getCulture();
            $this->isAdmin = $_SESSION['isAdmin'];
            $leaveDao = new LeaveDao();

            $this->sorter = new ListSorter('DocumentType', 'Leave', $this->getUser(), array('b.leave_type_id', ListSorter::ASCENDING));
            $this->sorter->setSort(array($request->getParameter('sort'), $request->getParameter('order')));

            if ($request->getParameter('mode') == 'search') {
                if (($request->getParameter('searchMode') == 'all') && (trim($request->getParameter('searchValue')) != '')) {
                    $this->setMessage('NOTICE', array('Select the field to search'));
                    $this->redirect('Leave/DocumentType');
                }
                $this->var = 1;
            }

            $this->searchMode = ($request->getParameter('searchMode') == null) ? 'all' : $request->getParameter('searchMode');
            $this->searchValue = ($request->getParameter('searchValue') == null) ? '' : $request->getParameter('searchValue');

            $this->sort = ($request->getParameter('sort') == '') ? 'b.leave_type_name' : $request->getParameter('sort');
            $this->order = ($request->getParameter('order') == '') ? 'ASC' : $request->getParameter('order');
            $res = $leaveDao->searchDocumentType($this->searchMode, $this->searchValue, $this->Culture, $this->sort, $this->order, $request->getParameter('page'));
            $this->knwdoctype = $res['data'];
            $this->pglay = $res['pglay'];
            $this->pglay->setTemplate('<a href="{%url}">{%page}</a>');
            $this->pglay->setSelectedTemplate('{%page}');
        } catch (Exception $e) {
            $errMsg = new CommonException($e->getMessage(), $e->getCode());
            $this->setMessage('WARNING', $errMsg->display());
            $this->redirect('default/error');
        }
    }

    public function executeSaveDocumentType(sfWebRequest $request) {
        $this->myCulture = $this->getUser()->getCulture();
        $leaveDao = new LeaveDao();
        $knwdt = new LeaveType();
        if ($request->isMethod('post')) {

            if (strlen($request->getParameter('txtName'))) {
                $knwdt->setLeave_type_name(trim($request->getParameter('txtName')));
            } else {
                $knwdt->setLeave_type_name(null);
            }
            if (strlen($request->getParameter('txtNamesi'))) {
                $knwdt->setLeave_type_name_si(trim($request->getParameter('txtNamesi')));
            } else {
                $knwdt->setLeave_type_name_si(null);
            }
            if (strlen($request->getParameter('txtNameta'))) {
                $knwdt->setLeave_type_name_ta(trim($request->getParameter('txtNameta')));
            } else {
                $knwdt->setLeave_type_name_ta(null);
            }

            try {
                $leaveDao->saveDocumentType($knwdt);
            } catch (Doctrine_Connection_Exception $e) {
                $errMsg = new CommonException($e->getPortableMessage(), $e->getPortableCode());
                $this->setMessage('WARNING', $errMsg->display());
                $this->redirect('Leave/DocumentType');
            } catch (Doctrine_Connection_Exception $e) {

                $errMsg = new CommonException($e->getPortableMessage(), $e->getPortableCode());
                $this->setMessage('WARNING', $errMsg->display());
                $this->redirect('Leave/DocumentType');
            } catch (Exception $e) {
                $errMsg = new CommonException($e->getMessage(), $e->getCode());
                $this->setMessage('WARNING', $errMsg->display());
                $this->redirect('Leave/DocumentType');
            }

            $this->setMessage('SUCCESS', array($this->getContext()->getI18N()->__("Successfully Added", $args, 'messages')));
            $this->redirect('Leave/DocumentType');
        }
    }

    public function executeUpdateDocumentType(sfWebRequest $request) {///die(var_dump($request));
        //Table Lock code is Open
        if (!strlen($request->getParameter('lock'))) {
            $this->lockMode = 0;
        } else {
            $this->lockMode = $request->getParameter('lock');
        }
        $transPid = $request->getParameter('id');

        if (isset($this->lockMode)) {
            if ($this->lockMode == 1) {

                $conHandler = new ConcurrencyHandler();

                $recordLocked = $conHandler->setTableLock('hs_hr_leave_type', array($transPid), 1);

                if ($recordLocked) {
                    // Display page in edit mode
                    $this->lockMode = 1;
                } else {

                    $this->setMessage('WARNING', array($this->getContext()->getI18N()->__('Can not update. Record locked by another user.', $args, 'messages')), false);
                    $this->lockMode = 0;
                }
            } else if ($this->lockMode == 0) {//dir("hgf");
                $conHandler = new ConcurrencyHandler();
                $recordLocked = $conHandler->resetTableLock('hs_hr_leave_type', array($transPid), 1);
                $this->lockMode = 0;
            }
        }

        //Table lock code is closed
        $this->myCulture = $this->getUser()->getCulture();
        $LeaveDao = new LeaveDao();

        $knwdt = $LeaveDao->readDocumentType($request->getParameter('id'));
        if (!$knwdt) {
            $this->setMessage('WARNING', array($this->getContext()->getI18N()->__('Can not update. Record has been Deleted', $args, 'messages')));
            $this->redirect('Leave/DocumentType');
        }

        $this->benifittypelist = $knwdt;
        if ($request->isMethod('post')) {

            if (strlen($request->getParameter('txtName'))) {
                $knwdt->setLeave_type_name(trim($request->getParameter('txtName')));
            } else {
                $knwdt->setLeave_type_name(null);
            }
            if (strlen($request->getParameter('txtNamesi'))) {
                $knwdt->setLeave_type_name_si(trim($request->getParameter('txtNamesi')));
            } else {
                $knwdt->setLeave_type_name_si(null);
            }
            if (strlen($request->getParameter('txtNameta'))) {
                $knwdt->setLeave_type_name_ta(trim($request->getParameter('txtNameta')));
            } else {
                $knwdt->setLeave_type_name_ta(null);
            }
            try {
                $LeaveDao->saveDocumentType($knwdt);
            } catch (Doctrine_Connection_Exception $e) {
                $errMsg = new CommonException($e->getPortableMessage(), $e->getPortableCode());
                $this->setMessage('WARNING', $errMsg->display());
                $this->redirect('Leave/UpdateDocumentType?id=' . $knwdt->getLeave_type_id() . '&lock=0');
            } catch (Exception $e) {
                $errMsg = new CommonException($e->getMessage(), $e->getCode());
                $this->setMessage('WARNING', $errMsg->display());
                $this->redirect('Leave/UpdateDocumentType?id=' . $knwdt->getLeave_type_id() . '&lock=0');
            }
            $this->setMessage('SUCCESS', array($this->getContext()->getI18N()->__("Successfully Updated", $args, 'messages')));
            $this->redirect('Leave/UpdateDocumentType?id=' . $knwdt->getLeave_type_id() . '&lock=0');
        }
    }

    public function executeDeleteDocumentType(sfWebRequest $request) {
        if (count($request->getParameter('chkLocID')) > 0) {

            $LeaveDao = new LeaveDao();
            try {
                $conn = Doctrine_Manager::getInstance()->connection();
                $conn->beginTransaction();
                $ids = array();
                $ids = $request->getParameter('chkLocID');

                $countArr = array();
                $saveArr = array();
                for ($i = 0; $i < count($ids); $i++) {
                    $conHandler = new ConcurrencyHandler();
                    $isRecordLocked = $conHandler->isTableLocked('hs_hr_leave_type', array($ids[$i]), 1);
                    if ($isRecordLocked) {

                        $countArr = $ids[$i];
                    } else {
                        $saveArr = $ids[$i];
                        $LeaveDao->deleteDocumentType($ids[$i]);
                        $conHandler->resetTableLock('hs_hr_leave_type', array($ids[$i]), 1);
                    }
                }

                $conn->commit();
            } catch (Doctrine_Connection_Exception $e) {

                $errMsg = new CommonException($e->getPortableMessage(), $e->getPortableCode());
                $this->setMessage('WARNING', $errMsg->display());
                $this->redirect('Leave/DocumentType');
            } catch (Exception $e) {
                $conn->rollBack();
                $errMsg = new CommonException($e->getMessage(), $e->getCode());
                $this->setMessage('WARNING', $errMsg->display());
                $this->redirect('Leave/DocumentType');
            }
            if (count($saveArr) > 0 && count($countArr) == 0) {
                $this->setMessage('SUCCESS', array($this->getContext()->getI18N()->__("Successfully Deleted", $args, 'messages')));
            } elseif (count($saveArr) > 0 && count($countArr) > 0) {
                $this->setMessage('WARNING', array($this->getContext()->getI18N()->__("Some records are can not be deleted as them  Locked by another user ", $args, 'messages')));
            } elseif (count($saveArr) == 0 && count($countArr) > 0) {
                $this->setMessage('WARNING', array($this->getContext()->getI18N()->__("Can not delete as them  Locked by another user ", $args, 'messages')));
            }
        } else {
            $this->setMessage('NOTICE', array('Select at least one record to delete'));
        }
        $this->redirect('Leave/DocumentType');
    }

    public function executeLeaveConfiguration(sfWebRequest $request) {
        if (!strlen($request->getParameter('lock'))) {
            $this->lockMode = 0;
        } else {
            $this->lockMode = $request->getParameter('lock');
        }
        $transPid = $request->getParameter('id');

        if (isset($this->lockMode)) {
            if ($this->lockMode == 1) {

                $conHandler = new ConcurrencyHandler();

                $recordLocked = $conHandler->setTableLock('hs_hr_leave_type_config', array($transPid), 1);
                if ($recordLocked) {
                    // Display page in edit mode
                    $this->lockMode = 1;
                } else {
                    $this->setMessage('WARNING', array($this->getContext()->getI18N()->__('Can not update. Record locked by another user.', $args, 'messages')), false);
                    $this->lockMode = 0;
                }
            } else if ($this->lockMode == 0) {
                $conHandler = new ConcurrencyHandler();
                $recordLocked = $conHandler->resetTableLock('hs_hr_leave_type_config', array($transPid), 1);
                $this->lockMode = 0;
            }
        }

        $this->Culture = $this->getUser()->getCulture();
        $LeaveDao = new LeaveDao();
        $this->loadbtype = $LeaveDao->getLeaveTypeloadall();

        if ($this->loadbtype[0][leave_type_id] == null) {
            $this->setMessage('WARNING', array($this->getContext()->getI18N()->__("Please Enter Leave Type", $args, 'messages')));
            $this->redirect('Leave/DocumentType');
        } else {
            $this->loadEtype = $LeaveDao->getEmployeeTypeload();
            $this->benifittypelist = $knwdt;
        }
        if ($request->isMethod('post')) {
         try {
          $conn = Doctrine_Manager::getInstance()->connection();
          $conn->beginTransaction();
            $status = $LeaveDao->IsLeaveType($request->getParameter('cmbbtype'));
            if ($status[0]['Status'] == 0) {
                $LeavetypeConfig = new LeaveTypeConfig();
            } else {
                $LeavetypeConfig = $LeaveDao->readLeaveTypeConfig($request->getParameter('cmbbtype'));
            }
            if (strlen($request->getParameter('cmbbtype'))) {
                $LeavetypeConfig->setLeave_type_id(trim($request->getParameter('cmbbtype')));
            } else {
                $LeavetypeConfig->setLeave_type_id(null);
            }
            if (strlen($request->getParameter('txtDescription'))) {
                $LeavetypeConfig->setLeave_type_description(trim($request->getParameter('txtDescription')));
            } else {
                $LeavetypeConfig->setLeave_type_description(null);
            }
            if (strlen($request->getParameter('chkActive'))) {
                $LeavetypeConfig->setLeave_type_active_flg(trim($request->getParameter('chkActive')));
            } else {
                $LeavetypeConfig->setLeave_type_active_flg(null);
            }
            if (strlen($request->getParameter('chkEcovering'))) {
                $LeavetypeConfig->setLeave_type_covering_employee_flg(trim($request->getParameter('chkEcovering')));
            } else {
                $LeavetypeConfig->setLeave_type_covering_employee_flg(null);
            }
            if (strlen($request->getParameter('chkAllowHD'))) {
                $LeavetypeConfig->setLeave_type_allow_halfday_flg(trim($request->getParameter('chkAllowHD')));
            } else {
                $LeavetypeConfig->setLeave_type_allow_halfday_flg(null);
            }
            if (strlen($request->getParameter('chkMerity'))) {
                $LeavetypeConfig->setLeave_type_maternity_leave_flg(trim($request->getParameter('chkMerity')));
            } else {
                $LeavetypeConfig->setLeave_type_maternity_leave_flg(null);
            }
            if (strlen($request->getParameter('chkNApprove'))) {
                $LeavetypeConfig->setLeave_type_need_approval_flg(trim($request->getParameter('chkNApprove')));
            } else {
                $LeavetypeConfig->setLeave_type_need_approval_flg(null);
            }
            if (strlen($request->getParameter('chkMonthly')) && strlen($request->getParameter('chkSL'))) {
                $LeavetypeConfig->setLeave_type_short_leave_flg(trim($request->getParameter('chkMonthly')));
            } else {
                $LeavetypeConfig->setLeave_type_short_leave_flg("0");
            }
            if (strlen($request->getParameter('txtEntitleDays'))) {
                $LeavetypeConfig->setLeave_type_entitle_days(trim($request->getParameter('txtEntitleDays')));
            } else {
                $LeavetypeConfig->setLeave_type_entitle_days(null);
            }
            if (strlen($request->getParameter('txtApplyBefore'))) {
                if ($request->getParameter('txtApplyBefore') == "0") {
                    $LeavetypeConfig->setLeave_type_need_to_apply_before("0");
                } else {
                    $LeavetypeConfig->setLeave_type_need_to_apply_before(trim($request->getParameter('txtApplyBefore')));
                }
            } else {
                $LeavetypeConfig->setLeave_type_need_to_apply_before("0");
            }
            if (strlen($request->getParameter('txtMaximumLeaveDays'))) {
                $LeavetypeConfig->setLeave_type_max_days_without_medi(trim($request->getParameter('txtMaximumLeaveDays')));
            } else {
                $LeavetypeConfig->setLeave_type_max_days_without_medi(null);
            }
            if (strlen($request->getParameter('txtComments'))) {
                $LeavetypeConfig->setLeave_type_comment(trim($request->getParameter('txtComments')));
            } else {
                $LeavetypeConfig->setLeave_type_comment(null);
            }

            $ConfigDetails = $LeaveDao->readLeaveTypeConfigdetails($request->getParameter('cmbbtype'));
            
                $LeaveDao->saveLeaveTypeConfig($LeavetypeConfig);
                $LeaveDao->deletereclevtypeconfigdetail($request->getParameter('cmbbtype'));
                foreach ($this->loadEtype as $etyp) {
                    $LeavetypeConfigDetail = new LeaveTypeConfigDetail();
                    if ($request->getParameter('chk' . $etyp['id']) == 1) {
                        $LeavetypeConfigDetail->setLeave_type_id(trim($request->getParameter('cmbbtype')));
                        $LeavetypeConfigDetail->setEstat_code($etyp['id']);
                        $satus = $LeaveDao->IsLeaveTypedetail($request->getParameter('cmbbtype'), $etyp['id']);
                        $LeaveDao->saveLeaveTypeConfigDetails($LeavetypeConfigDetail);
                    }
                }
                $conn->commit();
            } catch (Doctrine_Connection_Exception $e) {
                $conn->rollback();
                $errMsg = new CommonException($e->getPortableMessage(), $e->getPortableCode());
                $this->setMessage('WARNING', $errMsg->display());
                $this->redirect('Leave/LeaveConfiguration?id=' . $transPid . '&lock=0');
            } catch (Exception $e) {
                $conn->commit();
                $errMsg = new CommonException($e->getMessage(), $e->getCode());
                $this->setMessage('WARNING', $errMsg->display());
                $this->redirect('Leave/LeaveConfiguration?id=' . $transPid . '&lock=0');
            }
            $this->setMessage('SUCCESS', array($this->getContext()->getI18N()->__("Successfully Updated", $args, 'messages')));
            $this->redirect('Leave/LeaveConfiguration?id=' . $transPid . '&lock=0');
        }
    }

    public function executeAjaxDaysload(sfWebRequest $request) {

        $transPid = $request->getParameter('id');
        $LeaveDao = new LeaveDao();
        $Leavetype = $LeaveDao->LoadLeaveEntitledate($transPid);
        $Maternity = $LeaveDao->getLeaveTypeloadMeternity($transPid);
        echo json_encode(array("EntitleDays" =>$Leavetype[0]['leave_type_entitle_days'],"Maternity" =>$Maternity[0]['leave_type_maternity_leave_flg']));
        die;
    }

    public function executeEmpData(sfWebRequest $request) {
        $Culture = $this->getUser()->getCulture();
        $eid = $request->getParameter('eid');
        $year = $request->getParameter('year');
        $leaveDao = new LeaveDao();
        $Leavetype = $leaveDao->LoadEmpData($eid);
        $Leavetype[0]['job_title_code'];
        if ($Culture == 'en') {
            $abc = "getName";
        } else {
            $abc = "getName_" . $Culture;
        }

        if ($Leavetype[0]->jobTitle->$abc() == null) {
            $jobtitle = $Leavetype[0]->jobTitle->getName();
        } else {
            $jobtitle = $Leavetype[0]->jobTitle->$abc();
        }

        if ($Culture == 'en') {
            $ab = "getTitle";
        } else {
            $ab = "getTitle_" . $Culture;
        }

        if ($Leavetype[0]->subDivision->$ab() == null) {
            $Division = $Leavetype[0]->subDivision->getTitle();
        } else {
            $Division = $Leavetype[0]->subDivision->$ab();
        }
        $this->Sup = $leaveDao->LoadsubordinateData($eid);
        if ($this->Sup[0] !== null) {
            $EData = $leaveDao->LoadEmpData($this->Sup[0]['supervisorId']);
            if ($Culture == 'en') {
                $abcd = "getEmp_display_name";
            } else {
                $abcd = "getEmp_display_name_" . $Culture;
            }

            if ($EData[0]->$abcd() == " ") {
                $superviser = $EData[0]->getEmp_display_name();
            } else {
                $superviser = $EData[0]->$abcd();
            }
        } else {
            $superviser = "NO";
        }
        echo json_encode(array("Designation" => $jobtitle, "Department" => $Division, "Supervisor" => $superviser));
        die;
    }

    public function executeDefaultLeavedetails(sfWebRequest $request) {

        $eid = $request->getParameter('eid');
        $year = $request->getParameter('year');
        $leaveDao = new LeaveDao();
        $entitle = $leaveDao->readLeaveEntitlementDisplay($eid, $year);

        if ($entitle == null) {
            $entitle = array("|");
        }
        echo json_encode(array("entitle" => $entitle));
        die;
    }

    public function executeAjaxEmpType(sfWebRequest $request) {

        $transPid = $request->getParameter('id');
        $LeaveDao = new LeaveDao();
        $this->Leavevalidtype = $LeaveDao->validLeaveTypeConfigdetailsforemployee($transPid);
        echo json_encode(array("Leavevalidtype" => $this->Leavevalidtype));
        die;
    }

    public function executeAjaxLeaveValidation(sfWebRequest $request) {

        $transPid = $request->getParameter('id');
        $LeaveDao = new LeaveDao();
        $LeavetypeConfig = $LeaveDao->readLeaveTypeConfig($transPid);
        if ($LeavetypeConfig->getLeave_type_covering_employee_flg() != null) {
            $this->Cemp = $LeavetypeConfig->getLeave_type_covering_employee_flg();
        } else {
            $this->Cemp = "0";
        }
        if ($LeavetypeConfig->getLeave_type_need_approval_flg() != null) {
            $this->Napp = $LeavetypeConfig->getLeave_type_need_approval_flg();
        } else {
            $this->Napp = "0";
        }
        if ($LeavetypeConfig->getLeave_type_allow_halfday_flg() != null) {
            $this->Ahfd = $LeavetypeConfig->getLeave_type_allow_halfday_flg();
        } else {
            $this->Ahfd = "0";
        }
        if ($LeavetypeConfig->getLeave_type_short_leave_flg() != 0) {
            $this->SL = $LeavetypeConfig->getLeave_type_short_leave_flg();
        } else {
            $this->SL = "0";
        }
        if ($LeavetypeConfig->getLeave_type_need_to_apply_before() == 0) {
            $this->NAB = "0";
        } else {
            $numdaysba = $LeavetypeConfig->getLeave_type_need_to_apply_before();
            $Sdate = strtotime(date("Y-m-d"));
            $nday = date('Y-m-d', $Sdate + ($numdaysba * 86400));
            $this->NAB = $nday;
            $this->nodaysbefore = $numdaysba;
        }
        echo json_encode(array("Cemp" => $this->Cemp, "Napp" => $this->Napp, "Ahfd" => $this->Ahfd, "SL" => $this->SL, "NAB" => $this->NAB, "noofdays" => $this->nodaysbefore));
        die;
    }

    public function executeAjaxLeaveHolydayValidation(sfWebRequest $request) {
        $SempNum = $request->getParameter('Eid');
        $SDate = $request->getParameter('SDate');
        $EDate = $request->getParameter('EDate');
        $HD = $request->getParameter('HD');
        $SL = $request->getParameter('SL');
        $LType = $request->getParameter('LType');
        $leaveDao = new LeaveDao();
        $LeaavaeHD = $leaveDao->readLeaveHolyDay();
        $start_ts = strtotime($SDate);
        $end_ts = strtotime($EDate);
        $LDays = 0;
        $noDays = null;
        $Dyear = date_parse(date($SDate));
        $Dyear2 = date_parse(date($EDate));
        $LeavetypeConfig = $leaveDao->readLeaveTypeConfig($LType);
        $LeavaeBal = $leaveDao->readLeaveEntitlement($SempNum, $LType, $Dyear['year']);
        if ($LeavaeBal[0]['leave_ent_remain'] == null or $LeavaeBal[0]['leave_ent_remain'] < 0) {
            $this->LBal = "0";
        } else {
            if ($Dyear['year'] != $Dyear2['year']) {
                $end_ts = strtotime($Dyear['year'] . "-12-31");
                $rem = ($end_ts - $start_ts) / 86400;
                $this->LBal = $rem + 1;
            }
            $this->LBal = $LeavaeBal[0]['leave_ent_remain'];
        }
        $LeavaeRange = $leaveDao->readLeaveLeaveRange($SDate, $EDate, $SempNum);
        if ($HD == "yes") {
            $LeavaeHD = $leaveDao->readLeaveLeaveHD($SDate, $SempNum);
        }
        foreach ($LeaavaeHD as $row) {

            $user_ts = strtotime($row['leave_holiday_date']);

            if (($user_ts >= $start_ts) && ($user_ts <= $end_ts)) {
                if ($row['leave_holiday_fulorhalf'] == "0") {
                    $LDays+=0.5;
                    $user_ts = $user_ts + 5100000000;
                } else {
                    $LDays+=1;
                }
            }
            $hdate[] = $user_ts;
        }

        //------Shift basis calculation
        $AtnDao = new attendanceDao();
        $shift = $AtnDao->readADay();
        $SDay = getdate(strtotime($SDate));
        $EDay = getdate(strtotime($EDate));
        while ($SDay[0] <= $EDay[0]) {
            $middate = getdate($SDay[0]);

            foreach ($shift as $shiftday) {
                if ($shiftday['dt_id'] == "3") {
                    if ($shiftday['adt_day'] == $middate['weekday']) {
                        $hdate[] = $middate[0];
                        $LDays+=1;
                    }
                } else if ($shiftday['dt_id'] == "4") {
                    if ($shiftday['adt_day'] == $middate['weekday']) {
                        $hdate[] = $middate[0] + 5000000000;
                        $LDays+=0.5;
                    }
                }
            }

            $SDay[0]+=86400;
        }
        //-------end Shift basis calculation

        $Ddif = (($end_ts - $start_ts) / (24 * 60 * 60));
        if ($Ddif == 0) {
            $noDays+=1;
        } else if ($Ddif < 0) {
            $noDays = -1;
        } else {
            $noDays = ($Ddif + 1) - $LDays;
        }
        $LDATE = getdate($end_ts);
        if ($LDATE['mon'] < 10) {
            $LDATE['mon'] = '0' . $LDATE['mon'];
        }
        if ($LDATE['mday'] < 10) {
            $LDATE['mday'] = '0' . $LDATE['mday'];
        }
        $ENDDATE = $LDATE['year'] . "-" . $LDATE['mon'] . "-" . $LDATE['mday'];
        if (($LDays == "0.5") && ($noDays == "1")) {
            $noDays = "0.5";
        }
        if ($hdate == null) {
            $hdate = 0;
        }
        //Short Leave
        if($SL=="yes"){
            $ShortLeave = $leaveDao->readShortLeave($Dyear['year'], $Dyear['month'], $SempNum,$LType);
            //if($ShortLeave[0]['count']<2){
            if($ShortLeave[0]['count']<$LeavetypeConfig->leave_type_entitle_days){

                $ShortLeaveAllow="yes";
            }else{
                $ShortLeaveAllow="no";
            }
        }else{
            $ShortLeaveAllow="yes";
        }
        //End Short Leave

        $this->Ahfd = $LDays;
        $this->NoDays = $noDays;
        $this->Range = $LeavaeRange;
        $this->HD = $LeavaeHD;
        $this->LeaveDays = $hdate;
        $this->ENDDATE = $ENDDATE;
        $this->ShortLeaveAllow = $ShortLeaveAllow;
        echo json_encode(array("LDays" => $this->Ahfd, "NDays" => $this->NoDays, "Range" => $this->Range, "HD" => $this->HD, "LBal" => $this->LBal, "LeaveDays" => $this->LeaveDays, "ENDDate" => $this->ENDDATE, "ShortLeaveAllow" => $this->ShortLeaveAllow));
        die;
    }

    public function executeAjaxLeaveEmployeedataload(sfWebRequest $request) {

    }

    public function executeAjaxLeavecoveringEmployee(sfWebRequest $request) {

        $emp = $request->getParameter('eid');
        $SDate = $request->getParameter('sdate');
        $EDate = $request->getParameter('edate');
        $leaveDao = new LeaveDao();
        $LeavaeHD = $leaveDao->readLeaveLeaveRange($SDate, $EDate, $emp);
        echo json_encode($LeavaeHD);
        die;
    }

    public function executeDTConfig(sfWebRequest $request) {
        if (!strlen($request->getParameter('lock'))) {
            $this->lockMode = 0;
        } else {
            $this->lockMode = $request->getParameter('lock');
        }
        $transPid = $request->getParameter('id');
        if (isset($this->lockMode)) {
            if ($this->lockMode == 1) {

                $conHandler = new ConcurrencyHandler();

                $recordLocked = $conHandler->setTableLock('hs_hr_leave_type', array($transPid), 1);
                $recordLocked = $conHandler->setTableLock('hs_hr_leave_type_config', array($transPid), 1);
                if ($recordLocked) {
                    // Display page in edit mode
                    $this->lockMode = 1;
                } else {
                    $this->setMessage('WARNING', array($this->getContext()->getI18N()->__('Can not update. Record locked by another user.', $args, 'messages')), false);
                    $this->lockMode = 0;
                }
            } else if ($this->lockMode == 0) {//dir("hgf");
                $conHandler = new ConcurrencyHandler();
                $recordLocked = $conHandler->resetTableLock('hs_hr_leave_type', array($transPid), 1);
                $recordLocked = $conHandler->resetTableLock('hs_hr_leave_type_config', array($transPid), 1);
                $this->lockMode = 0;
            }
        }
        $LeaveDao = new LeaveDao();
        $Leavetype = $LeaveDao->IsLeaveType($transPid);

        if ($Leavetype[0]['Status'] == "0") {

            $this->ConfigT = null;
            echo json_encode(array($this->ConfigT, $this->lockMode));
        } else {
            $ConfigT = $LeaveDao->readLeaveTypeConfig($transPid);
            $employeeT = $LeaveDao->getEmployeeTypeload();
            $ConfigDetails = $LeaveDao->readLeaveTypeConfigdetails($transPid);
            $leave_type_id = $ConfigT['leave_type_id'];
            $leave_type_description = $ConfigT['leave_type_description'];
            $leave_type_active_flg = $ConfigT['leave_type_active_flg'];
            $leave_type_covering_employee_flg = $ConfigT['leave_type_covering_employee_flg'];
            $leave_type_allow_halfday_flg = $ConfigT['leave_type_allow_halfday_flg'];
            $leave_type_maternity_leave_flg = $ConfigT['leave_type_maternity_leave_flg'];
            $leave_type_need_approval_flg = $ConfigT['leave_type_need_approval_flg'];
            $leave_type_entitle_days = $ConfigT['leave_type_entitle_days'];
            $leave_type_max_days_without_medi = $ConfigT['leave_type_max_days_without_medi'];
            $leave_type_need_to_apply_before = $ConfigT['leave_type_need_to_apply_before'];
            $leave_type_wf_id = $ConfigT['leave_type_wf_id'];
            $leave_type_comment = $ConfigT['leave_type_comment'];
            $leave_type_short_leave_flg = $ConfigT['leave_type_short_leave_flg'];

            //leave type config details
            $leave_type_id = $ConfigT['leave_type_id'];
            echo json_encode(array($leave_type_id, $leave_type_description, $leave_type_active_flg, $leave_type_covering_employee_flg, $leave_type_allow_halfday_flg, $leave_type_maternity_leave_flg, $leave_type_need_approval_flg, $leave_type_entitle_days, $leave_type_max_days_without_medi, $leave_type_need_to_apply_before, $leave_type_wf_id, $leave_type_comment,$leave_type_short_leave_flg ,$ConfigDetails, $this->lockMode));
        }
        die;
    }

    public function executeEntitlement(sfWebRequest $request) {

        try {
            $this->Culture = $this->getUser()->getCulture();
            $this->isAdmin = $_SESSION['isAdmin'];
            $leaveDao = new LeaveDao();

            $this->sorter = new ListSorter('Entitlement', 'Leave', $this->getUser(), array('b.emp_number', ListSorter::ASCENDING));
            $this->sorter->setSort(array($request->getParameter('sort'), $request->getParameter('order')));

            if ($request->getParameter('mode') == 'search') {
                if (($request->getParameter('searchMode') == 'all') && (trim($request->getParameter('searchValue')) != '')) {
                    $this->setMessage('NOTICE', array('Select the field to search'));
                    $this->redirect('Leave/Entitlement');
                }
                $this->var = 1;
            }

            $this->searchMode = ($request->getParameter('searchMode') == null) ? 'all' : $request->getParameter('searchMode');
            $this->searchValue = ($request->getParameter('searchValue') == null) ? '' : $request->getParameter('searchValue');

            $this->sort = ($request->getParameter('sort') == '') ? 'b.emp_number' : $request->getParameter('sort');
            $this->order = ($request->getParameter('order') == '') ? 'ASC' : $request->getParameter('order');
            $res = $leaveDao->searchEntitlement($this->searchMode, $this->searchValue, $this->Culture, $this->sort, $this->order, $request->getParameter('page'));
            $this->knwdoctype = $res['data'];
            $this->pglay = $res['pglay'];
            $this->pglay->setTemplate('<a href="{%url}">{%page}</a>');
            $this->pglay->setSelectedTemplate('{%page}');
        } catch (Exception $e) {
            $errMsg = new CommonException($e->getMessage(), $e->getCode());
            $this->setMessage('WARNING', $errMsg->display());
            $this->redirect('default/error');
        }
    }

    public function executeSaveEntitlement(sfWebRequest $request) {
        $this->Culture = $this->getUser()->getCulture();
        $leaveDao = new LeaveDao();
        $this->loadbtype = $leaveDao->getLeaveTypeload();
        $ss = $request->getParameter('hiddenEmpNumber');

        if ($request->isMethod('post')) {
            try {
                $conn = Doctrine_Manager::getInstance()->connection();
                $conn->beginTransaction();

                foreach ($ss as $row) {

                    $knwdt = new LeaveEntitlement();
                    if (strlen($row)) {
                        $knwdt->setEmp_number(trim($row));
                    } else {
                        $knwdt->setEmp_number(null);
                    }
                    if (strlen($request->getParameter('cmbbtype'))) {
                        $knwdt->setLeave_type_id(trim($request->getParameter('cmbbtype')));
                    } else {
                        $knwdt->setLeave_type_id(null);
                    }
                    if (strlen($request->getParameter('txtYear'))) {
                        $knwdt->setLeave_ent_year(trim($request->getParameter('txtYear')));
                    } else {
                        $knwdt->setLeave_ent_day(null);
                    }
                    if (strlen($request->getParameter('txtEntitleDays'))) {
                        $knwdt->setLeave_ent_day(trim($request->getParameter('txtEntitleDays')));
                    } else {
                        $knwdt->setLeave_ent_day(0);
                    }
                    if (strlen($request->getParameter('txtSheduleDays'))) {
                        $knwdt->setLeave_ent_sheduled(trim($request->getParameter('txtSheduleDays')));
                    } else {
                        $knwdt->setLeave_ent_sheduled(0);
                    }
                    if (strlen($request->getParameter('txtEnTakenDays'))) {
                        $knwdt->setLeave_ent_taken(trim($request->getParameter('txtEnTakenDays')));
                    } else {
                        $knwdt->setLeave_ent_taken(0);
                    }

                    $remain = ($request->getParameter('txtEntitleDays') - ($request->getParameter('txtEnTakenDays') + $request->getParameter('txtSheduleDays')));
                    $knwdt->setLeave_ent_remain($remain);



                    $readLeaveEnt = $leaveDao->readLeaveEntitlement($row, $request->getParameter('cmbbtype'), $request->getParameter('txtYear'));
                    if ($readLeaveEnt == null) {
                        $leaveDao->saveEntitlement($knwdt);
                    } else {
                        $leaveDao->UpdateEntitlement($row, $request->getParameter('cmbbtype'), $request->getParameter('txtYear'), $request->getParameter('txtEntitleDays'), "null", "null", $remain);
                    }
                }
                $conn->commit();
            } catch (Doctrine_Connection_Exception $e) {
                $conn->rollBack();
                $errMsg = new CommonException($e->getPortableMessage(), $e->getPortableCode());
                $this->setMessage('WARNING', $errMsg->display());
                $this->redirect('Leave/Entitlement');
            } catch (Exception $e) {
                $conn->rollBack();
                $errMsg = new CommonException($e->getMessage(), $e->getCode());
                $this->setMessage('WARNING', $errMsg->display());
                $this->redirect('Leave/Entitlement');
            }
            $this->setMessage('SUCCESS', array($this->getContext()->getI18N()->__("Successfully Added", $args, 'messages')));
            $this->redirect('Leave/Entitlement');
        }
    }

    public function executeUpdateEntitlement(sfWebRequest $request) {
        //Table Lock code is Open

        if (!strlen($request->getParameter('lock'))) {
            $this->lockMode = 0;
        } else {
            $this->lockMode = $request->getParameter('lock');
        }
        $transPeid = $request->getParameter('eid');
        $transPlt = $request->getParameter('lt');
        $transPyr = $request->getParameter('yr');

        if (isset($this->lockMode)) {
            if ($this->lockMode == 1) {

                $conHandler = new ConcurrencyHandler();

                $recordLocked = $conHandler->setTableLock('hs_hr_leave_entitlement', array($transPeid, $transPlt, $transPyr), 1);

                if ($recordLocked) {
                    // Display page in edit mode
                    $this->lockMode = 1;
                } else {
                    $this->setMessage('WARNING', array($this->getContext()->getI18N()->__('Can not update. Record locked by another user.', $args, 'messages')), false);
                    $this->lockMode = 0;
                }
            } else if ($this->lockMode == 0) {
                $conHandler = new ConcurrencyHandler();
                $recordLocked = $conHandler->resetTableLock('hs_hr_leave_entitlement', array($transPeid, $transPlt, $transPyr), 1);
                $this->lockMode = 0;
            }
        }

        //Table lock code is closed
        $this->Culture = $this->getUser()->getCulture();
        $LeaveDao = new LeaveDao();
        $this->loadbtype = $LeaveDao->getLeaveTypeload();

        $readLeaveEnt = $LeaveDao->readLeaveEntitlement($request->getParameter('eid'), $request->getParameter('lt'), $request->getParameter('yr'));
        if (!$readLeaveEnt) {
            $this->setMessage('WARNING', array($this->getContext()->getI18N()->__('Can not update. Record has been Deleted', $args, 'messages')));
            $this->redirect('Leave/Entitlement');
        }
        $this->Entitle = $readLeaveEnt;
        $readLeave = $LeaveDao->LoadLeaveEntitledate($request->getParameter('lt'));
        $this->Entitlemax = $readLeave[0]['leave_type_entitle_days'];

        $Entitlemin1 = $readLeaveEnt[0]['leave_ent_sheduled'];
        $Entitlemin2 = $readLeaveEnt[0]['leave_ent_taken'];

        $Entitlemin = $Entitlemin1;
        if ($Entitlemin < $Entitlemin2) {
            $Entitlemin = $Entitlemin2;
        } else {
            $Entitlemin = $Entitlemin1;
        }
        $this->EntitleTS = $Entitlemin1+$Entitlemin2;
        $this->Entitlemin = $Entitlemin;
        $this->empname = $LeaveDao->reademplyeename($readLeaveEnt[0]['emp_number'], $this->Culture);
        if ($request->isMethod('post')) {
            if (strlen($request->getParameter('txtEmpId'))) {
                $txtEmpId = $request->getParameter('txtEmpId');
            } else {
                $txtEmpId = $request->getParameter('eid');
            }
            if (strlen($request->getParameter('cmbbtype'))) {
                $cmbbtype = $request->getParameter('cmbbtype');
            } else {
                $cmbbtype = "null";
            }
            if (strlen($request->getParameter('txtYear'))) {
                $txtYear = $request->getParameter('txtYear');
            } else {
                $txtYear = "null";
            }
            if (strlen($request->getParameter('txtEntitleDays'))) {
                $txtEntitleDays = $request->getParameter('txtEntitleDays');
            } else {
                $txtEntitleDays = "null";
            }
            if ($request->getParameter('txtSheduleDays') != null) {
                $txtSheduleDays = $request->getParameter('txtSheduleDays');
            } else {
                $txtSheduleDays = "null";
            }
            if (strlen($request->getParameter('txtEnTakenDays'))) {
                $txtEnTakenDays = $request->getParameter('txtEnTakenDays');
            } else {
                $txtEnTakenDays = "null";
            }
            $leave_ent_remain = ($txtEntitleDays - ($txtSheduleDays + $txtEnTakenDays));

            try {
                $LeaveDao->UpdateEntitlement($txtEmpId, $cmbbtype, $txtYear, $txtEntitleDays, $txtSheduleDays, $txtEnTakenDays, $leave_ent_remain);
            } catch (Doctrine_Connection_Exception $e) {
                $errMsg = new CommonException($e->getPortableMessage(), $e->getPortableCode());
                $this->setMessage('WARNING', $errMsg->display());
                $this->redirect('Leave/UpdateEntitlement?eid=' . $readLeaveEnt[0]['emp_number'] . '&lt=' . $readLeaveEnt[0]['leave_type_id'] . '&yr=' . $readLeaveEnt[0]['leave_ent_year'] . '&lock=0');
            } catch (Exception $e) {
                $errMsg = new CommonException($e->getMessage(), $e->getCode());
                $this->setMessage('WARNING', $errMsg->display());
                $this->redirect('Leave/UpdateEntitlement?eid=' . $readLeaveEnt[0]['emp_number'] . '&lt=' . $readLeaveEnt[0]['leave_type_id'] . '&yr=' . $readLeaveEnt[0]['leave_ent_year'] . '&lock=0');
            }
            $this->setMessage('SUCCESS', array($this->getContext()->getI18N()->__("Successfully Updated", $args, 'messages')));
            $this->redirect('Leave/UpdateEntitlement?eid=' . $readLeaveEnt[0]['emp_number'] . '&lt=' . $readLeaveEnt[0]['leave_type_id'] . '&yr=' . $readLeaveEnt[0]['leave_ent_year'] . '&lock=0');
        }
    }

    public function executeDeleteEntitlement(sfWebRequest $request) {

        if (count($request->getParameter('chkLocID')) > 0) {

            $LeaveDao = new LeaveDao();
            try {
                $conn = Doctrine_Manager::getInstance()->connection();
                $conn->beginTransaction();
                $ids = array();
                $ids = $request->getParameter('chkLocID');
                $i = 0;
                $countArr = array();
                $saveArr = array();
                foreach ($ids as $row) {
                    $col = explode("|", $row);
                    $conHandler = new ConcurrencyHandler();
                    $isRecordLocked = $conHandler->isTableLocked('hs_hr_leave_entitlement', array($col[0], $col[1], $col[2]), 1);
                    if ($isRecordLocked) {

                        $countArr[$i] = $col[0];
                    } else {
                        $saveArr[$i] = $col[0];
                        $LeaveDao->deleteEntitlement($row);
                        $conHandler->resetTableLock('hs_hr_leave_entitlement', array($col[0], $col[1], $col[2]), 1);
                    }
                    $i++;
                }
                $conn->commit();
            } catch (Doctrine_Connection_Exception $e) {

                $errMsg = new CommonException($e->getPortableMessage(), $e->getPortableCode());
                $this->setMessage('WARNING', $errMsg->display());
                $this->redirect('Leave/Entitlement');
            } catch (Exception $e) {
                $conn->rollBack();
                $errMsg = new CommonException($e->getMessage(), $e->getCode());
                $this->setMessage('WARNING', $errMsg->display());
                $this->redirect('Leave/Entitlement');
            }
            if (count($saveArr) > 0 && count($countArr) == 0) {
                $this->setMessage('SUCCESS', array($this->getContext()->getI18N()->__("Successfully Deleted", $args, 'messages')));
            } elseif (count($saveArr) > 0 && count($countArr) > 0) {
                $this->setMessage('WARNING', array($this->getContext()->getI18N()->__("Some records are can not be deleted as them  Locked by another user ", $args, 'messages')));
            } elseif (count($saveArr) == 0 && count($countArr) > 0) {
                $this->setMessage('WARNING', array($this->getContext()->getI18N()->__("Can not delete as them  Locked by another user ", $args, 'messages')));
            }
        } else {
            $this->setMessage('NOTICE', array('Select at least one record to delete'));
        }
        $this->redirect('Leave/Entitlement');
    }

    public function executeLeave(sfWebRequest $request) {

        try {
            $this->leaveDao=new LeaveDao();
            $this->Culture = $this->getUser()->getCulture();
            if ($request->getParameter('eid') != null) {
                $SempNum = $request->getParameter('eid');
                if($request->getParameter('eid')==$_SESSION['empNumber']){
                    $this->btnactive = 1;
                }
            } else {
                $SempNum = $_SESSION['empNumber'];
            }
            $leaveDao = new LeaveDao();

            $this->sorter = new ListSorter('Leave', 'Leave', $this->getUser(), array('b.leave_app_id', ListSorter::ASCENDING));
            $this->sorter->setSort(array($request->getParameter('sort'), $request->getParameter('order')));

            if ($request->getParameter('mode') == 'search') {
                if (($request->getParameter('searchMode') == 'all') && (trim($request->getParameter('searchValue')) != '')) {
                    $this->setMessage('NOTICE', array('Select the field to search'));
                    $this->redirect('Leave/Entitlement');
                }
                $this->var = 1;
            }

            $this->searchMode = ($request->getParameter('searchMode') == null) ? 'all' : $request->getParameter('searchMode');
            $this->searchValue = ($request->getParameter('searchValue') == null) ? '' : $request->getParameter('searchValue');

            $this->sort = ($request->getParameter('sort') == '') ? 'b.leave_app_start_date' : $request->getParameter('sort');
            $this->order = ($request->getParameter('order') == '') ? 'DESC' : $request->getParameter('order');
            $this->empid = ($SempNum == '') ? 'ASC' : $SempNum;
            $res = $leaveDao->searchLeave($this->searchMode, $this->searchValue, $this->Culture, $this->sort, $this->order, $request->getParameter('page'), $this->empid);
            $this->knwdoctype = $res['data'];
            $this->pglay = $res['pglay'];
            $this->pglay->setTemplate('<a href="{%url}">{%page}</a>');
            $this->pglay->setSelectedTemplate('{%page}');
        } catch (Exception $e) {
            $errMsg = new CommonException($e->getMessage(), $e->getCode());
            $this->setMessage('WARNING', $errMsg->display());
            $this->redirect('default/error');
        }
    }

    public function executeSaveLeave(sfWebRequest $request) {//die(print_r($_POST));
        $SempNum = $_SESSION['empNumber'];
        $this->eNum = $_SESSION['empNumber'];

        $this->Culture = $this->getUser()->getCulture();
        $leaveDao = new LeaveDao();
        $this->loadbtype = $leaveDao->getLeaveTypeload();
        $this->LeaveHoly = $leaveDao->readLeaveHolyDay();

        $this->EData = $leaveDao->LoadEmpData($SempNum);
        if ($this->EData[0]->getEmp_number() == null) {
            $this->setMessage('WARNING', array($this->getContext()->getI18N()->__("Admin Can not apply Leave. ", $args, 'messages')));
            $this->redirect('Leave/Leave');
        }
        $this->Sup = $leaveDao->LoadsubordinateData($SempNum);
        if ($this->Sup[0] !== null) {
            $this->ESupData = $leaveDao->LoadEmpData($this->Sup[0]['supervisorId']);
        } else {
            $this->setMessage('WARNING', array($this->getContext()->getI18N()->__("You don't have Superviser, Can't Apply Leave. ", $args, 'messages')));
            $this->redirect('Leave/Leave');
        }
        $knwdt = new LeaveApplication();
        if ($request->isMethod('post')) {//die(print_r($_POST));
            
            try {


                $sysConfinst = OrangeConfig::getInstance()->getSysConf();
                $sysConfs = new sysConf();

                if (array_key_exists('txtletter', $_FILES)) {
                    foreach ($_FILES as $file) {

                        if ($file['tmp_name'] > '') {
                            if (!in_array(end(explode(".", strtolower($file['name']))), $sysConfs->allowedExtensions)) {
                                throw new Exception("Invalid File Type", 8);
                            }
                        }
                    }
                } else {
                    throw new Exception("Invalid File Type", 6);
                }

                $fileName = $_FILES['txtletter']['name'];
                $tmpName = $_FILES['txtletter']['tmp_name'];
                $fileSize = $_FILES['txtletter']['size'];
                $fileType = $_FILES['txtletter']['type'];


                $maxFileSize2 = $sysConfs->getMaxFilesize();
                $sysConfinst = OrangeConfig::getInstance()->getSysConf();
                $sysConfs = new sysConf();
                //$maxsize=2097152;
                if ($fileSize > $maxFileSize2) {

                    throw new Exception("Maxfile size  Should be less than 2MB", 1);
                }
            } catch (Exception $e) {
                $errMsg = new CommonException($e->getMessage(), $e->getCode());
                $this->setMessage('WARNING', $errMsg->display());
                $this->redirect('Leave/Leave');
            }
            
            
            if (strlen($request->getParameter('txtEmpId'))) {
                $knwdt->setEmp_number(trim($request->getParameter('txtEmpId')));
            } else {
                $knwdt->setEmp_number(null);
            }
            if (strlen($request->getParameter('cmbbtype'))) {
                $knwdt->setLeave_type_id(trim($request->getParameter('cmbbtype')));
            } else {
                $knwdt->setLeave_type_id(null);
            }
            if (strlen($request->getParameter('txtLeaveStartDate'))) {
                $knwdt->setLeave_app_start_date(trim(LocaleUtil::getInstance()->convertToStandardDateFormat($request->getParameter('txtLeaveStartDate'))));
            } else {
                $knwdt->setLeave_app_start_date(null);
            }
            if (strlen($request->getParameter('txtLeaveEndDate'))) {
                $knwdt->setLeave_app_end_date(trim(LocaleUtil::getInstance()->convertToStandardDateFormat($request->getParameter('txtLeaveEndDate'))));
            } else {
                $knwdt->setLeave_app_end_date(null);
            }
            if (strlen($request->getParameter('txtAppEmpId'))) {
                $knwdt->setLeave_app_covemp_number(trim($request->getParameter('txtAppEmpId')));
            } else {
                $knwdt->setLeave_app_covemp_number(null);
            }
            if (strlen($request->getParameter('cmbLeaveReason'))) {
                $knwdt->setLeave_app_reason(trim($request->getParameter('cmbLeaveReason')));
            } else {
                $knwdt->setLeave_app_reason(null);
            }
            if (strlen($request->getParameter('txtComments'))) {
                $knwdt->setLeave_app_comment(trim($request->getParameter('txtComments')));
            } else {
                $knwdt->setLeave_app_comment(null);
            }
            if (strlen($request->getParameter('txtnofodays'))) {
                $knwdt->setLeave_app_workdays(trim($request->getParameter('txtnofodays')));
            } else {
                $knwdt->setLeave_app_workdays(null);
            }
            if (strlen($request->getParameter('txtApproved'))) {
                $knwdt->setLeave_app_status(trim($request->getParameter('txtApproved')));
            } else {
                $knwdt->setLeave_app_status(null);
            }
            $Approved = $request->getParameter('txtApproved');


            $knwdt->setLeave_app_applied_date(date("Y-m-d"));

            $userEntdays = $request->getParameter('txtnofodays');
            $year = date_parse(LocaleUtil::getInstance()->convertToStandardDateFormat($request->getParameter('txtLeaveStartDate')));
            if ($year == null) {
                $this->setMessage('WARNING', array($this->getContext()->getI18N()->__("Year Format has Change ", $args, 'messages')));
                $this->redirect('Leave/Leave');
            }

            try {
                
                $conn = Doctrine_Manager::getInstance()->connection();
                $conn->beginTransaction();
                $leavetypeconfig = $leaveDao->readLeaveTypeConfig($request->getParameter('cmbbtype'));
                if ($leavetypeconfig != null) {
                    $Entitleday = $leavetypeconfig->getLeave_type_entitle_days();
                }
                if($leavetypeconfig->getLeave_type_short_leave_flg()!=1){


                $readconfig = $leaveDao->readLeaveEntitlement($request->getParameter('txtEmpId'), $request->getParameter('cmbbtype'), $year['year']);
                if ($Approved == "2") {
                    $Taken = $userEntdays;
                } else {
                    $shedule = $userEntdays;
                }
                if ($readconfig[0] == null) {

                    $newEntReamin = $Entitleday - $userEntdays;


                    $leaveent = new LeaveEntitlement();
                    $leaveent->setEmp_number(trim($request->getParameter('txtEmpId')));
                    $leaveent->setLeave_type_id(trim($request->getParameter('cmbbtype')));
                    $leaveent->setLeave_ent_year($year['year']);
                    $leaveent->setLeave_ent_day($Entitleday);
                    $leaveent->setLeave_ent_sheduled($shedule);
                    $leaveent->setLeave_ent_remain($newEntReamin);
                    $leaveent->setLeave_ent_taken($Taken);
                    $leaveDao->saveEntitlement($leaveent);
                } else {
                    //-------------------------

                    if ($Approved == "2") {
                        $Entitletaken = $readconfig[0]['leave_ent_taken'] + $userEntdays;
                        $Entitleremain = $readconfig[0]['leave_ent_remain'] - $userEntdays;
                        $leaveDao->UpdateEntitlementLeaveNoShedule($request->getParameter('txtEmpId'), $request->getParameter('cmbbtype'), $year['year'], $Entitletaken, $Entitleremain);
                    } else {
                        $Entitleshedule = $readconfig[0]['leave_ent_sheduled'] + $userEntdays;
                        $Entitleremain = $readconfig[0]['leave_ent_remain'] - $userEntdays;
                        $leaveDao->UpdateEntitlementLeaveNoTaken($request->getParameter('txtEmpId'), $request->getParameter('cmbbtype'), $year['year'], $Entitleshedule, $Entitleremain);
                    }


                    //---------------
                }
}
                $leaveDao->saveLeave($knwdt);
                $leaveid = $leaveDao->findlastleave($request->getParameter('txtEmpId'), $request->getParameter('cmbbtype'), LocaleUtil::getInstance()->convertToStandardDateFormat($request->getParameter('txtLeaveStartDate')), LocaleUtil::getInstance()->convertToStandardDateFormat($request->getParameter('txtLeaveEndDate')), $request->getParameter('txtnofodays'));
                if ($request->getParameter('datedetails') != null) {
                    $row = explode(',', $request->getParameter('datedetails'));

                    foreach ($row as $data) {
                        $col = explode('|', $data);
                        $leavedetail = new LeaveDetail();
                        $leavedetail->setLeave_app_id($leaveid[0]['Max']);
                        $leavedetail->setLeave_app_applied_date($col[0]);
                        if ($col[1] == "1") {
                            $leavedetail->setLeave_dtl_amount(1);
                            $leavedetail->setLeave_dtl_type("1");
                            $leaveDao->saveLeavedeatil($leavedetail);
                        } elseif ($col[1] == "2") {
                            $leavedetail->setLeave_dtl_amount(0.5);
                            $leavedetail->setLeave_dtl_type("2");
                            $leaveDao->saveLeavedeatil($leavedetail);
                        } elseif ($col[1] == "3") {
                            $leavedetail->setLeave_dtl_amount(0.5);
                            $leavedetail->setLeave_dtl_type("3");
                            $leaveDao->saveLeavedeatil($leavedetail);
                        }
                    }
                }
//Attachment                
                
                $fp = fopen($tmpName, 'r');
                $content = fread($fp, filesize($tmpName));
                $content = addslashes($content);    
                if (strlen($content)) {

                $LeaveAttach = new LeaveAttachment();                     
                $LeaveAttach->setLeave_attach_filename($fileName);
                $LeaveAttach->setLeave_attach_type($fileType);
                $LeaveAttach->setLeave_attach_size($fileSize);
                $LeaveAttach->setLeave_attach_attachment($content);
                $lastLeaveid = $leaveDao->getLastLeaveAppID();
                $LeaveAttach->setLeave_app_id($lastLeaveid[0]['MAX']);
                $LeaveAttach->save();
                }
                
                
                $conn->commit();
                
//Email


        
        $Leavetypeconfig=$leaveDao->readLeaveTypeConfig($knwdt->leave_type_id);

        
        if($Leavetypeconfig->leave_type_need_approval_flg == 1){
            
            $directsupervisior=$leaveDao->getSupervisorData($knwdt->emp_number);
            $employee=$leaveDao->readEmployeeMaster($directsupervisior[0]['supervisorId']);
            
            
            $Message = "Hi ".$employee->emp_display_name.","."<br/>"."<br/>";
            $Message.= $knwdt->Employee->EmpTitle->title_name.".".$knwdt->Employee->emp_display_name;
            $Message.= " requested ";
            $Message.= $knwdt->LeaveType->leave_type_name;             
            $Message.=  " from ".$knwdt->leave_app_start_date;
            $Message.=  " to ".$knwdt->leave_app_end_date;
            $Message.=  ". No of leave days will be ".$knwdt->leave_app_workdays;
            $Message.= ".<br/>";            
            
            
            $Message.="Please approve the leave";
            $Subject = "Leave : ".$knwdt->LeaveType->leave_type_name.", ".$knwdt->Employee->emp_display_name ." - Pending Approval";

             
             $TO = $employee->EmpContact->con_off_email;
             $i=0;
            
        }else {
            
            $Message = "Hi,"."<br/>"."<br/>";
            $Message.= $knwdt->Employee->EmpTitle->title_name.".".$knwdt->Employee->emp_display_name;
            $Message.= " requested ";
            $Message.= $knwdt->LeaveType->leave_type_name;  
            if($knwdt->leave_type_id != 3){
            $Message.=  " from ".$knwdt->leave_app_start_date;
            $Message.=  " to ".$knwdt->leave_app_end_date;
            $Message.=  ". No of leave days will be ".$knwdt->leave_app_workdays;
            }
            $Message.= ".<br/>";
            
            $Subject = "Leave : ".$knwdt->LeaveType->leave_type_name.", ".$knwdt->Employee->emp_display_name ." - ".$knwdt->leave_app_start_date;
            $empsupemail=$leaveDao->getSuperEmail($knwdt->emp_number);
            $EmpSupervisorEmail = $empsupemail;
            $i=0;
            foreach($empsupemail as $row){
                $CC[]=$row['con_off_email'];
                $i++;
            }
            
            $TO = "staffmovements@icta.lk";
        }

        $Message.="<br/>"."<br/>";
        $Message.= "Reason In detail - "."<br/>";
        $Message.= $knwdt->leave_app_comment;
        $Message.= "<br/>"."<br/>"."Thanks."."<br/>";

        $EmpEmail = $knwdt->Employee->EmpContact->con_off_email;


        $CC[$i] ="commonhrm@icta.lk";
        $CC[$i+1] =$EmpEmail;


        //--End Message
        //die(print_r($EmpSupervisorEmail));
        $defaultDao = new DefaultDao();
        $t=$defaultDao->sendEmail($Message,$TO,$CC,$Subject);
                                
                
//End Emai                
        } catch (Doctrine_Connection_Exception $e) {
            $conn->rollback();
            $errMsg = new CommonException($e->getPortableMessage(), $e->getPortableCode());
            $this->setMessage('WARNING', $errMsg->display());
            $this->redirect('Leave/Leave');
        } catch (sfStopException $e) {

        } catch (Exception $e) {
            $conn->rollback();
            $errMsg = new CommonException($e->getMessage(), $e->getCode());
            $this->setMessage('WARNING', $errMsg->display());
            $this->redirect('Leave/Leave');
        }
            $this->setMessage('SUCCESS', array($this->getContext()->getI18N()->__("Successfully Added", $args, 'messages')));
            $this->redirect('Leave/Leave?eid=' . $request->getParameter('txtEmpId'));
        }
    }

    public function executeSaveLeaveuser(sfWebRequest $request) {

        $this->Culture = $this->getUser()->getCulture();
        $leaveDao = new LeaveDao();
        $this->loadbtype = $leaveDao->getLeaveTypeload();
        $this->LeaveHoly = $leaveDao->readLeaveHolyDay();
        $this->LeaveHoly = array();
        if($LeaveHolyDay){
        foreach ($LeaveHolyDay as $row) {
            $LeaveHoly[] = $row['leave_holiday_date'];
        }
        }
        $this->LeaveHD = $LeaveHoly;

        $knwdt = new LeaveApplication();
        if ($request->isMethod('post')) {//die(print_r($_FILES));
            
                        
            try {


                $sysConfinst = OrangeConfig::getInstance()->getSysConf();
                $sysConfs = new sysConf();

                if (array_key_exists('txtletter', $_FILES)) {
                    foreach ($_FILES as $file) {

                        if ($file['tmp_name'] > '') {
                            if (!in_array(end(explode(".", strtolower($file['name']))), $sysConfs->allowedExtensions)) {
                                throw new Exception("Invalid File Type", 8);
                            }
                        }
                    }
                } else {
                    throw new Exception("Invalid File Type", 6);
                }

                $fileName = $_FILES['txtletter']['name'];
                $tmpName = $_FILES['txtletter']['tmp_name'];
                $fileSize = $_FILES['txtletter']['size'];
                $fileType = $_FILES['txtletter']['type'];


                $maxFileSize2 = $sysConfs->getMaxFilesize();
                $sysConfinst = OrangeConfig::getInstance()->getSysConf();
                $sysConfs = new sysConf();
                //$maxsize=2097152;
                if ($fileSize > $maxFileSize2) {

                    throw new Exception("Maxfile size  Should be less than 2MB", 1);
                }
            } catch (Exception $e) {
                $errMsg = new CommonException($e->getMessage(), $e->getCode());
                $this->setMessage('WARNING', $errMsg->display());
                $this->redirect('Leave/Leave');
            }
            
            
            if (strlen($request->getParameter('txtEmpId'))) {
                $knwdt->setEmp_number(trim($request->getParameter('txtEmpId')));
            } else {
                $knwdt->setEmp_number(null);
            }
            if (strlen($request->getParameter('cmbbtype'))) {
                $knwdt->setLeave_type_id(trim($request->getParameter('cmbbtype')));
            } else {
                $knwdt->setLeave_type_id(null);
            }
            if (strlen($request->getParameter('txtLeaveStartDate'))) {
                $knwdt->setLeave_app_start_date(trim(LocaleUtil::getInstance()->convertToStandardDateFormat($request->getParameter('txtLeaveStartDate'))));
            } else {
                $knwdt->setLeave_app_start_date(null);
            }
            if (strlen($request->getParameter('txtLeaveEndDate'))) {
                $knwdt->setLeave_app_end_date(trim(LocaleUtil::getInstance()->convertToStandardDateFormat($request->getParameter('txtLeaveEndDate'))));
            } else {
                $knwdt->setLeave_app_end_date(null);
            }
            if (strlen($request->getParameter('txtAppEmpId'))) {
                $knwdt->setLeave_app_covemp_number(trim($request->getParameter('txtAppEmpId')));
            } else {
                $knwdt->setLeave_app_covemp_number(null);
            }
            if (strlen($request->getParameter('cmbLeaveReason'))) {
                $knwdt->setLeave_app_reason(trim($request->getParameter('cmbLeaveReason')));
            } else {
                $knwdt->setLeave_app_reason(null);
            }
            if (strlen($request->getParameter('txtComments'))) {
                $knwdt->setLeave_app_comment(trim($request->getParameter('txtComments')));
            } else {
                $knwdt->setLeave_app_comment(null);
            }
            if (strlen($request->getParameter('txtnofodays'))) {
                $knwdt->setLeave_app_workdays(trim($request->getParameter('txtnofodays')));
            } else {
                $knwdt->setLeave_app_workdays(null);
            }
            if (strlen($request->getParameter('txtApproved'))) {
                $knwdt->setLeave_app_status(trim($request->getParameter('txtApproved')));
            } else {
                $knwdt->setLeave_app_status(null);
            }
            $Approved = $request->getParameter('txtApproved');


            $knwdt->setLeave_app_applied_date(date("Y-m-d"));

            $userEntdays = $request->getParameter('txtnofodays');
            $year = date_parse(LocaleUtil::getInstance()->convertToStandardDateFormat($request->getParameter('txtLeaveStartDate')));
            if ($year == null) {
                $this->setMessage('WARNING', array($this->getContext()->getI18N()->__("Year Format has Change ", $args, 'messages')));
                $this->redirect('Leave/Leave');
            }

            try {
                $conn = Doctrine_Manager::getInstance()->connection();
                $conn->beginTransaction();
                $leavetypeconfig = $leaveDao->readLeaveTypeConfig($request->getParameter('cmbbtype'));
                if ($leavetypeconfig != null) {
                    $Entitleday = $leavetypeconfig->getLeave_type_entitle_days();
                }
 if($leavetypeconfig->getLeave_type_short_leave_flg()!=1){
                $readconfig = $leaveDao->readLeaveEntitlement($request->getParameter('txtEmpId'), $request->getParameter('cmbbtype'), $year['year']);
                if ($Approved == "2") {
                    $Taken = $userEntdays;
                } else {
                    $shedule = $userEntdays;
                }
                if ($readconfig[0] == null) {

                    $newEntReamin = $Entitleday - $userEntdays;


                    $leaveent = new LeaveEntitlement();
                    $leaveent->setEmp_number(trim($request->getParameter('txtEmpId')));
                    $leaveent->setLeave_type_id(trim($request->getParameter('cmbbtype')));
                    $leaveent->setLeave_ent_year($year['year']);
                    $leaveent->setLeave_ent_day($Entitleday);
                    $leaveent->setLeave_ent_sheduled($shedule);
                    $leaveent->setLeave_ent_remain($newEntReamin);
                    $leaveent->setLeave_ent_taken($Taken);
                    $leaveDao->saveEntitlement($leaveent);
                } else {
                    //-------------------------
                    
                    if ($Approved == "2") {
                        $Entitletaken = $readconfig[0]['leave_ent_taken'] + $userEntdays;
                        $Entitleremain = $readconfig[0]['leave_ent_remain'] - $userEntdays;
                        $leaveDao->UpdateEntitlementLeaveNoShedule($request->getParameter('txtEmpId'), $request->getParameter('cmbbtype'), $year['year'], $Entitletaken, $Entitleremain);
                    } else {
                        $Entitleshedule = $readconfig[0]['leave_ent_sheduled'] + $userEntdays;
                        $Entitleremain = $readconfig[0]['leave_ent_remain'] - $userEntdays;
                        $leaveDao->UpdateEntitlementLeaveNoTaken($request->getParameter('txtEmpId'), $request->getParameter('cmbbtype'), $year['year'], $Entitleshedule, $Entitleremain);
                    }


                    //---------------
                }
 }
                $leaveDao->saveLeave($knwdt);
                $leaveid = $leaveDao->findlastleave($request->getParameter('txtEmpId'), $request->getParameter('cmbbtype'), LocaleUtil::getInstance()->convertToStandardDateFormat($request->getParameter('txtLeaveStartDate')), LocaleUtil::getInstance()->convertToStandardDateFormat($request->getParameter('txtLeaveEndDate')), $request->getParameter('txtnofodays'));
                if ($request->getParameter('datedetails') != null) {
                    $row = explode(',', $request->getParameter('datedetails'));

                    foreach ($row as $data) {
                        $col = explode('|', $data);
                        $leavedetail = new LeaveDetail();
                        $leavedetail->setLeave_app_id($leaveid[0]['Max']);
                        $leavedetail->setLeave_app_applied_date($col[0]);
                        if ($col[1] == "1") {
                            $leavedetail->setLeave_dtl_amount(1);
                            $leavedetail->setLeave_dtl_type("1");
                            $leaveDao->saveLeavedeatil($leavedetail);
                        } elseif ($col[1] == "2") {
                            $leavedetail->setLeave_dtl_amount(0.5);
                            $leavedetail->setLeave_dtl_type("2");
                            $leaveDao->saveLeavedeatil($leavedetail);
                        } elseif ($col[1] == "3") {
                            $leavedetail->setLeave_dtl_amount(0.5);
                            $leavedetail->setLeave_dtl_type("3");
                            $leaveDao->saveLeavedeatil($leavedetail);
                        }
                    }
                }
                
                //Attachment                
                
                $fp = fopen($tmpName, 'r');
                $content = fread($fp, filesize($tmpName));
                $content = addslashes($content);    
                if (strlen($content)) {

                $LeaveAttach = new LeaveAttachment();                     
                $LeaveAttach->setLeave_attach_filename($fileName);
                $LeaveAttach->setLeave_attach_type($fileType);
                $LeaveAttach->setLeave_attach_size($fileSize);
                $LeaveAttach->setLeave_attach_attachment($content);
                $lastLeaveid = $leaveDao->getLastLeaveAppID();
                $LeaveAttach->setLeave_app_id($lastLeaveid[0]['MAX']);
                $LeaveAttach->save();
                }
                
                
                $conn->commit();
//Email


        
        $Leavetypeconfig=$leaveDao->readLeaveTypeConfig($knwdt->leave_type_id);

        
        if($Leavetypeconfig->leave_type_need_approval_flg == 1){
            
            $directsupervisior=$leaveDao->getSupervisorData($knwdt->emp_number);
            $employee=$leaveDao->readEmployeeMaster($directsupervisior[0]['supervisorId']);
            
            
            $Message = "Hi ".$employee->emp_display_name.","."<br/>"."<br/>";
            $Message.= $knwdt->Employee->EmpTitle->title_name.".".$knwdt->Employee->emp_display_name;
            $Message.= " requested ";
            $Message.= $knwdt->LeaveType->leave_type_name;             
            $Message.=  " from ".$knwdt->leave_app_start_date;
            $Message.=  " to ".$knwdt->leave_app_end_date;
            $Message.=  ". No of leave days will be ".$knwdt->leave_app_workdays;
            $Message.= ".<br/>";            
            
            
            $Message.="Please approve this leave";
            $Subject = "Leave : ".$knwdt->LeaveType->leave_type_name.", ".$knwdt->Employee->emp_display_name ." - Pending Approval";

             
             $TO = $employee->EmpContact->con_off_email;
             $i=0;
            
        }else {
            
            $Message = "Hi,"."<br/>"."<br/>";
            $Message.= $knwdt->Employee->EmpTitle->title_name.".".$knwdt->Employee->emp_display_name;
            $Message.= " requested ";
            $Message.= $knwdt->LeaveType->leave_type_name; 
            if($knwdt->leave_type_id != 3){
            $Message.=  " from ".$knwdt->leave_app_start_date;
            $Message.=  " to ".$knwdt->leave_app_end_date;
            $Message.=  ". No of leave days will be ".$knwdt->leave_app_workdays;
            }
            $Message.= ".<br/>";
            
            $Subject = "Leave : ".$knwdt->LeaveType->leave_type_name.", ".$knwdt->Employee->emp_display_name ." - ".$knwdt->leave_app_start_date;
            $empsupemail=$leaveDao->getSuperEmail($knwdt->emp_number);
            $EmpSupervisorEmail = $empsupemail;
            $i=0;
            foreach($empsupemail as $row){
                $CC[]=$row['con_off_email'];
                $i++;
            }
            
            $TO = "staffmovements@icta.lk";
        }

        $Message.="<br/>"."<br/>";
        $Message.= "Reason In detail - "."<br/>";
        $Message.= $knwdt->leave_app_comment;
        $Message.= "<br/>"."<br/>"."Thanks."."<br/>";

        $EmpEmail = $knwdt->Employee->EmpContact->con_off_email;
//die(print_r($Message));

        $CC[$i] ="commonhrm@icta.lk";
        $CC[$i+1] =$EmpEmail;


        //--End Message
        //die(print_r($EmpSupervisorEmail));
        $defaultDao = new DefaultDao();
        $t=$defaultDao->sendEmail($Message,$TO,$CC,$Subject);
                                
                
//End Emai                     
                
                
            } catch (Doctrine_Connection_Exception $e) {
                $conn->rollBack();
                $errMsg = new CommonException($e->getPortableMessage(), $e->getPortableCode());
                $this->setMessage('WARNING', $errMsg->display());
                $this->redirect('Leave/Leave');
            } catch (Exception $e) {
                $conn->rollBack();
                $errMsg = new CommonException($e->getMessage(), $e->getCode());
                $this->setMessage('WARNING', $errMsg->display());
                $this->redirect('Leave/Leave?eid=');
            }
            $this->setMessage('SUCCESS', array($this->getContext()->getI18N()->__("Successfully Added", $args, 'messages')));
            $this->redirect('Leave/Leave?eid=' . $request->getParameter('txtEmpId'));
        }
    }

    public function executeUpdateLeave(sfWebRequest $request) {

        //Table Lock code is Open

        if (!strlen($request->getParameter('lock'))) {
            $this->lockMode = 0;
        } else {
            $this->lockMode = $request->getParameter('lock');
        }
        $transPid = $request->getParameter('id');
        if (isset($this->lockMode)) {
            if ($this->lockMode == 1) {

                $conHandler = new ConcurrencyHandler();

                $recordLocked = $conHandler->setTableLock('hs_hr_leave_application', array($transPid), 1);

                if ($recordLocked) {
                    // Display page in edit mode
                    $this->lockMode = 1;
                } else {
                    $this->setMessage('WARNING', array($this->getContext()->getI18N()->__('Can not update. Record locked by another user.', $args, 'messages')), false);
                    $this->lockMode = 0;
                }
            } else if ($this->lockMode == 0) {//dir("hgf");
                $conHandler = new ConcurrencyHandler();
                $recordLocked = $conHandler->resetTableLock('hs_hr_leave_application', array($transPid), 1);
                $this->lockMode = 0;
            }
        }

        //Table lock code is closed
        $leaveDao = new LeaveDao();
        $knwdt = $leaveDao->getLeaveload($request->getParameter('id'));

        if (!$knwdt) {
            $this->setMessage('WARNING', array($this->getContext()->getI18N()->__('Can not update. Record has been Deleted', $args, 'messages')));
            $this->redirect('Leave/Leave');
        }
        if ($knwdt->getLeave_app_status() == 0) {
            $this->setMessage('WARNING', array($this->getContext()->getI18N()->__('Canceled Leave', $args, 'messages')));
            $this->show = 1;
        } else {
            $this->show = 0;
        }



        //$SempNum = $_SESSION['empNumber'];
        $SempNum = $knwdt->getEmp_number();
        $this->Culture = $this->getUser()->getCulture();


        $this->loadbtype = $leaveDao->getLeaveTypeload();
        $this->EData = $leaveDao->LoadEmpData($SempNum);
        if ($this->EData[0]->getEmp_number() == null) {
            $this->setMessage('WARNING', array($this->getContext()->getI18N()->__("You don't have Employee Number, So not an Employee, ", $args, 'messages')));
            $this->redirect('Leave/Leave');
        }
        $this->Sup = $leaveDao->LoadsubordinateData($SempNum);
        if ($this->Sup[0] !== null) {
            $this->ESupData = $leaveDao->LoadEmpData($this->Sup[0]['supervisorId']);
        } else {
            $this->setMessage('WARNING', array($this->getContext()->getI18N()->__("You don't have Superviser, Can't Apply Leave. ", $args, 'messages')));
            $this->redirect('Leave/Leave');
        }


        $this->Entitle = $knwdt;
        $this->ECov = $leaveDao->LoadEmpData($this->Entitle->getLeave_app_covemp_number());
        $Approved = $knwdt->getLeave_app_status();
        if($Approved != "2"){
        if ($request->isMethod('post')) {

            if (strlen($request->getParameter('txtEmpId'))) {
                $knwdt->setEmp_number(trim($request->getParameter('txtEmpId')));
            } else {
                $knwdt->setEmp_number(null);
            }
            if (strlen($request->getParameter('cmbbtype'))) {
                $knwdt->setLeave_type_id(trim($request->getParameter('cmbbtype')));
            } else {
                $knwdt->setLeave_type_id(null);
            }
            if (strlen($request->getParameter('txtLeaveStartDate'))) {
                $knwdt->setLeave_app_start_date(trim(LocaleUtil::getInstance()->convertToStandardDateFormat($request->getParameter('txtLeaveStartDate'))));
            } else {
                $knwdt->setLeave_app_start_date(null);
            }
            if (strlen($request->getParameter('txtLeaveEndDate'))) {
                $knwdt->setLeave_app_end_date(trim(LocaleUtil::getInstance()->convertToStandardDateFormat($request->getParameter('txtLeaveEndDate'))));
            } else {
                $knwdt->setLeave_app_end_date(null);
            }
            if (strlen($request->getParameter('txtAppEmpId'))) {
                $knwdt->setLeave_app_covemp_number(trim($request->getParameter('txtAppEmpId')));
            } else {
                $knwdt->setLeave_app_covemp_number(null);
            }
            if (strlen($request->getParameter('cmbLeaveReason'))) {
                $knwdt->setLeave_app_reason(trim($request->getParameter('cmbLeaveReason')));
            } else {
                $knwdt->setLeave_app_reason(null);
            }
            if (strlen($request->getParameter('txtComments'))) {
                $knwdt->setLeave_app_comment(trim($request->getParameter('txtComments')));
            } else {
                $knwdt->setLeave_app_comment(null);
            }
            if (strlen($request->getParameter('txtnofodays'))) {
                $knwdt->setLeave_app_workdays(trim($request->getParameter('txtnofodays')));
            } else {
                $knwdt->setLeave_app_workdays(null);
            }

            $knwdt->setLeave_app_applied_date(date("Y-m-d"));
            $knwdt->setLeave_app_status('0');

            $userEntdays = $request->getParameter('txtnofodays');
            $year = date_parse(LocaleUtil::getInstance()->convertToStandardDateFormat($request->getParameter('txtLeaveStartDate')));
            if ($year == null) {
                $this->setMessage('WARNING', array($this->getContext()->getI18N()->__("Year Format has Change ", $args, 'messages')));
                $this->redirect('Leave/Leave');
            }
            try {

                $conn = Doctrine_Manager::getInstance()->connection();
                $conn->beginTransaction();
                $leavetypeconfig = $leaveDao->readLeaveTypeConfig($request->getParameter('cmbbtype'));
                if ($leavetypeconfig != null) {
                    $Entitleday = $leavetypeconfig->getLeave_type_entitle_days();
                }

                $readconfig = $leaveDao->readLeaveEntitlement($request->getParameter('txtEmpId'), $request->getParameter('cmbbtype'), $year['year']);

                if($leavetypeconfig->leave_type_short_leave_flg!= 1){
                if ($readconfig[0] == null) { 
                    $newEntReamin = $Entitleday - $userEntdays;
                    if($newEntReamin<= 0){
                        $newEntReamin = 0;
                    }

                    $leaveent = new LeaveEntitlement();
                    $leaveent->setEmp_number(trim($request->getParameter('txtEmpId')));
                    $leaveent->setLeave_type_id(trim($request->getParameter('cmbbtype')));
                    $leaveent->setLeave_ent_year($year['year']);
                    $leaveent->setLeave_ent_day($Entitleday);
                    $leaveent->setLeave_ent_sheduled($userEntdays);
                    $leaveent->setLeave_ent_remain($newEntReamin);
                    $leaveDao->saveEntitlement($leaveent);
                } else { 
                        
                    if($userEntdays <= 0){
                            $userEntdays = 0;
                       }
                    
                    if ($Approved == "2") {

                        $Entitletaken = $readconfig[0]['leave_ent_taken'] - $userEntdays;
                        $Entitleremain = $readconfig[0]['leave_ent_remain'] + $userEntdays;
                        $leaveDao->UpdateEntitlementLeaveNoShedule($request->getParameter('txtEmpId'), $request->getParameter('cmbbtype'), $year['year'], $Entitletaken, $Entitleremain);
                    } else {
                        $Entitleshedule = $readconfig[0]['leave_ent_sheduled'] - $userEntdays;
                        $Entitleremain = $readconfig[0]['leave_ent_remain'] + $userEntdays;
                        $leaveDao->UpdateEntitlementLeaveNoTaken($request->getParameter('txtEmpId'), $request->getParameter('cmbbtype'), $year['year'], $Entitleshedule, $Entitleremain);
                    }
                }
                }

                $leaveDao->saveLeave($knwdt);
                $conn->commit();
                if($knwdt->leave_app_status=="0"){
//Email        
            
            $directsupervisior=$leaveDao->getSupervisorData($knwdt->emp_number);
            $employee=$leaveDao->readEmployeeMaster($directsupervisior[0]['supervisorId']);

            $Message = "Hi ".$employee->emp_display_name.","."<br/>"."<br/>";
            $Message.= "Leave ";           
            $Message.=  " from ".$knwdt->leave_app_start_date;
            $Message.=  " to ".$knwdt->leave_app_end_date;  
            $Message.=" has been canceled by the applicant.";
            $Subject = "Leave : ".$knwdt->LeaveType->leave_type_name.", ".$knwdt->Employee->emp_display_name ." - Canceled";
            $TO = $employee->EmpContact->con_off_email;

	    //$Message.=" ".$employee->emp_display_name.".";
            
            //$Subject = "Leave : ".$knwdt->Employee->emp_display_name ." - Pending Approval";
            

            $CC[0] ="commonhrm@icta.lk";
            $CC[1] = $knwdt->Employee->EmpContact->con_off_email;
            $CC[2] ="staffmovements@icta.lk";

            //die(print_r($CC)); 

 

        $Message.= "<br/>"."<br/>"."Thanks."."<br/>";





  
        $defaultDao = new DefaultDao();
        $t=$defaultDao->sendEmail($Message,$TO,$CC,$Subject);

        //--End Message                    
                }
                
            } catch (Doctrine_Connection_Exception $e) {
                $conn->rollBack();
                $errMsg = new CommonException($e->getPortableMessage(), $e->getPortableCode());
                $this->setMessage('WARNING', $errMsg->display());
                $this->redirect('Leave/UpdateLeave?id=' . $knwdt->getLeave_app_id() . '&lock=0');
            } catch (Exception $e) {
                $conn->rollBack();
                $errMsg = new CommonException($e->getMessage(), $e->getCode());
                $this->setMessage('WARNING', $errMsg->display());
                $this->redirect('Leave/UpdateLeave?id=' . $knwdt->getLeave_app_id() . '&lock=0');
            }
            $this->setMessage('SUCCESS', array($this->getContext()->getI18N()->__("Successfully Updated", $args, 'messages')));
            $this->redirect('Leave/UpdateLeave?id=' . $knwdt->getLeave_app_id() . '&lock=0');
        }
        }else{
            $this->setMessage('WARNING', array($this->getContext()->getI18N()->__('Can not Edit this Leave because of Approved.', $args, 'messages')));
            $this->show = 1;
        }
    }

    public function executeDeleteLeave(sfWebRequest $request) {

        if (count($request->getParameter('chkLocID')) > 0) {

            $LeaveDao = new LeaveDao();
            try {
                $conn = Doctrine_Manager::getInstance()->connection();
                $conn->beginTransaction();
                $ids = array();
                $ids = $request->getParameter('chkLocID');

                $countArr = array();
                $saveArr = array();
                for ($i = 0; $i < count($ids); $i++) {
                    $conHandler = new ConcurrencyHandler();
                    $isRecordLocked = $conHandler->isTableLocked('txtEnTakenDays', array($ids[$i]), 1);
                    if ($isRecordLocked) {

                        $countArr = $ids[$i];
                    } else {
                        $saveArr = $ids[$i];
                        $LeaveDao->deleteEntitlement($ids[$i]);
                        $conHandler->resetTableLock('txtEnTakenDays', array($ids[$i]), 1);
                    }
                }

                $conn->commit();
            } catch (Doctrine_Connection_Exception $e) {

                $errMsg = new CommonException($e->getPortableMessage(), $e->getPortableCode());
                $this->setMessage('WARNING', $errMsg->display());
                $this->redirect('Leave/Entitlement');
            } catch (Exception $e) {
                $conn->rollBack();
                $errMsg = new CommonException($e->getMessage(), $e->getCode());
                $this->setMessage('WARNING', $errMsg->display());
                $this->redirect('Leave/Entitlement');
            }
            if (count($saveArr) > 0 && count($countArr) == 0) {
                $this->setMessage('SUCCESS', array($this->getContext()->getI18N()->__("Successfully Deleted", $args, 'messages')));
            } elseif (count($saveArr) > 0 && count($countArr) > 0) {
                $this->setMessage('WARNING', array($this->getContext()->getI18N()->__("Some records are can not be deleted as them  Locked by another user ", $args, 'messages')));
            } elseif (count($saveArr) == 0 && count($countArr) > 0) {
                $this->setMessage('WARNING', array($this->getContext()->getI18N()->__("Can not delete as them  Locked by another user ", $args, 'messages')));
            }
        } else {
            $this->setMessage('NOTICE', array('Select at least one record to delete'));
        }
        $this->redirect('Leave/Entitlement');
    }

    public function executeLeaveSearch(sfWebRequest $request) { 
        
        

        
        $this->Culture = $this->getUser()->getCulture();
        $this->sorter = new ListSorter('Leave', 'LeaveSearch', $this->getUser(), array('a.leave_app_id', ListSorter::ASCENDING));
        $this->sorter->setSort(array($request->getParameter('sort'), $request->getParameter('order')));

        $leaveDao = new LeaveDao();
        $this->leaveDao = $leaveDao;
        $this->loadbtype = $leaveDao->LeavetypeList();

        $this->sort = ($request->getParameter('sort') == '') ? 'a.leave_app_id' : $request->getParameter('sort');
        $this->order = ($request->getParameter('order') == '') ? 'ASC' : $request->getParameter('order');
        $encrypt = new EncryptionHandler();
        if(strlen($request->getParameter('Employee'))){
        $this->EmployeeSub = (($encrypt->decrypt($request->getParameter('Employee')) == null)) ? $encrypt->decrypt($request->getParameter('Employee')) : $encrypt->decrypt($request->getParameter('Employee'));
        }else{
          $this->EmployeeSub = null;
        }
        $this->chkAll = ($request->getParameter('chkAll') == null) ? $request->getParameter('chkAll') : $_POST['chkAll'];
        $this->chkPending = ($request->getParameter('chkPending') == null) ? $request->getParameter('chkPending') : $_POST['chkPending'];
        $this->chkApproved = ($request->getParameter('chkApproved') == null) ? $request->getParameter('chkApproved') : $_POST['chkApproved'];
        $this->chkRejected = ($request->getParameter('chkRejected') == null) ? $request->getParameter('chkRejected') : $_POST['chkRejected'];
        $this->chkCanceled = ($request->getParameter('chkCanceled') == null) ? $request->getParameter('chkCanceled') : $_POST['chkCanceled'];
        $this->chkTaken = ($request->getParameter('chkTaken') == null) ? $request->getParameter('chkTaken') : $_POST['chkTaken'];
        
        $this->EmployeeName = ($request->getParameter('txtEmployeeName') == null) ? $request->getParameter('txtEmployeeName') : $_POST['txtEmployeeName'];
        $this->searchMode = ($request->getParameter('txttdate') == null) ? $request->getParameter('searchMode') : LocaleUtil::getInstance()->convertToStandardDateFormat($_POST['txttdate']);
        $this->searchValue = ($request->getParameter('txtfdate') == null) ? $request->getParameter('searchValue') : LocaleUtil::getInstance()->convertToStandardDateFormat($_POST['txtfdate']);
            $this->emp = ($request->getParameter('txtEmpId') == null) ? $request->getParameter('emp') : $_POST['txtEmpId'];
        $this->type = ($request->getParameter('abc') == null) ? $request->getParameter('type') : $_POST['abc'];
        $this->post = ($_POST == null) ? $request->getParameter('post') : $_POST;

        if($request->getParameter('emp')!= null && $request->getParameter('txtEmpId') == null ){
            //$pieces = explode("_", $request->getParameter('emp'));
            $pieces=str_replace("_",",",$request->getParameter('emp'));
            $this->emp =$pieces;

        }
//        die(print_r($this->emp));

        $res = $leaveDao->viewall($this->searchValue, $this->searchMode, $request->getParameter('page'), $this->emp, $this->type, $this->sort, $this->order, $this->EmployeeSub,$this->chkAll,$this->chkPending,$this->chkApproved,$this->chkRejected,$this->chkCanceled,$this->chkTaken);
        $this->view = $res['data'];
        $this->pglay = $res['pglay'];
        $this->pglay->setTemplate('<a href="{%url}">{%page}</a>');
        $this->pglay->setSelectedTemplate('{%page}');
    }

    public function executeAjaxTableLock(sfWebRequest $request) {


        if (!strlen($request->getParameter('lock'))) {
            $this->lockMode = 0;
        } else {
            $this->lockMode = $request->getParameter('lock');
        }
        $leaveid = $request->getParameter('leaveid');

        if (isset($this->lockMode)) {
            if ($this->lockMode == 1) {

                $conHandler = new ConcurrencyHandler();

                $recordLocked = $conHandler->setTableLock('hs_hr_leave_application', array($leaveid), 1);

                if ($recordLocked) {
                    $this->lockMode = 1;
                    $leaveDao = new LeaveDao();
                    $leave = $leaveDao->getLeaveload($leaveid);
                    $this->status = $leave->getLeave_app_status();
                    $this->comment = $leave->getLeave_app_comment();
                } else {

                    $this->lockMode = 0;
                }
            } else if ($this->lockMode == 0) {

                $conHandler = new ConcurrencyHandler();
                $recordLocked = $conHandler->resetTableLock('hs_hr_leave_application', array($leaveid), 1);
                $this->lockMode = 0;
            }
            echo json_encode(array("status" => $this->status, "comment" => $this->comment, "lockMode" => $this->lockMode));
            die;
        }
    }

    public function executeSaveLeaveApprove(sfWebRequest $request) {
        $leaveDao = new LeaveDao();
        $leaveid = $request->getParameter('leaveid');
        $status = $request->getParameter('status');
        $comment = $request->getParameter('comment');
        try {
            $LeaveRecord = $leaveDao->getLeaveload($leaveid);
            $year = date_parse($LeaveRecord->getLeave_app_start_date());
            $empnumber = $LeaveRecord->getEmp_number();
            $Leavtype = $LeaveRecord->getLeave_type_id();
            
            $LeaveConfig = $leaveDao->readLeaveTypeConfig($Leavtype);
            //$LeaveConfig->leave_type_need_approval_flg;

            $LeaveEntitlement = $leaveDao->readLeaveEntitlement($empnumber, $Leavtype, $year['year']);
            $taken = $LeaveEntitlement[0]['leave_ent_taken'];
            $remain = $LeaveEntitlement[0]['leave_ent_remain'];
            $shedule = $LeaveEntitlement[0]['leave_ent_sheduled'];
            //Core of Leave
            // 0-Cancel , 1-Pending,  2-Approved,  3-Reject
            $Prestatus = $LeaveRecord->getLeave_app_status();
            $Leavedays = $LeaveRecord->getLeave_app_workdays();
            $Newstatus = $status;

            $conn = Doctrine_Manager::getInstance()->connection();
            $conn->beginTransaction();
            
            if($LeaveConfig->leave_type_short_leave_flg == "1" ){
                if (($Prestatus == 2) && ($Newstatus == 0)) {
                //$remain+=$Leavedays;
                //$taken-=$Leavedays;
                //$shedule = null;
                //$leaveDao->UpdateEntitlementTakenPendingRemain($empnumber, $Leavtype, $year['year'], $taken, $shedule, $remain);
                }
            }else{

            if (($Prestatus == 0) && ($Newstatus == 1)) {
                $remain-=$Leavedays;
                $shedule+=$Leavedays;
                $taken = null ; 
                $leaveDao->UpdateEntitlementTakenPendingRemain($empnumber, $Leavtype, $year['year'], $taken, $shedule, $remain);
                //$leaveDao->UpdateEntitlementLeaveNoTaken($empnumber, $Leavtype, $year['year'], $shedule, $remain);
            } else if (($Prestatus == 0) && ($Newstatus == 2)) {
                $remain-=$Leavedays;
                $taken+=$Leavedays;
                $shedule = null;
                $leaveDao->UpdateEntitlementTakenPendingRemain($empnumber, $Leavtype, $year['year'], $taken, $shedule, $remain);
                //$leaveDao->UpdateEntitlementLeaveNoShedule($empnumber, $Leavtype, $year['year'], $taken, $remain);
            
            } else if (($Prestatus == 0) && ($Newstatus == 3)) {
                $remain+=$Leavedays;
                $taken-=$Leavedays;
                $shedule = null;
                $leaveDao->UpdateEntitlementTakenPendingRemain($empnumber, $Leavtype, $year['year'], $taken, $shedule, $remain);    
            } else if (($Prestatus == 1) && ($Newstatus == 0)) {
                $remain+=$Leavedays;
                $shedule-=$Leavedays;
                $taken = null ; 
                $leaveDao->UpdateEntitlementTakenPendingRemain($empnumber, $Leavtype, $year['year'], $taken, $shedule, $remain);
                //$leaveDao->UpdateEntitlementLeaveNoTaken($empnumber, $Leavtype, $year['year'], $shedule, $remain);
            } else if (($Prestatus == 1) && ($Newstatus == 2)) {
                $shedule-=$Leavedays;
                $taken+=$Leavedays;
                $remain = null;
                $leaveDao->UpdateEntitlementTakenPendingRemain($empnumber, $Leavtype, $year['year'], $taken, $shedule, $remain);
                //$leaveDao->UpdateEntitlementLeaveNoRemain($empnumber, $Leavtype, $year['year'], $taken, $shedule);
            } else if (($Prestatus == 1) && ($Newstatus == 3)) {
                $remain+=$Leavedays;
                $shedule-=$Leavedays;
                $taken = null;
                $leaveDao->UpdateEntitlementTakenPendingRemain($empnumber, $Leavtype, $year['year'], $taken, $shedule, $remain);
                //$leaveDao->UpdateEntitlementLeaveNoTaken($empnumber, $Leavtype, $year['year'], $shedule, $remain);
            } else if (($Prestatus == 2) && ($Newstatus == 0)) {
                $remain+=$Leavedays;
                $taken-=$Leavedays;
                $shedule = null;
                $leaveDao->UpdateEntitlementTakenPendingRemain($empnumber, $Leavtype, $year['year'], $taken, $shedule, $remain);
                //$leaveDao->UpdateEntitlementLeaveNoShedule($empnumber, $Leavtype, $year['year'], $taken, $remain);
            } else if (($Prestatus == 2) && ($Newstatus == 1)) {
                $shedule+=$Leavedays;
                $taken-=$Leavedays;
                $remain = null;
                $leaveDao->UpdateEntitlementTakenPendingRemain($empnumber, $Leavtype, $year['year'], $taken, $shedule, $remain);
                //$leaveDao->UpdateEntitlementLeaveNoRemain($empnumber, $Leavtype, $year['year'], $taken, $shedule);
            } else if (($Prestatus == 2) && ($Newstatus == 3)) {
                $remain+=$Leavedays;
                $taken-=$Leavedays;
                $shedule = null;
                $leaveDao->UpdateEntitlementTakenPendingRemain($empnumber, $Leavtype, $year['year'], $taken, $shedule, $remain);
                //$leaveDao->UpdateEntitlementLeaveNoShedule($empnumber, $Leavtype, $year['year'], $taken, $remain);
//           } else if (($Prestatus == 3) && ($Newstatus == 0)) {
//                $remain+=$Leavedays;
//                $taken-=$Leavedays;
//                $leaveDao->UpdateEntitlementTakenPendingRemain($empnumber, $Leavtype, $year['year'], $taken, $shedule, $remain);
//                //$leaveDao->UpdateEntitlementLeaveNoShedule($empnumber, $Leavtype, $year['year'], $taken, $remain);
            } else if (($Prestatus == 3) && ($Newstatus == 1)) {
                $remain-=$Leavedays;
                $shedule+=$Leavedays;
                $taken = null;
                $leaveDao->UpdateEntitlementTakenPendingRemain($empnumber, $Leavtype, $year['year'], $taken, $shedule, $remain);
                //$leaveDao->UpdateEntitlementLeaveNoTaken($empnumber, $Leavtype, $year['year'], $shedule, $remain);
            } else if (($Prestatus == 3) && ($Newstatus == 2)) {
                $remain-=$Leavedays;
                $taken+=$Leavedays;
                $shedule = null;
                $leaveDao->UpdateEntitlementTakenPendingRemain($empnumber, $Leavtype, $year['year'], $taken, $shedule, $remain);
                //$leaveDao->UpdateEntitlementLeaveNoShedule($empnumber, $Leavtype, $year['year'], $taken, $remain);
            }
            }
            //end Core
            $LeaveRecord->setLeave_app_status($status);
            $LeaveRecord->setLeave_app_comment($comment);
            $leaveDao->saveLeave($LeaveRecord);

            $conn->commit();
            $this->isupdated = "true";
            
//Email        
            
           $directsupervisior=$leaveDao->getSupervisorData($LeaveRecord->emp_number);
            $employee=$leaveDao->readEmployeeMaster($directsupervisior[0]['supervisorId']);
            
            
            $Message = "Hi ".$LeaveRecord->Employee->emp_display_name.","."<br/>"."<br/>";
            $Message.= $LeaveRecord->Employee->emp_display_name." applied ";
            $Message.= $LeaveRecord->LeaveType->leave_type_name;             
            $Message.=  " from ".$LeaveRecord->leave_app_start_date;
            $Message.=  " to ".$LeaveRecord->leave_app_end_date;
            $Message.=  ". No of leave days will be ".$LeaveRecord->leave_app_workdays;
            $Message.= ".<br/>";            
            $i=0;
            if($Newstatus == 0){
            $Message.="Leave canceled by";
            $Subject = "Leave : ".$LeaveRecord->LeaveType->leave_type_name.", ".$LeaveRecord->Employee->emp_display_name ." - Canceled";
            $TO = $LeaveRecord->Employee->EmpContact->con_off_email;
            } else if($Newstatus == 1){
            $Message.="Leave is pending by"; 
            $Subject = "Leave : ".$LeaveRecord->LeaveType->leave_type_name.", ".$LeaveRecord->Employee->emp_display_name ." - Pending";
            $TO = $LeaveRecord->Employee->EmpContact->con_off_email;
            } else if($Newstatus == 2){
            $Message.="Leave approved by"; 
            $Subject = "Leave : ".$LeaveRecord->LeaveType->leave_type_name.", ".$LeaveRecord->Employee->emp_display_name ." - Approved";
            $TO = $LeaveRecord->Employee->EmpContact->con_off_email;
            
            $empsupemail=$leaveDao->getSuperEmail($LeaveRecord->emp_number);
            $EmpSupervisorEmail = $empsupemail;            
            foreach($empsupemail as $row){
                if($employee->EmpContact->con_off_email != $row['con_off_email']){
                $CC[]=$row['con_off_email'];
                $i++;
                }
            }
            $CC[$i] ="staffmovements@icta.lk";         
            $i++;
            }else{
            $Message.="Leave rejected by"; 
            $Subject = "Leave : ".$LeaveRecord->LeaveType->leave_type_name.", ".$LeaveRecord->Employee->emp_display_name ." - Rejected";
            $TO = $LeaveRecord->Employee->EmpContact->con_off_email;
            }
            $Message.=" ".$employee->emp_display_name.".";
            
            //$Subject = "Leave : ".$knwdt->Employee->emp_display_name ." - Pending Approval";
            

            $CC[$i] ="commonhrm@icta.lk";
            $CC[$i+1] = $employee->EmpContact->con_off_email;

            //die(print_r($CC)); 

 

        $Message.= "<br/>"."<br/>"."Thanks."."<br/>";




        //--End Message
        //die(print_r($EmpSupervisorEmail));
        $defaultDao = new DefaultDao();
        $t=$defaultDao->sendEmail($Message,$TO,$CC,$Subject);
            
            
            
            
            
            
        } catch (Exception $e) {
            $conn->rollBack();
            $errMsg = new CommonException($e->getMessage(), $e->getCode());
            $this->setMessage('WARNING', $errMsg->display());
            $this->isupdated = "false";
            $this->redirect('Leave/LeaveSearch');
        }
        echo json_encode(array("isupdated" => $this->isupdated));
        die;
    }

    public function executeHolyday(sfWebRequest $request) {

        try {
            $this->Culture = $this->getUser()->getCulture();
            $this->isAdmin = $_SESSION['isAdmin'];
            $leaveDao = new LeaveDao();

            $this->sorter = new ListSorter('Holyday', 'Leave', $this->getUser(), array('b.leave_holiday_id', ListSorter::ASCENDING));
            $this->sorter->setSort(array($request->getParameter('sort'), $request->getParameter('order')));

            if ($request->getParameter('mode') == 'search') {
                if (($request->getParameter('searchMode') == 'all') && (trim($request->getParameter('searchValue')) != '')) {
                    $this->setMessage('NOTICE', array('Select the field to search'));
                    $this->redirect('Leave/Holyday');
                }
                $this->var = 1;
            }

            $this->searchMode = ($request->getParameter('searchMode') == null) ? 'all' : $request->getParameter('searchMode');
            $this->searchValue = ($request->getParameter('searchValue') == null) ? '' : $request->getParameter('searchValue');

            $this->sort = ($request->getParameter('sort') == '') ? 'leave_holiday_date' : $request->getParameter('sort');
            $this->order = ($request->getParameter('order') == '') ? 'ASC' : $request->getParameter('order');
            $res = $leaveDao->searchHolyDay($this->searchMode, $this->searchValue, $this->Culture, $this->sort, $this->order, $request->getParameter('page'));
            $this->knwdoctype = $res['data'];
            $this->pglay = $res['pglay'];
            $this->pglay->setTemplate('<a href="{%url}">{%page}</a>');
            $this->pglay->setSelectedTemplate('{%page}');
        } catch (Exception $e) {
            $errMsg = new CommonException($e->getMessage(), $e->getCode());
            $this->setMessage('WARNING', $errMsg->display());
            $this->redirect('default/error');
        }
    }

    public function executeSaveHolyday(sfWebRequest $request) {//die(print_r($_POST));
        $this->myCulture = $this->getUser()->getCulture();
        $leaveDao = new LeaveDao();
        $knwdt = new LeaveHoliday();
        if ($request->isMethod('post')) {

            if (strlen($request->getParameter('txtName'))) {
                $knwdt->setLeave_holiday_name(trim($request->getParameter('txtName')));
            } else {
                $knwdt->setLeave_holiday_name(null);
            }
            if (strlen($request->getParameter('txtNamesi'))) {
                $knwdt->setLeave_holiday_name_si(trim($request->getParameter('txtNamesi')));
            } else {
                $knwdt->setLeave_holiday_name_si(null);
            }
            if (strlen($request->getParameter('txtNameta'))) {
                $knwdt->setLeave_holiday_name_ta(trim($request->getParameter('txtNameta')));
            } else {
                $knwdt->setLeave_holiday_name_ta(null);
            }
            if (strlen($request->getParameter('txtLeaveStartDate'))) {
                $knwdt->setLeave_holiday_date(trim(LocaleUtil::getInstance()->convertToStandardDateFormat($request->getParameter('txtLeaveStartDate'))));
            } else {
                $knwdt->setLeave_holiday_date(null);
            }
            if (strlen($request->getParameter('cmbHalfDay'))) {
                $knwdt->setLeave_holiday_fulorhalf(trim($request->getParameter('cmbHalfDay')));
            } else {
                $knwdt->setLeave_holiday_fulorhalf(null);
            }
            if (strlen($request->getParameter('cmbannual'))) {
                $knwdt->setLeave_holiday_annual(trim($request->getParameter('cmbannual')));
            } else {
                $knwdt->setLeave_holiday_annual(null);
            }


            try {
                $leaveDao->saveHolyday($knwdt);
            } catch (Doctrine_Connection_Exception $e) {

                $errMsg = new CommonException($e->getPortableMessage(), $e->getPortableCode());
                $this->setMessage('WARNING', $errMsg->display());
                $this->redirect('Leave/Holyday');
            } catch (Exception $e) {
                $errMsg = new CommonException($e->getMessage(), $e->getCode());
                $this->setMessage('WARNING', $errMsg->display());
                $this->redirect('Leave/Holyday');
            }

            $this->setMessage('SUCCESS', array($this->getContext()->getI18N()->__("Successfully Added", $args, 'messages')));
            $this->redirect('Leave/Holyday');
        }
    }

    public function executeUpdateHolyday(sfWebRequest $request) {///die(var_dump($request));
        //Table Lock code is Open
        if (!strlen($request->getParameter('lock'))) {
            $this->lockMode = 0;
        } else {
            $this->lockMode = $request->getParameter('lock');
        }
        $transPid = $request->getParameter('id');

        if (isset($this->lockMode)) {
            if ($this->lockMode == 1) {

                $conHandler = new ConcurrencyHandler();

                $recordLocked = $conHandler->setTableLock('hs_hr_leave_holiday', array($transPid), 1);

                if ($recordLocked) {
                    // Display page in edit mode
                    $this->lockMode = 1;
                } else {

                    $this->setMessage('WARNING', array($this->getContext()->getI18N()->__('Can not update. Record locked by another user.', $args, 'messages')), false);
                    $this->lockMode = 0;
                }
            } else if ($this->lockMode == 0) {
                $conHandler = new ConcurrencyHandler();
                $recordLocked = $conHandler->resetTableLock('hs_hr_leave_holiday', array($transPid), 1);
                $this->lockMode = 0;
            }
        }

        //Table lock code is closed
        $this->myCulture = $this->getUser()->getCulture();
        $LeaveDao = new LeaveDao();

        $knwdt = $LeaveDao->readHolyday($request->getParameter('id'));
        if (!$knwdt) {
            $this->setMessage('WARNING', array($this->getContext()->getI18N()->__('Can not update. Record has been Deleted', $args, 'messages')));
            $this->redirect('Leave/Holyday');
        }

        $this->benifittypelist = $knwdt;
        if ($request->isMethod('post')) {

            if (strlen($request->getParameter('txtName'))) {
                $knwdt->setLeave_holiday_name(trim($request->getParameter('txtName')));
            } else {
                $knwdt->setLeave_holiday_name(null);
            }
            if (strlen($request->getParameter('txtNamesi'))) {
                $knwdt->setLeave_holiday_name_si(trim($request->getParameter('txtNamesi')));
            } else {
                $knwdt->setLeave_holiday_name_si(null);
            }
            if (strlen($request->getParameter('txtNameta'))) {
                $knwdt->setLeave_holiday_name_ta(trim($request->getParameter('txtNameta')));
            } else {
                $knwdt->setLeave_holiday_name_ta(null);
            }
            if (strlen($request->getParameter('txtLeaveStartDate'))) {
                $knwdt->setLeave_holiday_date(trim(LocaleUtil::getInstance()->convertToStandardDateFormat($request->getParameter('txtLeaveStartDate'))));
            } else {
                $knwdt->setLeave_holiday_date(null);
            }
            if (strlen($request->getParameter('cmbHalfDay'))) {
                $knwdt->setLeave_holiday_fulorhalf(trim($request->getParameter('cmbHalfDay')));
            } else {
                $knwdt->setLeave_holiday_fulorhalf(null);
            }
            if (strlen($request->getParameter('cmbannual'))) {
                $knwdt->setLeave_holiday_annual(trim($request->getParameter('cmbannual')));
            } else {
                $knwdt->setLeave_holiday_annual(null);
            }
            try {
                $LeaveDao->saveHolyday($knwdt);
            } catch (Doctrine_Connection_Exception $e) {
                $errMsg = new CommonException($e->getPortableMessage(), $e->getPortableCode());
                $this->setMessage('WARNING', $errMsg->display());
                $this->redirect('Leave/UpdateHolyday?id=' . $knwdt->getLeave_holiday_id() . '&lock=0');
            } catch (Exception $e) {
                $errMsg = new CommonException($e->getMessage(), $e->getCode());
                $this->setMessage('WARNING', $errMsg->display());
                $this->redirect('Leave/UpdateHolyday?id=' . $knwdt->getLeave_holiday_id() . '&lock=0');
            }
            $this->setMessage('SUCCESS', array($this->getContext()->getI18N()->__("Successfully Updated", $args, 'messages')));
            $this->redirect('Leave/UpdateHolyday?id=' . $knwdt->getLeave_holiday_id() . '&lock=0');
        }
    }

    public function executeDeleteHolyday(sfWebRequest $request) {
        if (count($request->getParameter('chkLocID')) > 0) {

            $LeaveDao = new LeaveDao();
            try {
                $conn = Doctrine_Manager::getInstance()->connection();
                $conn->beginTransaction();
                $ids = array();
                $ids = $request->getParameter('chkLocID');

                $countArr = array();
                $saveArr = array();
                for ($i = 0; $i < count($ids); $i++) {
                    $conHandler = new ConcurrencyHandler();
                    $isRecordLocked = $conHandler->isTableLocked('hs_hr_leave_holiday', array($ids[$i]), 1);
                    if ($isRecordLocked) {

                        $countArr = $ids[$i];
                    } else {
                        $saveArr = $ids[$i];
                        $LeaveDao->deleteHolyDay($ids[$i]);
                        $conHandler->resetTableLock('hs_hr_leave_holiday', array($ids[$i]), 1);
                    }
                }

                $conn->commit();
            } catch (Doctrine_Connection_Exception $e) {

                $errMsg = new CommonException($e->getPortableMessage(), $e->getPortableCode());
                $this->setMessage('WARNING', $errMsg->display());
                $this->redirect('Leave/Holyday');
            } catch (Exception $e) {
                $conn->rollBack();
                $errMsg = new CommonException($e->getMessage(), $e->getCode());
                $this->setMessage('WARNING', $errMsg->display());
                $this->redirect('Leave/Holyday');
            }
            if (count($saveArr) > 0 && count($countArr) == 0) {
                $this->setMessage('SUCCESS', array($this->getContext()->getI18N()->__("Successfully Deleted", $args, 'messages')));
            } elseif (count($saveArr) > 0 && count($countArr) > 0) {
                $this->setMessage('WARNING', array($this->getContext()->getI18N()->__("Some records are can not be deleted as them  Locked by another user ", $args, 'messages')));
            } elseif (count($saveArr) == 0 && count($countArr) > 0) {
                $this->setMessage('WARNING', array($this->getContext()->getI18N()->__("Can not delete as them  Locked by another user ", $args, 'messages')));
            }
        } else {
            $this->setMessage('NOTICE', array('Select at least one record to delete'));
        }
        $this->redirect('Leave/Holyday');
    }

    public function executeError(sfWebRequest $request) {

        $this->redirect('default/error');
    }

    public function executeLeaveApprovalSearch(sfWebRequest $request) {
       if (strlen($request->getParameter('empNumber'))) {

            $empNumber = $request->getParameter('empNumber');

            $_SESSION['PIM_EMPID'] = $empNumber;
        } elseif (strlen($_SESSION['PIM_EMPID'])) {

        } else {
            if (strlen($_SESSION['empNumber'])) {
                $_SESSION['PIM_EMPID'] = $_SESSION['empNumber'];
            }
        }

        if ($_SESSION['user'] == "USR001") {
             $this->setMessage('WARNING', array($this->getContext()->getI18N()->__("Admin not allow to perform leave approve.", $args, 'messages')));
            $this->redirect('pim/list');
        }

        $this->Culture = $this->getUser()->getCulture();
        $employee = $_SESSION['empNumber'];
        $leaveDao = new LeaveDao();
        $EmployeeData = $leaveDao->readEmployee($employee);
        $subordinates = $leaveDao->Loadsubordinate($EmployeeData->getEmp_number());
        $this->loadbtype = $leaveDao->LeavetypeList();

        $this->Culture = $this->getUser()->getCulture();
     
        $this->sorter = new ListSorter('Leave', 'LeaveSearch', $this->getUser(), array('a.leave_app_id', ListSorter::ASCENDING));
        $this->sorter->setSort(array($request->getParameter('sort'), $request->getParameter('order')));



        $this->sort = ($request->getParameter('sort') == '') ? 'a.leave_app_id' : $request->getParameter('sort');
        $this->order = ($request->getParameter('order') == '') ? 'ASC' : $request->getParameter('order');
        $encrypt = new EncryptionHandler();
        if(strlen($request->getParameter('Employee'))){
        $this->EmployeeSub = (($encrypt->decrypt($request->getParameter('Employee')) == null)) ? $encrypt->decrypt($request->getParameter('Employee')) : $encrypt->decrypt($request->getParameter('Employee'));
        }else{
          $this->EmployeeSub = null;
        }
        $this->EmployeeName = ($request->getParameter('txtEmployeeName') == null) ? $request->getParameter('txtEmployeeName') : $_POST['txtEmployeeName'];
        $this->searchMode = ($request->getParameter('txttdate') == null) ? $request->getParameter('searchMode') : LocaleUtil::getInstance()->convertToStandardDateFormat($_POST['txttdate']);
        $this->searchValue = ($request->getParameter('txtfdate') == null) ? $request->getParameter('searchValue') : LocaleUtil::getInstance()->convertToStandardDateFormat($_POST['txtfdate']);
        $this->emp = ($request->getParameter('txtEmpId') == null) ? $request->getParameter('emp') : $_POST['txtEmpId'];
        $this->type = ($request->getParameter('abc') == null) ? $request->getParameter('type') : $_POST['abc'];
        $this->post = ($_POST == null) ? $request->getParameter('post') : $_POST;
        if($request->getParameter('type')==0 || $_POST['abc']==0 ){

            foreach ($subordinates as $row) {
                if ($subordinates[0] == $row) {
                    $subordinate.=$row['subordinateId'];
                } else {
                    $subordinate.="," . $row['subordinateId'];
                }

            }
            $this->EmployeeSub=$subordinate;
           
        }else{
            $this->type=3;
        }
        //die(print_r($subordinates));
        $res = $leaveDao->viewall($this->searchValue, $this->searchMode, $request->getParameter('page'), $this->emp, $this->type, $this->sort, $this->order, $this->post, $this->EmployeeSub);
        $this->view = $res['data'];
        $this->pglay = $res['pglay'];
        $this->pglay->setTemplate('<a href="{%url}">{%page}</a>');
        $this->pglay->setSelectedTemplate('{%page}');
    }
    
    //OONotes
    
    public function executeOONote(sfWebRequest $request) {
        try {
            $this->Culture = $this->getUser()->getCulture();
            $LeaveDao = new LeaveDao();
            

            $this->sorter = new ListSorter('OfficeOut', 'Leave', $this->getUser(), array('b.oo_id', ListSorter::ASCENDING));
            $this->sorter->setSort(array($request->getParameter('sort'), $request->getParameter('order')));

            if ($request->getParameter('mode') == 'search') {
                if (($request->getParameter('searchMode') == 'all') && (trim($request->getParameter('searchValue')) != '')) {
                    $this->setMessage('NOTICE', array('Select the field to search'));
                    $this->redirect('Leave/OONote');
                }
            }

            $this->searchMode = ($request->getParameter('searchMode') == null) ? 'all' : $request->getParameter('searchMode');
            $this->searchValue = ($request->getParameter('searchValue') == null) ? '' : $request->getParameter('searchValue');

            $this->sort = ($request->getParameter('sort') == '') ? 'b.oo_id' : $request->getParameter('sort');
            $this->order = ($request->getParameter('order') == '') ? 'ASC' : $request->getParameter('order');
            $res = $LeaveDao->searchOFFOUT($this->searchMode, $this->searchValue, $this->Culture, $this->sort, $this->order, $request->getParameter('page'));
            $this->OONotelist = $res['data'];
            $this->pglay = $res['pglay'];
            $this->pglay->setTemplate('<a href="{%url}">{%page}</a>');
            $this->pglay->setSelectedTemplate('{%page}');
        } catch (Exception $e) {
            $errMsg = new CommonException($e->getMessage(), $e->getCode());
            $this->setMessage('WARNING', $errMsg->display());
            $this->redirect('default/error');
        }
    }
    
    
    public function executeUpdateOONote(sfWebRequest $request) {
        //Table Lock code is Open

        $LeaveDao = new LeaveDao();
        if (!strlen($request->getParameter('lock'))) {
            $this->lockMode = 0;
        } else {
            $this->lockMode = $request->getParameter('lock');
        }

        if($_SESSION['empNumber']){
        $Employee=$LeaveDao->LoadEmpData($_SESSION['empNumber']);
        $this->EmployeeNumber= $Employee[0]['empNumber'];
        $this->EmpDisplayName= $Employee[0]['emp_display_name'];
        }
        
        
        
        $transPid = $request->getParameter('id');
        if (isset($this->lockMode)) {
            if ($this->lockMode == 1) {

                $conHandler = new ConcurrencyHandler();

                $recordLocked = $conHandler->setTableLock('hs_hr_leave_office_out', array($transPid), 1);

                if ($recordLocked) {
                    // Display page in edit mode
                    $this->lockMode = 1;
                } else {
                    $this->setMessage('WARNING', array($this->getContext()->getI18N()->__('Can not update. Record locked by another user.', $args, 'messages')), false);
                    $this->lockMode = 0;
                }
            } else if ($this->lockMode == 0) {
                $conHandler = new ConcurrencyHandler();
                $recordLocked = $conHandler->resetTableLock('hs_hr_leave_office_out', array($transPid), 1);
                $this->lockMode = 0;
            }
        }

        //Table lock code is closed
        try {
            $this->Culture = $this->getUser()->getCulture();
            //$LeaveDao = new LeaveDao();
            //$wbmBeniftDao = new wbmDao();
            if($request->getParameter('id')!= null){
                
            
            $OfficeOut = $LeaveDao->readOONote($request->getParameter('id'));
            if (!$OfficeOut) {
                $this->setMessage('WARNING', array($this->getContext()->getI18N()->__('Can not update. Record has been Deleted', $args, 'messages')));
                $this->redirect('Leave/OONote');
            }
            }else{
                $OfficeOut = new OfficeOut();
                $this->lockMode = 1;
            }
            //$this->loadbtype = $wbmBeniftDao->getBenifitTypeload();
            

                $this->OfficeOut = $OfficeOut;
            $fromtimerow = $OfficeOut->oo_from;
            $totimerow = $OfficeOut->oo_to;
            if($fromtimerow=="00:00:00" && $totimerow=="00:00:00"){
                    $this->fromtimehrs = "";
                    $this->fromtimemins = "";
                    $this->totimehrs = "";
                    $this->totimemins = "";
            }else{
            $fromtimeexpand = explode(':', $fromtimerow);
            $this->fromtimehrs = $fromtimeexpand[0];
            $this->fromtimemins = $fromtimeexpand[1];

            $totimeexpand = explode(':', $totimerow);
            $this->totimehrs = $totimeexpand[0];
            $this->totimemins = $totimeexpand[1];

            }
            
            
            
            
            //$this->loadbstype = $wbmBeniftDao->getBenifitsubbTypeload($this->benifittypelist->getBt_id() . '&lock=0');
        } catch (Doctrine_Connection_Exception $e) {
            $errMsg = new CommonException($e->getPortableMessage(), $e->getPortableCode());
            $this->setMessage('WARNING', $errMsg->display());
            $this->redirect('Leave/UpdateOONote?id=' . $request->getParameter('id') . '&lock=0');
        } catch (sfStopException $e) {

        } catch (Exception $e) {
            $errMsg = new CommonException($e->getMessage(), $e->getCode());
            $this->setMessage('WARNING', $errMsg->display());
            $this->redirect('Leave/UpdateOONote?id=' . $request->getParameter('id') . '&lock=0');
        }
        if ($request->isMethod('post')) { //die(print_r($_POST));
                            

            
            try {
                
                if($OfficeOut->oo_id == null){
                    $OfficeOut = new OfficeOut();
                }
                
                $timefromHR = $request->getParameter('timefromHR');
                $timefromMM = $request->getParameter('timefromMM');
                $timetoHR = $request->getParameter('timetoHR');
                $timetoMM = $request->getParameter('timetoMM');
                if(($timefromHR==null && $timefromMM==null) && ($timetoHR==null && $timetoMM==null)){
                    $fromtime=null;
                    $totime=null;

                }else{
                  if ($timefromHR != -1 && $timefromMM != -1) {
                    $fromtime = $timefromHR . ":" . $timefromMM;
                } elseif ($timefromHR != -1 && $timefromMM == -1) {
                    $fromtime = $timefromHR . ":00";
                } elseif ($timefromHR == -1 && $timefromMM != -1) {
                    $fromtime = "00:" . $timefromMM;
                } else {
                    $fromtime = "";
                }

                if ($timetoHR != -1 && $timetoMM != -1) {
                    $totime = $timetoHR . ":" . $timetoMM;
                } elseif ($timetoHR == -1 && $timetoMM != -1) {
                    $totime = "00:" . $timetoMM;
                } elseif ($timetoHR != -1 && $timetoMM == -1) {
                    $totime = $timetoHR . ":00";
                } else {
                    $totime = "";
                }
                }

                //$OfficeOut->setOo_emp_number ($request->getParameter('txtEmpId'));
                $OfficeOut->setOo_date($request->getParameter('txtdisbdate'));
                $OfficeOut->setOo_to_date($request->getParameter('todate'));
                $OfficeOut->setOo_from($fromtime);
                $OfficeOut->setOo_to($totime);
                $OfficeOut->setOo_category($request->getParameter('cmbbtype'));
                $OfficeOut->setOo_vehicle_flg($request->getParameter('chkActive'));                            
                $OfficeOut->setOo_comment(trim($request->getParameter('txtcomment')));
                $OfficeOut->setOo_authority(trim($request->getParameter('txtauthority')));
                
                $OfficeOut->save();
                
                $Max=$LeaveDao->readOONoteMax();
                //$Max[0]['MAX'];

                foreach ($_POST['hiddenEmpNumber'] as $row){
                    $OfficeOutDetails = new OfficeOutDetails();
                    $OfficeOutDetails->setOo_id($Max[0]['MAX']);
                    $OfficeOutDetails->setEmp_number($row);
                    $OfficeOutDetails->save();
                }
                //die;
                //--Message
                
                $Message = "Hi,"."<br/>"."<br/>";
                $Message.= $OfficeOutDetails->Employee->EmpTitle->title_name.".".$OfficeOutDetails->Employee->emp_display_name;
                $Message.= " will be out of office on ";
                $Message.=  $OfficeOut->oo_date;
                $Message.=  " from ".$OfficeOut->oo_from;
                $Message.=  " to ".$OfficeOut->oo_to;
                $Message.=  " for ";
                        
                        if($OfficeOut->oo_category==1){
                            $Message.="a official reason";
                        }elseif ($OfficeOut->oo_category==2) {
                            $Message.="a personal reason";
                        }elseif ($OfficeOut->oo_category==3) {
                            $Message.="Lunch";
                        }
                if($OfficeOut->oo_authority){        
                $Message.=  " at ".$OfficeOut->oo_authority;
                }
                if($OfficeOut->oo_vehicle_flg == '1'){        
                $Message.=  " and transport required.";
                }
                $Message.="<br/>"."<br/>";
                $Message.= "Reason In detail - "."<br/>";
                $Message.= $OfficeOut->oo_comment;
                $Message.= "<br/>"."<br/>"."Thanks."."<br/>";
                
                $EmpEmail = $OfficeOutDetails->Employee->EmpContact->con_off_email;
                
                $empsupemail=$LeaveDao->getSuperEmail($OfficeOutDetails->emp_number);
                if($empsupemail == null){
                    $sysconfig = new sysConf();
                    $empsupemail[] = $sysconfig->Emailnotreportto;
                    
                }else{
                    $TO = "staffmovements@icta.lk";
                }
                $EmpSupervisorEmail = $empsupemail;
                $Subject = "OOO : ".$OfficeOutDetails->Employee->emp_display_name;
                foreach($empsupemail as $row){
                    if($employee->EmpContact->con_off_email != $row['con_off_email']){
                    $CC[]=$row['con_off_email'];
                    $i++;
                    }
                 }
                
                //die(print_r($CC));
                
                //$CC[0] = $EmpSupervisorEmail;
                $CC[$i] = $EmpEmail;
                $CC[$i+1] ="commonhrm@icta.lk";
                
                //--End Message
                //die(print_r($EmpSupervisorEmail));
                $defaultDao = new DefaultDao();
                $t=$defaultDao->sendEmail($Message,$TO,$CC,$Subject);
                
                
                //$this->redirect('default/SendEmail?Message='.$Message.'&TO='.$TO.'&Subject='.$Subject."&CC=".$CC);
                
//            } catch (Doctrine_Connection_Exception $e) {
//                $errMsg = new CommonException($e->getPortableMessage(), $e->getPortableCode());
//                $this->setMessage('WARNING', $errMsg->display());
//                //$this->redirect('Leave/UpdateOONote?id=' . $request->getParameter('id') . '&lock=0');
//                if($request->getParameter('id')== null){
//                $this->redirect('Leave/OONote');
//                }else{
//                $this->redirect('Leave/UpdateOONote?id=' . $request->getParameter('id') . '&lock=0');
//                }
//            } catch (sfStopException $e) {

            
            } catch (Exception $e) {
                $errMsg = new CommonException($e->getMessage(), $e->getCode());
                $this->setMessage('WARNING', $errMsg->display());
                if($request->getParameter('id')== null){
                $this->redirect('Leave/OONote');
                }else{
                $this->redirect('Leave/UpdateOONote?id=' . $request->getParameter('id') . '&lock=0');
                }
                //$this->redirect('Leave/UpdateOONote?id=' . $request->getParameter('id') . '&lock=0');
            }
            

            //$this->setMessage('SUCCESS', array($this->getContext()->getI18N()->__("Successfully Added", $args, 'messages')));
            if($request->getParameter('id')== null){
                $this->setMessage('SUCCESS', array($this->getContext()->getI18N()->__("Successfully Added", $args, 'messages')));
                $this->redirect('Leave/OONote');
            }else{
                $this->setMessage('SUCCESS', array($this->getContext()->getI18N()->__("Successfully Update", $args, 'messages')));
            $this->redirect('Leave/UpdateOONote?id=' . $request->getParameter('id') . '&lock=0');
            }
        }
    }
    
     public function executeDeleteOONote(sfWebRequest $request) {

        if (count($request->getParameter('chkLocID')) > 0) {

            $LeaveDao = new LeaveDao();
            try {
                $conn = Doctrine_Manager::getInstance()->connection();
                $conn->beginTransaction();
                $ids = array();
                $ids = $request->getParameter('chkLocID');

                $countArr = array();
                $saveArr = array();
                for ($i = 0; $i < count($ids); $i++) {
                    $conHandler = new ConcurrencyHandler();
                    $isRecordLocked = $conHandler->isTableLocked('hs_hr_leave_office_out', array($ids[$i]), 1);
                    if ($isRecordLocked) {

                        $countArr = $ids[$i];
                    } else {
                        $saveArr = $ids[$i];
                        $LeaveDao->deleteOONote($ids[$i]);
                        $conHandler->resetTableLock('hs_hr_leave_office_out', array($ids[$i]), 1);
                    }
                }

                $conn->commit();
            } catch (Doctrine_Connection_Exception $e) {
                $errMsg = new CommonException($e->getPortableMessage(), $e->getPortableCode());
                $this->setMessage('WARNING', $errMsg->display());
                $this->redirect('Leave/OONote');
            } catch (Exception $e) {
                $conn->rollBack();
                $errMsg = new CommonException($e->getMessage(), $e->getCode());
                $this->setMessage('WARNING', $errMsg->display());
                $this->redirect('Leave/OONote');
            }
            if (count($saveArr) > 0 && count($countArr) == 0) {
                $this->setMessage('SUCCESS', array($this->getContext()->getI18N()->__("Successfully Deleted", $args, 'messages')));
            } elseif (count($saveArr) > 0 && count($countArr) > 0) {
                $this->setMessage('WARNING', array($this->getContext()->getI18N()->__("Some records are can not be deleted as them  Locked by another user ", $args, 'messages')));
            } elseif (count($saveArr) == 0 && count($countArr) > 0) {
                $this->setMessage('WARNING', array($this->getContext()->getI18N()->__("Can not delete as them  Locked by another user ", $args, 'messages')));
            }
        } else {
            $this->setMessage('NOTICE', array('Select at least one record to delete'));
        }
        $this->redirect('Leave/OONote');
    }

    
    public function executeAjaxAOOCancel(sfWebRequest $request) {
        
        $invalid = 0;
        $LeaveDao = new LeaveDao();
        $Id = $request->getParameter('ID');

        $OfficeOut = $LeaveDao->readOONote($Id);
        
        
        
        
        if(date("Y-m-d")<= $OfficeOut->oo_date){
            
            if(date("H:i") <= $OfficeOut->oo_to){
                $OfficeOut->setOo_cancel("1");
                $VAl =$OfficeOut->save();
                
                $Message = "Hi,"."<br/>"."<br/>";
        $Message.= $OfficeOut->Employee->EmpTitle->title_name.".".$OfficeOut->Employee->emp_display_name;
        $Message.= " will be out of office on ";
        $Message.=  $OfficeOut->oo_date;
        $Message.=  " from ".$OfficeOut->oo_from;
        $Message.=  " to ".$OfficeOut->oo_to;
        $Message.=  " for a ";

                if($OfficeOut->oo_category==1){
                    $Message.="official purpose";
                }elseif ($OfficeOut->oo_category==2) {
                    $Message.="personal matter";
                }elseif ($OfficeOut->oo_category==3) {
                    $Message.="Lunch";
                }
        if($OfficeOut->oo_authority){        
        $Message.=  " at ".$OfficeOut->oo_authority;
        }
        if($OfficeOut->oo_vehicle_flg == '1'){        
        $Message.=  " and transport required.";
        $Message.=  "<br/>"." The above message has been canceled.";
        }
        $Message.="<br/>"."<br/>";
        $Message.= "Reason In detail - "."<br/>";
        $Message.= $OfficeOut->oo_comment;
        $Message.= "<br/>"."<br/>"."Thanks."."<br/>";

        $EmpEmail = $OfficeOut->Employee->EmpContact->con_off_email;

        $empsupemail=$LeaveDao->getSuperEmail($OfficeOut->oo_emp_number);
        $EmpSupervisorEmail = $empsupemail;
        $Subject = "OOO : ".$OfficeOut->Employee->emp_display_name ." - Canceled";
        $TO = "staffmovements@icta.lk";
//        $CC[0] = $EmpSupervisorEmail;
//        $CC[1] = $EmpEmail;
//        $CC[2] ="commonhrm@icta.lk";
        
                foreach($empsupemail as $row){
                    if($employee->EmpContact->con_off_email != $row['con_off_email']){
                    $CC[]=$row['con_off_email'];
                    $i++;
                    }
                 }

                $CC[$i] = $EmpEmail;
                $CC[$i+1] ="commonhrm@icta.lk";

        //--End Message
        //die(print_r($EmpSupervisorEmail));
        $defaultDao = new DefaultDao();
        $t=$defaultDao->sendEmail($Message,$TO,$CC,$Subject);
                
                
            }else{
                $invalid = 1;
            }
            
        }else{
            $invalid = 1;
        }
        
        if($invalid == 1){
            $message = "OOO Note Can't Cancel time has expired.";
        }else{
            $message = "Successfuly OOO Note Canceled.";
        }
        
        
        
        
        
        echo json_encode($message);
        die;
    }    

    public function setMessage($messageType, $message = array(), $persist=true) {
        $this->getUser()->setFlash('messageType', $messageType, $persist);
        $this->getUser()->setFlash('message', $message, $persist);
    }
    
    public function executeImagepop(sfWebRequest $request) {

        $LeaveDao = new LeaveDao();
        $attachment = $LeaveDao->getAttachdetails($request->getParameter('id'));
        $outname = stripslashes($attachment[0]['leave_attach_attachment']);
        $type = stripslashes($attachment[0]['leave_attach_type']);
        $name = stripslashes($attachment[0]['leave_attach_filename']);
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private",false);
        header('Content-Description: File Transfer');           
        header("Content-type:" . $type);
        header('Content-disposition: attachment; filename=' . $name);
        echo($outname);
        exit;
    }
    
    public function executeAttachementUpdate(sfWebRequest $request) {
        //die(print_r($_FILES));
        $leaveDao = new LeaveDao();
        if ($request->isMethod('post')) {
        
                    try {


                $sysConfinst = OrangeConfig::getInstance()->getSysConf();
                $sysConfs = new sysConf();

                if (array_key_exists('txtletter', $_FILES)) {
                    foreach ($_FILES as $file) {

                        if ($file['tmp_name'] > '') {
                            if (!in_array(end(explode(".", strtolower($file['name']))), $sysConfs->allowedExtensions)) {
                                throw new Exception("Invalid File Type", 8);
                            }
                        }
                    }
                } else {
                    throw new Exception("Invalid File Type", 6);
                }

                $fileName = $_FILES['txtletter']['name'];
                $tmpName = $_FILES['txtletter']['tmp_name'];
                $fileSize = $_FILES['txtletter']['size'];
                $fileType = $_FILES['txtletter']['type'];


                $maxFileSize2 = $sysConfs->getMaxFilesize();
                $sysConfinst = OrangeConfig::getInstance()->getSysConf();
                $sysConfs = new sysConf();
                //$maxsize=2097152;
                if ($fileSize > $maxFileSize2) {

                    throw new Exception("Maxfile size  Should be less than 2MB", 1);
                }
            } catch (Exception $e) {
                $errMsg = new CommonException($e->getMessage(), $e->getCode());
                $this->setMessage('WARNING', $errMsg->display());
                $this->redirect('Leave/Leave');
            }
                try{
                $fp = fopen($tmpName, 'r');
                $content = fread($fp, filesize($tmpName));
                $content = addslashes($content);    
                if (strlen($content)) {
                $leaveDao->deleteLeaveAttachment($_POST['txtLeaveID']);
                $LeaveAttach = new LeaveAttachment();                     
                $LeaveAttach->setLeave_attach_filename($fileName);
                $LeaveAttach->setLeave_attach_type($fileType);
                $LeaveAttach->setLeave_attach_size($fileSize);
                $LeaveAttach->setLeave_attach_attachment($content);
                //$lastLeaveid = $leaveDao->getLastLeaveAppID();
                $LeaveAttach->setLeave_app_id($_POST['txtLeaveID']);
                $LeaveAttach->save();
                }
            
            } catch (Doctrine_Connection_Exception $e) {
            $conn->rollback();
            $errMsg = new CommonException($e->getPortableMessage(), $e->getPortableCode());
            $this->setMessage('WARNING', $errMsg->display());
            $this->redirect('Leave/Leave');
        } catch (sfStopException $e) {

        } catch (Exception $e) {
            $conn->rollback();
            $errMsg = new CommonException($e->getMessage(), $e->getCode());
            $this->setMessage('WARNING', $errMsg->display());
            $this->redirect('Leave/Leave');
        }
            $this->setMessage('SUCCESS', array($this->getContext()->getI18N()->__("Successfully Added", $args, 'messages')));
            $this->redirect('Leave/Leave?eid=' . $request->getParameter('txtEmpId'));
        
        }
    }
    
        public function executeShortLeaveDisplayUpdate(sfWebRequest $request) {

        $Year = $request->getParameter('Year');
        $Month = $request->getParameter('Month');
        $lt = $request->getParameter('lt');
        $Enum = $request->getParameter('Enum');
        $leaveDao = new LeaveDao();
        
        $LeavetypeConfig = $leaveDao->readLeaveTypeConfig($lt);
        
        $ShortLeave = $leaveDao->readShortLeave($Year, $Month, $Enum,$lt);
//        if($ShortLeave[0]['count']<$LeavetypeConfig->leave_type_entitle_days){
//
//                $ShortLeaveAllow="yes";
//        }else{
//                $ShortLeaveAllow="no";
//        }
        $Total = $LeavetypeConfig->leave_type_entitle_days;
        $Taken = $ShortLeave[0]['count'];
        $Remain = $Total - $Taken;
        
        echo json_encode(array("Total" => $Total, "Taken" => $Taken, "Remain" => $Remain));
        die;
    }
    
}
