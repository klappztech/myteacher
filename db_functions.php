<?php

class DB_Functions
{

  private $db;
  private $conn; //mysqli

  //put your code here
  // constructor
  function __construct()
  {
    include_once './db_connect.php';
    // connecting to database
    $this->db = new DB_Connect();
    $this->conn = $this->db->connect();
  }

  // destructor
  function __destruct()
  {
  }


  public function loginCheckHash($myusername, $mypassword)
  {

    $result =  $this->conn->query("SELECT * FROM `mt_users` WHERE `username` = '$myusername' AND `test_subscribed`=1");
    $count = $result->num_rows;

    if ($count == 1) {

      $row = $result->fetch_array();
      if (password_verify($mypassword, $row['password'])) {
        // Success!
        $_SESSION['username'] = $row['username'];
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['batch_id'] = $row['batch_id'];
        $_SESSION['is_admin'] = $row['is_admin'];


        $return = true;
      } else {
        $return = false;
      }
    } else {
      $return = false;
    }

    return $return;
  }


  public function loginCheckHashApp($myusername, $mypassword)
  {

    $result =  $this->conn->query("SELECT * FROM `mt_users` WHERE `username` = '$myusername'");
    $count = $result->num_rows;

    if ($count == 1) {

      $row = $result->fetch_array();
      if (password_verify($mypassword, $row['password'])) {
        // Success!

        $return = 1;
      } else {
        $return = 0;
      }
    } else {
      $return = 0;
    }

    return $return;
  }


  public function userExist($myusername)
  {

    $result =  $this->conn->query("SELECT * FROM `mt_users` WHERE `username` = '$myusername'");
    $count = $result->num_rows;

    if ($count > 0) {
      $return = true;
    } else {
      $return = false;
    }

    return $return;
  }

  public function IsUserRegisteredForVideo($id)
  {
    $sql = "SELECT * FROM `mt_users` WHERE `id` = $id";
    $result =  $this->conn->query($sql);

    if ($result->num_rows > 0) {
      $row = $result->fetch_array();
      if ($row['video_subscribed'] == 1) {
        return 1;
      } else {
        return 2; //not subscribed
      }
    }

    return 0;
  }

  public function IsUserRegisteredForVideoByUsername($username)
  {
    $sql = "SELECT * FROM `mt_users` WHERE `username` = '$username'";
    //echo $sql;
    $result =  $this->conn->query($sql);

    if ($result->num_rows > 0) {
      $row = $result->fetch_array();
      if ($row['video_subscribed'] == 1) {
        return 1;
      } else {
        return 2; //not subscribed
      }
    }

    return 0;
  }

  public function IsUserRegisteredForTest($id)
  {
    $sql = "SELECT * FROM `mt_users` WHERE `id` = $id";
    $result =  $this->conn->query($sql);

    if ($result->num_rows > 0) {
      $row = $result->fetch_array();
      if ($row['test_subscribed'] == 1) {
        return 1;
      } else {
        return 0; //not subscribed
      }
    }

    return 0;
  }

  public function createUser($name, $myusername, $mypassword, $batch_id, $phone, $test_subscribed, $video_subscribed)
  {

    $hash = password_hash($mypassword, PASSWORD_DEFAULT);
    $sql = "INSERT INTO `mt_users`(`name`,`username`, `password`,`batch_id`,`phone`, `test_subscribed`, `video_subscribed`) VALUES ('$name','$myusername','$hash',$batch_id, $phone,$test_subscribed,$video_subscribed) ";
    $result =  $this->conn->query($sql);
    //echo $sql;
    return $myusername;
  }

  public function editUser($id, $name, $batch_id, $phone, $test_subscribed, $video_subscribed)
  {
    $sql = "UPDATE `mt_users` SET `name`='$name', `phone`='$phone', `batch_id`=$batch_id, `test_subscribed`=$test_subscribed, `video_subscribed`=$video_subscribed  WHERE `id`=$id";
    $result =  $this->conn->query($sql);
    return $sql;
  }


  public function editVideo($id, $title, $batch_id, $release_time)
  {
    $sql = "UPDATE `mt_videos` SET `title`='$title', `batch_id`=$batch_id, `release_time`=$release_time  WHERE `id`=$id ";
    //echo $sql;
    $result =  $this->conn->query($sql);
    return $sql;
  }

