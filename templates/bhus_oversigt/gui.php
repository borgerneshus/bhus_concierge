<?php
include_once "includes/functions.php";
$b = new bhus_oversigt();
//$calendar_events = $b->enrich_with_obib_data($calendar_events);
$calendar_events = $b->enrich_with_område_booking_data($calendar_events);
$calenda//r_events = $b->EnrichWithCustomEvents($calendar_events);

?>
<div id="wrapper" class="col-sm-12 col-lg-12 col-md-12 " style="margin:0px;padding:5px;">
    <div id="top-bar" class="col-sm-12 col-lg-12 col-md-12 removemargin">
        <div id="logo" class="col-sm-12 col-lg-12 col-md-12 removepadding">
            <div class="col-sm-2 col-lg-2 col-md-2 removepadding"><img style="float:left;height: 100px;margin-top: 18px;"  src="bhus_booking_events(1).png"></div>
            <div class="col-sm-8 col-lg-8 col-md-8 align-center "><h1 class="middle-text" style="padding-top:15px !important;" >Mødeoversigt</h1></div>  
            <div class="col-sm-2 col-lg-2 col-md-2 align-center"><h1 class="right-text" style="pading-top:13px !important;font-size:40px !important;" ><?php echo !$b->isTodayWeekend() ? "Åbent 8-21" : "Åbent 10-16"; ?></h1></div>
        </div>
    </div>
    <br/>
    <div class="col-md-12" style="margin-top:20px;">
        <?php
        $pagecount = 0;
        $total_show = 0;
        if (sizeof($calendar_events) != 0) {
            $calendar_events = array_chunk($calendar_events, $_GET['displaycount']);
            foreach ($calendar_events as $page => $events) {
                ?>
                <table id="main-table" class="table  table-striped <?php echo "page_" . $page ?>" <?php if ($page != 0) { echo "style='display:none'";} ?> >
                    <thead class="">
                        <tr>
                            <th scope="col">Tidspunkt</th>
                            <th scope="col">Titel</th>
                            <th scope="col">Lokale</th>
                        </tr>
                    </thead>
                    <tbody style="font-size:22px !important;">
                        <?php
                        $show_count = 0;
                        foreach ($events as $event) {
                            $php_Funny = str_replace('Æ', 'æ', strtolower($event->Location));
                            if (strpos($php_Funny, 'skærm') !== false) {
                                $event->Location = str_replace('Æ', 'æ',$event->Location);
                                $event->Location = str_replace("skærm", "", strtolower($event->Location));
                                $event->Location = str_replace("()", "", strtolower($event->Location));
                                
                                date_default_timezone_set('Europe/Copenhagen');
                                $start = new DateTime($event->Start);
                                $start->setTimeZone(new DateTimeZone('Europe/Copenhagen'));
                                $end = new DateTime($event->End);
                                $end->setTimeZone(new DateTimeZone('Europe/Copenhagen'));
                                $locations = explode(';',$event->Location );
                                ?>
                                <tr>
                                    <td style="white-space: nowrap;border-top-style:none;" class="col-md-2"><?php echo $start->format('H:i') . " - " . $end->format('H:i') ?></td>
                                    <td class="col-md-5" style="border-top-style:none;" ><?php echo $event->Subject; ?></td>
                                    <td style="width:20%;border-top-style:none;" class="col-md-5"><?php foreach($locations as $index => $location){echo $index >= 1 ? "<br/>". $location : $location;} ?></td>
                                </tr>
                                <?php
                                $show_count++;
                            }
                        }
                        
                        ?>
                    </tbody>
                </table>
                <?php
                if($show_count != 0)
                {
                    $total_show += $show_count;
                    $pagecount++;
                }
            }
        }
        if($total_show == 0){
            ?>
         <div id="no-results"><br/><br/>Ingen aktiviter på nuværende tidspunkt.</div>
            <?php
        }
        ?>
         <div class="col-md-12" style="margin-top:100px;"><div class="col-md-3"></div><div class="col-md-6"><div id="widget"></div></div><div class="col-md-3"></div>
        <input id="pagecount" value="<?php echo $pagecount ?>" type="hidden">
    </div>
</div>
<script type="text/javascript">
  var fffWidgetConfig = [];
  fffWidgetConfig.push({
    'widget' : 'interactive',
    'target' : '#widget',
    'style' : {
      'type' : 'minimal',
      'color' : 'turquoise'
    },
    'tracking' : false,
    'button' : { 'reload' : true, 'create' : false },
    'event' : { 'loadComplet' : null }
  });

  (function() {
    var fff = document.createElement('script'); fff.type = 'text/javascript'; fff.async = true;
    fff.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'service.finurligefakta.dk/widgets/fff.widget.min.js';  
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(fff, s);
  })();
</script>
