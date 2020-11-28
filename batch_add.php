<?php
include('session_admin.php');
?>

<!DOCTYPE html>

<?php

include_once 'db_functions.php';
$db = new DB_Functions();

//print_r($_POST);



if (isset($_POST['add'])) {    // admin add 

    $add_result = $db->addBatch($_POST['title']);
} else if (isset($_POST['delete'])) {    // admin delete 

    $delete_result = $db->deleteBatch($_POST['id']);
}


$search_result = $db->getAllBatches();

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

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.js"></script>



    <script>
        $(document).ready(function() {

            $('#batch_table').DataTable({
                paging: false
            });

            /////// DELETE BUTTON
            $(".btn_delete").click(function() {

                var r = confirm("Are you sure to delete?");
                if (r == true) {
                    //$("#form_delete").submit();
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


                <?php if (isset($add_result)) { ?>
                    <div class="alert alert-success">Added Successfully!</div>
                <?php }  ?>

                <?php if (isset($delete_result)) { ?>
                    <div class="alert alert-success">Deleted Successfully!</div>
                <?php }  ?>

                <div class="jumbotron">
                    <h2>Add Batch</h2>

                    <form action="batch_add.php" method="post">

                        <div class="form-group">
                            <label class="control-label">Title</label>
                            <input type='text' class="form-control" name="title" autocomplete="off" placeholder="Enter Title" required />
                        </div>


                        <button type="submit" name="add" class="btn btn-warning">Add New</button>
                    </form>
                </div>


                <hr />

                <div>

                    <table class="table table-striped" id="batch_table">
                        <thead>
                            <th>Title</th>
                            <th>Action</th>
                            </th>
                        </thead>
                        <tbody>
                            <?php
                            while ($row =  $search_result->fetch_array()) {
                            ?>
                                <tr>
                                    <td><?php echo $row['title']; ?></td>
                                    <td>
                                        <form action="batch_add.php" method="post">
                                            <input type="hidden" name="id" value="<?php echo $row['id'] ?>">
                                            <button type="submit" name="delete" class="btn_delete btn btn-danger btn-xs">delete</button>
                                        </form>
                                    </td>
                                </tr>

                            <?php } ?>
                        </tbody>

                    </table>


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