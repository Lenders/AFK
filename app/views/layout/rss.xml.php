<?xml version="1.0" encoding="UTF-8" ?>
<rss version="2.0">
    <channel>
        <title>AFK - <?php echo $this->title?></title>
        <link><?php echo $this->helpers->baseUrl()?></link>
        <description>AFK est un site communautaire pour geek permettant l'organisation de tournois pour différents jeux.</description>
        <copyright>AFK copyright © <?php echo date('Y')?></copyright>
        <image>
            <url><?php echo $this->helpers->url('resources/images/logo.png')?></url>
            <title>Logo AFK</title>
            <link><?php echo $this->helpers->baseUrl()?></link>
        </image>
        <?php echo $this->contents?>
    </channel>
</rss>