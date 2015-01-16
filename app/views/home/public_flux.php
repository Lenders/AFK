<section id="left_menu">
    <h2>Informations</h2>
    <p>
        <strong>AFK</strong> est un site communautaire pour geek permettant l'organisation de tournois pour différents jeux.<br/>
        Ce site est un projet fait au sein de la formation à l'<strong>IUT d'Aix-en-Provence</strong>. <br/>
        <a href="http://www.mickael-martin-nevot.com/iut-informatique/programmation-web-c%C3%B4t%C3%A9-serveur/s24-projet.pdf" target="_blank">Lien du projet</a>
    </p>
    <h2>Chiffres</h2>
    <ul>
        <li><strong>Inscriptions : </strong><?php echo $inscriptions?></li>
        <li><strong>Évènements : </strong><?php echo $events?></li>
        <li><strong>Connectés : </strong><?php echo $online?></li>
    </ul>
    <h2>Connection</h2>
    <form method="post" action="<?php echo $this->url('login/submit.php')?>">
        <input type="text" name="pseudo" placeholder="Pseudo" />
        <input type="password" name="pass" placeholder="Mot de passe" />
        <input type="submit" value="Connexion" />
    </form>
</section>
<section id="contents">
    <h1>AFK</h1>
    <?php foreach ($flux as $article): ?>
        <?php echo $article->getArticle('html') ?>
    <?php endforeach ?>
    <?php echo $this->js('pagination')?>
</section>