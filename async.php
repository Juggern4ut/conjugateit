<?php
  if(isset($_POST["validate"]) && $_SERVER["REQUEST_METHOD"] === "POST"){
    header('Content-Type: application/json');
    $baseverb = $_POST["baseverb_id"];
    $pronoun = $_POST["pronoun"];
    $answer = $_POST["answer"];

    $stmt = $db->prepare("SELECT verb FROM tbl_conjugation WHERE pronoun_fk = ? AND baseverb_fk = ? LIMIT 1");
    $stmt->bind_param("ii", $pronoun, $baseverb);
    $stmt->execute();
    $stmt->bind_result($solution);
    $stmt->fetch();
    if($solution == $answer){
      echo json_encode(array(
        "status"=>"1",
        "message"=>"Richtig",
        "solution"=>$solution
      ));
    }else{
      echo json_encode(array(
        "status"=>"-1",
        "message"=>"Falsch",
        "solution"=>$solution
      ));
    }
  }

  if(isset($_POST["getcard"]) && $_SERVER["REQUEST_METHOD"] === "POST"){
    header('Content-Type: application/json');
    
    $q = $db->query("SELECT baseverb_id AS id, verb, translation FROM tbl_baseverb ORDER BY RAND() LIMIT 1");
    $res = $q->fetch_assoc();
    
    $q2 = $db->query("SELECT p.pronoun, p.pronoun_id FROM tbl_conjugation AS c LEFT JOIN tbl_pronoun AS p ON c.pronoun_fk = p.pronoun_id WHERE baseverb_fk = '".$res["id"]."' ORDER BY RAND() LIMIT 1");
    $res2 = $q2->fetch_assoc();

    echo json_encode(array(
      "baseverb_id"=>$res["id"],
      "baseverb"=>$res["verb"],
      "translation"=>$res["translation"],
      "pronoun_id"=>$res2["pronoun_id"],
      "pronoun"=>$res2["pronoun"]
    ));
  }
?>