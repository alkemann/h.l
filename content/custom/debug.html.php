<h1> Start page</h1>

<h2>Defines are : </h2>
<?php
$debug = new \Debug();
$defines = $debug->defines();
dd($debug->defines());
?>
<h2>SERVER are :</h2>
<?php
dd($_SERVER);
