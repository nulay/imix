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

<div align="center" style="background-color:#DFE9CE;">Страницы:&nbsp;&nbsp;&nbsp;
<?php if($sC->numPage!=1){?>				
		<a title="Предыдущая" style="text-decoration:none;" href="<?php print $urlRoot; ?>&numPage=<?php print $sC->numPage-1;?>">&nbsp;&#60;&#60;&nbsp;</a>
<?php } ?>
<?php if ($sC->totalPages>13 && $sC->numPage-6>0){?>
		<a style="text-decoration:none;" href="<?php print $urlRoot; ?>&numPage=1">&nbsp;1&nbsp;</a>&nbsp;...&nbsp;
<?php } ?>	
<?php $fi=($sC->totalPages>13 && $sC->numPage-6>0)?($sC->numPage-4):1;
	$fe=($sC->totalPages>13 && $sC->numPage+6<$sC->totalPages)?($sC->numPage+4):$sC->totalPages;
	for($i=$fi;$i<=$fe;$i++){
		if($i==$sC->numPage){?>	
			<span style="background-color:cadetblue;">&nbsp;<?php print $i;?>&nbsp;</span>
<?php }else{ ?>	
			&nbsp;<a style="text-decoration:none;" href="<?php print $urlRoot.'&numPage='.$i;?>"><?php print $i;?></a>&nbsp;
	<?php }} ?>
<?php if ($sC->totalPages>13 && $sC->numPage+6<$sC->totalPages){ ?>
		&nbsp;...&nbsp;<a style="text-decoration:none;" href="<?php print $urlRoot.'&numPage='.$sC->totalPages;?>">&nbsp;<?php print $sC->totalPages;?>&nbsp;</a>
<?php } ?>
<?php if ($sC->numPage<$sC->totalPages){ ?>
		<a title="Следующая" style="text-decoration:none;" href="<?php print $urlRoot.'&numPage='.($sC->numPage+1);?>">&nbsp;&#62;&#62;&nbsp;</a>
<?php } ?>
</div> 