<?php
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
include_once 'events.php';
$calendar_events = getOutlookCalendarEvents("tfpet@odense.dk","",'00:00:00','23:59:59');
?>
<html>
    <head>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <meta charset="utf-8" />
        <style>
            body
            {
                
            }
            #wrapper
            {
                margin: 10px;
                
                padding: 20px;
            }
        </style>
    </head>
    <body>
        <h1>Borgernes Hus - Det Sker I Dag</h1>
        <div id="wrapper">
            <h2><?php echo (new \DateTime())->format('d-m-Y'); ?> </h2>
            <table class="table table-responsive-md">
                <thead class="table-striped thead-dark">
                  <tr>
                    <th scope="col">Titel</th>
                    <th scope="col">Start - Slut</th>
                    <th scope="col">Lokale</th>
                  </tr>
                </thead>
                <tbody>
                    <?php
            foreach($calendar_events as $event)
            {
                $start = new DateTime($event->Start);
                $end = new DateTime($event->End);
            ?>
                  <tr>
                    <td class="col-md-5" ><?php echo $event->Subject ?></td>
                    <td class="col-md-5"><?php echo $start->format('H:m') . " - " .$end->format('H:m') ?></td>
                    <td class="col-md-2"><?php echo $event->Location ?></td>
                  </tr>
            <?php
            }
            ?>
                </tbody>
            </table>
        </div>
        
    </body>
</html>

<?php

