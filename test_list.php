<?php
include('session_admin.php');
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

  <script src="https://kit.fontawesome.com/f7fd399f30.js" crossorigin="anonymous"></script>


  <link rel="stylesheet" type="text/css" href="css/style.css">
  <script src="js/myscript.js"></script>

  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.js"></script>


  <script>
    $(document).ready(function() {

      $('#completed_table').DataTable();

      /////// OMR BUTTON

    });
  </script>
</head>


<?php $pdf_path = "assets/q/5Q.pdf" ?>

<body>
  <div id="mySidenav" class="sidemenu">
  </div>


  <header id="header-bar" class="w3-container w3-top w3-theme ">
    <div id="server-results" style="width:500px;word-wrap: break-word;"></div>

    <div class="container-fluid">
      <div class="row">
        <div class="col-xs-2 text-left">
          <span style="font-size:30px;cursor:pointer;margin:0;" onclick="openNav()"><i class="fas  fa-xs fa-bars"></i></span>
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





  <div class="container-fluid text-center " style="margin-top:100px;margin-bottom:100px;">
    <div class="row content">
      <div class="col-sm-2 sidenav">
      </div>
      <div class="col-sm-8 text-left">

        <div class="row content">
          <div class="col-sm-4 ">
            <div class="w3-panel w3-card">
              <p>Completed Tests</p>
              <h1><?php echo $db->getNumCompletedTest() ?></h1>
            </div>

          </div>
          <div class="col-sm-4 ">
            <div class="w3-panel w3-card">
              <p>Answer Sheets</p>
              <h1><?php echo $db->getNumAnsSheets() ?></h1>

            </div>

          </div>
          <div class="col-sm-4 ">
            <div class="w3-panel w3-card">
              <p>Students Attended</p>
              <h1><?php echo $db->getNumStudentsAttended() ?></h1>
            </div>

          </div>
        </div>

        <h2>Ongoing Tests</h2>
        <table class="table table-striped">
          <thead>
            <tr>
              <th>#</th>
              <th>Title</th>
              <th>Batch</th>
              <th>Time</th>
              <th></th>

            </tr>
          </thead>
          <tbody>


            <?php
            $search_result = $db->getAllOngoingTest();
            $number = 0;
            while ($row =  $search_result->fetch_array()) {
              $number++;

            ?>
              <tr>
                <td><?php echo $number ?></td>
                <td><?php echo $row['title'] ?></td>
                <td><?php echo $db->getBatchNameById($row['batch_id']) ?></td>
                <td><?php echo  date(" d.m.Y, h:i A", $row['test_start']); ?> - <?php echo   date("h:i A", $row['test_end']); ?></td>


                <td>


                  <div class="dropdown">
                    <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown"><i class="fas fa-angle-down"></i></button>
                    <ul class="dropdown-menu dropdown-menu-right">
                      <li><a href="preview_test.php?test_id=<?php echo $row['id'] ?>">Preview</a></li>
                      <li><a target="_blank" href="rank_list.php?test_id=<?php echo $row['id'] ?>">Print Rank List</a></li>
                      <li><a href="rank_list_live.php?test_id=<?php echo $row['id'] ?>">Live Rank List</a></li>
                      <li><a href="edit_test.php?test_id=<?php echo $row['id'] ?>">Edit</a></li>
                      <?php
                      if($row['type'] == 1) { ?>
                        <li><a href="drag_questions.php?sel_subject=0&sel_chapter=0&test_id=<?php echo $row['id'] ?>">Select Questions</a></li>
                      <?php }  ?>
                    </ul>
                  </div>

                </td>
              </tr>

            <?php
            }  ?>

          </tbody>
        </table>

        <h2>Upcoming Tests</h2>
        <table class="table table-striped">
          <thead>
            <tr>
              <th>#</th>
              <th>Title</th>
              <th>Batch</th>
              <th>Time</th>
              <th></th>


            </tr>
          </thead>
          <tbody>


            <?php
            $search_result = $db->getAllUpcomingTest();
            $number = 0;
            while ($row =  $search_result->fetch_array()) {
              $number++;

            ?>
              <tr>
                <td><?php echo $number ?></td>
                <td><?php echo $row['title'] ?></td>
                <td><?php echo $db->getBatchNameById($row['batch_id']) ?></td>
                <td><?php echo  date(" d.m.Y, h:i A", $row['test_start']); ?> - <?php echo   date("h:i A", $row['test_end']); ?></td>
                <td>

                  <div class="dropdown">
                    <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown"><i class="fas fa-angle-down"></i></button>
                    <ul class="dropdown-menu dropdown-menu-right">
                      <li><a href="preview_test.php?test_id=<?php echo $row['id'] ?>">Preview</a></li>
                      <li><a href="edit_test.php?test_id=<?php echo $row['id'] ?>">Edit</a></li>
                      <?php
                      if($row['type'] == 1) { ?>
                        <li><a href="drag_questions.php?sel_subject=0&sel_chapter=0&test_id=<?php echo $row['id'] ?>">Select Questions</a></li>
                      <?php }  ?>
                    </ul>
                  </div>

                </td>
              </tr>

            <?php
            }  ?>

          </tbody>
        </table>

        <h2>Completed Tests</h2>
        <table class="table table-striped" id="completed_table">
          <thead>
            <tr>
              <th>#</th>
              <th>Title</th>
              <th>Batch</th>
              <th>Time</th>
              <th></th>

            </tr>
          </thead>
          <tbody>


            <?php
            $search_result = $db->getAllCompletedTest();
            $number = 0;
            while ($row =  $search_result->fetch_array()) {
              $number++;

            ?>
              <tr>
                <td><?php echo $number ?></td>
                <td><?php echo $row['title'] ?></td>
                <td><?php echo $db->getBatchNameById($row['batch_id']) ?></td>
                <td><?php echo  date(" d.m.Y, h:i A", $row['test_start']); ?> - <?php echo   date("h:i A", $row['test_end']); ?></td>
                <td>
                  <div class="dropdown">
                    <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown"><i class="fas fa-angle-down"></i></button>
                    <ul class="dropdown-menu dropdown-menu-right">
                      <li><a href="preview_test.php?test_id=<?php echo $row['id'] ?>">Preview</a></li>
                      <li><a href="rank_list.php?test_id=<?php echo $row['id'] ?>">Print Rank List</a></li>
                      <li><a href="rank_list_live.php?test_id=<?php echo $row['id'] ?>">Live Rank List</a></li>
                      <li><a href="edit_test.php?test_id=<?php echo $row['id'] ?>">Edit</a></li>
                      <li><a href="rank_list_sms.php?test_id=<?php echo $row['id'] ?>">Send SMS</a></li>
                    </ul>
                  </div>

                </td>
              </tr>

            <?php
            }  ?>

          </tbody>
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