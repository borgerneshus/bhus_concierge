<?php

function isTodayWeekend() {
    $currentDate = new DateTime("now", new DateTimeZone("Europe/Amsterdam"));
    return $currentDate->format('N') >= 6;
}
?>
<div id="wrapper" class="col-md-12 " style="margin:0px;padding:5px;">
    <div id="top-bar" class="col-md-12 navbar-fixed-top removemargin">
        <div id="logo" class="col-md-12 removepadding">
            <div class="col-md-3 removepadding"><img style="float:left;"  src="bhus_booking_events(1).png"></div>
            <div class="col-md-6 align-center "><h1 class="middle-text">Det sker i dag</h1></div>  
            <div class="col-md-3 removepadding" style="float:right;text-align: right;"><h1 class="right-text"><?php echo!isTodayWeekend() ? "Åbent 8-21" : "Åbent 10-16"; ?></h1></div>
        </div>
    </div>
    <br/>
    <div class="col-md-12" style="margin-top:150px;">
        <?php
        if (sizeof($calendar_events) != 0) {

            $calendar_events = array_chunk($calendar_events, $_GET['displaycount']);
            foreach ($calendar_events as $page => $events) {
                ?>
                <table id="main-table" class="table <?php echo "page_" . $page ?>" <?php if ($page != 0) { echo "style='display:none'";} ?> >
                    <thead class="">
                        <tr>
                            <th scope="col">Tidspunkt</th>
                            <th scope="col">Titel</th>
                            <th scope="col">Lokale</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($events as $event) {
                            if (strpos(strtolower($event->Location), 'skærm') !== true) {
                                $event->Location = str_replace("skærm", "", $event->Location);
                                date_default_timezone_set('Europe/Copenhagen');
                                $start = new DateTime($event->Start);
                                $start->setTimeZone(new DateTimeZone('Europe/Copenhagen'));
                                $end = new DateTime($event->End);
                                $end->setTimeZone(new DateTimeZone('Europe/Copenhagen'));
                                ?>
                                <tr>
                                    <td class="col-md-3"><?php echo $start->format('H:i') . " - " . $end->format('H:i') ?></td>
                                    <td class="col-md-6" ><?php echo $event->Subject ?></td>
                                    <td class="col-md-3"><?php echo $event->Location ?></td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
                <?php
            }
        } else {
            ?>
        <div id="no-results"><br/><br/>Ingen aktiviter på nuværende tidspunkt.</div>
            <?php
        }
        ?>
        <input id="pagecount" value="<?php echo sizeof($calendar_events) ?>" type="hidden">
    </div>
</div>