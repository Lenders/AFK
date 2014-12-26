<section id="contents">
    <article>
        <h1 class="title">Créer un évènement</h1>
        <form id="create_event_form" method="post" action="<?php echo $this->url('createevent/submit.php')?>">
            <table>
                <tr>
                    <td><label for="<?php echo $form->getName()->getName()?>">Nom</label></td>
                    <td><?php echo $form->getName()?></td>
                </tr>
                <tr>
                    <td><label for="<?php echo $form->getPrivacy()->getName()?>">Type</label></td>
                    <td><?php echo $form->getPrivacy()?></td>
                </tr>
                <tr>
                    <td><label for="<?php echo $form->getStart()->getName()?>">Date de début</label></td>
                    <td><?php echo $form->getStart()?></td>
                </tr>
                <tr>
                    <td><label for="<?php echo $form->getEnd()->getName()?>">Date de fin</label></td>
                    <td><?php echo $form->getEnd()?></td>
                </tr>
                <?php foreach($form->getProperties() as $property):?>
                <tr data-name="<?php echo $property->getName()?>">
                    <td><label for="<?php echo $property->getName()?>"><?php echo $property->getName()?></label></td>
                    <td><?php echo $property?></td>
                </tr>
                <?php endforeach?>
            </table>
            <input type="submit" value="Créer"/>
        </form>
    </article>
</section>
<?php echo $this->js('form')?>
<script src="<?php echo $this->url('createevent/script.js')?>" type="text/javascript"></script>
<?php echo $this->js('create_event')?>