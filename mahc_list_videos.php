
<?php

echo "<br/><br/><br/><br/><br/><br/><br/>";


include_once 'db_functions.php';
$db = new DB_Functions();


$youtube_result = $db->getAllYoutubeVideosFull();
$i=0;


while ($row =  $youtube_result->fetch_array()) {
    $i++;
    echo $i."-->".$row['source'] . "<br />";
}


?>