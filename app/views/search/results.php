<section id="contents">
    <h1>Rechercher</h1>
    <article>
        <h2 class="title">Utilisateurs</h2>
        <?php echo $this->loadView('search/userlist.php', array('users' => $users))?>
    </article>
    <article>
        <h2 class="title">Évènements</h2>
        <?php echo $this->loadView('event/event_list.php', array('events' => $events))?>
    </article>
</section>