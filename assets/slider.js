import './styles/app.css';

$(document).ready( function () {

  var count = $('.item').length;
  var largeurItem = $(".image-annonceSlide").width()
  var lgSlides = largeurItem*count;

  function animSlide() {
    if (count >= 2) {
        $("#slides").animate({
          "margin-left" : -largeurItem},
          5000,
          function () {
            var items = $("#slides .item");
            var itemAjoute = items.slice(0,1);
            $("#slides").append(itemAjoute);
            $("#slides").css("margin-left","0");
          }
          );
          animSlide()
      }
  }

    function hover() {
      $(".item").on("mouseover",function() {
        $("#slides").clearQueue().stop();
        }).on("mouseleave", function() {
            animSlide();
        });
    }

    function largeur() {
      if (count > 3) {
          if ($(window).width() > 768) {
            var longSl = largeurItem*(count-1)
        } else if ($(window).width() > 425) {
          var longSl = largeurItem*3
        } else {
          var longSl = largeurItem*2
        }
      } else {
        var longSl = largeurItem*2
      }

      $("#slideshow").css("max-width",longSl + "px");
      $("#slideshow").css("max-height",400 + "px");
      $("#slides").css("width",lgSlides + "px");
      var item = $("#slideshow .item");
      item.css("max-width", largeurItem);
    }
  $(window).on("resize",function () {
    largeur()
  });

  hover()
  largeur()
  animSlide()

});

