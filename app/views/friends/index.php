<?php echo $this->widget('ProfileMenu', $this->getSession('id'))?>
<section id="contents">
    <h1>Mes amis</h1>
    <article>
        <h2 class="title">Requêtes</h2>
        <?php echo $this->loadView('search/userlist.php', array('users' => $requests))?>
    </article>
    <article>
        <h2 class="title">Liste</h2>
        <?php echo $this->loadView('search/userlist.php', array('users' => $friends))?>
    </article>
</section>