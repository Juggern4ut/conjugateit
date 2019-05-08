$(document).ready(function() {
  submitSolution();
  loadNewCard();
});

function submitSolution() {
  $(".card__form").submit(function(e) {
    e.preventDefault();
    let answer = $(".card__answer").val();
    let pronoun_id = $(".card__pronoun").attr("pronoun_id");
    let baseverb = $(".card__pronoun").attr("baseverb_id");
    $.post(
      "/conjugateit/index.php",
      {
        async: true,
        validate: true,
        answer: answer,
        pronoun: pronoun_id,
        baseverb_id: baseverb
      },
      function(data) {
        $(".card").append(
          "<div class='card__result'><h3>" + data.message + "</h3></div>"
        );
        if (data.status < 0) {
          $(".card__result").append(
            "<h4>Korrekt: " + data.solution + "</h4></div>"
          );
        }
        setTimeout(function() {
          loadNewCard();
        }, 2000);
      }
    );
  });
}

function loadNewCard(){
  $(".card__result").remove();
  $(".card__answer").val("");
  $.post(
    "/conjugateit/index.php",
    {
      async: true,
      getcard: true
    },
    function(data) {
      $(".card__pronoun").attr("baseverb_id", data.baseverb_id);
      $(".card__pronoun").attr("pronoun_id", data.pronoun_id);
      $(".card__pronoun").html(data.pronoun);
      $(".card__verb").html(data.baseverb + " (" + data.translation + ")");
    }
  );
}