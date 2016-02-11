<?php
/**
 * Created by PhpStorm.
 * User: Stimepy
 * Date: 5/20/15
 * Time: 11:20 PM
 */

interface plugin {
    function installed();


    function uninstall();


    function installModule();

    function version();

} 