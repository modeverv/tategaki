<?php
require_once("tategaki.class.php");

if(isset($_REQUEST["value"])){
    $value = htmlspecialchars($_REQUEST["value"]);
    $tategaki = new Tategaki($value);
    echo $tategaki->get();
}else{
    echo "";
}

