<?php echo $this->widget('ProfileMenu', $this->getSession('id'))?>
<section id="contents">
    <h1>Nouvelles</h1>
    <?php foreach ($flux as $article): ?>
        <?php echo $article->getArticle('html') ?>
    <?php endforeach ?>
</section>