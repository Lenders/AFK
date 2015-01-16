<section id="contents">
    <h1>Mes images</h1>
    <article>
        <h2 class="title">Mes images</h2>
        <?php if($images->count() === 0):?>
        <p>Pas encore d'images !</p>
        <?php else:?>
        <div id="image_grid">
        <?php foreach ($images as $image):?>
            <a href="<?php echo $this->secureUrl('image', 'info', $image['file'])?>"><img class="avatar" src="<?php echo $this->secureUrl('image', 'get', $image['owner'], $image['file'])?>" alt="Image"/></a>
        <?php endforeach?>
        </div>
        <?php endif?>
    </article>
    <article>
        <h2 class="title">Upload</h2>
        <form action="<?php echo $this->url('image/upload.php')?>" method="post" enctype="multipart/form-data">
            <input type="file" name="file"/>
            <input type="submit" value="Upload"/>
        </form>
    </article>
</section>