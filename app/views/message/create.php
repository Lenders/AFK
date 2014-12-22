<section id="contents">
    <article>
        <h1 class="title">Créer une discussion</h1>
        <form action="" method="post" id="new_msg_form">
            <?php if(!empty($error)):?>
            <p class="error"><?php echo $error?></p>
            <?php endif?>
            <input name="name" placeholder="Nom de la discussion" <?php if(isset($name)) echo 'value="', $name, '"'?> required/>
            <input name="target" placeholder="Destinataires (séparés par une virgule)" <?php if(isset($target)) echo 'value="', $target, '"'?> require id="target"/>
            <textarea name="message" id="message" placeholder="Message" required><?php if(isset($message)) echo htmlentities($message)?></textarea>
            <input type="submit" value="Envoyer"/>
        </form>
    </article>
</section>
<?php echo $this->js('new_msg')?>