  public function editVideoFull($source, $title, $batch_id, $subject_id, $chapter_id)
  {
    $sql = "UPDATE `mt_videos` SET `title`='$title', `batch_id`=$batch_id,`subject_id`='$subject_id', `chapter_id`=$chapter_id  WHERE `source`='$source' ";
    //echo $sql;
    $result =  $this->conn->query($sql);
    return $sql;
  }

  public function setDeviceId($id, $app_device)
  {
    $sql = "UPDATE `mt_users` SET `app_device`='$app_device'WHERE `id`=$id";
    $result =  $this->conn->query($sql);
    return $sql;
  }

  public function getStudentById($id)
  {

    $sql = "SELECT * FROM `mt_users` WHERE  `id`=$id ";
    //echo $sql;
    $result =  $this->conn->query($sql);

    return $result;
  }

  public function getStudentByUsername($username)
  {

    $sql = "SELECT * FROM `mt_users` WHERE  `username`='$username' ";
    //echo $sql;
    $result =  $this->conn->query($sql);

    return $result;
  }

  public function getUsernameById($id)
  {

    $sql = "SELECT * FROM `mt_users` WHERE  `id`=$id ";
    $result =  $this->conn->query($sql);

    if ($result->num_rows > 0) {
      $row = $result->fetch_array();
      $ret = $row['username'];
    }
    //echo $ret;
    return $ret;
  }

  public function getStudentIdByUsername($username)
  {
    $ret = 0;

    $sql = "SELECT * FROM `mt_users` WHERE  `username`='$username' ";
    $result =  $this->conn->query($sql);

    if ($result->num_rows > 0) {
      $row = $result->fetch_array();
      $ret = $row['id'];
    }
    return $ret;
  }



  public function getStudentNameById($id)
  {

    $result =  $this->conn->query("SELECT * FROM `mt_users` WHERE `id` = $id ");
    $count = $result->num_rows;

    if ($count == 1) {
      $row = $result->fetch_array();

      $name = $row['name'];
    } else {
      $name = "unknown";
    }

    return $name;
  }

  public function getStudentNameAndPhoneById($id)
  {
    $ret = array("name" => "Unknown", "phone" => "0");

    $result =  $this->conn->query("SELECT * FROM `mt_users` WHERE `id` = $id ");
    $count = $result->num_rows;

    if ($count == 1) {
      $row = $result->fetch_array();

      $ret['name'] = $row['name'];
      $ret['phone'] = $row['phone'];
    } else {
      //$name = "unknown";
    }

    return $ret;
  }

  public function getBatchNameById($id)
  {

    $result =  $this->conn->query("SELECT * FROM `mt_batches` WHERE `id` = $id ");
    $count = $result->num_rows;

    if ($count == 1) {
      //session_register("myusername");
      $row = $result->fetch_array();

      $name = $row['title'];
    } else {
      $name = "unknown";
    }

    return $name;
  }


  public function getSubjectNameById($id)
  {

    $result =  $this->conn->query("SELECT * FROM `mt_subjects` WHERE `id` = $id ");
    $count = $result->num_rows;

    if ($count == 1) {
      //session_register("myusername");
      $row = $result->fetch_array();

      $name = $row['title'];
    } else {
      $name = "unknown";
    }

    return $name;
  }


  public function getChapterNameById($id)
  {

    $result =  $this->conn->query("SELECT * FROM `mt_chapters` WHERE `id` = $id ");
    $count = $result->num_rows;

    if ($count == 1) {
      //session_register("myusername");
      $row = $result->fetch_array();

      $name = $row['title'];
    } else {
      $name = "unknown";
    }

    return $name;
  }


  public function getBatchIdbyStudentId($id)
  {
    $result =  $this->conn->query("SELECT `batch_id` FROM `mt_users` WHERE `id`=$id");
    $count = $result->num_rows;

    if ($count == 1) {
      $row = $result->fetch_array();
      $batch_id = $row['batch_id'];
    } else {
      $batch_id = 9999;
    }

    return $batch_id;
  }


  public function getStudentsByBatchId($batch_id)
  {
    $result =  $this->conn->query("SELECT * FROM `mt_users` WHERE `batch_id`=$batch_id AND `is_test_user`=0 ORDER BY `name` ASC");
    return $result;
  }

