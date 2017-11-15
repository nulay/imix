<?php

/**
 * @file page-listGoods.tpl.php
 * Default theme implementation to present a linked feed item for summaries.
 *
 * @see template_preprocess()
 * s
 */
?>
<style>
.cocretGoods {
    display: inline;
    padding-right: 15px;
}
</style>
<div id="contentListAllGoods">	
 <?php for($i=0;$i<=count($listCompany);$i++){?>
    <div>
        <h1> <?php print $listCompany[$i]->name;?></h1>
        <div class='blockGoods'>			
			<?php for($y=0;$y<=count($listCompany[$i]->listGoods);$y++){?>
			    <div class='cocretGoods'><a href='/catalog/concretGoods/<?php print $listCompany[$i]->listGoods[$y][id];?>'><?php print $listCompany[$i]->listGoods[$y][model];?></a></div>
			<?php } ?>
		</div>
	</div>
<?php } ?>
</div> 
