<?php

/**
 * @file item-goods-pager.tpl.php
 * Default theme implementation to present a linked feed item for summaries.
 *
 * @see template_preprocess()
 * @see template_preprocess_item_goods_pager()
 *Собираем блок с номерами страниц
 */
 //dpm($urlRoot);
?>

<table width="100%" style="border-collapse: separate;">
<?php 	$i=0;
	while ($i < count($arrCatG)) {	    
		$cg1=$arrCatG[$i];		
		$cg2=null;
		if($i+1<count($arrCatG)) $cg2=$arrCatG[$i+1];
		$i+=2;	?>		
	<tr>
	  <td width="50%" style="font-size:x-large;">
	    <div class="titleCateg ui-helper-reset ui-state-default ui-corner-all"><?php print $cg1->value; ?></div>
	   </td><td width="50%" style="font-size:x-large;"><?php if ($cg2->id){ ?>
	<div class="titleCateg ui-helper-reset ui-state-default ui-corner-all"><?php print $cg2->value; ?></div><?php } ?>
	</td></tr>
	<tr><td width="50%" style="vertical-align:top;" >
	<?php for($y=0;$y<count($cg1->listTypeAnnounce);$y++){ ?>	
			<div class="nR texMin"><div><a href="listGoods?id_Catal[]=<?php print $cg1->listTypeAnnounce[$y]->id; ?>"><?php print $cg1->listTypeAnnounce[$y]->value; ?></a></div></div>
		<?php } ?>	
	</td><td width="50%" style="vertical-align:top;">
	<?php if($cg2!=null){
	        for($y=0;$y<count($cg2->listTypeAnnounce);$y++){ ?>	
		<div class="nR texMin"><div><a href="listGoods?id_Catal[]=<?php print $cg2->listTypeAnnounce[$y]->id; ?>"><?php print $cg2->listTypeAnnounce[$y]->value; ?></a></div></div>
	<?php } } ?>
		</td></tr><tr><td width="50%" style="font-size:10px;" colspan="2">&nbsp;</td></tr>
	<?php } ?>
	</table>
	