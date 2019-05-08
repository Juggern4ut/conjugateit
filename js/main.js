var is_corrected = false;

$(document).ready(function() {
  submitSolution();
  loadNewCard();
});

function submitSolution() {
  $(".card__form").submit(function(e) {
    e.preventDefault();

    if(is_corrected){
      loadNewCard();
      is_corrected = false;
    }else{
      let answer = $(".card__answer").val();
      let pronoun_id = $(".card__pronoun").attr("pronoun_id");
      let baseverb = $(".card__pronoun").attr("baseverb_id");
      $.post(
        "/index.php",
        {
          async: true,
          validate: true,
          answer: answer,
          pronoun: pronoun_id,
          baseverb_id: baseverb
        },
        function(data) {
          var card_class = data.status > 0 ? "card__response--correct" : "card__response--wrong";
          var card_data = data.status > 0 ? data.message : data.solution;
          $(".card__form").append(
            "<div class='card__result'><h3 class='card__response "+card_class+"'>" + card_data + "</h3></div>"
          );
          is_corrected = true;
        }
      );
    }
  });
}

function loadNewCard(){
  $(".card__result").remove();
  $(".card__answer").val("");
  $.post(
    "/index.php",
    {
      async: true,
      getcard: true
    },
    function(data) {
      $(".card__pronoun").attr("baseverb_id", data.baseverb_id);
      $(".card__pronoun").attr("pronoun_id", data.pronoun_id);
      $(".card__pronoun").html(data.pronoun+" ");
      $(".card__verb").html(data.baseverb + " - " + data.translation);
    }
  );
}