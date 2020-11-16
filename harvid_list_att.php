<?php
include('session_admin.php');
?>

<!DOCTYPE html>

<?php

include_once 'db_functions.php';
$db = new DB_Functions();

$IS_ADMIN = 1;
$student_id = $_student_id;

$search_result = $db->getAllVideosByBatchId($_GET['batch_id']);

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
                    <span style="font-size:30px;cursor:pointer;margin:0;" onclick="goBack()"> <i class="fas  fa-xs fa-arrow-left"></i> </span>
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


                <?php if (isset($add_result)) { ?>
                    <div class="alert alert-success">Added Successfully!</div>
                <?php }  ?>

                <?php if (isset($delete_result)) { ?>
                    <div class="alert alert-success">Deleted Successfully!</div>
                <?php }  ?>




                <hr />

                <table id="table_id" class="display">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Video</th>
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


                                <td><?php echo $num; ?></td>
                                <td>
                                    <img src="https://img.youtube.com/vi/<?php echo $row['source'] ?>/default.jpg">
                                    <?php echo $row['title']; ?> <br />


                                </td>
                                <td><?php echo  date(" d.m.Y, h:i A", $row['release_time']); ?></td>
                                <td>

                                    <?php

                                    if ($db->IsWatchedByVideoIdUsername($row['source'], $db->getUsernameById($student_id))) {

                                        echo "Watched";
                                    } else {
                                        echo "X";
                                    }

                                    ?>

                                </td>







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

</html>