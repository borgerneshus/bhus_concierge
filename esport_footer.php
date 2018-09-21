<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<html>
    <head>
        <title></title>
        <style>
            body{
                background-color:black;
                color: white;
                font-size:25px;
            }
            .dot {
                height: 25px;
                width: 25px;
                background-color: red;
                border-radius: 50%;
                display: inline-block;
                margin-right: 5px;
                margin-top: 2px;
 
            }
  .dot:before {
    content: '';
    //position: relative;
    display: block;
    width: 300%;
    height: 300%;
    box-sizing: border-box;
    margin-left: -100%;
    margin-top: -100%;
    border-radius: 45px;
    background-color: red;
    animation: pulse-ring 4.25s cubic-bezier(0.215, 0.61, 0.355, 1) infinite;
  }

@keyframes pulse-ring {
  0% {
    transform: scale(.33);
  }
  80%, 100% {
    opacity: 0;
  }
}

@keyframes pulse-dot {
  0% {
    transform: scale(.8);
  }
  50% {
    transform: scale(1);
  }
  100% {
    transform: scale(.8);
  }
}
        </style>
    </head>
    <body>
            <div>
                <div style="margin-top:13px;float:left;">
                    <div class="dot" style="float:left;"></div>
                    <div style="float:left;">Kom og se E-sport live i stuen!</div>
                </div>
            <div style="float:right;"><img src="/esport_logo_small.jpg"></div>
            </div>
    </body>
</html>
