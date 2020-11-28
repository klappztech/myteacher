<?php
include('session_admin.php');
?>
<!DOCTYPE html>


<?php

include_once 'db_functions.php';
$db = new DB_Functions();

$video_id = isset($_GET['video_id']) ? $_GET['video_id'] : -1; // NOT Youtube Id


if (isset($_POST['submit'])) {

   // print_r($_POST);

   $title             = $_POST['title'];
   $batch_id          = $_POST['batch_id'];
   $release_time      = $_POST['release_time'];
   $video_id                = $_POST['video_id'];


   $updated = $db->editVideo($video_id, $title, $batch_id, strtotime($release_time));
}

$result = $db->getAllVideosByVideoId($video_id);

if ($result->num_rows > 0) {
   $row = $result->fetch_array();
}

if (isset($_GET['from_add_vid']) || isset($_POST['from_add_vid']) ) {
   $return_link = "harvid_add.php";
} else {
   $return_link = "harvid_list.php?batch_id=" . $row['batch_id'] . "&subject_id=" . $row['subject_id'] . "&chapter_id=" . $row['chapter_id'];
}


?>

<head>
   <title>MyTeacher - Online Test</title>
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <meta http-equiv="content-type" content="text/html; charset=UTF-8">
   <meta name="robots" content="noindex, nofollow">
   <meta name="googlebot" content="noindex, nofollow">
   <meta name="viewport" content="width=device-width, initial-scale=1">

   <link rel="stylesheet" href="css/w3pro.css">
   <link rel="stylesheet" href="css/w3-theme-teal.css">
   <link rel="stylesheet" href="css/bootstrap.min.css">


   <script src="js/jquery.min.js"></script>
   <script src="js/bootstrap.min.js"></script>


   <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
   <link href="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/build/css/bootstrap-datetimepicker.css" rel="stylesheet">


   <script type="text/javascript" src="//code.jquery.com/jquery-2.1.1.min.js"></script>
   <script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
   <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
   <script src="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js"></script>

   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.js"></script>

   <script src="https://kit.fontawesome.com/f7fd399f30.js" crossorigin="anonymous"></script>


   <link rel="stylesheet" type="text/css" href="css/style.css">
   <script src="js/myscript.js"></script>

   <script>
      $(document).ready(function() {
         /////// DELETE BUTTON
         $("#btn_delete").click(function() {
            var r = confirm("Are you sure to delete?");
            if (r == true) {
               $("#delete_form").submit();
               return true;
            } else {
               return false;
            }
         });




      });
   </script>
</head>



<body>
   <div id="mySidenav" class="sidemenu">
   </div>


   <header id="header-bar" class="w3-container w3-top w3-theme ">
      <div id="server-results" style="width:500px;word-wrap: break-word;"></div>

      <div class="container-fluid">
         <div class="row">
            <div class="col-xs-2 text-left">
               <a style="font-size:30px;cursor:pointer;margin:0;" class="navig-btn" href="<?php echo $return_link; ?>"><i class="fas fa-xs fa-arrow-left"></i></a>
            </div>
            <div class="col-xs-8 text-center">
               <h4 class="w3-bar-item">MyTeacher</h4>
            </div>

            <div class="col-  xs-2">
               <!-- <button type="button" id="btn_complete_test" class="btn btn-warning">SUBMIT</button> -->
            </div>
         </div>
      </div>

   </header>

   <div class="container text-center " style="margin-top:100px;margin-bottom:100px;">
      <div class="row content">
         <div class="col-sm-2 sidenav">
         </div>
         <div class="col-sm-8 text-left">

            <h2>Edit Video</h2>
            <?php if (isset($updated)) { ?>
               <div class="alert alert-success">Updated Successfully!</div>
            <?php }  ?>

            <form action="harvid_edit.php" method="post" id="mainForm">
               <?php
               //if (strlen($row['source']) > 4) {
               //   echo '<a href="https://www.youtube.com/watch?v=' . $row['source'] . '" ><img src="https://img.youtube.com/vi/'.$row['source'].'/default.jpg"></a>';
               //} 
               ?>
               <div class="form-group">
                  <label for="title">Title</label>
                  <input value="<?php echo $row['title'] ?>" type="text" class="form-control" placeholder="Enter Name" id="name" name="title" autocomplete="off" minlength="3" required>
               </div>


               <div class="form-group">
                  <label for="sel1">Batch</label>
                  <select class="form-control" id="sel1" name="batch_id">
                     <?php
                     $search_result = $db->getAllBatches();
                     while ($row_select =  $search_result->fetch_array()) {
                        echo '<option value="' . $row_select['id'] . '"';
                        if ($row_select['id'] == $row['batch_id']) {
                           echo " selected";
                        }
                        echo " >" . $row_select['title'] . "</option>";
                     }

                     ?>
                  </select>
               </div>


               <div class="form-group">
                  <label for="dtpickerdemo" class="control-label">Release Time</label>
                  <input value="<?php echo  date(" d.m.Y, h:i A", $row['release_time']) ?>" type='text' class="form-control" name="release_time" autocomplete="off" minlength="18" required />

               </div>


               <div class="row">
                  <div class="col-xs-12">

                     <button type="submit" class="btn btn-primary btn-block" name="submit">Save</button>
                  </div>
               </div>
               <p></p>
               <input type="hidden" name="video_id" value="<?php echo    $video_id ?>">

               <?php
               if (isset($_GET['from_add_vid'])) {
                  echo '<input type="hidden" name="from_add_vid" value="1">';
               }
               ?>
               

            </form>

            <div class="row">

               <div class="col-xs-6">
                  <form action="fn_delete_video.php" method="POST" id="delete_form">
                     <button class="btn btn-danger btn-block" id="btn_delete">Delete</button>
                     <input type="hidden" name="video_id" value="<?php echo    $video_id ?>">
                  </form>
               </div>

               <div class="col-xs-6">

                  <form action="fn_duplicate_video.php" method="POST" id="duplicate_form">
                     <button class="btn btn-warning btn-block" id="btn_clear_device_id">Duplicate</button>
                     <input type="hidden" name="video_id" value="<?php echo    $video_id ?>">
                  </form>
               </div>
            </div>

         </div>

         <div class="col-sm-2 sidenav"> </div>
      </div>
   </div>

   <footer id="footer-bar" class="w3-container w3-bottom w3-margin-top">

      <div class="container-fluid">
         <div class="row">
            <div class="col-xs-9">
            </div>
            <div class="col-xs-3">
               <!-- <a href="index.php" type="button" id="show-home" class="btn btn-warning fullwidth-btn">Home</a> -->
            </div>
         </div>
      </div>

   </footer>


</body>

<script>
   $("#mainForm").validate();
</script>

</html>