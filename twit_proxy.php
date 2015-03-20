<?php


session_start(); 
$_SESSION['url'] = $_POST['url'];
$_SESSION['twit'] = $_POST['inpTwit'];

header('Location: '.$_POST['url']);

