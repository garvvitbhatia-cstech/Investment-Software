// Owlcarousel
$(document).ready(function() {
    $(".owl-carousel").owlCarousel({
        nav: false,
        dots: false,
        autoplay: false,
        margin: 0,
        loop: false,
        infinite: false,
        responsive: {
            0: {
                items: 1,
                infinite: true,
                loop: true,
                
            },

            550: {
                
                items: 1,
                infinite: true,
                loop: true,
               
            },

            620: {
                
                items: 2,
                infinite: true,
                loop: true,
            },
            768: {
                
                items: 2,
                
                
            },
            1000: {
               
                items: 2,

                
            },
            1200: {
                
                items: 4,
            }
        }
    });
});





