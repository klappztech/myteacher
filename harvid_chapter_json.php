<?php

include_once 'db_functions.php';
$db = new DB_Functions();

$all_video = array();

$search_result = $db->getAllValidChaptersByBatchIdSubjectId($_GET['batch_id'],$_GET['subject_id']);


$subject = 0;

while ($row =  $search_result->fetch_array()) {

    $row_temp['title'] = $db->getChapterNameById($row['chapter_id']);
    $row_temp['chapter_id'] = $row['chapter_id'];

    array_push($all_video, $row_temp);

}


$all_video_json['video'] = $all_video;
echo json_encode($all_video_json);
