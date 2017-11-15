<?php
/**
 * @file shop-registration.tpl.php
 * Default theme implementation to present a linked feed item for summaries.
 *
 * @see template_preprocess()
 * @see template_preprocess_procat_registration()
 */
global $user;
//print_r($forms);
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
 .errDatashop li{
	color:red;
 }
</style>

<div class="content centerAl">
<?php  if ($shopData && $shopData['err'] && count($shopData['err'])!=0){ ?>
<ul class='errDatashop'>
	<?php for($i=0;$i<count($shopData['err']);$i++){
	?>
	<li><?php print($shopData['err'][$i]); ?></li>
<?php } ?>
    <li>Также пожалуйста еще раз выберите логотип и установите флажок ознакомлен с пользовательским соглашением.</li>
</ul>
<?php } ?>

<form id="fChUser" class="fReg" enctype="multipart/form-data" action="/procat/registrationcompl" method="POST" style="padding:30px 0 0 50px; width:80%;"> 
<table cellspasing=4 cellpadding=4>
<tr><td colspan=2 class='headerLabel'><label>Информация о владельце</label></td></tr>
<tr><td class='nameLabel'><label>Фамилия: *</label></td><td><input name="fname" type="text" size="50" value="<?php print ($shopData && $shopData['fname'])?$shopData['fname']:""; ?>" style="margin-left:10px;"></td></tr>
<tr><td class='nameLabel'><label>Имя: *</label></td><td><input name="lname" type="text" size="50" value="<?php print ($shopData && $shopData['lname'])?$shopData['lname']:""; ?>" style="margin-left:10px;"></td></tr>
<tr><td class='nameLabel'><label>Отчество: *</label></td><td><input name="pname" type="text" size="50" value="<?php print ($shopData && $shopData['pname'])?$shopData['pname']:""; ?>" style="margin-left:10px;"></td></tr>
<tr><td class='nameLabel'><label>Телефон вида (8(029)7350741): *</label></td><td><input name="tel" type="text" size="50" value="<?php print ($shopData && $shopData['tel'])?$shopData['tel']:""; ?>" style="margin-left:10px;"></td></tr>

<tr><td colspan=2 class='headerLabel'><label>Информация по прокату</label></td></tr>
<tr><td class='nameLabel'><label>Название проката: *</label></td><td><input name="named" type="text" size="50" value="<?php print ($shopData && $shopData['named'])?$shopData['named']:""; ?>" style="margin-left:10px;"></td></tr>
<tr><td class='nameLabel'><label>Адрес проката (физический): </label></td><td><textarea name="adres" rows="3" cols="38" style="margin-left:10px;"><?php print ($shopData && $shopData['adres'])?$shopData['adres']:""; ?></textarea></td></tr>
<tr><td class='nameLabel'><label>Электронный адрес проката (название сайта): </label></td><td><input name="eladres" type="text" size="50" value="<?php print ($shopData && $shopData['eladres'])?$shopData['eladres']:""; ?>" style="margin-left:10px;"></td></tr>
<tr><td class='nameLabel'><label>E-mail проката (менеджера): *</label></td><td><input name="email" type="text" size="30" value="<?php print ($shopData && $shopData['email'])?$shopData['email']:""; ?>" style="margin-left:10px;"></td></tr>
<tr><td class='nameLabel'><label>Описание проката<br>(выводится на странице о прокате): </label></td><td><textarea name="aboutshop" rows="3" cols="38" style="margin-left:10px;"><?php print ($shopData && $shopData['aboutshop'])?$shopData['aboutshop']:""; ?></textarea></td></tr>
<tr><td class='nameLabel'><label>Телефоны менеджеров, консультантов : *</label></td><td><div><input name="tel1" type="text" size="30" value="<?php print ($shopData && $shopData['tel1'])?$shopData['tel1']:""; ?>" style="margin-left:10px;"><div><div><input name="tel2" type="text" size="30" value="<?php print ($shopData && $shopData['tel2'])?$shopData['tel2']:""; ?>" style="margin-left:10px;"><div><div><input name="tel3" type="text" size="30" value="<?php print ($shopData && $shopData['tel3'])?$shopData['tel3']:""; ?>" style="margin-left:10px;"><div><div><input name="tel4" type="text" size="30" value="<?php print ($shopData && $shopData['tel4'])?$shopData['tel4']:""; ?>" style="margin-left:10px;"><div><div><input name="tel5" type="text" size="30" value="<?php print ($shopData && $shopData['tel5'])?$shopData['tel5']:""; ?>" style="margin-left:10px;"><div></td></tr>
<?php if($shopData && $shopData['timeWork']) $timeWork=json_decode($shopData['timeWork'], true);?>
<tr><td class='nameLabel'><label>Время работы : *<br>(время образец c 8:00 до 10:30)</label></td><td><table>

<tr><td><input name='chPn' type='checkbox' <?php print ($timeWork['Pn'])?'checked':'' ?>> Пн</td><td>&nbsp;&nbsp;&nbsp;c<input name='sPn' type="text" size="4" value="<?php print ($timeWork['Pn'])?$timeWork['Pn']['s']:'' ?>" style="margin-left:10px;"></td><td>до<input name='doPn' type="text" size="4" value="<?php print ($timeWork['Pn'])?$timeWork['Pn']['do']:'' ?>" style="margin-left:10px;"></td></tr>

