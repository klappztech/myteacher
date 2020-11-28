<?php
include('session_admin.php');
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

  <script src="https://kit.fontawesome.com/f7fd399f30.js" crossorigin="anonymous"></script>


  <link rel="stylesheet" type="text/css" href="css/style.css">
  <script src="js/myscript.js"></script>

  <script>
    $(document).ready(function() {

      /////// OMR BUTTON

    });
  </script>



</head>



<body>

  <header id="header-bar" class="w3-container w3-top w3-theme ">
    <div id="server-results" style="width:500px;word-wrap: break-word;"></div>

    <div class="container-fluid">
      <div class="row">
        <div class="col-xs-2 text-left">
          <span style="font-size:30px;cursor:pointer;margin:0;" onclick="goBack()"> <i class="fas  fa-xs fa-arrow-left"></i> </span>
        </div>
        <div class="col-xs-8 text-center">
          <h4 class="w3-bar-item">MyTeacher</h4>
        </div>

        <div class="col-xs-2">
          <!-- <button type="button" id="btn_complete_test" class="btn btn-warning">SUBMIT</button> -->
        </div>
      </div>
    </div>

  </header>

  <div class="container-fluid text-center " style="margin-top:100px;margin-bottom:100px;">
    <div class="row content">
      <div class="col-sm-2 sidenav">
      </div>
      <div class="col-sm-8 text-left">

        <h2>Batches</h2>


        <table class="table table-striped">
          <tr>
            <th>Select Batch</th>
          </tr>
          <?php

          $search_result = $db->getAllValidSubjectsByBatchId($_GET['batch_id']);

          while ($row =  $search_result->fetch_array()) {

          ?>
            <tr>
              <td><a href="harvid_chapter_list.php?subject_id=<?php echo $row['subject_id']; ?>&batch_id=<?php echo $_GET['batch_id']; ?>"><?php echo $db->getSubjectNameById($row['subject_id']); ?></a></td>
            </tr>

          <?php } ?>

        </table>

      </div>
      <div class="col-sm-2 sidenav">

      </div>
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


</html>