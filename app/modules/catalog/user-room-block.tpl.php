<?php

/**
 * @file user-room.tpl.php
 * Default theme implementation to present a linked feed item for summaries.
 *
 * @see template_preprocess()
 * @see template_preprocess_item_goods()
 */
 global $user;
?>
<div class="content">
<div id="user-login-form">Добро пожаловать <?php print $user->name; ?>. <a href="/user/room">Кабинет пользователя.</a> <a href="/logout">Выход.</a></div>
</div>