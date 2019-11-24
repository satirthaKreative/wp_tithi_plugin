<?php

/**
* Plugin Name:Tithi Plugin
* Plugin URI: http://localhost/
* Description: A plugin which is used to save data on db and export as exel
* Version: 1.0
* Author: Satirtha Das
* Author URI: http://localhost/
* License: GPL1
*/ 

add_action( 'admin_menu', 'custom_tithi');
function custom_tithi(){
	
	add_menu_page( 'tithi','TITHI','',__FILE__,'dashboard', plugins_url( 'tithi/images/half-moon.png'));
	add_submenu_page( __FILE__, 'Update Tithi', 'Update Tithi', 'administrator', __FILE__.'_addTithi', 'add_Tithi' );
	add_submenu_page( __FILE__, 'View Current Tithi', 'View Current Tithi', 'administrator', __FILE__.'_viewTithi', 'view_Tithi' );

}
function add_Tithi(){
	include 'add_tithi.php';
}
function view_Tithi(){
	include 'view-tithi.php';
}

?>

