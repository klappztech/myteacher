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

    $result =  $this->conn->query("SELECT * FROM `hot_students` WHERE `username` = '$myusername' AND `test_subscribed`=1");
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

    $result =  $this->conn->query("SELECT * FROM `hot_students` WHERE `username` = '$myusername'");
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


  public function checkVideoAttendance($username, $video_id)
  {

    $result =  $this->conn->query("SELECT * FROM `hot_video_attendance` WHERE `username` = '$username' AND `video_id` = '$video_id' ");
    $count = $result->num_rows;

    if ($count > 0) {
      $return = 1; //already attended
    } else {
      $current_time = time();
      $sql = "INSERT INTO `hot_video_attendance`(`username`, `video_id`, `first_watched`) VALUES ('$username', '$video_id', $current_time) ";
      //echo $sql;
      $result =  $this->conn->query($sql);

      $return = 0; //not attended, but marked now
    }

    return $return;
  }


  public function userExist($myusername)
  {

    $result =  $this->conn->query("SELECT * FROM `hot_students` WHERE `username` = '$myusername'");
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
    $sql = "SELECT * FROM `hot_students` WHERE `id` = $id";
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
    $sql = "SELECT * FROM `hot_students` WHERE `username` = '$username'";
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
    $sql = "SELECT * FROM `hot_students` WHERE `id` = $id";
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
    $sql = "INSERT INTO `hot_students`(`name`,`username`, `password`,`batch_id`,`phone`, `test_subscribed`, `video_subscribed`) VALUES ('$name','$myusername','$hash',$batch_id, $phone,$test_subscribed,$video_subscribed) ";
    $result =  $this->conn->query($sql);
    //echo $sql;
    return $myusername;
  }

  public function editUser($id, $name, $batch_id, $phone, $test_subscribed, $video_subscribed)
  {
    $sql = "UPDATE `hot_students` SET `name`='$name', `phone`='$phone', `batch_id`=$batch_id, `test_subscribed`=$test_subscribed, `video_subscribed`=$video_subscribed  WHERE `id`=$id";
    $result =  $this->conn->query($sql);
    return $sql;
  }


  public function editVideo($id, $title, $batch_id, $release_time)
  {
    $sql = "UPDATE `hot_videos` SET `title`='$title', `batch_id`=$batch_id, `release_time`=$release_time  WHERE `id`=$id ";
    //echo $sql;
    $result =  $this->conn->query($sql);
    return $sql;
  }

  public function setDeviceId($id, $app_device)
  {
    $sql = "UPDATE `hot_students` SET `app_device`='$app_device'WHERE `id`=$id";
    $result =  $this->conn->query($sql);
    return $sql;
  }



  /* search candidates by PB_NO */
  public function add2Log($user_id, $username, $action, $param1)
  {
    $current_time = time();

    $result =  $this->conn->query("INSERT INTO `hot_log`(`TIME`, `USER_ID`, `USERNAME_TXT`, `ACTION`, `PARAM1`) VALUES ( $current_time,$user_id,'$username','$action', '$param1')");
  }

  /* list of all distincts agent who distributed gifts */
  public function getAlllog()
  {

    $result =  $this->conn->query("SELECT * FROM `hot_log` WHERE 1 ORDER BY `TIME` DESC LIMIT 100");
    return $result;
  }


  /********* FOR ONLINE test *************/

  public function updateAns($test_id, $student_id, $ans)
  {
    //echo $ans;

    $sql = "SELECT * FROM `hot_ans_sheet` WHERE  `test_id`=$test_id AND `student_id`=$student_id ";
    $result =  $this->conn->query($sql);

    if ($result->num_rows > 0) {
      $sql = "UPDATE `hot_ans_sheet` SET `ans`='$ans' WHERE `test_id`=$test_id AND `student_id`=$student_id ";
      $result =  $this->conn->query($sql);
    } else {
      // first time update
      $sql = "INSERT INTO `hot_ans_sheet`(`test_id`, `student_id`, `ans`) VALUES ($test_id,$student_id,'$ans')";
      $result =  $this->conn->query($sql);
      //echo "**INSERT**";
    }
  }

  public function updateAnsKey($test_id, $ans, $file_path)
  {
    $sql = "UPDATE `hot_ans_key` SET `ans_key_str`='$ans',`file_path`='$file_path' WHERE `id`=$test_id ";
    $result =  $this->conn->query($sql);
  }

  public function updateQuestionStr($test_id, $q_key_str)
  {
    $ans_key_str = "";
    //get corresponding answer key

    if ($q_key_str != "") {
      $q_id_array = explode(",",  $q_key_str);  //got array of all questions id

      for ($i = 0; $i < sizeof($q_id_array); $i++) {

        $sql = "SELECT `answer` FROM `hot_single_questions` WHERE `id`=$q_id_array[$i] ";
        $result =  $this->conn->query($sql);
        $count = $result->num_rows;

        if ($count == 1) {
          $row = $result->fetch_array();
          $ans_key_str = $ans_key_str . $row['answer'] . ",";
        } else {
          $ans_key_str = $ans_key_str . 'x,';
        }
      }
    }




    $sql = "UPDATE `hot_ans_key` SET `q_key_str`='$q_key_str',`ans_key_str`='$ans_key_str'  WHERE `id`=$test_id ";
    $result =  $this->conn->query($sql);
  }

  public function getAnsStr($test_id, $student_id)
  {
    $ret = "x";

    $sql = "SELECT `ans` FROM `hot_ans_sheet` WHERE  `test_id`=$test_id AND `student_id`=$student_id ";
    $result =  $this->conn->query($sql);

    if ($result->num_rows > 0) {
      $row = $result->fetch_array();
      $ret = $row['ans'];
    }

    return $ret;
  }

  public function getQKeyStr($test_id)
  {
    $ret = "x";

    $sql = "SELECT `q_key_str` FROM `hot_ans_key` WHERE  `id`=$test_id ";
    $result =  $this->conn->query($sql);

    if ($result->num_rows > 0) {
      $row = $result->fetch_array();
      $ret = $row['q_key_str'];
    }

    return $ret;
  }

  public function getFilePath($test_id)
  {
    $ret = "x";

    $sql = "SELECT `file_path` FROM `hot_ans_key` WHERE  `id`=$test_id ";
    $result =  $this->conn->query($sql);

    if ($result->num_rows > 0) {
      $row = $result->fetch_array();
      $ret = $row['file_path'];
    }

    return $ret;
  }


  public function getScore($test_id, $student_id)
  {
    $ret = "x";

    $sql = "SELECT `score` FROM `hot_ans_sheet` WHERE  `test_id`=$test_id AND `student_id`=$student_id ";
    $result =  $this->conn->query($sql);

    if ($result->num_rows > 0) {
      $row = $result->fetch_array();
      $ret = $row['score'];
    }

    return $ret;
  }




  public function getTestById($test_id)
  {

    $sql = "SELECT * FROM `hot_ans_key` WHERE  `id`=$test_id ";
    //echo $sql;
    $result =  $this->conn->query($sql);

    return $result;
  }


  public function getStudentById($id)
  {

    $sql = "SELECT * FROM `hot_students` WHERE  `id`=$id ";
    //echo $sql;
    $result =  $this->conn->query($sql);

    return $result;
  }

  public function getStudentByUsername($username)
  {

    $sql = "SELECT * FROM `hot_students` WHERE  `username`='$username' ";
    //echo $sql;
    $result =  $this->conn->query($sql);

    return $result;
  }

  public function getUsernameById($id)
  {

    $sql = "SELECT * FROM `hot_students` WHERE  `id`=$id ";
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

    $sql = "SELECT * FROM `hot_students` WHERE  `username`='$username' ";
    $result =  $this->conn->query($sql);

    if ($result->num_rows > 0) {
      $row = $result->fetch_array();
      $ret = $row['id'];
    }
    return $ret;
  }



  public function getAnsKeyStr($test_id)
  {
    $ret = "x";

    $sql = "SELECT `ans_key_str` FROM `hot_ans_key` WHERE  `id`=$test_id ";
    $result =  $this->conn->query($sql);

    if ($result->num_rows > 0) {
      $row = $result->fetch_array();
      $ret = $row['ans_key_str'];
    }

    return $ret;
  }

  public function markAsComplate($test_id, $student_id)
  {

    $sql = "UPDATE `hot_ans_sheet` SET `status`=1 WHERE `test_id`=$test_id AND `student_id`=$student_id ";
    $result =  $this->conn->query($sql);
  }

  public function IsComplate($test_id, $student_id)
  {

    $sql = "SELECT `status` FROM  `hot_ans_sheet` WHERE `test_id`=$test_id AND `student_id`=$student_id ";
    $result =  $this->conn->query($sql);
    $count = $result->num_rows;


    if ($count == 1) {
      $row = $result->fetch_array();

      $status = $row['status'];
    } else {
      $status = 0;
    }

    return $status;
  }

  public function IsStartedTest($test_id, $student_id)
  {

    $sql = "SELECT `status` FROM  `hot_ans_sheet` WHERE `test_id`=$test_id AND `student_id`=$student_id ";
    $result =  $this->conn->query($sql);
    $count = $result->num_rows;


    if ($count == 0) {
      return false;
    } else {
      return true;
    }
  }

  public function IsSMSSentByTest($test_id)
  {

    $sql = "SELECT `sms_sent` FROM `hot_ans_key` WHERE `id`=$test_id ";
    $result =  $this->conn->query($sql);
    $count = $result->num_rows;


    if ($count == 1) {
      $row = $result->fetch_array();

      $status = $row['sms_sent'];
    } else {
      $status = 0;
    }

    return $status;
  }

  public function getQuestionPaperType($test_id)
  {

    $sql = "SELECT `type` FROM `hot_ans_key` WHERE `id`=$test_id ";
    $result =  $this->conn->query($sql);
    $count = $result->num_rows;


    if ($count == 1) {
      $row = $result->fetch_array();

      $type = $row['type'];
    } else {
      $type = 0;
    }

    return $type;
  }




  public function getImageByQuestionId($id)
  {

    $sql = "SELECT `img` FROM `hot_single_questions` WHERE `id`=$id ";
    $result =  $this->conn->query($sql);
    $count = $result->num_rows;


    if ($count == 1) {
      $row = $result->fetch_array();

      $ret = $row['img'];
    } else {
      $ret = 0;
    }

    return $ret;
  }



  public function deleteSingleQuestion($id)
  {

    /****** move folder to "deleted"  *******/

    $sql = "SELECT * FROM `hot_single_questions` WHERE `id`=$id ";
    $result =  $this->conn->query($sql);
    $count = $result->num_rows;


    if ($count == 1) {
      $row = $result->fetch_array();

      $img = $row['img'];
      $ans_img = $row['ans_img'];

      rename("questions/" . $img, "deleted/" . $img);
      rename("answers/" . $ans_img, "deleted/" . $ans_img);
    } else {
      return  "Error: No Such Question Found!";
    }


    /****** delete DB entry  *******/
    $sql = "DELETE FROM `hot_single_questions` WHERE `id`=$id";
    $result =  $this->conn->query($sql);

    return  "Deleted Successfully";

  }

  public function getSolutionImageByQuestionId($id)
  {

    $sql = "SELECT `ans_img` FROM `hot_single_questions` WHERE `id`=$id ";
    $result =  $this->conn->query($sql);
    $count = $result->num_rows;


    if ($count == 1) {
      $row = $result->fetch_array();

      $ret = $row['ans_img'];
    } else {
      $ret = 0;
    }

    return $ret;
  }

  public function getSingleQuestionByQuestionId($id)
  {

    $sql = "SELECT * FROM `hot_single_questions` WHERE `id`=$id ";

    $result =  $this->conn->query($sql);
    $count = $result->num_rows;


    if ($count == 1) {
      $row = $result->fetch_array();

      $ret = $row;
    } else {
      $ret = NULL;
    }

    return $ret;
  }


  public function getAllSingleQuestionsBySubjectAndChapter($subject_id, $chapter_id)
  {

    $sql = "SELECT * FROM `hot_single_questions` WHERE `subject_id`=$subject_id  AND  `chapter_id`=$chapter_id ";
    $result =  $this->conn->query($sql);

    return $result;
  }

  public function markAsSMSSent($test_id)
  {

    $sql = "UPDATE `hot_ans_key` SET `sms_sent`=1 WHERE `id`=$test_id ";
    $result =  $this->conn->query($sql);
  }

  public function updateScore($test_id, $student_id, $score)
  {

    $getAnsKeyStr = "";
    $getAnsStr = "";

    $section1_score = 0;
    $section2_score = 0;
    $section3_score = 0;
    $total_score = 0;

    $section1_updated = false;
    $section2_updated = false;
    $section3_updated = false;




    //get ans by student

    $sql = "SELECT `ans` FROM `hot_ans_sheet` WHERE  `test_id`=$test_id AND `student_id`=$student_id ";
    $result_ans =  $this->conn->query($sql);

    if ($result_ans->num_rows > 0) {
      $row_ans = $result_ans->fetch_array();
      $getAnsStr = $row_ans['ans'];
    }


    // get KEY
    $sql = "SELECT * FROM `hot_ans_key` WHERE  `id`=$test_id ";
    $result =  $this->conn->query($sql);

    if ($result->num_rows > 0) {
      $row = $result->fetch_array();
      $getAnsKeyStr = $row['ans_key_str'];
      $num_ques = $row['num_question'];
    }


    $ans_key_array  = explode(",", $getAnsKeyStr);  //0 index = 1st question
    $ans_array      = explode(",", $getAnsStr);  //0 index = 1st question


    for ($i = 1; $i <= $num_ques; $i++) {

      if ($ans_array[$i - 1] != "x") {

        if ($ans_array[$i - 1] == $ans_key_array[$i - 1]) {
          $score = 4;
        } else {
          $score = -1;
        }

        // section 1
        if (($row['section1_start'] <= $i) && ($i <= $row['section1_end'])) {
          $section1_score += $score;
          $section1_updated = true;
          // section 2
        } else if (($row['section2_start'] <= $i) && ($i <= $row['section2_end'])) {
          $section2_score += $score;
          $section2_updated = true;
          // section 3
        } else if (($row['section3_start'] <= $i) && ($i <= $row['section3_end'])) {
          $section3_score += $score;
          $section3_updated = true;
        }

        $total_score += $score;
      }
    }

    //save scores 
    $sql = "UPDATE `hot_ans_sheet` SET `score`=$total_score ";

    if ($section1_updated) {
      $sql = $sql . ",`section1_score`=$section1_score ";
    }
    if ($section2_updated) {
      $sql = $sql . ",`section2_score`=$section2_score ";
    }
    if ($section3_updated) {
      $sql = $sql . ",`section3_score`=$section3_score ";
    }

    $sql = $sql . " WHERE `test_id`=$test_id AND `student_id`=$student_id ";

    //echo $sql;


    $result =  $this->conn->query($sql);
  }

  public function calculateScore($test_id, $student_id)
  {

    $sql = "SELECT * FROM `hot_ans_key` WHERE  `id`=$test_id ";
    $result =  $this->conn->query($sql);

    // get num , anskey
    if ($result->num_rows > 0) {

      $row = $result->fetch_array();

      $num_ques = $row['num_question'];
      $ans_str  = $row['ans_key_str'];
    } else {
      return "-";
    }

    // get students ans
    $sql = "SELECT `ans` FROM `hot_ans_sheet` WHERE  `test_id`=$test_id AND `student_id`=$student_id ";
    $result =  $this->conn->query($sql);

    if ($result->num_rows > 0) {
      $row = $result->fetch_array();
      $ans = $row['ans'];
    }


    $ans_key_array  = explode(",", $ans_str);  //0 index = 1st question
    $ans_array      = explode(",", $ans);  //0 index = 1st question


    $score = 0;
    for ($i = 1; $i <= $num_ques; $i++) {

      $my_ans =  isset($ans_array[$i - 1]) ? $ans_array[$i - 1] : "x";
      $ans_key  =  isset($ans_key_array[$i - 1]) ? $ans_key_array[$i - 1] : "x";

      if ($my_ans != "x") {
        if ($my_ans == $ans_key) {
          $score += 4;
        } else {
          $score += -1;
        }
      } else {
        $score += 0;
      }
    }


    $sql = "UPDATE `hot_ans_sheet` SET `score`=$score WHERE `test_id`=$test_id AND `student_id`=$student_id ";
    $result =  $this->conn->query($sql);

    return $score;
  }

  public function getStudentNameById($id)
  {

    $result =  $this->conn->query("SELECT * FROM `hot_students` WHERE `id` = $id ");
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

    $result =  $this->conn->query("SELECT * FROM `hot_students` WHERE `id` = $id ");
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

    $result =  $this->conn->query("SELECT * FROM `hot_batches` WHERE `id` = $id ");
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

    $result =  $this->conn->query("SELECT * FROM `hot_subjects` WHERE `id` = $id ");
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

    $result =  $this->conn->query("SELECT * FROM `hot_chapters` WHERE `id` = $id ");
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



  public function getStudentsByTest($test_id)
  {

    $result =  $this->conn->query("SELECT * FROM `hot_ans_sheet` WHERE `status`=1 AND `test_id`=$test_id ORDER BY `score` DESC");
    return $result;
  }

  public function getLiveStudentsByTest($test_id) // for live rank list
  {

    $result =  $this->conn->query("SELECT * FROM `hot_ans_sheet` WHERE  `test_id`=$test_id ORDER BY `score` DESC");
    return $result;
  }

  public function getAllTest()
  {
    $result =  $this->conn->query("SELECT * FROM `hot_ans_key` WHERE 1 ORDER BY `test_start` DESC");
    return $result;
  }

  public function getAllUpcomingTest()
  {
    $current_time = time();
    $result =  $this->conn->query("SELECT * FROM `hot_ans_key` WHERE $current_time<`test_start` ORDER BY `test_start` DESC");
    return $result;
  }

  public function getAllCompletedTest()
  {
    $current_time = time();
    $result =  $this->conn->query("SELECT * FROM `hot_ans_key` WHERE $current_time>`test_end` ORDER BY `test_start` DESC");
    return $result;
  }

  public function getNumCompletedTest()
  {
    $current_time = time();
    $result =  $this->conn->query("SELECT `id` FROM `hot_ans_key` WHERE $current_time>`test_end` ORDER BY `test_start` DESC");
    return $result->num_rows;
  }


  public function getNumAnsSheets()
  {
    $result =  $this->conn->query("SELECT `id` FROM `hot_ans_sheet` WHERE `status`=1");
    return $result->num_rows;
  }

  public function getNumStudentsAttended()
  {
    $result =  $this->conn->query(" SELECT DISTINCT `student_id` FROM `hot_ans_sheet`");
    return $result->num_rows;
  }


  public function getNumCompletedTestByStudentId($student_id)
  {
    $result =  $this->conn->query("SELECT `id` FROM `hot_ans_sheet` WHERE`student_id`=$student_id");
    return $result->num_rows;
  }

  public function getNumCompletedTestByBatchId($batch_id)
  {
    $result =  $this->conn->query("SELECT `id` FROM `hot_ans_key` WHERE`batch_id`=$batch_id");
    return $result->num_rows;
  }


  public function getNumCompletedTestByStudentIdLastWeek($student_id)
  {
    $lastweek = strtotime("last week");

    $sql = "SELECT * FROM `hot_ans_sheet` INNER JOIN `hot_ans_key` ON `hot_ans_sheet`.`test_id`= `hot_ans_key`.`id` WHERE `hot_ans_sheet`.`student_id`=$student_id AND `hot_ans_key`.`test_start`>$lastweek AND `hot_ans_sheet`.`status`=1";

    $result =  $this->conn->query($sql);
    //echo $sql;
    return $result->num_rows;
  }


  public function getNumCompletedTestByBatchIdLastWeek($batch_id)
  {
    $lastweek = strtotime("last week");
    $now = time();

    $sql = "SELECT `id` FROM `hot_ans_key` WHERE`batch_id`=$batch_id AND `test_start`>$lastweek AND `test_end`<$now ";

    $result =  $this->conn->query($sql);

    return $result->num_rows;
  }



  public function getAllOngoingTest()
  {
    $current_time = time();
    $result =  $this->conn->query("SELECT * FROM `hot_ans_key` WHERE $current_time <= `test_end` AND $current_time >= `test_start` ORDER BY `test_start` DESC");
    return $result;
  }

  public function getScheduledTestsByBatchId($batch_id)
  {
    $current_time = time();
    $result =  $this->conn->query("SELECT * FROM `hot_ans_key` WHERE `batch_id`=$batch_id AND $current_time <= `test_end` ORDER BY `window_start` DESC");
    return $result;
  }

  public function getBatchIdbyTestId($test_id)
  {
    $result =  $this->conn->query("SELECT `batch_id` FROM `hot_ans_key` WHERE `id`=$test_id");
    $count = $result->num_rows;

    if ($count == 1) {
      $row = $result->fetch_array();
      $batch_id = $row['batch_id'];
    } else {
      $batch_id = 9999;
    }

    return $batch_id;
  }

  public function getBatchIdbyStudentId($id)
  {
    $result =  $this->conn->query("SELECT `batch_id` FROM `hot_students` WHERE `id`=$id");
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
    $result =  $this->conn->query("SELECT * FROM `hot_students` WHERE `batch_id`=$batch_id AND `is_test_user`=0 ORDER BY `name` ASC");
    return $result;
  }

  public function getTestBlockedStudentsByBatchId($batch_id)
  {
    $result =  $this->conn->query("SELECT * FROM `hot_students` WHERE `batch_id`=$batch_id AND `is_test_user`=0 AND `test_subscribed`=0 ORDER BY `name` ASC");
    return $result;
  }

  public function getAllCrashDocs()
  {
    $result =  $this->conn->query("SELECT * FROM `hot_crash_docs` ORDER BY `date` DESC");
    return $result;
  }

  public function addDoc($title, $url, $video_url)
  {
    $datestamp = time();

    $sql = "INSERT INTO `hot_crash_docs`(`title`, `url`,`video_url`,`date`) VALUES ('$title', '$url', '$video_url', $datestamp)";
    //echo $sql;
    $result =  $this->conn->query($sql);
    return true;
  }


  public function getAllVideos()
  {
    $result =  $this->conn->query("SELECT * FROM `hot_videos` WHERE 1");
    return $result;
  }

  public function getAllVideosLimit20()
  {
    $result =  $this->conn->query("SELECT * FROM `hot_videos` WHERE 1 ORDER BY `id` DESC LIMIT 20");
    return $result;
  }

  public function getAllVideosByBatchSubjectChapter($batch_id, $subject_id, $chapter_id)
  {
    $sql = "SELECT * FROM `hot_videos` WHERE `batch_id`=$batch_id AND `subject_id`=$subject_id AND `chapter_id`=$chapter_id ORDER BY `id` DESC ";
    //echo $sql;
    $result =  $this->conn->query($sql);
    return $result;
  }

  public function getVideos()
  {
    $result =  $this->conn->query("SELECT * FROM `hot_videos` WHERE 1");
    return $result;
  }



  public function  IsWatchedByVideoIdUsername($video_id, $username)
  {
    $sql = "SELECT * FROM `hot_video_attendance` WHERE `video_id`='$video_id' AND `username`='$username'  ";
    //echo $sql;
    $result =  $this->conn->query($sql);

    $count = $result->num_rows;

    if ($count == 1) {
      $watched = true;
    } else {
      $watched = false;
    }

    //echo $watched;

    return $watched;
  }


  public function getAllVideosByBatchId($batch_id)
  {
    $result =  $this->conn->query("SELECT * FROM `hot_videos` WHERE `batch_id`=$batch_id ORDER BY `subject_id` ");
    return $result;
  }

  public function getAllVideosByBatchIdVideoId($batch_id, $video_id)
  {
    $result =  $this->conn->query("SELECT * FROM `hot_videos` WHERE `batch_id`=$batch_id AND `source`='$video_id'  ");
    return $result;
  }

  public function getAllVideosByVideoId($id)
  {
    $result =  $this->conn->query("SELECT * FROM `hot_videos` WHERE  `id`=$id  ");
    return $result;
  }

  public function getAllVideosByBatchIdSubjectId($batch_id, $subject_id)
  {
    $result =  $this->conn->query("SELECT * FROM `hot_videos` WHERE `batch_id`=$batch_id AND `subject_id`=$subject_id ORDER BY `subject_id` ");
    return $result;
  }

  public function getAllVideosByBatchIdSubjectIdChapterId($batch_id, $subject_id, $chapter_id)
  {

    $now               = time();
    $sql = "SELECT * FROM `hot_videos` WHERE `batch_id`=$batch_id AND `subject_id`=$subject_id  AND `chapter_id`=$chapter_id AND `release_time`<= $now ORDER BY `subject_id` ";
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



    $sql = "SELECT * FROM `hot_videos` WHERE `batch_id`=$batch_id AND `release_time`>= $start_time AND `release_time`<= $end_time  ";

    //echo $sql;
    $result =  $this->conn->query($sql);
    return $result;
  }




  public function updateVideoRestriction(
    $student_id,  //      
    $physics,     //  // Physics         
    $chemistry,   //  // Chemistry        
    $mathematics, //  // Mathematics    
    $botany,      //  // Botany     
    $zoology,     //  // Zoology       
    $english
  )     //  // English        
  {
    //echo $ans;

    $sql = "SELECT * FROM `hot_video_restict` WHERE  `student_id`=$student_id  ";
    $result =  $this->conn->query($sql);

    if ($result->num_rows > 0) {
      $sql = "UPDATE `hot_video_restict` SET `botany`=$botany,`chemistry`=$chemistry,`english`=$english,`mathematics`=$mathematics,`physics`= $physics,`zoology`=$zoology WHERE `student_id`=$student_id ";

      $result =  $this->conn->query($sql);
    } else {
      // first time update
      $sql = "INSERT INTO `hot_video_restict`(`student_id`, `botany`, `chemistry`, `english`, `mathematics`, `physics`, `zoology`) VALUES ($student_id, $botany, $chemistry, $english, $mathematics, $physics, $zoology)";

      $result =  $this->conn->query($sql);
    }
  }


  public function getVideoRestrictionByStudentId(
    $student_id,  //      
    &$physics,     //  // Physics         
    &$chemistry,   //  // Chemistry        
    &$mathematics, //  // Mathematics    
    &$botany,      //  // Botany     
    &$zoology,     //  // Zoology       
    &$english
  )     //  // English        
  {
    //echo $ans;

    $sql = "SELECT * FROM `hot_video_restict` WHERE  `student_id`=$student_id  ";
    $result =  $this->conn->query($sql);

    if ($result->num_rows > 0) {

      $row = $result->fetch_array();

      $physics     = $row['physics'];  // Physics    
      $chemistry   = $row['chemistry'];  // Chemistry  
      $mathematics = $row['mathematics'];  // Mathematics
      $botany      = $row['botany'];  // Botany     
      $zoology     = $row['zoology'];  // Zoology    
      $english     = $row['english'];  // English    


    } else {

      $physics     = 0;
      $chemistry   = 0;
      $mathematics = 0;
      $botany      = 0;
      $zoology     = 0;
      $english     = 0;
    }
  }

  public function IsStudentEnrolledForSubjectId($student_id, $subject_id)
  {

    $sql = "SELECT * FROM `hot_video_restict` WHERE  `student_id`=$student_id  ";
    $result =  $this->conn->query($sql);

    if ($result->num_rows > 0) {

      $row = $result->fetch_array();

      $physics     = $row['physics'];  // Physics    
      $chemistry   = $row['chemistry'];  // Chemistry  
      $mathematics = $row['mathematics'];  // Mathematics
      $botany      = $row['botany'];  // Botany     
      $zoology     = $row['zoology'];  // Zoology    
      $english     = $row['english'];  // English    

    } else {

      $physics     = 1; // if no entry, that means, no RESTRICTION
      $chemistry   = 1;
      $mathematics = 1;
      $botany      = 1;
      $zoology     = 1;
      $english     = 1;
    }
    switch ($subject_id) {
        // 1 Physics
        // 2 Chemistry
        // 3 Mathematics
        // 4 Botany
        // 5 Zoology
        // 6 English

      case 1:
        return $physics;
        break;

      case 2:
        return  $chemistry;
        break;

      case 3:
        return $mathematics;
        break;

      case 4:
        return $botany;
        break;

      case 5:
        return $zoology;
        break;

      case 6:
        return $english;
        break;
    }
  }




  public function getAllTestsByBatchIdTomorrow($batch_id)
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


    $sql = "SELECT * FROM `hot_ans_key` WHERE `batch_id`=$batch_id AND `test_start`>= $start_time AND `test_start`<= $end_time ";

    //echo $sql;
    $result =  $this->conn->query($sql);
    return $result;
  }


  public function getAllValidSubjectsByBatchId($batch_id)
  {
    $result =  $this->conn->query("SELECT DISTINCT `subject_id` FROM `hot_videos` WHERE `batch_id`=$batch_id ORDER BY `subject_id` ");
    return $result;
  }

  public function getAllValidChaptersByBatchIdSubjectId($batch_id, $subject_id)
  {
    $sql = "SELECT DISTINCT `chapter_id` FROM `hot_videos` WHERE `batch_id`=$batch_id AND `subject_id`=$subject_id ORDER BY `chapter_id` ";
    $result =  $this->conn->query($sql);

    //echo $sql;
    return $result;
  }


  public function addVideo($title, $description, $source, $batch_id, $subject_id, $chapter_id, $release_time)
  {
    $datestamp = time();

    $sql = "INSERT INTO `hot_videos`(`title`, `description`,`source`,`batch_id`,`date`,`subject_id`,`chapter_id`,`release_time`) VALUES ('$title', '$description', '$source', $batch_id, $datestamp,$subject_id ,$chapter_id ,$release_time )";
    //echo $sql;
    $result =  $this->conn->query($sql);
    return true;
  }

  public function deleteVideo($id)
  {
    $sql = "DELETE FROM `hot_videos` WHERE `id`=$id";
    $result =  $this->conn->query($sql);

    if ($result) {
      return true;
    } else {
      return false;
    }
  }

  public function addChapter($title, $subject_id)
  {

    $sql = "INSERT INTO `hot_chapters`(`title`, `subject_id`) VALUES ('$title', '$subject_id')";
    //echo $sql;
    $result =  $this->conn->query($sql);
    return true;
  }

  public function deleteChapter($id)
  {
    $sql = "DELETE FROM `hot_chapters` WHERE `id`=$id";
    $result =  $this->conn->query($sql);

    if ($result) {
      return true;
    } else {
      return false;
    }
  }

  public function addBatch($title)
  {

    $sql = "INSERT INTO `hot_batches`(`title`) VALUES ('$title')";
    //echo $sql;
    $result =  $this->conn->query($sql);
    return true;
  }

  public function deleteBatch($id)
  {
    $sql = "DELETE FROM `hot_batches` WHERE `id`=$id";
    $result =  $this->conn->query($sql);

    if ($result) {
      return true;
    } else {
      return false;
    }
  }


  public function addSubject($title)
  {

    $sql = "INSERT INTO `hot_subjects`(`title`) VALUES ('$title')";
    //echo $sql;
    $result =  $this->conn->query($sql);
    return true;
  }

  public function deleteSubject($id)
  {
    $sql = "DELETE FROM `hot_subjects` WHERE `id`=$id";
    $result =  $this->conn->query($sql);

    if ($result) {
      return true;
    } else {
      return false;
    }
  }

  public function deleteDoc($id)
  {
    $sql = "DELETE FROM `hot_crash_docs` WHERE `id`=$id";
    $result =  $this->conn->query($sql);

    if ($result) {
      return true;
    } else {
      return false;
    }
  }

  public function getCompletedTestsByStudentId($student_id) //todo: phase2
  {
    $current_time = time();
    $sql = "SELECT * FROM `hot_ans_sheet` WHERE `student_id`=$student_id AND `status`=1 ORDER BY `id` DESC";
    $result =  $this->conn->query($sql);

    //echo $sql;
    return $result;
  }

  public function ifWithinTestTiming($test_id)
  {
    $current_time = time();
    $result =  $this->conn->query("SELECT * FROM `hot_ans_key` WHERE `id`=$test_id AND $current_time < `test_end` AND $current_time > `test_start`");
    $count = $result->num_rows;

    if ($count == 1) {
      return true;
    } else {
      return false;
    }
  }

  public function isTestTimeOver($test_id)
  {
    $current_time = time();
    $result =  $this->conn->query("SELECT * FROM `hot_ans_key` WHERE `id`=$test_id AND $current_time > `test_end` ");
    $count = $result->num_rows;

    if ($count == 1) {
      return true;
    } else {
      return false;
    }
  }



  public function getQuestionNumberById($id)
  {

    $result =  $this->conn->query("SELECT * FROM `hot_ans_key` WHERE `id` = $id ");
    $count = $result->num_rows;

    if ($count == 1) {
      $row = $result->fetch_array();

      $num_question = $row['num_question'];
    } else {
      $num_question = 0;
    }

    return $num_question;
  }

  public function getQuestionPaperById($id)
  {

    $row = null;

    $result =  $this->conn->query("SELECT * FROM `hot_ans_key` WHERE `id` = $id ");
    $count = $result->num_rows;

    if ($count == 1) {
      $row = $result->fetch_array();
    }

    return $row;
  }

  public function IsJEE($id)
  {

    $result =  $this->conn->query("SELECT `is_jee` FROM `hot_ans_key` WHERE `id` = $id ");
    $count = $result->num_rows;

    if ($count == 1) {
      $row = $result->fetch_array();
      return $row['is_jee'];
    }

    return 0;
  }

  public function createQuestion(
    $subject_id,
    $chapter_id,
    $question_file_name,
    $answer_file_name,
    $question_answer,
    $source
  ) {

    $sql = "INSERT INTO hot_single_questions(img, subject_id, chapter_id, answer,ans_img, source) VALUES ('$question_file_name', $subject_id, $chapter_id, '$question_answer','$answer_file_name', '$source' )";
    $result =  $this->conn->query($sql);
    return  $this->conn->insert_id;
  }

  public function createTest( // deprecated
    $title,
    $num_question,
    $num_pages,
    $test_start,
    $test_end,
    $window_start,
    $window_end,
    $batch_id,
    $is_jee
  ) {

    $sql = "INSERT INTO `hot_ans_key`(`title`, `num_question`,`num_pages`, `window_start`, `window_end`, `test_start`, `test_end`, `batch_id`, `is_jee`) VALUES ('$title', $num_question,$num_pages,$test_start,$test_end,$window_start,$window_end,$batch_id,$is_jee)";

    $result =  $this->conn->query($sql);

    return  $this->conn->insert_id;
  }

  public function createTestFull(
    $title,
    $num_question,
    $num_pages,
    $window_start,
    $window_end,
    $test_start,
    $test_end,
    $batch_id,
    $is_jee,
    $num_sections,
    $section1_title,
    $section1_start,
    $section1_end,
    $section2_title,
    $section2_start,
    $section2_end,
    $section3_title,
    $section3_start,
    $section3_end,
    $type
  ) {

    $sql = "INSERT INTO `hot_ans_key`( `title`, `num_question`,`num_pages`,`window_start`, `window_end`, `test_start`, `test_end`, `batch_id`, `is_jee`, `num_sections`, `section1_title`, `section1_start`, `section1_end`, `section2_title`, `section2_start`, `section2_end`, `section3_title`, `section3_start`, `section3_end`, `type`) VALUES ('$title', $num_question, $num_pages, $window_start,  $window_end,  $test_start,  $test_end,  $batch_id,  $is_jee,  $num_sections,  '$section1_title', $section1_start,  $section1_end,  '$section2_title', $section2_start,  $section2_end, '$section3_title', $section3_start,  $section3_end, $type )";


    $result =  $this->conn->query($sql);

    return  $this->conn->insert_id;
  }

  public function duplicateTest($test_id)
  {

    $rest_of_fileds = "`type`, `num_question`, `num_pages`, `sms_sent`, `ans_key_str`, `window_start`, `window_end`, `test_start`, `test_end`, `batch_id`, `file_path`, `is_jee`, `num_sections`, `section1_title`, `section1_start`, `section1_end`, `section2_title`, `section2_start`, `section2_end`, `section3_title`, `section3_start`, `section3_end`, `q_key_str`";

    $sql = "INSERT INTO `hot_ans_key`(`title`, $rest_of_fileds) SELECT CONCAT(`title`, '_duplicate'), $rest_of_fileds FROM `hot_ans_key` WHERE `id`=$test_id";
    //echo $sql;
    $result =  $this->conn->query($sql);



    if ($result) {
      echo "Test Duplicated Successfully";
    } else {
      echo "Error In Duplication";
    }

    return  $this->conn->insert_id;
  }

  public function duplicateVideo($id)
  {

    $rest_of_fileds = "`description`, `thumb`, `source`, `date`, `batch_id`, `subject_id`, `chapter_id`, `release_time`";

    $sql = "INSERT INTO `hot_videos`(`title`, $rest_of_fileds) SELECT CONCAT(`title`, '(duplicate)'), $rest_of_fileds FROM `hot_videos` WHERE `id`=$id";
    //echo $sql;
    $result =  $this->conn->query($sql);



    if ($result) {
      echo "Duplicated Successfully";
    } else {
      echo "Error In Duplication";
    }

    return  $this->conn->insert_id;
  }



  public function editTest(
    $id,
    $title,
    $test_start,
    $test_end,
    $batch_id
  ) {

    $sql = "UPDATE `hot_ans_key` SET `title`='$title',`test_start`=$test_start,`test_end`=$test_end,`batch_id`=$batch_id WHERE `id`=$id";

    // echo $sql;

    $result =  $this->conn->query($sql);
    return  $sql;
  }


  public function deleteTest($id)
  {

    /****** move folder to "deleted"  *******/

    $result =  $this->conn->query("SELECT * FROM `hot_ans_key` WHERE `id` = $id ");
    $count = $result->num_rows;

    if ($count == 1) {
      $row = $result->fetch_array();
      $path = $row['file_path'];
    } else {
      $path = 0;
    }

    $same_file_result =  $this->conn->query("SELECT * FROM `hot_ans_key` WHERE `file_path` ='$path' ");

    if ($same_file_result->num_rows == 1) {

      $path_split = explode("/", $path);

      $old = $path_split[0] . "/" . $path_split[1];
      $new = "deleted/" . $path_split[0] . "/" . $path_split[1];
      //echo $old . "-->" . $new;

      rename($path_split[0] . "/" . $path_split[1], "deleted/" . $path_split[0] . "/" . $path_split[1]);
      echo "File Moved to Trash. <br />";
    } else {
      echo "File Already in Use for Other Test. <br />";
    }

    /****** delete DB entry  *******/
    $sql = "DELETE FROM `hot_ans_key` WHERE `id`=$id";

    $result =  $this->conn->query($sql);

    /****** delete answer DB entry  *******/
    $sql = "DELETE FROM `hot_ans_sheet` WHERE `test_id`=$id";

    $result =  $this->conn->query($sql);

    if ($result) {
      echo "Test Deleted Successfully";
    } else {
      echo "Error In Deleting";
    }
  }

  public function deleteUser($id)
  {

    /****** delete answer DB entry  *******/
    $sql = "DELETE FROM `hot_students` WHERE `id`=$id";
    $result =  $this->conn->query($sql);

    if ($result) {
      echo "User Deleted Successfully";
    } else {
      echo "Error In Deleting";
    }
  }

  public function clearAppDeviceId($id)
  {

    $sql = "UPDATE `hot_students` SET `app_device`='' WHERE `id`=$id";
    $result =  $this->conn->query($sql);

    if ($result) {
      echo "Phone information reset. Phone can be changed now!";
    } else {
      echo "Error In Resetting";
    }
  }


  public function getAllBatches()
  {
    $result =  $this->conn->query("SELECT * FROM `hot_batches` WHERE 1 ");
    return $result;
  }

  public function getAllSubjects()
  {
    $result =  $this->conn->query("SELECT * FROM `hot_subjects` WHERE 1 ");
    return $result;
  }

  public function getAllChapters()
  {
    $result =  $this->conn->query("SELECT * FROM `hot_chapters` WHERE 1 ORDER BY `subject_id`");
    return $result;
  }

  public function getChaptersBySubject($subject)
  {
    $result =  $this->conn->query("SELECT * FROM `hot_chapters` WHERE subject_id ='$subject' ORDER BY `title`");
    return $result;
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
