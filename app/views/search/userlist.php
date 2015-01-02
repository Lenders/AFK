<?php if(empty($users)):?>
<p class="error">pas de résultats</p>
<?php else:?>
<ul class="user_list">
    <?php foreach ($users as $user): ?>
    <li>
        <img class="thumb" src="<?php echo $user['AVATAR'] ? $this->secureUrl('image', 'get', $user['USER_ID'], $user['AVATAR']) : $this->url('resources/images/default.png') ?>" alt="Avatar"/>
        <a class="userlink" href="<?php echo $this->secureUrl('account', 'profile', $user['USER_ID']) ?>">
            <?php echo $user['PSEUDO'] ?> (<?php echo $user['FIRST_NAME'], ' ', $user['LAST_NAME'] ?>)
        </a>
        <div class="buttons">
            <?php echo $this->widget('FriendButton', $user['USER_ID']) ?>
        </div>
    </li>
    <?php endforeach ?>
</ul>
<?php endif?>