<section id="contents">
    <article>
        <h1 class="title">Information image</h1>
        <p id="image_info"><img src="<?php echo $this->secureUrl('image', 'get', $owner, $image)?>" alt="Image"/></p>
    </article>
    <article>
        <h2 class="title">Actions</h2>
        <div class="button_container">
            <a class="button red" href="<?php echo $this->secureUrl('image', 'delete', $image)?>">Supprimer</a>
            <a class="button" href="<?php echo $this->secureUrl('image', 'use', $image)?>">Utiliser en tant qu'avatar</a>
            <a class="button" href="<?php echo $this->secureUrl('image', 'get', $owner, $image)?>" target="_blank">Lien direct</a>
            <a class="button" href="<?php echo $this->url('image.php')?>">Retourner à la liste</a>
        </div>
    </article>
</section>