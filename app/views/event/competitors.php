<?php include 'left_menu.php'?>
<section id="contents">
    <h1>Participants - <a href="<?php echo $this->secureUrl('events', 'show', $event['EVENT_ID'])?>"><?php echo htmlentities($event['EVENT_NAME'])?></a></h1>
    <article>
        <h2 class="title">Demandes</h2>
        <ul class="user_list">
            <?php if(!empty($requests)):?>
            <?php foreach($requests as $user):?>
            <li>
                <img class="thumb" src="<?php echo $user['AVATAR'] ? $this->secureUrl('image', 'get', $user['USER_ID'], $user['AVATAR']) : $this->url('resources/images/default.png')?>" alt="Avatar"/>
                <a class="userlink" href="<?php echo $this->secureUrl('account', 'profile', $user['USER_ID'])?>">
                    <?php echo $user['PSEUDO']?> (<?php echo $user['FIRST_NAME'], ' ', $user['LAST_NAME']?>)
                </a>
                <div class="buttons">
                    <a href="<?php echo $this->secureUrl('events', 'accept', $event['EVENT_ID'], $user['USER_ID'])?>" class="button">Accepter</a>
                    <a href="<?php echo $this->secureUrl('events', 'cancel', $event['EVENT_ID'], $user['USER_ID'])?>" class="button red">Refuser</a>
                </div>
            </li>
            <?php endforeach?>
            <?php else:?>
            <p class="error">Pas de demandes</p>
            <?php endif?>
        </ul>
    </article>
    <article>
        <h2 class="title">Participants</h2>
        <ul class="user_list">
            <?php foreach($competitors as $user):?>
            <li>
                <img class="thumb" src="<?php echo $user['AVATAR'] ? $this->secureUrl('image', 'get', $user['USER_ID'], $user['AVATAR']) : $this->url('resources/images/default.png')?>" alt="Avatar"/>
                <a class="userlink" href="<?php echo $this->secureUrl('account', 'profile', $user['USER_ID'])?>">
                    <?php echo $user['PSEUDO']?> (<?php echo $user['FIRST_NAME'], ' ', $user['LAST_NAME']?>)
                </a>
                <?php if($user['USER_ID'] != $event['ORGANIZER'] && time() < $event['EVENT_START']):?>
                <div class="buttons">
                    <a href="<?php echo $this->secureUrl('events', 'expel', $event['EVENT_ID'], $user['USER_ID'])?>" class="button red">Expulser</a>
                </div>
                <?php endif?>
            </li>
            <?php endforeach?>
        </ul>
    </article>
</section>