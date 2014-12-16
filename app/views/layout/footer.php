<footer>
    <div class="col">
        <a id="bottom-logo" href="<?php echo $this->helpers->baseUrl()?>"><?php echo $this->helpers->img('bottom-logo.png')?></a>
        <div id="copyright"><?php echo $this->helpers->conf('name')?> © <?php echo date('Y')?></div>
    </div>
    <div class="col">
        <h3><?php echo $this->helpers->conf('name')?></h3>
        <ul>
            <li><a href="">Nous contacter</a></li>
            <li><a href="">À propos...</a></li>
            <li><a href="">Conditions générales d'utilisation</a></li>
        </ul>
    </div>
    <div class="col">
        <div id="location">Position: <a id="loc_value" href="#" title="Recharger la position ?">inconnue</a></div>
    </div>
</footer>