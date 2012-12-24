<ul>
    <?php foreach($messages as $message): ?>
    <li><?php echo $Responder->Escaper->escapeHtmlContent($message); ?></li>
    <?php endforeach; ?>
</ul>
