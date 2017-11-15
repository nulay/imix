<?php

/**
 * @file redact-item-procat.tpl.php
 * Default theme implementation to present a linked feed item for summaries.
 *
 * @see template_preprocess()
 * @see template_preprocess_redact_item_shop()
 */  
?>
<div>
<div><span style='font-size:18px;'><a  href='redactgoods/<?php print $itemShop->Id;?>'><?php print $itemShop->named;?></a> (<?php print $itemShop->countGoods->count;?>)</span>  <a  href='/procat/registrationchange/<?php print $itemShop->Id;?>'>Редактировать данные проката</a></div>
<div>Адрес: <?php print $itemShop->adres;?></div>
<div>Телефон: <?php print $itemShop->tel;?></div>
<?php if(isset($itemShop->email)){?><div>E-mail: <?php print $itemShop->email;?></div><?php } ?>
</div>