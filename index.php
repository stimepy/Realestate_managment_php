<?php

//define("PB_CRYPT_LINKS" , 1);
require "config.php";

$site = new CSite("./site.xml",true);
$site->Run();

?>
