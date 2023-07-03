(function($){
    "use strict";
    $(window).load(function(){

        $("#mix-container").mixItUp();
        
        $('.gallery-carousel').owlCarousel({
        loop: true,
        autoplay: true,
        margin: 15,
        dots: false,
        animateIn: true,
        responsiveClass: true,
        navText: [
        '<i class="fa fa-angle-left"></i>',
        '<i class="fa fa-angle-right"></i>'
        ],
        responsive:{
            0:{
                items:1,
                nav:true
            },
            600:{
                items:1,
                nav:true
            },
            1000:{
                items:1,
                nav:true,
                loop:true
            }
        }
      });

      $('.gallery-photo').magnificPopup({
        type: 'image'
        // other options
    });
    });
    
})(jQuery);