<!DOCTYPE html>
<html>
    <head>
        <?php $this->helpers->loadCSS('small-logo')?>
        <?php include 'head.php'?>
    </head>
    <body>
        <header>
            <a href="<?php echo $this->helpers->url('search.php')?>" title="WebCovoiturage" id="logo"><?php echo $this->helpers->img('small-logo.png') ?></a>
            <?php require 'header-links.php'?>
        </header>
        <section id="messages">
            
        </section>
        <div id="contents">
            <?php echo $this->contents?>
        </div>
        
        <?php include 'footer.php'?>
    </body>
</html>