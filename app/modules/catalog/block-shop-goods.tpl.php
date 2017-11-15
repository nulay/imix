<?php
/**
 * @file block-shop-goods.tpl.php
 * Default theme implementation to present a linked feed item for summaries.
 *
 * @see template_preprocess()
 * @see template_preprocess_item_goods_pager()
 * Собираем блок с номерами страниц
 * shops - список магазинов для конкретного товара.
 */
?>
<div>
  <table>
  <?php foreach($shops as $shop){ ?>
  <tr><td colspan=2><a href='../concretShop/<?php print $shop->id; ?>'><div style="text-align: center; "><?php print $shop->name; ?></div></a><td></tr>
  <tr style=" border-bottom: 1px solid lightGrey; ">
     <td style="padding-bottom: 10px;">           
           <div style="text-align: center;font-size:30px;"><?php print $shop->price; ?>$</div>
	 </td>
	<td><?php print $shop->tel; ?></div></td>
  </tr>
  <?php } ?>
  </table>
</div>