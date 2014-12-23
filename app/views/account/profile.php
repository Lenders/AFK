<section id="left_menu">
    <h2>
        Profil
        <?php if($this->isOnline($user['USER_ID'])):?>
        <span class="button green" data-online-button data-user-id="<?php echo $user['USER_ID']?>">En ligne</span>
        <?php else:?>
        <span class="button red" data-online-button data-user-id="<?php echo $user['USER_ID']?>">Hors ligne</span>
        <?php endif?>
    </h2>
    <ul>
        <li>
            <?php if($this->getSession('id') == $user['USER_ID']):?>
            <a title="Changer d'image" href="<?php echo $this->url('image.php')?>">
            <?php endif?>
                <img class="avatar" src="<?php echo $user['AVATAR'] ? $this->secureUrl('image', 'get', $user['USER_ID'], $user['AVATAR']) : $this->url('resources/images/default.png')?>" alt="Avatar"/>
            <?php if($this->getSession('id') == $user['USER_ID']):?>
            </a>
            <?php endif?>
        </li>
        <li><strong>Nom :</strong> <?php echo $user['FIRST_NAME'], ' ', $user['LAST_NAME']?></li>
        <li><strong>Pseudo :</strong> <?php echo $user['PSEUDO']?></li>
    </ul>
    <div class="button_container">
        <?php echo $this->friendButton($user['USER_ID'])?>
    </div>
    <div class="button_container">
        <a href="<?php echo $this->secureUrl('message', 'private', $user['USER_ID'])?>#last" class="button">Message privé</a>
    </div>
</section>
<section id="contents">
    <h1><?php echo $user['PSEUDO']?></h1>
    <?php for($i = 0; $i < 100; ++$i):?>
    <article>
        <h2 class="title">titre</h2>
        <p>Contenue</p>
    </article>
    <?php endfor?>
</section>
<?php echo $this->js('profile')?>