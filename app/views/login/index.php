<section id="contents">
    <article>
        <h1 class="title">Connexion</h1>
        <p id="form_error"><?php if($error) echo $error?></p>

        <form id="login_form" method="post" action="<?php echo $this->url('login/submit.php')?>">
            <?php echo $form->getPseudo()?>
            <?php echo $form->getPass()?>
            <input type="submit" value="Connexion" />
        </form>
        <?php echo $this->js('form')?>
        <script src="<?php echo $this->url('login/script.js')?>"></script>
    </article>
</section>