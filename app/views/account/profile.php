<?php echo $this->widget('ProfileMenu', $user)?>
<section id="contents">
    <h1><?php echo $user['PSEUDO']?></h1>
    <?php foreach($flux as $article):?>
    <?php echo $article->getArticle('html')?>
    <?php endforeach?>
</section>