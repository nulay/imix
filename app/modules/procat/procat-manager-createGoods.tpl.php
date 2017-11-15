<?php

/**
 * @file procat-manager-createGoods.tpl.php
 * Default theme implementation to present a linked feed item for summaries.
 *
 * @see template_preprocess() 
 */ 
//print_r($procatCateg)
//print_r($procatData)
?>
<style>
.rootCateg{
    background-color: lightGrey;
}
.childCateg{
	padding-left:20px;
}
</style>
<div id="contentProcat">
<?php  if ($procatData && count($procatData['err'])!=0){ ?>
<ul class='errDataprocat'>
	<?php for($i=0;$i<count($procatData['err']);$i++){
	?>
	<li><?php print($procatData['err'][$i]); ?></li>
<?php } ?>    
</ul>
<?php } ?>
<form action='/procat/manager/createGoods/complete' method='POST'>
    <table width='100%'>
	   <tr class='onePrG'><td class='namePrG'><label> Категория товара*: </label></td><td class='valuePrG'>
	     <select name='idcateg' id='idcateg'><option value=0></option>
	     <?php for($i=0;$i<count($procatCateg);$i++){ ?>
			<option class='rootCateg' value=<?php print $procatCateg[$i]->id; ?> onclick="this.parentNode.selectedIndex = 0;"><?php print $procatCateg[$i]->value; ?></option>
		 <?php for($y=0;$y<count($procatCateg[$i]->listTypeGoods);$y++){ ?>
			<option class='childCateg' <?php if($procatData!=null && $procatData['idcateg']!=null && $procatCateg[$i]->listTypeGoods[$y]->id==$procatData['idcateg']) print 'selected=selected'; ?> value=<?php print $procatCateg[$i]->listTypeGoods[$y]->id; ?>><?php print $procatCateg[$i]->listTypeGoods[$y]->value; ?></option>
		 <?php }} ?>
		 </select>
	   </td></tr>
	   <tr class='onePrG'><td class='namePrG'><label> Ключевое слово (используйте при необходимости уточнения категории товара): </label></td><td class='valuePrG'><input type='text' name='keyword' value='<?php if($procatData!=null && $procatData['keyword']!=null) print $procatData['keyword'] ?>'></td></tr>
	   <tr class='onePrG'><td class='namePrG'><label> Описание товара*: </label></td><td class='valuePrG'>
	     <table>
		 <thead>
		 <tr><td><label>Фирма производитель: </label></td><td><input type='text' name='company' value='<?php if($procatData!=null && $procatData['company']!=null) print $procatData['company'] ?>'></td><td></td></tr>
		 <tr><td><label>Модель: </label></td><td><input type='text' name='model' value='<?php if($procatData!=null && $procatData['model']!=null) print $procatData['model'] ?>'></td><td></td></tr>
		 </thead>
		 <tbody id='bodyT'>
		 <?php if($procatData!=null && $procatData['prop']!=null){
			for($i=0;$i<count($procatData['prop']);$i++){?>
		       <tr><td><input type='text' name='prop[]' value='<?php print $procatData['prop'][$i]; ?>'></td><td><input type='text' name='value[]' value='<?php print $procatData['value'][$i]; ?>'></td><td><img src='/<?php print drupal_get_path('module', 'procat'); ?>/images/remB.gif'  title='Удалить свойство' width=20 height=20 onclick="jQuery(this.parentNode.parentNode).remove();"></td></tr>
		 <?php }} ?>
		 </tbody>
		 </table>
	     <input type='button' value='Добавить свойство' id='addProp'>
	   </td></tr>
	   <tr class='onePrG'><td class='namePrG'><label> Дополнительное описание: </label></td><td class='valuePrG'><textarea rows="7" cols="35" name="additiontext"><?php if($procatData!=null && $procatData['additiontext']!=null) print $procatData['additiontext'] ?></textarea></td></tr>
	   <tr class='onePrG'><td class='namePrG'><label> Стоимость в сутки*: </label></td><td class='valuePrG'><input type='text' name='priceday' value='<?php if($procatData!=null && $procatData['priceday']!=null) print $procatData['priceday'] ?>'></td></tr>
	   <tr class='onePrG'><td class='namePrG'><label> Стоимость за неделю: </label></td><td class='valuePrG'><input type='text' name='priceweek' value='<?php if($procatData!=null && $procatData['priceweek']!=null) print $procatData['priceweek'] ?>'></td></tr>
	   <tr class='onePrG'><td class='namePrG'><label> Стоимость за месяц: </label></td><td class='valuePrG'><input type='text' name='pricemonth' value='<?php if($procatData!=null && $procatData['pricemonth']!=null) print $procatData['pricemonth'] ?>'></td></tr>
	   <tr class='onePrG'><td class='namePrG'><label> Оценочная стоимость*: </label></td><td class='valuePrG'><input type='text' name='priceestimated' value='<?php if($procatData!=null && $procatData['priceestimated']!=null) print $procatData['priceestimated'] ?>'></td></tr>
	   
	</table>
	<input type='submit'>
	</form>
</div> 

<script>
    var UprProp={
		create:function(){
			var thisEl=this;
			jQuery('#addProp').click(thisEl.createNewProp)
		},
		createNewProp:function(){
			var butR=jQuery("<img src='/<?php print drupal_get_path('module', 'procat'); ?>/images/remB.gif'  title='Удалить свойство' width=20 height=20>");
			var tr=jQuery("<tr><td><input type='text' name='prop[]'></td><td><input type='text' name='value[]'></td></tr>").append(jQuery('<td>').append(butR));		
			var bT=jQuery('#bodyT').append(tr);			
			butR.click(function(){tr.remove();});
		}
	}
	UprProp.create();
</script>