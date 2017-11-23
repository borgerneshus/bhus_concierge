var PageCount = 0;
var CurrentPage = 0;
var IntervalPageChange = 0;
$(document).ready(function(){

    var PageRotaote_timeout = 0;
    UpdateTemplate();
    
    /*
    * We need to keep repeating this everytime its done ?
    * And we need to determin optimal scroll rate.
    */
     setTimeout(function(){ 
        UpdateTemplate();
     },900000);
     
    function UpdateTemplate()
    {
        var hidden_fields = $( this ).find( 'input:hidden' );
        var template = $('#template').val();
        var TargetMails = $('#targetemails').val();
        var StartDate = $('#startdate').val();
        var EndDate = $('#enddate').val();
        var DisplayCount = $('#displaycount').val();
        var url = "/contentgenerator.php?skabelon=" + template + "&targetmailbox=" + TargetMails + "&start=" + StartDate + "&end=" + EndDate + "&displaycount="+DisplayCount;
        var jqxhr = $.get( url, function(data ) {
             $("#cover").css('display','none');
            $('#content').html(data);
            /*
             * Update the page counts = 0;
             */
            unloadScrollBars();
            PageCount = parseInt($('#pagecount').val());
            CurrentPage = 0;
            clearInterval(IntervalPageChange);
            $("#pagecounter").text((CurrentPage+1) + "/" + PageCount);
            IntervalPageChange = setInterval(function(){ PageRotate() }, 6000);
           
          })
         .done(function() {
            })
        .fail(function() {
          alert( "error" );
        })

    }
    function PageRotate()
    {
        if(PageCount > 1)
        {
            if(CurrentPage == (PageCount-1))
            {
                  $( ".page_" +CurrentPage ).fadeOut( "slow", function() {
                    $( ".page_0"  ).fadeIn( "slow", function() {
                        CurrentPage = 0;
                        $("#pagecounter").text((CurrentPage+1) + "/" + PageCount);
                    });
                  });
            }
            else
            {
                $( ".page_" +CurrentPage ).fadeOut( "slow", function() {
                    $( ".page_" +(CurrentPage+1) ).fadeIn( "slow", function() {
                        CurrentPage++;
                        $("#pagecounter").text((CurrentPage+1) + "/" + PageCount);
                    });
                  });
            }
        }
        
        
    }
    function unloadScrollBars() {
        document.documentElement.style.overflow = 'hidden';  // firefox, chrome
        document.body.scroll = "no"; // ie only
    }
});

        
 
