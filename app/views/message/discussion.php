<section id="left_menu">
    <?php if(!empty($current_discussion)):?>
    <h2>Participants</h2>
    <ul>
        <?php foreach($current_discussion['real_users'] as $user):?>
        <li>
            <a href="<?php echo $this->secureUrl('account', 'profile', $user['USER_ID'])?>"><?php echo $user['PSEUDO']?></a>
            <?php echo $this->widget('StatusButton', $user['USER_ID'])?>
        </li>
        <?php endforeach?>
    </ul>
    <?php endif?>
    <h2>Discussions</h2>
    <ul id="discussions_list">
        <?php foreach ($discussions as $discussion):?>
        <li <?php if($current_discussion['_id'] == $discussion['_id']) echo 'class="current"'; elseif(!in_array($this->getSession('id'), $discussion['views'])) echo 'class="unread"'?>>
            <a href="<?php echo $this->secureUrl('message', 'discussion', $discussion['_id']) . '#last'?>"><?php echo htmlentities($discussion['name'])?></a>
        </li>
        <?php endforeach?>
    </ul>
    <div class="button_container"><a class="button" href="<?php echo $this->url('message/create.php')?>">Nouvelle discussion</a></div>
</section>
<section id="contents">
    <?php if(!empty($current_discussion)):?>
    <article>
        <h1 class="title">Discussion : <span id="discussion_name"><?php echo htmlentities($current_discussion['name'])?></span></h1>
        <div id="discussion_room">
            <?php foreach ($current_discussion['messages'] as $message):?>
            <p data-message-date="<?php echo $message['date']->sec, ',', $message['date']->usec?>" class="message <?php echo $message['sender'] == $this->getSession('id') ? 'me' : 'other'?>">
                <span class="who"><?php echo $current_discussion['real_users'][$message['sender']]['PSEUDO']?>, le <?php echo date('d/m/y à H:i:s', $message['date']->sec)?></span>
                <?php echo nl2br(htmlentities($message['message']->bin))?>
            </p>
            <?php endforeach?>
            <a id="last" style="float: left;"></a>
        </div>
    </article>
    <article>
        <h2 class="title">Message</h2>
        <form id="message_form" method="post" action="<?php echo $this->secureUrl('message', 'post', $current_discussion['_id'])?>" data-discussion-id="<?php echo $current_discussion['_id']?>">
            <textarea id="message" name="message" placeholder="Message... (Ctrl + Entrer pour envoyer)" required></textarea>
            <input type="submit" value="Envoyer" title="Ctrl + Entrer"/>
        </form>
    </article>
    <?php endif?>
</section>
<?php echo $this->js('message')?>