$(document).ready(function(){
    UpdateTemplate();
    unloadScrollBars();
    /*
    * We need to keep repeating this everytime its done ?
    * And we need to determin optimal scroll rate.
    */
     setTimeout(function(){ 
        UpdateTemplate();
     },900000);
     
     setTimeout(function(){ 
          $('html, body').animate({ scrollTop: $(document).height() - $(window).height() }, 400000, function() {
            $(this).animate({ scrollTop: 0 }, 400000);
         });
      }, 850000);

    function UpdateTemplate()
    {
        var hidden_fields = $( this ).find( 'input:hidden' );
        var template = $('#template').val();
        var TargetMails = $('#targetemails').val();
        var StartDate = $('#startdate').val();
        var EndDate = $('#enddate').val();
        var url = "/contentgenerator.php?skabelon=" + template + "&targetmailbox=" + TargetMails + "&start=" + StartDate + "&end=" + EndDate;
        
        var jqxhr = $.get( url, function(data ) {
             $("#cover").css('display','none');
            $('#content').html(data);
           
          })
         .done(function() {
            
             
            })
        .fail(function() {
          alert( "error" );
        })
 
    }
    function unloadScrollBars() {
        document.documentElement.style.overflow = 'hidden';  // firefox, chrome
        document.body.scroll = "no"; // ie only
    }
});

        
 
