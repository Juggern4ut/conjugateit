<?php
  $db = mysqli_connect("195.110.58.240", "weble", "MySqlR00t", "conjugateit");
  $db->set_charset('utf8');

  if(isset($_POST["async"])){
    include 'async.php';
  }else{
    include 'page.php';
  }
?>