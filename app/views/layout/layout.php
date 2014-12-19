<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8"/>
        <title><?php echo $this->helpers->conf('name')?> - <?php echo $this->title?></title>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <?php echo $this->helpers->css('style')?>
        <script type="text/javascript">
            var Config = {
                getBaseUrl: function(){
                    return '<?php echo $this->helpers->baseUrl()?>';
                }
            };
        </script>
        <?php echo $this->helpers->js('global')?>
    </head>
    <body>
        <header>
            <div id="logo"><a href="<?php echo $this->helpers->baseUrl()?>" title="Retourner à la page d'acceuil">AFK</a></div>
            <?php include 'header_nav.php'?>
        </header>
        <?php echo $this->contents?>
    </body>
</html>