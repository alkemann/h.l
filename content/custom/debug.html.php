<h1> Start page</h1>

<h2>Defines are : </h2>
<?php
$debug = \util\Debug::get_instance();
$defines = $debug->defines();
dw('defines');
?>
<h2>SERVER are :</h2>
<?php
d($_SERVER);
