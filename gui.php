<?php
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

include_once 'events.php';
include_once 'bhus_concierge.php';
function isTodayWeekend() {
    $currentDate = new DateTime("now", new DateTimeZone("Europe/Amsterdam"));
    return $currentDate->format('N') >= 6;
}
$_GET['targetmailbox'] = "lok11_borghus@odense.dk,lok12_borghus@odense.dk,lok21_borghus@odense.dk,lok22_borghus@odense.dk,lok31_borghus@odense.dk,lok32_borghus@odense.dk,lok33_borghus@odense.dk,lok34_borghus@odense.dk,lok35_borghus@odense.dk,lok36_borghus@odense.dk";
$_GET['start'] = '00:00:00'; 
$_GET['end'] = '23:59:59';
$concierge = new bhus_concierge();
$calendar_events = $concierge->GetByServiceInput();
//$calendar_events = getOutlookCalendarEvents("tfpet@odense.dk","MinKode201777",'00:00:00','23:59:59');
?>
<!DOCTYPE html>
<html>
    <head>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
        
        <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>      
        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        
        <meta charset="utf-8" />
        <style>
            body,#top-bar
            {
                color:white;
                background-color: #115959;
                overflow-y: no-display;
            }
            #wrapper
            {
                margin: 10px;
                padding: 20px;
                
            }
            .align-center
            {
                text-align: center;
            }
            #main-table
            {
                font-size: 34px;
                
            }
            #logo h1{
                font-size: 80px;
            }
            #logo h2{
                font-size: 45px;
            }
        </style>
        <script>
            $(function() {
                function unloadScrollBars() {
                    document.documentElement.style.overflow = 'hidden';  // firefox, chrome
                    document.body.scroll = "no"; // ie only
                }
                    unloadScrollBars();
                    $('html, body').animate({ scrollTop: $(document).height() - $(window).height() }, 400000, function() {
                      $(this).animate({ scrollTop: 0 }, 1000);
                   });
             
             setTimeout(function(){ 
                 
                }, 3000);
                });
        </script>
    </head>
    <body class="col-md-12 ">
        
        <div id="wrapper" class="col-md-12 " style="margin:0px;padding:5px;">
            <div id="top-bar" class="col-md-12 navbar-fixed-top">
                  <div id="logo" class="col-md-12">
                    <div class="col-md-2"><img  src="bhus_booking_events(1).png"></div>
                    <div class="col-md-7 align-center"><h1>Det sker i dag</h1></div>  <div class="col-md-3" style="float:right;text-align: right;"><h1><?php echo isTodayWeekend() ? "Åbent 8-21" : "Åbent 10-16"; ?></h1></div>
                  </div>
            </div>
            <div class="col-md-12" style="margin-top:150px;">
            <table id="main-table" class="table">
                <thead class="">
                  <tr>
                    <th scope="col">Tidspunkt</th>
                    <th scope="col">Titel</th>
                    <th scope="col">Lokale</th>
                  </tr>
                </thead>
                <tbody>
                    <?php
            foreach($calendar_events as $event)
            {
                if(strpos(strtolower($event->Location), 'skærm') !== false)
                {
                    $event->Location = str_replace("skærm", "", $event->Location);
                    date_default_timezone_set('Europe/Copenhagen');
                    $start = new DateTime($event->Start);
                    $start->setTimeZone(new DateTimeZone('Europe/Copenhagen'));
                    $end = new DateTime($event->End);
                    $end->setTimeZone(new DateTimeZone('Europe/Copenhagen'));
                ?>
                      <tr>
                        <td class="col-md-2"><?php echo $start->format('H:i') . " - " .$end->format('H:i') ?></td>
                        <td class="col-md-8" ><?php echo $event->Subject ?></td>
                        <td class="col-md-2"><?php echo $event->Location ?></td>
                      </tr>
                <?php
                }
            }
            ?>
                </tbody>
            </table>
            </div>
        </div>
        
    </body>
</html>

<?php
// For the current date

