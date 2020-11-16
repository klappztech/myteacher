<?php

include_once 'db_functions.php';
$db = new DB_Functions();

$all_video = array();

$search_result = $db->getAllVideosByBatchId($_GET['batch_id']);


$num = 0;

// $row =  $search_result->fetch_array();
// echo json_encode($row);


while ($row =  $search_result->fetch_array()) {

    array_push($all_video, $row);
}


$all_video_json['video'] = $all_video;
echo json_encode($all_video_json);

?>
