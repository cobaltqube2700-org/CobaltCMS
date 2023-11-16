<?php
    
include_once('CobaltCMS.php');

$cms = new CobaltCMS();
$cms->host     = "127.0.0.1:3306";
$cms->username = "root";
$cms->password = "";
$cms->connect();

// will this work in php 4.0.4? Register_globals is on but not sure $_POST exists until >= 4.1

if (!empty($title) && !empty($bodytext)) {

  $post_content = array();
  $post_content['title'] = $title;
  $post_content['bodytext'] = $bodytext;
  $cms->write($post_content);
}
    
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>CobaltCMS for PHP 4.0.4</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
  </head>
  <body>
  	<div id="page-wrapper"><?php
      echo ( 1 == "$admin" ) ? $cms->display_admin() : $cms->display_content();
?>
	</div>
  </body>
</html>