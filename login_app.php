<?php

include_once 'db_functions.php';
$db = new DB_Functions();

if (isset($_GET['username'])) {
   // username and password sent from form 

   $myusername = $_GET['username'];
   $mypassword = $_GET['password'];
   $device     = $_GET['device'];

   $user_array = array();

   $login_result = $db->loginCheckHashApp($myusername, $mypassword);

   if ($login_result == 1) {

      $result = $db->getStudentByUsername($myusername);

      if ($result->num_rows > 0) {
         $row = $result->fetch_array();

         $user['username']    = $row['username'];
         $user['name']        = $row['name'];
         $user['batch_id']    = $row['batch_id'];
         $user['batch_name']  = $db->getBatchNameById($row['batch_id']);

         if ($row['app_device'] == $device) {
            $user['valid_user']  = 1; // registered device
         } else if ($row['app_device'] == "") {
            //set device id
            $db->setDeviceId($row['id'], $device);
            $user['valid_user']  = 1; // first time registration
         } else {
            $user['valid_user']  = 3; // registered in another device
         }

         if ($row['video_subscribed'] != 1) {
            $user['valid_user']  = 2; // registered device
         }

      }
   } else {
      //invalid credentials
      $user['valid_user']  = 0;
      $user['username']    = "";
      $user['name']        = "";
      $user['batch_id']    = 0;
      $user['batch_name']  = "";
   }

   array_push($user_array, $user);
   echo json_encode($user_array);
}
