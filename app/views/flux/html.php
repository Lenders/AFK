<article>
    <h2 class="title small">
        <a href="<?php echo $senderUrl?>"><?php echo $sender?></a>
        <?php if(isset($target)):?>
        &gt;
        <a href="<?php echo $targetUrl?>"><?php echo $target?></a>
        <?php endif?>
    </h2>
    <div class="date"><?php echo date('d / m / y à H:i:s', $date)?></div>
    <p><?php echo $message?></p>
</article>