<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8"/>
        <title><?php echo $this->helpers->conf('name')?> - <?php echo $this->title?></title>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <?php echo $this->helpers->css('style')?>
    </head>
    <body>
        <header>
            <nav>
                <ul>
                    <li><a href="<?php echo $this->helpers->url('register.php')?>">Inscription</a></li>
                    <li><a href="<?php echo $this->helpers->url('account/login.php')?>">Connexion</a></li>
                </ul>
            </nav>
        </header>
        <?php echo $this->contents?>
    </body>
</html>