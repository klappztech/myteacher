<?php
session_start();

if (isset($_SESSION['username'])) {

   if ($_SESSION['is_admin'] == 0) {
      header("location:welcome.php");
   } else {
      header("location:welcome.php");
   }
}

include_once 'db_functions.php';
$db = new DB_Functions();

if (isset($_POST['username'])) {
   // username and password sent from form 

   $myusername = $_POST['username'];
   $mypassword = $_POST['password'];


   $login_result = $db->loginCheckHash($myusername, $mypassword);

   if ($login_result == true) {


      //$db->add2Log( $_SESSION['user_id'], $_SESSION['username'], "logged in", "");


      if ($_SESSION['is_admin'] == 0) {
         header("location:welcome.php");
      } else {
         header("location:welcome.php");
      }
   }
}
?>



<!DOCTYPE html>

<?php

include_once 'db_functions.php';
$db = new DB_Functions();

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

   <link href="http://hayageek.github.io/jQuery-Upload-File/4.0.11/uploadfile.css" rel="stylesheet">
   <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
   <script src="http://hayageek.github.io/jQuery-Upload-File/4.0.11/jquery.uploadfile.min.js"></script>

   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.js"></script>


   <link rel="stylesheet" type="text/css" href="css/style.css">
   <script src="js/myscript.js"></script>

   <script>
      $(document).ready(function() {


         var extraObj = $("#fileuploader").uploadFile({
            url: "upload.php",
            fileName: "myfile",

            autoSubmit: false
         });
         $("#extrabutton").click(function() {

            extraObj.startUpload();
         });




      });
   </script>
</head>


<?php $pdf_path = "assets/q/5Q.pdf" ?>

<body>


   <header id="header-bar" class="w3-container w3-top w3-theme ">
      <div id="server-results" style="width:500px;word-wrap: break-word;"></div>

      <div class="container-fluid">
         <div class="row">
            <div class="col-xs-6">
               <h4 class="w3-bar-item">MyTeacher</h4>
            </div>
            <div class="col-xs-3">
               <h4> </h4>
            </div>
            <div class="col-xs-3">
               <!-- <button type="button" id="btn_complete_test" class="btn btn-warning">SUBMIT</button> -->
            </div>
         </div>
      </div>

   </header>

   <div class="container-fluid text-center " style="margin-top:100px;">
      <div class="row content">
         <div class="col-sm-2 sidenav">
         </div>
         <div class="col-sm-8 text-left">


            <h2>Login</h2>
            <form action="login.php" method="post" id="loginForm">
               <?php if (isset($login_result) && $login_result == false) { ?>
                  <div class="alert alert-danger">Invalid Username/Password or Blocked User. Please Try Again or Contact Admin!</div>
               <?php }  ?>
               <div class="form-group">
                  <label for="username">Username:</label>
                  <input type="username" class="form-control" placeholder="Enter username" id="username" name="username" autocomplete="off" minlength="3" required>
               </div>
               <div class="form-group">
                  <label for="pwd">Password:</label>
                  <input type="password" class="form-control" placeholder="Enter password" id="pwd" name="password" autocomplete="off" minlength="3" required>
               </div>
               <div class="form-group form-check">
                  <label class="form-check-label">
                     <input class="form-check-input" type="checkbox"> Remember me
                  </label>
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
   $("#loginForm").validate();
</script>

</html>