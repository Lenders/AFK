<section id="contents">
    <h1>Recherche</h1>
    <article>
        <h2 class="title">Nouvelle recherche</h2>
        <p>Vous pouvez utiliser lors de vos recherche le caractère jocker <b>*</b> qui remplace n'importe quel nombre de caractères.</p>
        <form method="get" action="<?php echo $this->url('search.php')?>">
            <input value="<?php echo $query?>" name="search" placeholder="Rechercher..." id="glob_search" />
            <input type="submit" value="Rechercher" />
        </form>
    </article>
    <article>
        <h2 class="title">Utilisateurs</h2>
        <?php echo $this->loadView('search/userlist.php', array('users' => $users))?>
        <div class="button_container">
            <a class="button" href="<?php echo $this->url('search/users.php?search=' . $query)?>">Plus</a>
        </div>
    </article>
    <article>
        <h2 class="title">Évènements</h2>
        <?php echo $this->loadView('event/event_list.php', array('events' => $events))?>
        <div class="button_container">
            <a class="button" href="<?php echo $this->url('search/events.php?search=' . $query)?>">Plus</a>
        </div>
    </article>
</section>