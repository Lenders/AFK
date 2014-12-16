<meta charset="utf-8" />
<base href="<?php echo $this->helpers->baseUrl() ?>"/>
<script type="text/javascript">
    var Config = {
        getBaseUrl: function() {
            return '<?php echo $this->helpers->baseUrl() ?>';
        }
    };
</script>
<!--[if lt IE 9]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<?php $this->helpers->loadCSS('global') ?>
<?php echo $this->helpers->getCSS(); ?>
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<?php echo $this->helpers->js('global') ?>
<?php echo $this->helpers->js('location') ?>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />


<title><?php echo $this->helpers->conf('name') ?> - <?php echo $this->title ?></title>