            <nav id="header_nav">
                <ul>
                    <li><a href="<?php echo $this->helpers->baseUrl()?>">Accueil</a></li>
                    <?php if(!$this->helpers->isLogged()):?>
                    <li><a href="<?php echo $this->helpers->url('register.php')?>">Inscription</a></li>
                    <li><a href="<?php echo $this->helpers->url('login.php')?>">Connexion</a></li>
                    <?php else:?>
                    <li><a href="<?php echo $this->helpers->url('account.php')?>">Mon compte</a></li>
                    <li><a href="<?php echo $this->helpers->url('friends.php')?>">Mes amis<?php echo $this->helpers->getFriendNotif()?></a></li>
                    <li><a href="<?php echo $this->helpers->url('message.php#last')?>">Messages<?php echo $this->helpers->getMessageNotif()?></a></li>
                    <li><a href="<?php echo $this->helpers->url('account/logout.php')?>">Déconnexion</a></li>
                    <li>
                        <form id="speed_search" method="get" action="<?php echo $this->helpers->url('search.php')?>">
                            <input name="search" placeholder="Rechercher"/>
                            <input type="submit" value="Go !"/>
                        </form>
                        <?php echo $this->helpers->js('speed_search')?>
                    </li>
                    <?php endif?>
                </ul>
            </nav>