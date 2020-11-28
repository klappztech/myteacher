<?php

include_once 'db_functions.php'; 
$db = new DB_Functions();

$all_video = array();

$is_user_valid = $db->IsUserRegisteredForVideoByUsername($_GET['username']);
$search_result = $db->getAllValidSubjectsByBatchId($_GET['batch_id']);
$student_id    = $db->getStudentIdByUsername($_GET['username']);


if ($is_user_valid == 1) //user subscribed
{
    while ($row =  $search_result->fetch_array()) {

        $row_temp['title'] = $db->getSubjectNameById($row['subject_id']);
        $row_temp['subject_id'] = $row['subject_id'];

        array_push($all_video, $row_temp);

    }


} else {
    $row_temp['title'] = "Invalid User!";
    $row_temp['subject_id'] = "0";
    array_push($all_video, $row_temp);


}


$all_video_json['video'] = $all_video;
echo json_encode($all_video_json);
