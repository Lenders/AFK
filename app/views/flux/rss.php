<item>
    <title>
        <?php echo $sender?>
        <?php if(isset($target)):?>
        &gt;
        <?php echo $target?>
        <?php endif?>
    </title>
    <link><?php echo isset($targetUrl) ? $targetUrl : $senderUrl?></link>
    <pubDate><?php echo date('d / m / y H:i:s', $date)?></pubDate>
    <description><?php echo $message?></description>
</item>