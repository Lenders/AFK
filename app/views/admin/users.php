<?php include 'menu.php'?>
<section id="contents">
    <h1>Gestion des utilisateurs</h1>
    <article>
        <h2 class="title">Rechercher</h2>
        <form method="get" action="<?php echo $this->url('admin/users.php')?>">
            <input value="<?php echo $query?>" name="search" placeholder="Rechercher..." id="user_search" />
            <input type="submit" value="Rechercher" />
        </form>
    </article>
    <article>
        <h2 class="title">Liste d'utilisateurs</h2>
        <table style="width: 100%;">
            <tr>
                <th>Avatar</th><th>Pseudo</th><th>Nom</th><th>Actions</th>
            </tr>
            <?php foreach($users as $user):?>
            <tr>
                <td><img class="thumb" src="<?php echo $user['AVATAR'] ? $this->secureUrl('image', 'get', $user['USER_ID'], $user['AVATAR']) : $this->url('resources/images/default.png') ?>" alt="Avatar"/></td>
                <td><a href="<?php echo $this->secureUrl('account', 'profile', $user['USER_ID'])?>" target="_blank"><?php echo $user['PSEUDO']?></a></td>
                <td><?php echo $user['FIRST_NAME'], ' ', $user['LAST_NAME']?></td>
                <td>
                    <?php if($user['USER_ID'] != $this->getSession('id')):?>
                    <div class="button_container">
                        <?php if($user['IS_ADMIN'] != 'YES'):?>
                        <a href="<?php echo $this->secureUrl('admin', 'deluser', $user['USER_ID'])?>" class="button red">Supprimer</a>
                        <a href="<?php echo $this->secureUrl('admin', 'setadm', $user['USER_ID'])?>" class="button green">Promouvoir Admin.</a>
                        <?php else:?>
                        <a href="<?php echo $this->secureUrl('admin', 'deladm', $user['USER_ID'])?>" class="button red">Révoquer Admin.</a>
                        <?php endif?>
                    </div>
                    <?php endif?>
                </td>
            </tr>
            <?php endforeach?>
        </table>
    </article>
</section>