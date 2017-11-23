var PageCount = 0;
var CurrentPage = 0;
var IntervalPageChange = 0;
$(document).ready(function(){
    var PageRotaote_timeout = 0;
    UpdateTemplate();
    setInterval(function(){ 
        UpdateTemplate();
     },900000);

    function UpdateTemplate()
    {
        var template = $('#template').val();
        var TargetMails = $('#targetemails').val();
        var StartDate = $('#startdate').val();
        var EndDate = $('#enddate').val();
        var DisplayCount = $('#displaycount').val();
        var url = "/contentgenerator.php?skabelon=" + template + "&targetmailbox=" + TargetMails + "&start=" + StartDate + "&end=" + EndDate + "&displaycount="+DisplayCount;
        var jqxhr = $.get( url, function(data ) {
            $("#cover").css('display','none');
            $( "#content" ).fadeOut( "slow", function() {
                clearInterval(IntervalPageChange);
                 $('#content').html(data);
                $( "#content" ).fadeIn( "slow", function() {
                     /*
                    * Update the page counts = 0;
                    */
                   if($('#bhus_open').is(':hidden'))
                   {
                    $('#bhus_open').toggle();
                   }
                    unloadScrollBars();
                    PageCount = parseInt($('#pagecount').val());
                    CurrentPage = 0;
                    
                    if(PageCount > 1)
                    {
                        $("#pagecounter").text((CurrentPage+1) + "/" + PageCount);
                        IntervalPageChange = setInterval(function(){ PageRotate() }, 8000);
                    }
                });
               
            });
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

        
 
