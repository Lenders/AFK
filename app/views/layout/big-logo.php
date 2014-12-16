<!DOCTYPE html>
<html>
    <head>
        <?php $this->helpers->loadCSS('big-logo')?>
        <?php include 'head.php'?>
    </head>
    <body>
        <header>
            <?php require 'header-links.php'?>
        </header>
        <section id="messages">

        </section>
        <div id="contents">
            <a href="<?php echo $this->helpers->url('search.php')?>" title="WebCovoiturage" id="logo"><?php echo $this->helpers->img('logo.png') ?></a>
            <?php echo $this->contents?>
        </div>
        <?php include 'footer.php'?>
    </body>
</html>