<?php
/**
 * @file shop-registration.tpl.php
 * Default theme implementation to present a linked feed item for summaries.
 *
 * @see template_preprocess()
 * @see template_preprocess_item_goods()
 */
global $user;
print_r($forms);
dpm($announceData);
?>
<style>
 .headerLabel{
	background-color:#54B6BB;
	font-weight: bold;
	color:#00494D;
	padding:4px;
 }
 .nameLabel{
	text-align: right;
	vertical-align: top;
 }
 .centerAl{
	//text-align:center;
 }
 .battonPanel{
	text-align:right;
 }
 .errAnnounceData li{
	color:red;
 }
 .propertyAn{
	margin-left:10px;
 }
</style>

<div class="content centerAl">
<?php  if ($announceData && count($announceData['err'])!=0){ ?>
<ul class='errAnnounceData'>
	<?php for($i=0;$i<count($announceData['err']);$i++){
	?>
	<li><?php print($announceData['err'][$i]); ?></li>
<?php } ?>    
</ul>
<?php } ?>

<form class="fReg" enctype="multipart/form-data" action="/announce/saveannounce" method="POST" style="padding:30px 0 0 50px; width:80%;"> 
<table cellspasing=4 cellpadding=4>
<tr><td colspan=2 class='headerLabel'><label>Объявление</label></td></tr>
<tr><td class='nameLabel'><label>Действие: *</label></td><td><select name='actionAn' class="propertyAn"><option value='0'>Продам</option><option value='1'>Куплю</option><option value='2'>Услуга</option><option value='3'>Аренда</option></select></td></tr>
<tr><td class='nameLabel'><label>Раздел: *</label></td><td><select name='ta_id' class="propertyAn">
<?php for($i=0;$i<count($announceData['lA']);$i++){ ?>
      <optgroup label="<?php print $announceData['lA'][$i]->value; ?>"> 
	    <?php for($y=0;$y<count($announceData['lA'][$i]->listTypeAnnounce);$y++){ ?>
		     <option <?php if($announceData['ob']['ta_id']==$announceData['lA'][$i]->listTypeAnnounce[$y]->id) print 'selected="selected"'; ?> value="<?php print $announceData['lA'][$i]->listTypeAnnounce[$y]->id; ?>"><?php print $announceData['lA'][$i]->listTypeAnnounce[$y]->value; ?></option>
	    <?php } ?>
	  </optgroup>
<?php } ?>
</td></tr>
<tr><td class='nameLabel'><label>Ключевое слово: *</label></td><td><input name="keyword" type="text" size="50" value="<?php print ($announceData['ob']['keyword'])?$announceData['ob']['keyword']:""; ?>" class="propertyAn"></td></tr>
<tr><td class='nameLabel'><label>Oписание товара или услуги: *</label></td><td><textarea name="discription" rows="5" cols="38" class="propertyAn"><?php print ($announceData['ob']['discription'])?$announceData['ob']['discription']:""; ?></textarea></td></tr>

<tr><td class='nameLabel'><label>Цена: </label></td><td><input name="price" type="text" size="20" value="<?php print ($announceData['ob']['price'])?$announceData['ob']['price']:""; ?>" style="margin-left:10px;"> у.е.<input name="torg" type="checkbox"  <?php print ($announceData['ob']['torg'])?'checked="checked':''; ?> style="margin-left:10px;"> торг</td></tr>
<tr><td class='nameLabel'><label>Телефон1: </label></td><td><input name="tel1" type="text" size="50" value="<?php print ($announceData['ob']['tel1'])?$announceData['ob']['tel1']:""; ?>" style="margin-left:10px;"></td></tr>
<tr><td class='nameLabel'><label>Телефон2: </label></td><td><input name="tel2" type="text" size="50" value="<?php print ($announceData['ob']['tel2'])?$announceData['ob']['tel2']:""; ?>" style="margin-left:10px;"></td></tr>
<tr><td class='nameLabel'><label>Примечание: </label></td><td><input name="note" type="text" size="50" value="<?php print ($announceData['ob']['note'])?$announceData['ob']['note']:""; ?>" style="margin-left:10px;"></td></tr>
<tr><td colspan=2 class='battonPanel'><input type='submit' value='Сохранить объявление'/></td></tr>
</table>
</form>
</div>