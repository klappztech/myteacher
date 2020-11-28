<?php
include('session_admin.php');
?>

<!DOCTYPE html>

<?php

echo "<br/><br/><br/><br/><br/><br/><br/>";


include_once 'db_functions.php';
$db = new DB_Functions();

$IS_ADMIN = 1;




if ($IS_ADMIN && isset($_POST['source'])) {    // admin search

    $search_result = $db->getYoutubeVideoTitle($_POST['source']); //$title;// $ytarr['title'];

    $title = preg_replace('!\s+!', ' ', $search_result);

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

        // echo $std_str;

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

        $subject_start  = stripos($title, "STD") + 7;
        $subject_end    =  stripos($title, "Class");

        $subject = substr(
            $title,
            $subject_start,
            $subject_end - $subject_start
        );
    }

    echo "<br/>";
    echo "STD =" . ($std);
    echo "<br/>";

    echo "Subject =" . $subject;
    echo "<br/>";
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

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.js"></script>



    <script>
        $(document).ready(function() {

            $('#table_id').DataTable();

            /////// delete BUTTON
            $("#btn_delete").click(function() {

                var r = prompt("Are you sure you want to delete?\n\nType 'yes' in the below box and press [OK] to submit\nPress [Cancel] to go back");

                if (r.trim().toUpperCase() == 'YES') {

                    $("#deleteform").submit();
                    return true;
                } else {
                    return false;
                }

            });


        });
    </script>



</head>



<body>

    <header id="header-bar" class="w3-container w3-top w3-theme ">
        <div id="server-results" style="width:500px;word-wrap: break-word;"></div>

        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-2 text-left">
                    <a style="font-size:30px;cursor:pointer;margin:0;" class="navig-btn" href="welcome.php"><i class="fas fa-xs fa-arrow-left"></i></a>
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


                <?php if (isset($search_result)) { ?>
                    <div class="alert alert-success"><?php echo $search_result; ?></div>
                <?php }  ?>



                <div class="jumbotron">

                    <h2>Add Video</h2>


                    <form action="harvid_youtube_video_details.php" method="post">


                        <div class="form-group">
                            <label class="control-label">Youtube Video ID</label>
                            <input type='text' class="form-control" name="source" autocomplete="off" placeholder="Enter ID" />
                        </div>


                        <button type="submit" class="btn btn-warning">Search</button>
                    </form>
                </div>


                <hr />

                <table id="table_id" class="display">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Video</th>
                            <th>Batch</th>
                            <th>Subject</th>
                            <th>Class Num</th>
                            <th>Time</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php
                        $num = 0;

                        $youtube_result = $db->getAllYoutubeVideos();


                        while ($row =  $youtube_result->fetch_array()) {

                            $num++; {    // admin search

                                $search_result = $db->getYoutubeVideoTitle($row['source']); //$title;// $ytarr['title'];

                                $title      = preg_replace('!\s+!', ' ', $search_result);
                                $exp_title  = explode(" ",$title);

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


                            }


                        ?>

                            <tr>
                                <form action="harvid_add.php" method="post" id="deleteform">

                                    <td><?php echo $num; ?></td>
                                    <td>
                                        <!-- <img src="https://img.youtube.com/vi/<?php echo $row['source'] ?>/default.jpg"> -->
                                        <?php echo '<a href="https://www.youtube.com/watch?v=' . $row['source'] . '" >' .  $title . '</a>'; ?>
                                    </td>
                                    <td><?php echo  $std; ?></td>
                                    <td><?php echo  $subject; ?></td>
                                    <td><?php echo  $class_num; ?></td>
                                    <td><?php echo  date(" d.m.Y, h:i A", 0); ?></td>
                                    <td>

                                        <a href="#" class="btn btn-primary btn-xs">edit</a>

                                    </td>

                                </form>


                            </tr>
                        <?php } ?>


                    </tbody>


                </table>



                <div>

                </div>
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

<script type="text/javascript">
    $(function() {
        $('#dtpicker1').datetimepicker({
            format: "DD.MM.YYYY h:mm A"
        });

        $('#dtpicker2').datetimepicker({
            format: "DD.MM.YYYY h:mm A",
            useCurrent: false
        });

        $("#dtpicker1").on("dp.change", function(e) {
            $('#dtpicker2').data("DateTimePicker").minDate(e.date);
        });
        $("#dtpicker2").on("dp.change", function(e) {
            $('#dtpicker1').data("DateTimePicker").maxDate(e.date);
        });


    });
</script>


<script>
    $("#mainForm").validate();
</script>

</html>



<?php


?>