<?php
$template = isset($_GET['skabelon']) ?  $_GET['skabelon']: null;
if($template == null)
{
    return null;
}

