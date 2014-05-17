<?php

//define("PB_CRYPT_LINKS" , 1);
require "config.php";

$site = new CMaster("./site.xml",true);
global $gx_user, $gx_template;

if(!$gx_users->checkloggedin()){
    $gx_users->GoLogin();
}
$site->Run();

?>
