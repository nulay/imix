<?php
/**
 * @file item-goods.tpl.php
 * Default theme implementation to present a linked feed item for summaries.
 *
 * @see template_preprocess()
 * @see template_preprocess_item_goods()
 */
//$l=getimagesize($itemGoods['image']);
//print_r($itemGoods);
?>
<?php     
    /** preg_match('/[0-9]$/',$itemGoods['count'],$matches);
	$matches=$matches[0];
	if($matches==0 | $matches>4) $strP=t(' продовцов');
	if($matches>1 & $matches<5) $strP=t(' продовца');
	if($matches==1) $strP=t(' продовец');
	*/
?>
<tr class='contItG' style='border-bottom:1px solid lightgray;'><td width='10%' class='contItGIm'>
<a href="../../concretGoods/<?php print $itemGoods['Id']; ?>"><img src="/<?php print $itemGoods['image']; ?>" alt="<?php print $itemGoods['company']; ?> <?php print $itemGoods['model']; ?>" <?php print ($l[0]>=$l[1])?'width':'height'; ?>="100px"/></a>
</td>
<td width='75%' align=left valign=top class='contItGtext'>
<a href="../../concretGoods/<?php print $itemGoods['Id']; ?>"><?php print $itemGoods['company']; ?> <?php print $itemGoods['model']; ?> </a>
<br>&nbsp;<?php print $itemGoods['discription']; ?>
</td>
<td width='15%'><div width="90px" style="color:black; font-size:small; text-align:center;">
<div>Цена за сутки: <?php print $itemGoods['priceday']; ?></div>
<div>Цена за неделю: <?php print $itemGoods['priceweek']; ?></div>
<div>Цена за месяц: <?php print $itemGoods['pricemonth']; ?></div>
<div>Оценочная стоимость: <?php print $itemGoods['priceestimated']; ?></div>
</div>				
</td></tr>