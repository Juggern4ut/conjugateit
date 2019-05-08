<!DOCTYPE html>
<html>
  <head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>ConjugateIt</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' type='text/css' media='screen' href='css/main.css'>
    <script src='https://code.jquery.com/jquery-3.4.1.min.js'></script>
    <script src='js/main.js'></script>
  </head>
  <body>
    <?php
      echo "<article class='card'>";

        echo "<h2 class='card__verb'></h2>";

        echo "<span pronoun_id='0' baseverb_id='0' class='card__pronoun'></span>";
        echo "<form class='card__form'>";
          echo "<input value='' class='card__answer' type='text'>";
          echo "<button type='submit' class='card__submit'>Check</button>";
        echo "</form>";

      echo "</article>";
    ?>    
  </body>
</html>