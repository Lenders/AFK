<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8"/>
        <!--[if lt IE 9]>
            <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
            <script src="http://jamesallardice.github.io/Placeholders.js/assets/js/placeholders.min.js"></script>
            <script src="http://s3.amazonaws.com/nwapi/nwmatcher/nwmatcher-1.2.5-min.js"></script>
            <?php echo $this->helpers->js('selectivizr-min')?>
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
        <a href="#" id="button_top"></a>
        <footer>
            <?php if(DEBUG):?>
            <div id="generation_time">Temps de génération : <?php echo round($this->helpers->bench(), 1)?>ms</div>
            <table>
                <legend>Benchmark</legend>
                <tr>
                    <th>#</th><th>Label</th><th>Temps</th>
                </tr>
                <?php foreach($this->helpers->benchEntries() as $key => $entry):?>
                <tr>
                    <td>#<?php echo $key?></td>
                    <td><?php echo $entry[0]?></td>
                    <td><?php echo round($entry[3], 2)?>ms</td>
                </tr>
                <?php endforeach?>
            </table>
            <?php endif?>
        </footer>
    </body>
</html>