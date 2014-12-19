            <nav id="header_nav">
                <ul>
                    <li><a href="<?php echo $this->helpers->baseUrl()?>">Accueil</a></li>
                    <?php if(!$this->helpers->isLogged()):?>
                    <li><a href="<?php echo $this->helpers->url('register.php')?>">Inscription</a></li>
                    <li><a href="<?php echo $this->helpers->url('login.php')?>">Connexion</a></li>
                    <?php else:?>
                    <li><a href="<?php echo $this->helpers->url('')?>">Mon compte</a></li>
                    <li>
                        <form id="speed_search">
                            <input name="search" placeholder="Rechercher"/>
                            <input type="submit" value="Go !"/>
                        </form>
                        <?php echo $this->helpers->js('speed_search')?>
                    </li>
                    <?php endif?>
                </ul>
            </nav>