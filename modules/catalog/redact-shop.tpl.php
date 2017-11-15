<?php

/**
 * @file redact-item-shop.tpl.php
 * Default theme implementation to present a linked feed item for summaries.
 *
 * @see template_preprocess()
 * @see template_preprocess_redact_item_shop()
 */

?>


<br><br>
<div>
<?php if(count($listShop)<0){?>
   У вас нет под управлением ни одного магазина.
<?php }else{ ?>
   
	<?php for($i=0;$i<count($listShop);$i++){ ?>
	   <div> <?php print $listShop[$i];?> </div>
	   <br>
	<?php } ?>

<?php } ?>

</div>