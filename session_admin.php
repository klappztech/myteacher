<?php
   session_start();
   
   if(  !isset($_SESSION['username'])){
      header("location:login.php");
   } else if( $_SESSION['is_admin']==0 ){
      header("location:logout.php");
   } else {
   
	   $_username  = $_SESSION['username']; 
	   $_student_id  = $_SESSION['user_id']; 
	   $_batch_id    = $_SESSION['batch_id']; 
	   $_is_admin    = $_SESSION['is_admin'];
   }
?>