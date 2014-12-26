<ul class="event_list">
    <?php foreach($events as $event):?>
    <li>
        <img src="<?php echo $this->url('resources/images/default.png')?>" class="thumb" />
        <div class="left">
            <a href="<?php echo $this->secureUrl('events', 'show', $event['EVENT_ID'])?>"><h3><?php echo $event['EVENT_NAME']?></h3></a>
            <span class="event_description"><?php echo $event['PROPERTIES']['DESCRIPTION']['PROPERTY_VALUE']?></span>
        </div>
        <div class="right">
            <div class="event_dates"><strong>Début :</strong> <?php echo date('d-m-y à G:i', $event['START_TIME'])?> <strong>Fin :</strong> <?php echo date('d-m-y à G:i', $event['END_TIME'])?></div>
            <div class="event_game"><strong>Jeu :</strong> <?php echo $event['PROPERTIES']['JEU']['PROPERTY_VALUE']?></div>
        </div>
    </li>
    <?php endforeach?>
</ul>