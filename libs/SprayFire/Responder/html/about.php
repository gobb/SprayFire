<ul>
    <?php foreach($messages as $message): ?>
    <li><?= $Responder->Escaper->escapeHtmlContent($message); ?></li>
    <?php endforeach; ?>
</ul>
