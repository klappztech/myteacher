<?php
include('session.php');
?>

<?php

include_once 'db_functions.php';
$db = new DB_Functions();

$student_id = $_SESSION['user_id'];


$IS_ADMIN = $_SESSION['is_admin'];
$name = $db->getStudentNameById($student_id);


?>


<div class="list-group">
    <?php if ($IS_ADMIN) { ?>
        <div class=" text-center">
            <img src="img/admin.svg" style="width:100px;height:100px;" />
            <h2 style="text-align:center;text-transform: capitalize"><?php echo $name; ?> </h2>
        </div>
    <?php } else { ?>
        <div class=" text-center">
            <img src="img/student.svg" style="width:100px;height:100px;" />
            <h2 style="text-align:center;text-transform: capitalize"><?php echo $name; ?> </h2>
        </div>
    <?php } ?>

    <br />
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>

    <?php if ($IS_ADMIN) { ?>

        <div class="bg-info">Student Accounts</div>
        <a class="list-group-item list-group-item-action" href="create_user.php">Create User</a>
        <a class="list-group-item list-group-item-action" href="#">Students List</a>
        <a class="list-group-item list-group-item-action" href="batch_add.php">Add Batch</a>

        <div class="bg-info">Online Class</div>
        <a class="list-group-item list-group-item-action" href="harvid_add.php">Add Video</a>
        <a class="list-group-item list-group-item-action" href="harvid_batch_list.php">List Videos</a>

        <div class="bg-info">Curriculum</div>
        <a class="list-group-item list-group-item-action" href="chapter_add.php">Add Chapter</a>
        <a class="list-group-item list-group-item-action" href="subject_add.php">Add Subject</a>

        <div class="bg-info">Others</div>

        <a class="list-group-item list-group-item-action" href="#">Log</a>

    <?php } else { ?>
        <a class="list-group-item list-group-item-action" href="scheduled_welcome.php">My Scheduled Tests</a>

    <?php } ?>
    <a class="list-group-item list-group-item-action bg-danger" href="logout.php">Logout</a>
</div>