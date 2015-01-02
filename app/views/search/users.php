<section id="contents">
    <h1>Recherche d'utilisateurs</h1>
    <article>
        <h2 class="title">Nouvelle recherche</h2>
        <p>Vous pouvez utiliser lors de vos recherche le caractère jocker <b>*</b> qui remplace n'importe quel nombre de caractères.</p>
        <form method="get" action="<?php echo $this->url('search/users.php')?>">
            <input value="<?php echo $query?>" name="search" placeholder="Rechercher..." id="user_search" />
            <input type="submit" value="Rechercher" />
        </form>
    </article>
    <article>
        <h2 class="title">Résulats</h2>
        <?php echo $this->loadView('search/userlist.php', array('users' => $users))?>
    </article>
</section>