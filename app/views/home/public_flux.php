<section id="contents">
    <h1>AFK</h1>
    <?php foreach ($flux as $article): ?>
        <?php echo $article->getArticle('html') ?>
    <?php endforeach ?>
</section>