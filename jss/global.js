$(document).ready(function(){
    unloadScrollBars();
    /*
    * We need to keep repeating this everytime its done ?
    * And we need to determin optimal scroll rate.
    */
     setTimeout(function(){ 
          $('html, body').animate({ scrollTop: $(document).height() - $(window).height() }, 400000, function() {
            $(this).animate({ scrollTop: 0 }, 400000);
         });
      }, 850000);

    
});
function UpdateTemplate()
{
    var RequestUrl = "";
    var Template = "";
    var result = "";
    var nextrefresh = "";
    
    //fire event ? 
}
    function unloadScrollBars() {
        document.documentElement.style.overflow = 'hidden';  // firefox, chrome
        document.body.scroll = "no"; // ie only
    }
        
 
