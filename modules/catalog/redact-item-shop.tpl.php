<?php

/**
 * @file redact-item-shop.tpl.php
 * Default theme implementation to present a linked feed item for summaries.
 *
 * @see template_preprocess()
 * @see template_preprocess_redact_item_shop()
 */  
?>
<div style='border-bottom:1px solid gray;'>
<div style='font-size:18px;'><a  href='redactprice?id_shop=<?php print $itemShop->id;?>'><?php print $itemShop->named;?></a> (<?php print $itemShop->countGoods->count;?>)</div>
<div>Адрес: <?php print $itemShop->adres;?></div>
<div>Телефон: <?php print $itemShop->tel;?></div>
<?php if(isset($itemShop->email)){?><div>E-mail: <?php print $itemShop->email;?></div><?php } ?>
</div>