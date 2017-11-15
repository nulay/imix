<?php

/**
 * @file title-goods.tpl.php
 * Default theme implementation to present a linked feed item for summaries.
 *
 * @see template_preprocess()
 * @see template_preprocess_title_goods()
 */
?>
<div style="border-bottom:4px groove #878787;">
<span style="font-size: x-large;">
<?php for($i=0;$i<count($titleArr);$i++){ print $titleArr[$i]->value." ";}?>
</span>
<?php $fgh=($urlData['sord'])?"↓":"↑"; $ful=($urlData['sord'])?"&sord=false":"";?>
<span style="font-size:12px;">Сортировать по: 
<a href="<?php  print $urlData['urlRoot'];?>&orderName=min<?php print(($urlData['orderName']=='min')?$ful:''); ?>">Цене<?php print ($urlData['orderName']=='min')?$fgh:""; ?></a> 
<a href="<?php  print $urlData['urlRoot'];?>&orderName=company<?php print(($urlData['orderName']=='company')?$ful:''); ?>">Компании<?php print ($urlData['orderName']=='company')?$fgh:""; ?></a> 
<a href="<?php  print $urlData['urlRoot'];?>&orderName=model<?php print(($urlData['orderName']=='model')?$ful:''); ?>">Модели<?php print ($urlData['orderName']=='model')?$fgh:""; ?></a> 

</span></div>