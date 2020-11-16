<?php

include_once 'db_functions.php';
$db = new DB_Functions();

$all_video = array();

$search_result = $db->getAllValidSubjectsByBatchId($_GET['batch_id']);


$subject = 0;

// $row =  $search_result->fetch_array();
// echo json_encode($row);


while ($row =  $search_result->fetch_array()) {

    $row_temp['title'] = $db->getSubjectNameById($row['subject_id']);
    $row_temp['subject_id'] = $row['subject_id'];
    array_push($all_video, $row_temp);

}


$all_video_json['video'] = $all_video;
echo json_encode($all_video_json);
