<section id="contents">
    <h1><?php echo $account['PSEUDO']?></h1>
    
    <article>
        <h2 class="title">Informations</h2>
        <p>
            <strong>Nom : </strong> <?php echo $account['FIRST_NAME'], ' ', $account['LAST_NAME']?><br/>
            <strong>Pseudo : </strong> <?php echo $account['PSEUDO']?><br/>
            <?php if($this->isLogged()):?>
            <a class="button" href="<?php echo $waiting ? '#' : $this->secureUrl('account', 'addfriend', $account['USER_ID'])?>" data-type="<?php echo $waiting ? 'waiting' : 'add_friend'?>" data-user-id="<?php echo $account['USER_ID']?>"></a>
            <?php endif?>
        </p>
    </article>
</section>
<?php echo $this->js('profile')?>