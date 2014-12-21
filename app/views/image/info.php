<section id="contents">
    <article>
        <h1 class="title">Information image</h1>
        <p id="image_info"><img src="<?php echo $this->secureUrl('image', 'get', $owner, $image)?>"/></p>
    </article>
    <article>
        <h2 class="title">Actions</h2>
        <ul style="margin-left: 15px;">
            <li><a href="<?php echo $this->secureUrl('image', 'delete', $image)?>">Supprimer</a>
            <li><a href="<?php echo $this->secureUrl('account', 'setavatar', $image)?>">Utiliser en tant qu'avatar</a></li>
            <li><a href="<?php echo $this->secureUrl('image', 'get', $owner, $image)?>" target="_blank">Lien direct</a></li>
            <li><a href="<?php echo $this->url('image.php')?>">Retourner à la liste</a></li>
        </ul>
    </article>
</section>