<?php
include('session_admin.php');
?>

<?php

include_once 'db_functions.php';
$db = new DB_Functions();

if (isset($_POST['username'])) {
   // username and password sent from form 

   //print_r($_POST);

   $name = $_POST['name'];
   $myusername = $_POST['username'];
   $mypassword = $_POST['password'];
   $mypassword2 = $_POST['password2'];
   $phone               = $_POST['phone'];
   $batch_id            = $_POST['batch_id'];
   $test_subscribed     = $_POST['test_subscribed'];
   $video_subscribed    = $_POST['video_subscribed'];

   if ($mypassword != $mypassword2) {
      $pwd_mismatch = true;
   } else if ($db->userExist($myusername)) {
      $user_exist = true;
   } else {
      $login_result = $db->createUser($name, $myusername, $mypassword, $batch_id, $phone,$test_subscribed,$video_subscribed);
   }
}
?>

<!DOCTYPE html>

<?php

include_once 'db_functions.php';
$db = new DB_Functions();

?>

<head>
   <title>Harvest - Online Test</title>
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

   <link href="http://hayageek.github.io/jQuery-Upload-File/4.0.11/uploadfile.css" rel="stylesheet">
   <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
   <script src="http://hayageek.github.io/jQuery-Upload-File/4.0.11/jquery.uploadfile.min.js"></script>

   <script src="https://kit.fontawesome.com/f7fd399f30.js" crossorigin="anonymous"></script>

   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.js"></script>

   <link rel="stylesheet" type="text/css" href="css/style.css">
   <script src="js/myscript.js"></script>
   <title>Create User</title>
</head>



<body>
   <div id="mySidenav" class="sidemenu">
   </div>


   <header id="header-bar" class="w3-container w3-top w3-theme ">
      <div id="server-results" style="width:500px;word-wrap: break-word;"></div>

      <div class="container-fluid">
         <div class="row">
            <div class="col-xs-2 text-left">
               <span style="font-size:30px;cursor:pointer;margin:0;" onclick="openNav()"><i class="fas fa-xs fa-bars"></i></span>
            </div>
            <div class="col-xs-8 text-center">
               <h4 class="w3-bar-item">Harvest</h4>
            </div>

            <div class="col-xs-2">
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

            <h2>Create User</h2>

            <?php if (isset($pwd_mismatch)) { ?>
               <div class="alert alert-danger">Passwords doesn't match!</div>
            <?php } else if (isset($user_exist)) { ?>
               <div class="alert alert-danger">Username already taken, Please choose another one!</div>
            <?php } else if (isset($login_result)) { ?>
               <div class="alert alert-success">"<?php echo $login_result; ?>" added!</div>
            <?php }  ?>


            <form action="create_user.php" method="post" id="mainForm">

               <div class="form-group">
                  <label for="username">Name:</label>
                  <input type="text" class="form-control" placeholder="Enter Name" id="name" name="name" autocomplete="off" minlength="3" required>
               </div>
               <div class="form-group">
                  <label for="username">Username:</label>
                  <input type="text" class="form-control" placeholder="Enter Username" id="username" name="username" autocomplete="off" minlength="3" required>
               </div>
               <div class="form-group">
                  <label for="pwd">Password:</label>
                  <input type="password" class="form-control" placeholder="Enter Password" id="pwd" name="password" autocomplete="off" minlength="6" required>
               </div>
               <div class="form-group">
                  <label for="batch">Confirm Password:</label>
                  <input type="password" class="form-control" placeholder="Confirm Password" id="pwd2" name="password2" autocomplete="off" minlength="6" required>
               </div>
               <div class="form-group">
                  <label for="sel1">Batch</label>
                  <select class="form-control" id="sel1" name="batch_id">
                     <?php
                     $search_result = $db->getAllBatches();
                     while ($row =  $search_result->fetch_array()) {
                        echo "<option value=" . $row['id'] . ">" . $row['title'] . "</option>";
                     }

                     ?>
                  </select>
               </div>

               <div class="form-group">
                  <label for="username">Phone:</label>
                  <input type="number" class="form-control" placeholder="Enter 10 Digit Phone Number" id="phone" name="phone" autocomplete="off" minlength="10" maxlength="10" required>
               </div>

               <div class="form-group">
                  <label for="sel2">Enrolled for Online Test</label>
                  <select class="form-control" id="sel2" name="test_subscribed">
                     <option value="1">Yes</option>
                     <option value="0">No</option>
                  </select>
               </div>
               <div class="form-group">
                  <label for="sel3">Enrolled for Video App</label>
                  <select class="form-control" id="sel3" name="video_subscribed">
                     <option value="1">Yes</option>
                     <option value="0">No</option>
                  </select>
               </div>


               <button type="submit" class="btn btn-primary">Submit</button>



            </form>
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