<h1> Start page</h1>

<h2>Defines are : </h2>
<?php
$defines = array();
$defines['CONTENT_DIR'] = CONTENT_DIR;
$defines['LAYOUT_DIR'] = LAYOUT_DIR;
$defines['DS'] = DS;
$defines['ROOT'] = ROOT;
$defines['WEBROOT_DIR'] = WEBROOT_DIR;
$defines['WWW_ROOT'] = WWW_ROOT;
$defines['CORE_PATH'] = CORE_PATH;
$defines['CONTENT_PATH'] = CONTENT_PATH;
$defines['LAYOUT_PATH'] = LAYOUT_PATH;

dd($defines);
?>
<h2>SERVER are :</h2>
<?php
dd($_SERVER);
