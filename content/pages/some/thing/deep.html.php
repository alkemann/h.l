<h1>URL Aliases</h1>

<h2>You can get here by:</h2>
<ol>
    <li><a href="/start">/start</a></li>
    <li><a href="/start.html">/start.html</a></li>
    <li><a href="/place">/place</a></li>
    <li><a href="/place.html">/place.html</a></li>
    <li><a href="/some/thing/deep">/some/thing/deep</a></li>
    <li><a href="/some/thing/deep.html">/some/thing/deep.html</a></li>
</ol>

<h2>However the canonical url should be:</h2>
<p><strong style="color:blue;">/some/thing/deep.html</strong></p>
<p>There should therefore be a canonical &lt;link tag in the header like
this :<br><strong>
&lt;link rel="canonical" href="http://www.example.com/some/thing/deep.html" />
</strong></p>
<p>The reason the last, deepest one is counted as canonical in this case
is that the actual document is located at <i>/content/pages/some/thing/deep.html.php</i></p>
<p>Also any url without a <i>.something</i> is assumed to be a <i>.html</i></p>

<h2>To create aliases:</h2>
<p>Put this in ur <i>/content/bootstrap.php</i></p>
<blockquote style="color:#355;background-color:#EEE;"><pre>
&lt;?php

use \core\Router;

Router::alias('/', 'home');
Router::alias('/start', 'some/thing/deep');
Router::alias('/place', 'some/thing/deep');
Router::alias('/players.json', 'another/place.json');
</pre></blockquote>