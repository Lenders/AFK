<article>
    <h2 class="title">
        <a href="<?php echo $senderUrl?>"><?php echo $sender?></a>
        <?php if(isset($target)):?>
        &gt;
        <a href="<?php echo $targetUrl?>"><?php echo $target?></a>
        <?php endif?>
    </h2>
    <span class="date"><?php echo $date?></span>
    <p><?php echo $message?></p>
</article>