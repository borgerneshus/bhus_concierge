<?php
include_once 'includes/bhus_concierge.php';
$template = isset($_GET['skabelon']) ?  $_GET['skabelon']: null;
if($template == null)
{
	echo "Angiv en skabelon";
	exit(1);
}

//echo realpath(dirname(__FILE__)) ."/templates/" . $template . "/gui.php<br/>" ;
if(!file_exists (realpath(dirname(__FILE__)). "/templates/" . $template . "/gui.php" ));
{
	//echo "skabelon findes ikke";
	//exit(1);
}
function isTodayWeekend() {
    $currentDate = new DateTime("now", new DateTimeZone("Europe/Amsterdam"));
    return $currentDate->format('N') >= 6;
}
$_GET['targetmailbox'] = "lok11_borghus@odense.dk,lok12_borghus@odense.dk,lok21_borghus@odense.dk,lok22_borghus@odense.dk,lok31_borghus@odense.dk,lok32_borghus@odense.dk,lok33_borghus@odense.dk,lok34_borghus@odense.dk,lok35_borghus@odense.dk,lok36_borghus@odense.dk";
$_GET['start'] = '00:00:00'; 
$_GET['end'] =  '23:59:59';
$calendar_events = array();
try {
    $concierge = new bhus_concierge();
    $calendar_events = $concierge->GetByServiceInput();
} catch (Exception $e) {
    
}

?>
<!DOCTYPE html>
<html>
    <head>
    	 <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
        <link rel="stylesheet" href="/css/global.css">
        <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>      
        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <?php 	include_once("templates/" . $template . "/header.php");
				
        ?>
        <meta charset="utf-8" />
    </head>
    <body class="col-md-12 removemargin removepadding">
        <input type="hidden" val="<?php $template ?>">
        <div id="content">
            <?php include_once("templates/" . $template . "/gui.php") ?>
        </div>
        <div id="preloader"></div>
    </body>
</html>