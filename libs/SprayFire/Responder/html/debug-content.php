<h2>Server Data</h2>
<pre><?php echo $Responder->Escaper->escapeHtmlContent($serverData); ?></pre>
<h2>Session Data</h2>
<pre>Session active: <?php echo $Responder->Escaper->escapeHtmlContent($sessionActive); ?></pre>
<pre>Session ID: <?php echo \session_id(); ?></pre>
<pre><?php echo $Responder->Escaper->escapeHtmlContent($sessionData); ?></pre>
<h2>POST Data</h2>
<pre><?php echo $Responder->Escaper->escapeHtmlContent($postData); ?></pre>
<h2>GET Data</h2>
<pre><?php echo $Responder->Escaper->escapeHtmlContent($getData); ?></pre>
<h2>Controller Instantiated</h2>
<pre><?php echo $Responder->Escaper->escapeHtmlContent($controller); ?></pre>
<h2>Controller Action invoked</h2>
<pre><?php echo $Responder->Escaper->escapeHtmlContent($action); ?></pre>
<h2>Parameters</h2>
<pre><?php echo $Responder->Escaper->escapeHtmlContent($parameters); ?></pre>