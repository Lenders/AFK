<?php include 'left_menu.php'?>
<section id="contents">
    <h1><?php echo htmlentities($event['EVENT_NAME'])?></h1>
    <article>
        <h2 class="title">Rejoindre</h2>
        <p class="error">Vous ne faites pas partis de cet évènement.</p>
        <?php if($event['EVENT_STATE'] !== 'OPEN' || time() > $event['EVENT_START']):?>
        <p class="error">Cet évènement est fermé !</p>
        <?php else:?>
        <p>
            Cet évènement est 
            <strong>
            <?php switch ($event['EVENT_PRIVACY']){
                case 'PUBLIC':
                    echo 'ouvert à tous';
                    break;
                case 'PRIVATE':
                    echo 'privé';
                    break;
                case 'FRIEND':
                    echo 'ouvert pour les amis';
                    break;
            }?>
            </strong>!
        </p>
        <?php if($this->isLogged()):?>
        <div class="button_container">
            <?php if($canJoin):?>
            <a href="<?php echo $this->secureUrl('events', 'join', $event['EVENT_ID'])?>" class="button">Rejoindre</a>
            <?php elseif(!$pending):?>
            <a href="<?php echo $this->secureUrl('events', 'request', $event['EVENT_ID'])?>" class="button">Envoyer une demande</a>
            <?php else:?>
            <a href="<?php echo $this->secureUrl('events', 'cancel', $event['EVENT_ID'], $this->getSession('id'))?>" class="button red">Annuler la demande</a>
            <?php endif?>
        </div>
        <?php endif?>
        <?php endif?>
    </article>
</section>