<section id="contents">
    <article>
        <h1 class="title">Connexion</h1>
        <p id="form_error" style="display: none;"></p>

        <form id="login_form" method="post" action="<?php echo $this->url('login/submit.php')?>">
            <input type="text" name="pseudo" placeholder="Pseudo" />
            <input type="password" name="pass" placeholder="Mot de passe" />
            <input type="submit" value="Connexion" />
        </form>
        <?php echo $this->js('form')?>
        <script src="<?php echo $this->url('login/script.js')?>"></script>
    </article>
</section>