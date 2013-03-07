<h2>Server Data</h2>
<pre><?= $Responder->Escaper->escapeHtmlContent($serverData); ?></pre>
<h2>Session Data</h2>
<pre>Session active: <?= $Responder->Escaper->escapeHtmlContent($sessionActive); ?></pre>
<pre>Session ID: <?= \session_id(); ?></pre>
<pre><?= $Responder->Escaper->escapeHtmlContent($sessionData); ?></pre>
<h2>POST Data</h2>
<pre><?= $Responder->Escaper->escapeHtmlContent($postData); ?></pre>
<h2>GET Data</h2>
<pre><?= $Responder->Escaper->escapeHtmlContent($getData); ?></pre>
<h2>Controller Instantiated</h2>
<pre><?= $Responder->Escaper->escapeHtmlContent($controller); ?></pre>
<h2>Controller Action invoked</h2>
<pre><?= $Responder->Escaper->escapeHtmlContent($action); ?></pre>
<h2>Parameters</h2>
<pre><?= $Responder->Escaper->escapeHtmlContent($parameters); ?></pre>
