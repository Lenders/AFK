<section id="left_menu">
    <h2>Profile</h2>
    <ul>
        <li><strong>Nom :</strong> <?php echo $user['FIRST_NAME'], ' ', $user['LAST_NAME']?></li>
        <li><strong>Pseudo :</strong> <?php echo $user['PSEUDO']?></li>
        <li><?php echo $this->friendButton($user['USER_ID'])?></li>
    </ul>
</section>
<section id="contents">
    <h1><?php echo $user['PSEUDO']?></h1>
    <?php for($i = 0; $i < 100; ++$i):?>
    <article>
        <h2 class="title">titre</h2>
        <p>Contenue</p>
    </article>
    <?php endfor?>
</section>