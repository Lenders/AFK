<article>
    <h1 class="title">Connexion</h1>
    <p id="login_error" <?php if(!$error) echo 'style="display:none;"'?>>Login incorrect</p>
    
    <form id="login_form" method="post" action="<?php echo $this->url('account/performlogin.php')?>">
        <input type="text" name="pseudo" placeholder="Pseudo" />
        <input type="password" name="pass" placeholder="Mot de passe" />
        <input type="submit" value="Connexion" />
    </form>
    <?php echo $this->js('form')?>
    <?php echo $this->js('login')?>
</article>