<div id="container">
    <header>
        <h1>Erreur <?php echo $code?> (<?php echo $type?>)</h1>
    </header>
    <section id="debug_info">
        Une erreur interne au serveur est survenue.<br/>
        Veuillez nous excuser pour le désagrément.<br/><br/>
        Si le problème persiste veuillez contacter l'administrateur à l'adresse <a href="mailto:<?php echo $this->conf('mail') ?>"><?php echo $this->conf('mail') ?></a>
        <h2>Message :</h2>
        <article id="message">
            <a href="<?php echo $this->getFileUrl($file, $line)?>" target="_blank"><?php echo $this->pathToRelative($file), ':', $line?></a> : <?php echo $message?>
        </article>
        <h2>Trace :</h2>
        <?php echo $this->loadView('error/trace.php', array('trace' => $trace))?>
    </section>
</div>