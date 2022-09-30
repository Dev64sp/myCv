import './styles/app.css';
      
/* burger */
$("#toggle-button").on("click", function () {
    if ($(".list-item1:visible").length != 0) {
        $(".list-item1").slideUp("normal");
    } else {
      $(".list-item2").slideUp("normal");
      $(".list-item1").slideDown("normal");
    }    
});
$("#toggle-button-profil").on("click", function () {
  if ($(".list-item2:visible").length != 0) {
      $(".list-item2").slideUp("normal");
  } else {
        $(".list-item1").slideUp("normal");
        $(".list-item2").slideDown("normal");
  }    
});  
function largeur() {
    if ($(window).width() > 768) {
        $(".list-item1").show();
        $(".list-item2").show();
    } else if ($(window).width() > 425) {
      $(".list-item1").hide();
      $(".list-item2").hide();
    } else {
      $(".list-item1").hide();
      $(".list-item2").hide();
    }
  }
$(window).on("resize",function () {
  largeur()
});
largeur()