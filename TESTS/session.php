<?php
/**
 * Created by PhpStorm.
 * User: ksherrerd
 * Date: 4/15/14
 * Time: 4:34 PM
 */


function test(){
    include('./config.php');

    $session = new CSession();

    $_SESSION['bugger']='test';
}



?>