<tr><td><input name='chVt' type='checkbox' <?php print ($timeWork['Vt'])?'checked':'' ?>> Вт</td><td>&nbsp;&nbsp;&nbsp;c<input name='sVt' type="text" size="4" value="<?php print ($timeWork['Vt'])?$timeWork['Vt']['s']:'' ?>" style="margin-left:10px;"></td><td>до<input name='doVt' type="text" size="4" value="<?php print ($timeWork['Vt'])?$timeWork['Vt']['do']:'' ?>" style="margin-left:10px;"></td></tr>

<tr><td><input name='chSr' type='checkbox' <?php print ($timeWork['Sr'])?'checked':'' ?>> Ср</td><td>&nbsp;&nbsp;&nbsp;c<input name='sSr' type="text" size="4" value="<?php print ($timeWork['Sr'])?$timeWork['Sr']['s']:'' ?>" style="margin-left:10px;"></td><td>до<input name='doSr' type="text" size="4" value="<?php print ($timeWork['Sr'])?$timeWork['Sr']['do']:'' ?>" style="margin-left:10px;"></td></tr>

<tr><td><input name='chCh' type='checkbox' <?php print ($timeWork['Ch'])?'checked':'' ?>> Чт</td><td>&nbsp;&nbsp;&nbsp;c<input name='sCh' type="text" size="4" value="<?php print ($timeWork['Ch'])?$timeWork['Ch']['s']:'' ?>" style="margin-left:10px;"></td><td>до<input name='doCh' type="text" size="4" value="<?php print ($timeWork['Ch'])?$timeWork['Ch']['do']:'' ?>" style="margin-left:10px;"></td></tr>

<tr><td><input name='chPt' type='checkbox' <?php print ($timeWork['Pt'])?'checked':'' ?>> Пт</td><td>&nbsp;&nbsp;&nbsp;c<input name='sPt' type="text" size="4" value="<?php print ($timeWork['Pt'])?$timeWork['Pt']['s']:'' ?>" style="margin-left:10px;"></td><td>до<input name='doPt' type="text" size="4" value="<?php print ($timeWork['Pt'])?$timeWork['Pt']['do']:'' ?>" style="margin-left:10px;"></td></tr>

<tr><td><input name='chSb' type='checkbox' <?php print ($timeWork['Sb'])?'checked':'' ?>> Сб</td><td>&nbsp;&nbsp;&nbsp;c<input name='sSb' type="text" size="4" value="<?php print ($timeWork['Sb'])?$timeWork['Sb']['s']:'' ?>" style="margin-left:10px;"></td><td>до<input name='doSb' type="text" size="4" value="<?php print ($timeWork['Sb'])?$timeWork['Sb']['do']:'' ?>" style="margin-left:10px;"></td></tr>

<tr><td><input name='chVs' type='checkbox' <?php print ($timeWork['Vs'])?'checked':'' ?>> Вс</td><td>&nbsp;&nbsp;&nbsp;c<input name='sVs' type="text" size="4" value="<?php print ($timeWork['Vs'])?$timeWork['Vs']['s']:'' ?>" style="margin-left:10px;"></td><td>до<input name='doVs' type="text" size="4" value="<?php print ($timeWork['Vs'])?$timeWork['Vs']['do']:'' ?>" style="margin-left:10px;"></td></tr></table></td></tr>

<tr><td colspan=2 class='headerLabel'><label>Информация по государственной регистрации</label></td></tr>
<tr><td class='nameLabel'><label>Юридическое название: *</label></td><td><input name="urName" type="text" size="50" value="<?php print ($shopData && $shopData['urName'])?$shopData['urName']:""; ?>" style="margin-left:10px;"></td></tr>
<tr><td class='nameLabel'><label>УНП: *</label></td><td><input name="unp" type="text" size="50" value="<?php print ($shopData && $shopData['unp'])?$shopData['unp']:""; ?>" style="margin-left:10px;"></td></tr>
<tr><td class='nameLabel'><label>Свидетельство о гос. регистрации (номер и дата): *</label></td><td><input name="svidGosReg" type="text" size="50" value="<?php print ($shopData && $shopData['svidGosReg'])?$shopData['svidGosReg']:""; ?>" style="margin-left:10px;"></td></tr>
<tr><td class='nameLabel'><label>Логотип проката (не более 300кб и тип jpeg, png, gif): </label></td><td><input type="file" class="file" name="logo" size="30" value="" style="margin-left:10px;"></td></tr>
<tr><td class='nameLabel'><label>Адрес: *<br>(Индекс, Республика, Область,<br>Улица, дом, корпус, офис)<br></label></td><td><textarea name="uradress" rows="3" cols="38" style="margin-left:10px;"><?php print ($shopData && $shopData['uradress'])?$shopData['uradress']:""; ?></textarea></td></tr>

<tr><td colspan=2 class='battonPanel'><input name='iselSogl' type='checkbox'>* Я ознакомился и принимаю пользовательское соглашение на предоставление услуг</td></tr>
<tr><td colspan=2 class='battonPanel'><input type='submit' value='Зарегистрировать прокат'/></td></tr>

</table>
</form>
</div>