<?php

/**
 * @file catalog-admin-concretGoods.tpl.php
 * Default theme implementation to present a linked feed item for summaries.
 *
 * @see template_preprocess()
 */
//dpm($user);
?>
<div>Название категории: <input type='text' size='55' value='<?php print $categ->value; ?>'></div>

<table width='100%' >
<thead align=center>
<tr><td rowspan=2 width='40%'>Название</td><td rowspan=2 width='10%'>Тип</td><td rowspan=2 width='10%'>Ссылка на список</td><td colspan=3>Операнд</td><td rowspan=2 width='10%'>Учавствовать в поиске</td></tr>
<tr><td width='10%'>до</td><td width='10%'>между</td><td width='10%'>после</td></tr>
</thead>
<?php for($i=0;$i<count($categ->listPr);$i++){
	     $c=$categ->listPr[$i];
		// dpm($c);
	      if($c->id_parent==0){?>
		    <tr><td colspan=7 style="background-color:lightgray; font-weight:bold;"> Раздел: <input type='text' size='65' value='<?php print $c->name; ?>'></td></tr>
<?php      for($y=0;$y<count($categ->listPr);$y++){
	            $c2=$categ->listPr[$y];
	             if($c2->id_parent==$c->id){
	?>
	        
	        <tr><td> <input type='text' size='45' value='<?php print $c2->name; ?>'></td><td align=center>
                      <select><option value=null></option><option value=1 <?php print ($c2->type==1)?'selected':''; ?>>Список</option><option value=2 <?php print ($c2->type==2)?'selected':''; ?>>Флажок</option><option value=3 <?php print ($c2->type==3)?'selected':''; ?>>Поле от</option><option value=4 <?php print ($c2->type==4)?'selected':''; ?>>Поле до</option><option value=5 <?php print ($c2->type==5)?'selected':''; ?>>Поле от - до</option></select>
 
            </td><td align=center><input type='text' value='<?php print $c2->id_linklist; ?>'></td><td align=center><input type='text' value='<?php print $c2->uper; ?>'></td><td align=center><input type='text' value='<?php print $c2->inners; ?>'></td><td align=center><input type='text' value='<?php print $c2->downer; ?>'></td><td align=center><input type='checkbox' <?php print ($c2->usesearch==1)?'checked=\'checked\'':''; ?>></td></tr>
			<?php }}}} ?>
</table>