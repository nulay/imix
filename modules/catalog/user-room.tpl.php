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
<table width="100%">
<tr><td width="50%"><img src='<?php print $userFull->imageUser; ?>'/></td><td><div><?php print $userFull->firstName; ?> <?php print $userFull->lastName; ?><span style='float: right;'><a href='redact'>Редактировать данные.</a> <a href='/user/<?php print $user->uid; ?>/edit'>Редактировать данные входа.</a></span></div></td></tr>
<tr><td width="50%"><div><a href='/catalog/user/selectShop'>Мои магазины</a></div><div><a href='/shop/registration'>Регистрация нового магазина</a></div>
<div><a href='/procat/manager/controlProcat'>Мои прокаты</a></div><div><a href='/procat/registration'>Регистрация нового проката</a></div></td><td>&nbsp;</td></tr>
<tr></tr>
</table>
