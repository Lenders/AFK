<?php include 'left_menu.php'?>
<section id="contents">
    <h1><?php echo htmlentities($event['EVENT_NAME'])?></h1>
    <article>
        <h2 class="title">Message</h2>
        <form id="message_form" action="<?php echo $this->secureUrl('events', 'sendmessage', $event['EVENT_ID'])?>" method="post">
            <textarea name="message" placeholder="Message..." id="message" maxlength="128" required></textarea>
            <input type="submit" value="Envoyer"/>
        </form>
    </article>
    <?php foreach ($flux as $article):?>
    <?php echo $article->getArticle('html')?>
    <?php endforeach?>
    <?php echo $this->js('pagination')?>
</section>