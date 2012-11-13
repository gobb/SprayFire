<h2>Server Data</h2>
<pre><?php echo $serverData; ?></pre>
<h2>Session Data</h2>
<pre>Session active: <?php echo $sessionActive; ?></pre>
<pre>Session ID: <?php echo \session_id(); ?></pre>
<pre><?php echo $sessionData; ?></pre>
<h2>POST Data</h2>
<pre><?php echo $postData; ?></pre>
<h2>GET Data</h2>
<pre><?php echo $getData; ?></pre>
<h2>Controller Instantiated</h2>
<pre><?php echo $controller; ?></pre>
<h2>Controller Action invoked</h2>
<pre><?php echo $action; ?></pre>
<h2>Parameters</h2>
<pre><?php echo $parameters; ?></pre>