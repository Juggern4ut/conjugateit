<?php
  include 'db.php';
  echo "<form method='POST' action='/add.php'>";
    echo "<input type='text' placeholder='Grundform' name='baseverb'><br>";
    echo "<input type='text' placeholder='Übersetzung auf Deutsch' name='translation'><br>";
    $q = $db->query("SELECT pronoun_id, pronoun FROM tbl_pronoun ORDER BY pronoun_id ASC");
    while($res = $q->fetch_assoc()){
      echo $res["pronoun"]." ";
      echo "<input type='text' name='conjugated_".$res["pronoun_id"]."'>";
      echo "<br>";
    }
    echo "<button type='submit'>Hinzufügen</button>";
  echo "</form>";

  if($_SERVER["REQUEST_METHOD"] === "POST"){
    $stmt = $db->prepare("INSERT INTO tbl_baseverb (verb, translation) VALUES (?, ?)");
    $stmt->bind_param("ss", $_POST["baseverb"], $_POST["translation"]);
    $stmt->execute();
    $baseverb_id = $db->insert_id;
    foreach ($_POST as $k => $value) {
      if(strpos($k, 'conjugated_') !== false){
        $stmt2 = $db->prepare("INSERT INTO tbl_conjugation (baseverb_fk, pronoun_fk, verb) VALUES (?, ?, ?)");
        $stmt2->bind_param("iis", $baseverb_id, str_replace('conjugated_', '', $k), $_POST[$k]);
        $stmt2->execute();
        echo $db->error;
      }
    }
    $db->query("INSERT INTO tbl_baseverb_bundle (baseverb_fk, bundle_fk) VALUES ('".$baseverb_id."', '1')");
  }
?>