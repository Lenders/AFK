<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $this->title?></title>
        <meta charset="utf-8"/>
        <?php echo $this->helpers->css('error')?>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <?php echo $this->helpers->js('ui')?>
        <?php echo $this->helpers->js('debug')?>
    </head>
    <body>
        <?php echo $this->contents?>
    </body>
</html>
