<section id="contents">
    <article>
        <h1 class="title">Inscription</h1>
        <form id="reg_form" method="post" action="<?php echo $this->url('register/submit.php')?>">
            <?php echo $form->getPseudo()?>
            <?php echo $form->getPass()?>
            <?php echo $form->getPass2()?>
            <?php echo $form->getEmail()?>
            <?php echo $form->getFirstName()?>
            <?php echo $form->getLastName()?>
            <?php echo $form->getGender()?>
            <input type="submit" value="S'inscrire"/>
        </form>
        <?php echo $this->js('form')?>
        <script src="<?php echo $this->url('register/script.js')?>"></script>
    </article>
</section>