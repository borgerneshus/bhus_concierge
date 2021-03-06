<?php
require_once 'vendor/autoload.php';
use jamesiarmes\PhpEws\Autodiscover;

// Replace these with the appropriate values.
$email = '';
$password = '';

// Simplest usage, no special options.
$template = isset($_GET['skabelon']) ?  $_GET['skabelon']: null;
if($template == null)
{
	echo "Angiv en skabelon";
	exit(1);
}
$_GET['targetmailbox'] = isset($_GET['targetmailbox']) ? $_GET['targetmailbox'] : "borgerneshusbooking@odense.dk";
$_GET['start'] = isset($_GET['start']) ? $_GET['start'] : '00:00:00'; 
$_GET['end'] =  isset($_GET['end']) ? $_GET['end'] : '23:59:59';
$_GET['displaycount'] =  isset($_GET['displaycount']) ? $_GET['displaycount'] : '15';
$_GET['filter_screen'] =  isset($_GET['filter_screen']) ? $_GET['filter_screen'] : 1;

?>
<!DOCTYPE html>
<html>
    <head>
    	 <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
        <link rel="stylesheet" href="/css/global.css">
         <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        
        <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>      
        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <script src="/js/global.js"></script>
        <?php 	include_once("templates/" . $template . "/header.php");
				
        ?>
        <meta charset="utf-8" />
    </head>
    <body class="col-md-12 removemargin removepadding">
 
        
        <div id="content">
            <?php //include_once("templates/" . $template . "/gui.php") ?>
        </div>
        <br/><br/><br/><br/>
        <div id="" class="col-md-12" style="padding:10px;text-align:center;font-size: 30px;display:none;" class="pull-left">Se programmet for åbningsweekenden på borgerneshus.dk</div>
        <div id="cover"><div id="center-cover"><i class="fa fa-cog fa-spin fa-3x fa-fw"></i><b>Indlæser...</b><br/></div></div>
        <input id="template" type="hidden" value="<?php echo $template ?>">
        <input id="targetemails" type="hidden" value="<?php echo $_GET['targetmailbox'] ?>">
        <input id="startdate" type="hidden" value="<?php echo $_GET['start'] ?>">
        <input id="enddate" type="hidden" value="<?php echo $_GET['end'] ?>">
        <input id="displaycount" type="hidden" value="<?php echo $_GET['displaycount'] ?>">
        <input id="filter_screen" type="hidden" value="<?php echo $_GET['filter_screen'] ?>">
         <footer class="footer col-md-12">
            <div class="col-md-12">
              <div id="pagecounter" class="col-md-12" style="text-align: right;font-size:30px;padding-right:20px !important;"></div>
            </div>
        </footer>
    </body>
</html>
