<?php
  if($_SERVER["REQUEST_METHOD"] === "POST"){
    header('Content-Type: application/json');

    if(isset($_POST["validate"])){
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

    if(isset($_POST["getwords"])){
      $words = array();
      $q = $db->query("SELECT baseverb_id, verb, translation FROM tbl_baseverb ORDER BY verb ASC");
      while($res = $q->fetch_assoc()){
        $words[] = array(
          "id"=>$res["baseverb_id"],
          "verb"=>$res["verb"],
          "translation"=>$res["translation"]
        );
      }
      echo json_encode($words);
    }

    if(isset($_POST["getbundles"])){
      $bundles = array();
      $q = $db->query("SELECT bundle_id, name, description FROM tbl_bundle ORDER BY name ASC");
      while($res = $q->fetch_assoc()){
        $words[] = array(
          "id"=>$res["bundle_id"],
          "name"=>$res["name"],
          "description"=>$res["description"]
        );
      }
      echo json_encode($words);
    }

    if(isset($_POST["getcard"])){

      $query = isset($_POST["bundle"])  ? "SELECT b.baseverb_id AS id, b.verb, b.translation FROM tbl_baseverb AS b LEFT JOIN tbl_baseverb_bundle AS bb ON b.baseverb_id = bb.baseverb_fk WHERE bb.bundle_fk = ? ORDER BY RAND() LIMIT 1"
                                        : "SELECT baseverb_id AS id, verb, translation FROM tbl_baseverb ORDER BY RAND() LIMIT 1";
      $stmt = $db->prepare($query);

      if(isset($_POST["bundle"])){
        $stmt->bind_param("i", $_POST["bundle"]);
      }

      $stmt->execute();
      $stmt->bind_result($id, $verb, $translation);
      $stmt->fetch();
      $stmt->close();
          
      $stmt = $db->prepare("SELECT p.pronoun, p.pronoun_id FROM tbl_conjugation AS c LEFT JOIN tbl_pronoun AS p ON c.pronoun_fk = p.pronoun_id WHERE baseverb_fk = ? ORDER BY RAND() LIMIT 1");
      $stmt->bind_param("i", $id);
      $stmt->execute();
      $stmt->bind_result($pronoun, $pronoun_id);
      $stmt->fetch();
      $stmt->close();

      echo json_encode(array(
        "baseverb_id"=>$id,
        "baseverb"=>$verb,
        "translation"=>$translation,
        "pronoun_id"=>$pronoun_id,
        "pronoun"=>$pronoun
      ));
    }

  }
?>