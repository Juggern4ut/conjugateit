<?php
  include 'db.php';
  
  if(isset($_POST["async"])){
    include 'async.php';
  }else{
    include 'page.php';
  }
?>