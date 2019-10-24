<?php

$arr = Array();
//$n="td_course_name_".$culture;
$LeaveDao = new LeaveDao();

foreach ($emplist as $row) {

    if ($culture == "en") {
        $abc = "emp_display_name";
    } else {
        $abc = "emp_display_name_" . $culture;
    }
    if ($culture == "en") {
        $title = "title";
    } else {
        $title = "title_" . $culture;
    }
    $comStruture = $LeaveDao->getCompnayStructure($row['work_station']);
    if ($culture == "en") {
        $title = "getTitle";
    } else {
        $title = "getTitle_" . $culture;
    }
    if ($comStruture) {
        $comTitle = $comStruture->$title();
    }
    $arr[$row['employeeId']] = $row['employeeId'] . "|" . $row[$abc] . "|" . $comTitle . "|" . $row['emp_status'] . "|" . $row['empNumber']."|".$row['gender_code'];
}



echo json_encode($arr);
?>