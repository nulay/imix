<?php

/**
 * @file catalog-shopsGoods.tpl.php
 * Default theme implementation to present a linked feed item for summaries.
 *
 * @see template_preprocess()
 * @see template_preprocess_item_goods()
 * 
 * shops - список магазинов продающий конкретный товар
 */
?>
<table>
<?php foreach($shops as $shop){ ?>
  <tr style=" border-bottom: 1px solid lightGrey; ">
   <td width='150'>
      <div style="text-align: center;font-size:30px;"><?php print $shop->price; ?>$</div>
   </td>
   <td width='220px'>       
       <div><a href='../concretShop/<?php print $shop->id; ?>'><?php print $shop->name; ?></a></div>
	   <div><?php print $shop->tel; ?></div>
	   <div><?php print $shop->eladres; ?></div>
	   <div><?php print $shop->email; ?></div>
   </td>
   <td>
      <div><?php print $shop->description; ?></div>
   </td>
  </tr>
<?php } ?>
</table>
