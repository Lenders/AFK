<?php require 'menu.php'?> 
<section id="contents">
    <h1>Gestion des évènements</h1>
    <article>
        <h2 class="title">Rechercher</h2>
        <form method="get" action="<?php echo $this->url('admin/events.php')?>">
            <input value="<?php echo $query?>" name="search" placeholder="Rechercher..." id="evt_search" />
            <input type="submit" value="Rechercher" />
        </form>
    </article>
    <article>
        <h2 class="">Liste d'évènements</h2>
        <table style="width: 100%;">
            <tr>
                <th>Image</th><th>Nom</th><th>Début</th><th>Fin</th><th>Actions</th>
            </tr>
            <?php foreach($events as $event):?>
            <tr>
                <td><img src="<?php echo $event['IMAGE'] ? $this->secureUrl('image', 'get', $event['ORGANIZER'], $event['IMAGE']) : $this->url('resources/images/default.png')?>" class="thumb"  alt="Image"/></td>
                <td><a href="<?php echo $this->secureUrl('events', 'show', $event['EVENT_ID'])?>" target="_blank"><?php echo $event['EVENT_NAME']?></a></td>
                <td><?php echo $event['EVENT_START']?></td>
                <td><?php echo $event['EVENT_END']?></td>
                <td>
                    <div class="button_container">
                        <a href="<?php echo $this->secureUrl('admin', 'delevent', $event['EVENT_ID'])?>" class="button red">Supprimer</a>
                    </div>
                </td>
            </tr>
            <?php endforeach?>
        </table>
    </article>
</section>