<?php echo $this->widget('ProfileMenu', $user_id)?>
<section id="contents">
    <h1>
        <?php if($user_id == $this->getSession('id')):?>
        Mes évènements
        <?php else:?>
        Évènements - <a href="<?php echo $this->secureUrl('account', 'profile', $user_id)?>"><?php echo $user_pseudo?></a>
        <?php endif?>
    </h1>
    
    <article>
        <h2 class="title">Mes évènements</h2>
        <?php echo $this->loadView('event/event_list.php', array('events' => $org_events))?>
        <?php if($user_id == $this->getSession('id')):?>
        <div class="button_container">
            <a href="<?php echo $this->url('createevent.php')?>" class="button">Créer un évènement</a>
        </div>
        <?php endif?>
    </article>
    
    <?php if(!empty($par_events)):?>
    <article>
        <h2 class="title">Participations</h2>
        <?php echo $this->loadView('event/event_list.php', array('events' => $par_events))?>
        <?php if($user_id == $this->getSession('id')):?>
        <div class="button_container">
            <a href="<?php echo $this->url('search/events.php')?>" class="button">Rechercher un évènement</a>
        </div>
        <?php endif?>
    </article>
    <?php endif?>
</section>