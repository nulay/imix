<?php

/**
 * @file page-listGoods.tpl.php
 * Default theme implementation to present a linked feed item for summaries.
 *
 * @see template_preprocess()
 * s
 */
?>

<div id="contentListGoods">
<?php print $titleListG;?>				
<table width='100%'>
 <?php for($i=0;$i<=count($listItTh);$i++){?>
             <?php print $listItTh[$i];?>	
<?php } ?>
</table>
<?php print $numPage;?>	
</div> 