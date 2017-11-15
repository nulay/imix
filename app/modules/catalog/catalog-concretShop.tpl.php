<?php
/**
 * @file catalog-concretShop.tpl.php
 * Default theme implementation to present a linked feed item for summaries.
 *
 * @see template_preprocess()
 * @see template_preprocess_item_goods_pager()
 * Собираем блок с номерами страниц
 * shop - информация о магазине.
 */
?>
<br>
<br>
<div style="font-size:20px;">
  <div style="font-size:30px;"><?php print $shop->named; ?></div><br>
  <?php if(isset($shop->eladres)){ ?><div><a href='<?php print $shop->eladres; ?>'><?php print $shop->eladres; ?></a></div><br><?php } ?>
  <div>Телефон: <?php print $shop->tel; ?></div><br>
  <?php if(isset($shop->email)){ ?><div>E-mail: <?php print $shop->email; ?></div><br><?php } ?>
  <?php if(isset($shop->adres)){ ?><div>Адрес: <?php print $shop->adres; ?></div><br><?php } ?>
  <div>УНП: <?php print $shop->unp; ?><br><br><br></div>
  <?php if(isset($shop->aboutshop)){ ?><div><?php print $shop->aboutshop; ?></div><br><?php } ?> 
</div>