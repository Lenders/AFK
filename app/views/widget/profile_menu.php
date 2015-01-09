<section id="left_menu">
    <h2>
        Profil
        <?php echo $this->widget('StatusButton', $user['USER_ID'])?>
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
    <?php if($this->isLogged()):?>
    <div class="button_container">
        <?php echo $this->widget('FriendButton', $user['USER_ID'])?>
    </div>
    <div class="button_container">
        <a href="<?php echo $this->secureUrl('message', 'private', $user['USER_ID'])?>#last" class="button">Message privé</a>
    </div>
    <?php endif?>
    <h2>Liens</h2>
    <ul class="links_list">
        <li><a href="<?php echo $this->secureUrl('friends', 'list', $user['USER_ID'])?>">Amis</a></li>
        <li><a href="<?php echo $this->secureUrl('events', 'list', $user['USER_ID'])?>">Évènements</a></li>
        <?php if($user['USER_ID'] == $this->getSession('id')):?>
        <li><a href="<?php echo $this->secureUrl('home', 'rss', $user['USER_ID'], sha1($user['SALT']))?>.xml">RSS privé</a></li>
        <?php endif?>
    </ul>
</section>
<?php echo $this->js('profile')?>