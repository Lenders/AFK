            <ul class="user_list">
            <?php foreach($users as $user):?>
                <li>
                    <a class="userlink" href="<?php echo $this->secureUrl('account', 'profile', $user['USER_ID'])?>">
                        <?php echo $user['PSEUDO']?> (<?php echo $user['FIRST_NAME'], ' ', $user['LAST_NAME']?>)
                    </a>
                    <div class="buttons">
                        <?php echo $this->friendButton($user['USER_ID'])?>
                    </div>
                </li>
            <?php endforeach?>
            </ul>