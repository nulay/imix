<?php

/**
 * @file page-listcateg-procat.tpl.php
 * Default theme implementation to present a linked feed item for summaries.
 *
 * @see template_preprocess() 
 */ 
?>
<div id="contentListGoods">

<table width="100%" style="border-collapse: separate;">
	<?php $i=0; 
	while ($i < count($procatCateg)) {	    
		$cg1=$procatCateg[$i];	
		//dpm($cg1);
		$cg2=null;
		if($i+1<count($procatCateg)) $cg2=$procatCateg[$i+1];
		$i+=2;?>			
		<tr><td width="50%" style="font-size:x-large;"><?php print $cg1->value; ?></td><td width="50%" style="font-size:x-large;"><?php print $cg2->value; ?></td></tr><tr>
		<td width="50%" style="font-size:1px; background-color:#878787;"></td><td width="50%" style="font-size:1px; background-color:#878787;"></td></tr><tr><td width="50%" style="vertical-align:text-top;" >
		
		<?php for($y=0;$y<count($cg1->listTypeGoods);$y++){ ?>
			
			<div class="nR texMin"><a href="list/goods/<?php print $cg1->listTypeGoods[$y]->id; ?>"><?php print $cg1->listTypeGoods[$y]->value; ?></a></div>
		<?php } ?>
		</td><td width="50%" style="vertical-align:text-top;">
		<?php if($cg2!=null){
			//	dpm($cg2);
			for($y=0;$y<count($cg2->listTypeGoods);$y++){ ?>
				<div class="nR texMin"><a href="list/goods/<?php print $cg2->listTypeGoods[$y]->id; ?>"><?php print $cg2->listTypeGoods[$y]->value; ?></a></div>
		<?php }} ?>
		</td></tr><tr><td width="50%" style="font-size:10px;" colspan="2">&nbsp;</td></tr>
	<?php } ?>
</table>

</div> 