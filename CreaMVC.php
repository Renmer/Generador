<?php
session_start();
include_once 'CreaHelper.php';
include_once 'CreaModelo.php';
include_once 'CreaControlador.php';

    //new CreaHelper($_POST['select']);
    //new CreaModelo($_POST['select']);
    new CreaControlador($_POST['select']);

    
?>