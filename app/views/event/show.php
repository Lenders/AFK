<section id="left_menu">
    <h2>Informations</h2>
    <ul>
        <li><img class="avatar" src="<?php echo $this->url('resources/images/default.png')?>" alt="Avatar"/></li>
        <?php foreach($properties as $property):?>
        <li><strong><?php echo $property['PROPERTY_NAME']?> : </strong><?php echo $property['PROPERTY_VALUE']?></li>
        <?php endforeach?>
        <li><strong>Organisateur : </strong><a href="<?php echo $this->secureUrl('account', 'profile', $event['ORGANIZER'])?>"><?php echo $event['PSEUDO']?></a></li>
    </ul>
    <h2>Participants</h2>
    <ul>
    <?php foreach($competitors as $competitor):?>
        <li><a href="<?php echo $this->secureUrl('account', 'profile', $competitor['USER_ID'])?>"><?php echo $competitor['PSEUDO']?></a></li>
    <?php endforeach?>
    </ul>
</section>
<section id="contents">
    <h1><?php echo htmlentities($event['EVENT_NAME'])?></h1>
    <?php if($isCompetitor):?>
    <?php for($i = 0; $i < 100; ++$i):?>
    <article>
        <h2 class="title">titre</h2>
        <p>Contenue</p>
    </article>
    <?php endfor?>
    <?php else:?>
    <article>
        <h2 class="title">Rejoindre</h2>
        <p>Vous ne faites pas partis de cet évènement.</p>
    </article>
    <?php endif?>
</section>