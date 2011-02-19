<h1>Sitemap</h1>
<link rel="stylesheet" type="text/css" media="screen,print" href="/libs/slickmap/slickmap.css" />
<?php
$sitemap = \util\Sitemap::generate();
?>
<div class="sitemap">
		<ul id="utilityNav"> 
			<li><a href="/sitemap.html">Site Map</a></li> 
		</ul> 
 
        <ul id="primaryNav" class="col<?php count($sitemap);?>"> 
			<li id="home"><a href="http://hjemmesiden.l">Home</a></li> 
<?php
        foreach ($sitemap as $name => $contains) {
            echo '<li>';
            if (is_string($contains[0]))
                echo '<a href="' . $contains[0] . '" >' . $name . '</a>';
            $children = (isset($contains[1])) ? $contains[1] : $contains[0];
            if (is_array($children)) {
                echo '<ul>';
                foreach ($children as $name1 => $contains1) {
                    echo '<li>';
                    if (is_string($contains1[0]))
                        echo '<a href="/' . $name . '/' . $contains1[0] . '" >' . $name1 . '</a>';
                    $children1 = (isset($contains1[1])) ? $contains1[1] : $contains1[0];
                    if (is_array($children1)) {
                        echo '<ul>';
                        foreach ($children1 as $name2 => $contains2) {
                            echo '<li>';
                            if (is_string($contains2[0]))
                                echo '<a href="/' . $name . '/' . $name1 . $contains2[0] . '" >' . $name2 . '</a>';
                            echo '</li>';
                        }
                        echo '</ul>';
                    }

                    echo '</li>';
                }
                echo '</ul>';
            }
            echo '</li>';
        }
?>
		</ul> 
 </div>
<?php

