<section id="left_menu">
    <h2>Informations</h2>
    <?php if($event['ORGANIZER'] == $this->getSession('id')):?>
    <a href="<?php echo $this->secureUrl('image', 'select', $event['EVENT_ID'])?>" title="Changer d'image">
    <?php endif?>
    <img class="avatar" src="<?php echo $event['IMAGE'] ? $this->secureUrl('image', 'get', $event['ORGANIZER'], $event['IMAGE']) : $this->url('resources/images/default.png')?>" alt="Image"/>
    <?php if($event['ORGANIZER'] == $this->getSession('id')):?>
    </a>
    <?php endif?>
    <ul>
        <?php foreach($properties as $property):?>
        <li><strong><?php echo $property['PROPERTY_NAME']?> : </strong><?php echo $property['PROPERTY_VALUE']?></li>
        <?php endforeach?>
        <li><strong>Organisateur : </strong><a href="<?php echo $this->secureUrl('account', 'profile', $event['ORGANIZER'])?>"><?php echo $event['PSEUDO']?></a></li>
    </ul>
    <h2>Participants</h2>
    <ul id="competitors_list">
    <?php foreach($competitors as $competitor):?>
        <img class="thumb" src="<?php echo $competitor['AVATAR'] ? $this->secureUrl('image', 'get', $competitor['USER_ID'], $competitor['AVATAR']) : $this->url('resources/images/default.png')?>" alt="Avatar"/>
        <li><a href="<?php echo $this->secureUrl('account', 'profile', $competitor['USER_ID'])?>"><?php echo $competitor['PSEUDO']?></a></li>
    <?php endforeach?>
    </ul>
    <?php if($event['ORGANIZER'] == $this->getSession('id')):?>
    <h2>Administration</h2>
    <ul class="links_list">
        <li><a href="<?php echo $this->secureUrl('events', 'competitors', $event['EVENT_ID'])?>">Participants <span class="notif"><?php echo $req_count > 0 ? $req_count : ''?></span></a></li>
    </ul>
    <?php endif?>
</section>