  public function getTestBlockedStudentsByBatchId($batch_id)
  {
    $result =  $this->conn->query("SELECT * FROM `mt_users` WHERE `batch_id`=$batch_id AND `is_test_user`=0 AND `test_subscribed`=0 ORDER BY `name` ASC");
    return $result;
  }


  public function getAllVideos()
  {
    $result =  $this->conn->query("SELECT * FROM `mt_videos` WHERE 1");
    return $result;
  }

  public function getAllVideosLimit20()
  {
    $result =  $this->conn->query("SELECT * FROM `mt_videos` WHERE 1 ORDER BY `id` DESC LIMIT 20");
    return $result;
  }

  public function getAllVideosByBatchSubjectChapter($batch_id, $subject_id, $chapter_id)
  {
    $sql = "SELECT * FROM `mt_videos` WHERE `batch_id`=$batch_id AND `subject_id`=$subject_id AND `chapter_id`=$chapter_id ORDER BY `id` DESC ";
    //echo $sql;
    $result =  $this->conn->query($sql);
    return $result;
  }

  public function getVideos()
  {
    $result =  $this->conn->query("SELECT * FROM `mt_videos` WHERE 1");
    return $result;
  }



  public function getAllVideosByBatchId($batch_id)
  {
    $result =  $this->conn->query("SELECT * FROM `mt_videos` WHERE `batch_id`=$batch_id ORDER BY `subject_id` ");
    return $result;
  }

  public function getAllVideosByBatchIdVideoId($batch_id, $video_id)
  {
    $result =  $this->conn->query("SELECT * FROM `mt_videos` WHERE `batch_id`=$batch_id AND `source`='$video_id'  ");
    return $result;
  }

  public function getAllVideosByVideoId($id)
  {
    $result =  $this->conn->query("SELECT * FROM `mt_videos` WHERE  `id`=$id  ");
    return $result;
  }

  public function getAllVideosByBatchIdSubjectId($batch_id, $subject_id)
  {
    $result =  $this->conn->query("SELECT * FROM `mt_videos` WHERE `batch_id`=$batch_id AND `subject_id`=$subject_id ORDER BY `subject_id` ");
    return $result;
  }

  public function getAllVideosByBatchIdSubjectIdChapterId($batch_id, $subject_id, $chapter_id)
  {

    $now               = time();
    $sql = "SELECT * FROM `mt_videos` WHERE `batch_id`=$batch_id AND `subject_id`=$subject_id  AND `chapter_id`=$chapter_id AND `release_time`<= $now ORDER BY `subject_id` ";
    // echo $sql;
    $result =  $this->conn->query($sql);
    return $result;
  }

  public function getAllVideosByBatchIdTomorrow($batch_id)
  {
    $now               = time();
    $today_morning     = strtotime("today");
    $today_midnight    = strtotime("tomorrow");
    $tomorrow_midnight = $today_midnight + 24 * 60 * 60;

    if (($today_midnight - $now) < 6 * 60 * 60 /* 6 hours before midnight */) {
      //echo "show tomorrow <br />";
      $start_time = $today_midnight;
      $end_time   = $tomorrow_midnight;
    } else {
      //echo "show today <br />";
      $start_time = $today_morning;
      $end_time   = $today_midnight;
    }

    //echo "Start time: " .date(" d.m.Y, h:i A", $start_time). "<br />End Time: ".date(" d.m.Y, h:i A", $end_time) ."<hr />";



    $sql = "SELECT * FROM `mt_videos` WHERE `batch_id`=$batch_id AND `release_time`>= $start_time AND `release_time`<= $end_time  ";

    //echo $sql;
    $result =  $this->conn->query($sql);
    return $result;
  }




  public function getAllValidSubjectsByBatchId($batch_id)
  {
    $result =  $this->conn->query("SELECT DISTINCT `subject_id` FROM `mt_videos` WHERE `batch_id`=$batch_id ORDER BY `subject_id` ");
    return $result;
  }

  public function getAllValidChaptersByBatchIdSubjectId($batch_id, $subject_id)
  {
    $sql = "SELECT DISTINCT `chapter_id` FROM `mt_videos` WHERE `batch_id`=$batch_id AND `subject_id`=$subject_id ORDER BY `chapter_id` ";
    $result =  $this->conn->query($sql);

    //echo $sql;
    return $result;
  }


