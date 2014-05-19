<?php
/**
 * File description: Common functions file
 * @author Kris Sherrerd  stimepy@aodhome.com
 * Modified by Kris Sherrerd
 * Last updated: 5/18/2014
 * Copyright (c) 2014
 * Version 1.0
 */
if(!defined('PMC_INIT')){
    die('Your not suppose to be in here! - Ibid');
}


function CreateHeader(){
    global $gx_template;
    $tid = $gx_template->AddTemplate('Main_head.tpl');
    //todo  title, nav buttoms etc.  not manually!

    $gx_template->AddVariables($tid, 'Capital Property Management',  'title' );
    $gx_template->AddVariables($tid,['style'=>'style.css', 'buttons' => [['url'=>'index.php?mod=properties' ,'link_name'=>'Properties']]] );

    $gx_template->RenderTemplate($tid, $display = true, $display_type = TEMPLATE_HOLD);
    AddSideMenu();
}

function CreateFooter(){
    global $gx_template;
    $tid = $gx_template->AddTemplate('Main_Footer.tpl');

    $gx_template->AddVariables($tid, 'Capital Property Management',  'website' );
    //$gx_template->AddVariables($tid, [['link' => 'index.php?mod=properties'], 'title' => 'Properties' ],  'footlinks' );

    $gx_template->RenderTemplate($tid, $display = true);
}

function AddSideMenu(){
    global $gx_template;
    $tid = $gx_template->AddTemplate('Main_menu.tpl');
    //todo create menus, title, etc.  not manually!
    $gx_template->AddVariables($tid, ['sidbars'=>[['menu_title'=>'Navigation', 'item'=>[['link'=>'#', 'name'=>'Thing you Do!']] ] ]] );
    $gx_template->RenderTemplate($tid, $display = true, $display_type = TEMPLATE_HOLD);

}

?>