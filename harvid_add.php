<?php
include('session_admin.php');
?>

<!DOCTYPE html>

<?php

include_once 'db_functions.php';
$db = new DB_Functions();

$IS_ADMIN = 1;


if ($IS_ADMIN && isset($_POST['source'])) {    // admin add 

    $add_result = $db->addVideo($_POST['title'], $_POST['description'], $_POST['source'], $_POST['batch_id'], $_POST['subject_id'], $_POST['chapter_id'],  strtotime($_POST['release_time']));
} else if ($IS_ADMIN && isset($_POST['doc_id'])) {    // admin delete 

    $delete_result = $db->deleteVideo($_POST['doc_id']);
} else {
    //header("location:login.php");
}



$search_result = $db->getAllVideosLimit20();

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


    <style>
        ul {
            list-style-type: none;
            /* width: 500px; */
            padding-left: 0px;
            ;
        }

        li img {
            float: left;
            margin: 0 15px 0 0;
            width: 90px;
            height: 60px;
        }

        li p {
            font: 200 12px/1.5 Georgia, Times New Roman, serif;
        }

        li {
            padding: 10px;
            overflow: auto;
        }

        li:hover {
            background: #eee;
            cursor: pointer;
        }
    </style>


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


                <?php if (isset($add_result)) { ?>
                    <div class="alert alert-success">Added Successfully!</div>
                <?php }  ?>

                <?php if (isset($delete_result)) { ?>
                    <div class="alert alert-success">Deleted Successfully!</div>
                <?php }  ?>

                <div class="jumbotron">

                <h2>Add Video</h2>


                    <form action="harvid_add.php" method="post">

                        <div class="form-group">
                            <label class="control-label">Title</label>
                            <input type='text' class="form-control" name="title" autocomplete="off" placeholder="Enter Title" required />
                        </div>

                        <div class="form-group">
                            <label class="control-label">Description</label>
                            <input type='text' class="form-control" name="description" autocomplete="off" placeholder="Enter Description" />
                        </div>

                        <div class="form-group">
                            <label for="sel1">Batch</label>
                            <select class="form-control" id="sel1" name="batch_id">
                                <?php
                                $batch_search_result = $db->getAllBatches();
                                while ($batch_row =  $batch_search_result->fetch_array()) {
                                    echo "<option value=" . $batch_row['id'] . ">" . $batch_row['title'] . "</option>";
                                }

                                ?>
                            </select>
                        </div>


                        <div class="form-group">
                            <label for="sel1">Subject</label>
                            <select class="form-control" id="sel2" name="subject_id">
                                <?php
                                $subject_search_result = $db->getAllSubjects();
                                while ($subject_row =  $subject_search_result->fetch_array()) {
                                    echo "<option value=" . $subject_row['id'] . ">" . $subject_row['title'] . "</option>";
                                }

                                ?>
                            </select>
                        </div>


                        <div class="form-group">
                            <label for="sel1">Chapter</label>
                            <select class="form-control" id="sel3" name="chapter_id">
                                <?php
                                $chapter_search_result = $db->getAllChapters();
                                while ($chapter_row =  $chapter_search_result->fetch_array()) {
                                    echo "<option value=" . $chapter_row['id'] . ">" . $chapter_row['title'] . "</option>";
                                }

                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="control-label">Youtube Video ID</label>
                            <input type='text' class="form-control" name="source" autocomplete="off" placeholder="Enter ID" />
                        </div>

                        <div class="form-group">
                            <label for="dtpickerdemo" class="control-label">Release time</label>
                            <div class=' input-group date' id='dtpicker1'>
                                <input type='text' class="form-control" name="release_time" autocomplete="off" minlength="18" required />
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>


                        <button type="submit" class="btn btn-warning">Add New</button>
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
                            <th>Chapter</th>
                            <th>Release Time</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php
                        $num = 0;
                        while ($row =  $search_result->fetch_array()) {
                            $num++;
                        ?>

                            <tr>
                                <form action="harvid_add.php" method="post" id="deleteform">
                                    <input type="hidden" name="doc_id" value="<?php echo $row['id'] ?>">

                                    <td><?php echo $num; ?></td>
                                    <td>
                                        <img src="https://img.youtube.com/vi/<?php echo $row['source'] ?>/default.jpg">
                                        <?php echo '<a href="https://www.youtube.com/watch?v=' . $row['source'] . '" >'.$row['title']. '</a>'; ?>
                                    </td>
                                    <td><?php echo  $db->getBatchNameById($row['batch_id']); ?></td>
                                    <td><?php echo  $db->getSubjectNameById($row['subject_id']); ?></td>
                                    <td><?php echo  $db->getChapterNameById($row['chapter_id']); ?></td>
                                    <td><?php echo  date(" d.m.Y, h:i A", $row['release_time']); ?></td>
                                    <td>


                                        <!-- <button class="btn btn-danger btn-xs" id="btn_delete">delete</button> -->
                                        <a href="harvid_edit.php?video_id=<?php echo $row['id'] ?>&from_add_vid=1" class="btn btn-primary btn-xs" >edit</a>

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