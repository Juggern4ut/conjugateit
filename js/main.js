var is_corrected = false;

$(document).ready(function() {
  submitSolution();
  loadNewCard();
  getWordList();
});

function submitSolution() {
  $(".card__form").submit(function(e) {
    e.preventDefault();

    if (is_corrected) {
      loadNewCard();
      is_corrected = false;
      $(".card__submit").html("Check");
    } else {
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
          var card_class =
            data.status > 0 ? "card__result--correct" : "card__result--wrong";
          var card_data = data.status > 0 ? data.message : data.solution;
          $(".card").append(
            "<div class='card__result " +
              card_class +
              "'><h3 class='card__response'>" +
              card_data +
              "</h3></div>"
          );
          is_corrected = true;
          $(".card__submit").html("Next");
        }
      );
    }
  });
}

function loadNewCard() {
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
      $(".card__pronoun").html(data.pronoun + " ");
      $(".card__verb").html(data.baseverb + " - " + data.translation);
    }
  );
}

function getBundles(){
  $.post(
    "/index.php",
    {
      async: true,
      getbundles: true
    },
    function(data) {
      console.log(data);
    }
  );
}

function getWordList() {
  $.post(
    "/index.php",
    {
      async: true,
      getwords: true
    },
    function(data) {
      data.forEach(word => {
        $(".wordlist").append("<li>" + word.verb + "</li>");
      });
    }
  );
}
