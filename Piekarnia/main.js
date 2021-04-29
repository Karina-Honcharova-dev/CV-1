$("#myCarousel").carousel({
  interval: false
});

$(document).ready(function() {

  $("#next").on("click", function() {
    if($('#slider1').is(':visible')) {
      $("#slider1").css("display", "none");
      $("#slider2").css("display", "block");
      $(".fa-chevron-left").css({
        opacity: 1,
        cursor: "pointer"
      });
    }
    else if($('#slider2').is(':visible')) {
      $("#slider2").css("display", "none");
      $(".fa-chevron-right").css({
        opacity: 0.3,
        cursor: "default"
      });
      $("#slider3").css("display", "block");
    }
  });

  $("#previous").on("click", function() {
    if($('#slider3').is(':visible')) {
      $("#slider3").css("display", "none");
      $("#slider2").css("display", "block");
      $(".fa-chevron-right").css({
        opacity: 1,
        cursor: "pointer"
      });
    }
    else if($('#slider2').is(':visible')) {
      $("#slider2").css("display", "none");
      $(".fa-chevron-left").css({
        opacity: 0.3,
        cursor: "default"
      });
      $("#slider1").css("display", "block");
    }
  });



});
