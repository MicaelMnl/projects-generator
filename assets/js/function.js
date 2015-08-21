$(document).ready(function() {
    /*********************************************************************************************************
        INDEX
    ***********************************************************************************************************/
   var lang = $('html').attr('lang');
    $( ".voir-avis" ).hover(
    function () {

        $( '.voir-avis' ).attr('src','assets/img/b-voir-les-avis-over_'+lang+'.png')

    },
    function() {

        $( '.voir-avis' ).attr('src','assets/img/b-voir-les-avis_'+lang+'.png')
    }
    );
    /*********************************************************************************************************
       SCROOL
    ***********************************************************************************************************/

    $('.scroll-top').click(function () {
         $('.news-text ').animate({ scrollTop: "-=60px" }, 1000);
     });
    $('.scroll-down').click(function () {
          $('.news-text ').animate({ scrollTop: "+=60px"}, 1000);
     });

    /*********************************************************************************************************
       IMAGE  IDEX HOVER 
    ***********************************************************************************************************/

    $(".pdf-thumb-box").hover(function() {

        $(this).children(".pdf-thumb-box-overlay").fadeIn();
    }, 
    function() {
        $(this).children(".pdf-thumb-box-overlay").fadeOut();
    });
    // fancybox
    $(document).delegate('*[data-toggle="lightbox"]', 'click', function(event) {
                            event.preventDefault();
                            return $(this).ekkoLightbox({
                                    always_show_close: true
                            });
    });
    /// fancy box small
    $(document).delegate('*[data-toggle="lightbox-small"]', 'click', function(event) {
                            event.preventDefault();
                            return $(this).ekkoLightbox({
                                    always_show_close: true
                            });
    });

       /*********************************************************************************************************
        CARROUSEL
        ***********************************************************************************************************/
    $('#slide-home .carousel-inner .item:first').addClass('active');

        /*********************************************************************************************************
        RESERVIT
        ***********************************************************************************************************/
        $('#myframe').mouseenter(function(){
            $('#myframe').attr('height','271');
        
        });
        
        $('#reservit').mouseout(function(){
            
            $('#myframe').attr('height','33');
            
         });
         
       /*********************************************************************************************************
        fANCY BOX MODAL
        ***********************************************************************************************************/
    $('#notification').modal();
     
       /*********************************************************************************************************
        MOBILE
        ***********************************************************************************************************/
       
    if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
      // tasks to do if it is a Mobile Device

        $('.tel').attr("href","tel:0298068217");
        $('.okala-lien').attr("href","http://v3.olakala.com/api/v3/reviews/14182/14182");
        $('.okala-lien').attr("target","_blank");
    }
    /*********************************************************************************************************
       PARTENAIRES
    ***********************************************************************************************************/

    $(".partenaire-item").hover(function() {

        $(this).children(".pdf-thumb-box-overlay-partenaire").slideDown();
    }, 
    function() {
        $(this).children(".pdf-thumb-box-overlay-partenaire").slideUp();
    });
     

    
});