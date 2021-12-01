<?php
/*
Plugin Name: پرواز - اعمال تغییر روی گزارشات دکان
Plugin URI: https://github.com/mhmmdq/parvaz-change-dokan-sale-log
Description: افزونه ای برای اصلاح سیستم گزارش گیری دکان
Author: Mhmmdq
Version: 0.3.0
Author URI: https://github.com/mhmmdq
*/
if (!defined('ABSPATH'))
  exit;

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );


define( "PLUGIN_URL" , plugins_url( __FILE__ ) );
define( "PLUGIN_PATH" , __DIR__ );
define( "VIEW" , PLUGIN_PATH . '/views/');

if( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
  include 'vendor/autoload.php';
}

include_once 'inc/class-dokan-reports.php';
$_reports = new PARVAZ_DCL_REPORTS;

if( !file_exists( __DIR__ . '/functions.php') ) 
  exit;
include_once 'functions.php';

if( file_exists( __DIR__ . '/inc/class-database-table.php') ) {
  include_once 'inc/class-database-table.php';
  new PARVAZ_DCL_DB_TABLE;
}



if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) 
{

  if(is_plugin_active( 'dokan-lite/dokan.php' )) 
  { 
      include 'inc/class-dokan-change-log.php';
      new PARVAZ_DCL();
  }
  else
  {
    add_action('admin_notices' , function() {
      $message = 'اعمال تغییر روی گزارشات دکان برای کارکرد نیاز مند نصب و فعال سازی دکان را دارد';
      $html_message = sprintf( '<div class="notice notice-error" style="padding:10px;"> %s </div>', $message);
      echo $html_message; 
    });
  }

}
else
{
  add_action('admin_notices' , function() {
    $message = 'اعمال تغییر روی گزارشات دکان برای کارکرد نیاز مند نصب و فعال سازی ووکامرس را دارد';
    $html_message = sprintf( '<div class="notice notice-error" style="padding:10px;"> %s </div>', $message);
    echo $html_message; 
  });
}