  public function addVideoIdOnly($source)
  {
    $datestamp = time();

    $sql = "INSERT INTO `mt_videos`(`source`) VALUES ('$source' )";
    //echo $sql;
    $result =  $this->conn->query($sql);
    return true;
  }

  public function deleteAllVideos()
  {

    $sql = "DELETE FROM `mt_videos` WHERE 1";
    //echo $sql;
    $result =  $this->conn->query($sql);
    return true;
  }

  public function addVideo($title, $description, $source, $batch_id, $subject_id, $chapter_id, $release_time)
  {
    $datestamp = time();

    $sql = "INSERT INTO `mt_videos`(`title`, `description`,`source`,`batch_id`,`date`,`subject_id`,`chapter_id`,`release_time`) VALUES ('$title', '$description', '$source', $batch_id, $datestamp,$subject_id ,$chapter_id ,$release_time )";
    //echo $sql;
    $result =  $this->conn->query($sql);
    return true;
  }

  public function deleteVideo($id)
  {
    $sql = "DELETE FROM `mt_videos` WHERE `id`=$id";
    $result =  $this->conn->query($sql);

    if ($result) {
      return true;
    } else {
      return false;
    }
  }

  public function addChapter($title, $subject_id)
  {

    $sql = "INSERT INTO `mt_chapters`(`title`, `subject_id`) VALUES ('$title', '$subject_id')";
    //echo $sql;
    $result =  $this->conn->query($sql);
    return true;
  }

  public function deleteChapter($id)
  {
    $sql = "DELETE FROM `mt_chapters` WHERE `id`=$id";
    $result =  $this->conn->query($sql);

    if ($result) {
      return true;
    } else {
      return false;
    }
  }

  public function addBatch($title)
  {

    $sql = "INSERT INTO `mt_batches`(`title`) VALUES ('$title')";
    //echo $sql;
    $result =  $this->conn->query($sql);
    return true;
  }

  public function deleteBatch($id)
  {
    $sql = "DELETE FROM `mt_batches` WHERE `id`=$id";
    $result =  $this->conn->query($sql);

    if ($result) {
      return true;
    } else {
      return false;
    }
  }


  public function addSubject($title)
  {

    $sql = "INSERT INTO `mt_subjects`(`title`) VALUES ('$title')";
    //echo $sql;
    $result =  $this->conn->query($sql);
    return true;
  }

  public function deleteSubject($id)
  {
    $sql = "DELETE FROM `mt_subjects` WHERE `id`=$id";
    $result =  $this->conn->query($sql);

    if ($result) {
      return true;
    } else {
      return false;
    }
  }



  public function duplicateVideo($id)
  {

    $rest_of_fileds = "`description`, `thumb`, `source`, `date`, `batch_id`, `subject_id`, `chapter_id`, `release_time`";

    $sql = "INSERT INTO `mt_videos`(`title`, $rest_of_fileds) SELECT CONCAT(`title`, '(duplicate)'), $rest_of_fileds FROM `mt_videos` WHERE `id`=$id";
    //echo $sql;
    $result =  $this->conn->query($sql);



    if ($result) {
      echo "Duplicated Successfully";
    } else {
      echo "Error In Duplication";
    }

    return  $this->conn->insert_id;
  }



  public function deleteUser($id)
  {

    /****** delete answer DB entry  *******/
    $sql = "DELETE FROM `mt_users` WHERE `id`=$id";
    $result =  $this->conn->query($sql);

    if ($result) {
      echo "User Deleted Successfully";
    } else {
      echo "Error In Deleting";
    }
  }

  public function clearAppDeviceId($id)
  {

    $sql = "UPDATE `mt_users` SET `app_device`='' WHERE `id`=$id";
    $result =  $this->conn->query($sql);

    if ($result) {
      echo "Phone information reset. Phone can be changed now!";
    } else {
      echo "Error In Resetting";
    }
  }


  public function getAllBatches()
  {
    $result =  $this->conn->query("SELECT * FROM `mt_batches` WHERE 1 ");
    return $result;
  }

  public function getAllSubjects()
  {
    $result =  $this->conn->query("SELECT * FROM `mt_subjects` WHERE 1 ");
    return $result;
  }

