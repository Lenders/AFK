<?php echo $this->widget('ProfileMenu', $user)?>
<section id="contents">
    <h1>Amis - <a href="<?php echo $this->secureUrl('account', 'profile', $user['USER_ID'])?>"><?php echo $user['PSEUDO']?></a></h1>
    <article>
        <h2 class="title">Liste</h2>
        <?php echo $this->loadView('search/userlist.php', array('users' => $friends))?>
    </article>
</section>