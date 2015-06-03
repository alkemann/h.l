<h1>Page Not Found</h1>
<h2>We appologize for the inconvenience</h2>
<p>You can try the <a href="/sitemap.html">Sitemap</a> or 
go to the <a href="/">homepage</a>.</p>

<?php if (DEBUG): // dev mode ?>
<p>View file not found!</p>
<p>Create it at: <strong style="color:blue"> <?=$this->missing?> </strong></p>
<?php endif; ?>