  public function getAllChapters()
  {
    $result =  $this->conn->query("SELECT * FROM `mt_chapters` WHERE 1 ORDER BY `subject_id`");
    return $result;
  }

  public function getChaptersBySubject($subject)
  {
    $result =  $this->conn->query("SELECT * FROM `mt_chapters` WHERE subject_id ='$subject' ORDER BY `title`");
    return $result;
  }


  public function getAllYoutubeVideos()
  {
    $sql = "SELECT  *  FROM `mt_videos` WHERE 1 ORDER BY `source` DESC LIMIT 0,5 ";

    //echo $sql;
    $result =  $this->conn->query($sql);
    return $result;
  }

  public function getAllYoutubeVideosFull()
  {
    $sql = "SELECT *  FROM `mt_videos` WHERE 1 ORDER BY `source` ";

    //echo $sql;
    $result =  $this->conn->query($sql);
    return $result;
  }

  public function extractDetailsFromVideoId($youtube_id, &$std, &$subject, &$class_num, &$title_compact)
  {
    //get title from youtube.con
    $url = "http://youtube.com/get_video_info?video_id=" . $youtube_id;
    $content = file_get_contents($url);
    parse_str($content, $video_info_array);
    $title = json_decode($video_info_array['player_response'])->videoDetails->title;

    //convert multiple spaces to single space
    $title      = preg_replace('!\s+!', ' ', $title);
    $exp_title  = explode(" ", $title);

    $std = (int) substr(
      $title,
      stripos($title, "STD") + 4,
      2
    );

    if ($std == 0) { //could be plus one/two

      $std_str = substr(
        $title,
        stripos($title, "Plus"),
        8
      );

      if (stripos($std_str, "One")) {
        $std = 11;
      } else if (stripos($std_str, "Two")) {
        $std = 12;
      }

      $subject_start  = stripos($title, "Plus") + 9;
      $subject_end    =  stripos($title, "Class");

      $subject = substr(
        $title,
        $subject_start,
        $subject_end - $subject_start
      );
    } else { //standard 1 to 10

      $subject_start  = stripos($title, $exp_title[4]);
      $subject_end    =  stripos($title, "Class");

      $subject = substr(
        $title,
        $subject_start,
        $subject_end - $subject_start
      );
    }
    $subject = strtoupper($subject);


    $class_num_start  = stripos($title, "Class") + 5;
    $class_num_end    =  stripos($title, "(First");

    $class_num = (int) substr(
      $title,
      $class_num_start,
      $class_num_end - $class_num_start
    );


    $title_compact_start  = stripos($title, "VICTERS") + 7;
    $title_compact_end    =  stripos($title, "(First");

    $title_compact = substr(
      $title,
      $title_compact_start,
      $title_compact_end - $title_compact_start
    );
  }


  function getYoutubeVideoTitle($id)
  {

    $url = "http://youtube.com/get_video_info?video_id=" . $id;
    //echo $url;
    $content = file_get_contents($url);
    parse_str($content, $video_info_array);
    $title = json_decode($video_info_array['player_response'])->videoDetails->title;

    return $title;
  }

  function sendsms($mobileno, $message)
  {
    if (strlen($mobileno) != 10) { //mobile number should be 10 digit
      return "invalid number";
    } else if (strlen($message) > 918) {
      $message = substr($message, 0, 918); //limit size to maximum 918, which is the maximum sms size
    }
    /*
    http://sms.datagenit.com/api.php?username=Maheshc&password=197315&sender=DGENIT&sendto=918861133005&message=Hi%20Mahesh,%0ANice%20to%20meet%20you
    */
    $message_enc = urlencode($message);
    $time = time();
    $url = 'http://sms.datagenit.com/api.php?username=Maheshc&password=197315&sender=HARVST&sendto=91' . $mobileno . '&message=' . $message_enc;
    //echo "</p>".$url."</p>";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_POST, false);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    // Use file get contents when CURL is not installed on server.
    if (!$response) {
      $response = file_get_contents($url);
      //echo "server error";
      return "server error";
    }
    return "success";
  }

  /********* FOR ONLINE test END *************/


  /**
   * Getting all users
   */
  public function runQuery($query)
  {
    $result = $this->conn->query($query);
    return $result;
  }
}
