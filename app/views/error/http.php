<div id="container">
    <header>
        <h1>Erreur <?php echo $code?></h1>
    </header>
    <section id="debug_info">
        Une erreur est survenue lors du chargement de la page.<br/>
        Il se peut que la page n'existe pas, ou que vous n'ayez pas l'autorisation de la voir.<br/>
        <h2>Message :</h2>
        <article id="message">
            <?php echo $message?>
        </article>
    </section>
</div>