<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8"/>
        <!--[if lt IE 9]>
            <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <meta name=viewport content="width=device-width, initial-scale=1"/>
        <title><?php echo $this->helpers->conf('name')?> - <?php echo $this->title?></title>
        <?php echo $this->helpers->css('style')?>
        <script type="text/javascript">
            var Config = {
                getBaseUrl: function(){
                    return '<?php echo $this->helpers->baseUrl()?>';
                }
            };
        </script>
        <?php echo $this->helpers->js('global')?>
        <?php if($this->helpers->isLogged()) echo $this->helpers->js('notif')?>
    </head>
    <body>
        <header>
            <div id="logo"><a href="<?php echo $this->helpers->baseUrl()?>" title="Retourner à la page d'acceuil">AFK</a></div>
            <?php include 'header_nav.php'?>
        </header>
        <div id="body">
            <?php echo $this->contents?>
        </div>
        <footer>
            Temps de génération : <?php echo round($this->helpers->bench(), 1)?>ms
        </footer>
    </body>
</html>