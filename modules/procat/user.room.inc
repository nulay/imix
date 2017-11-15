<?php
require_once 'org/nulay/procat/nsi/ProcatDAO.php';

function procat_registration($shopData=NULL){
   return theme('procat_registration',$shopData);
}

function procat_registrationchange($idProcat){
   if($idProcat==null){drupal_set_message('Не выбран прокат редактирования данных!');  return drupal_goto('procat/manager/controlProcat');}
   global $user;
   $obS=new ProcatDAO();
   $f=$obS->isUserManagerProcat($user->uid,$idProcat);
   if($f==false){drupal_set_message('Вы не имеете права редактировать данные этого проката!');  return drupal_goto('procat/manager/controlProcat');}   
   $f=$obS->getProcatByIDA($idProcat);
   return procat_registration($f);
}

function procat_registrationcompl(){ 
   $err=array();
  // print_r($_POST);
   if(isset($_POST['fname']) && $_POST['fname']!="") $shopData['fname']=$_POST['fname']; else $err[]='Не заполнено обязательное поле Фамилия владельца.';
   if(isset($_POST['lname']) && $_POST['lname']!="") $shopData['lname']=$_POST['lname']; else $err[]='Не заполнено обязательное поле Имя владельца.';
   if(isset($_POST['pname']) && $_POST['pname']!="") $shopData['pname']=$_POST['pname']; else $err[]='Не заполнено обязательное поле Отчество владельца.';
   if(isset($_POST['tel']) && $_POST['tel']!="") $shopData['tel']=$_POST['tel']; else $err[]='Не заполнено обязательное поле Телефон владельца.';
   
   if(isset($_POST['named']) && $_POST['named']!="") $shopData['named']=$_POST['named']; else $err[]='Не заполнено обязательное поле Название проката.';   
   if(isset($_POST['adres']) && $_POST['adres']!="") $shopData['adres']=$_POST['adres']; 
   if(isset($_POST['eladres']) && $_POST['eladres']!="") $shopData['eladres']=$_POST['eladres'];
   if(isset($_POST['email']) && $_POST['email']!="") $shopData['email']=$_POST['email']; else $err[]='Не заполнено обязательное поле E-mail.';
   if(isset($_POST['aboutshop']) && $_POST['aboutshop']!="") $shopData['aboutshop']=$_POST['aboutshop'];
   if(isset($_POST['tel1']) && $_POST['tel1']!="") $shopData['tel1']=$_POST['tel1']; 
   if(isset($_POST['tel2']) && $_POST['tel2']!="") $shopData['tel2']=$_POST['tel2'];
   if(isset($_POST['tel3']) && $_POST['tel3']!="") $shopData['tel3']=$_POST['tel3'];
   if(isset($_POST['tel4']) && $_POST['tel4']!="") $shopData['tel4']=$_POST['tel4'];
   if(isset($_POST['tel5']) && $_POST['tel5']!="") $shopData['tel5']=$_POST['tel5'];
   
   if(isset($_POST['chPn']) && ($_POST['chPn']=='on')){ $shopData['timeWork']['Pn']['s']=$_POST['sPn']; $shopData['timeWork']['Pn']['do']=$_POST['doPn'];}else $shopData['timeWork']['Pn']=null;
   if(isset($_POST['chVt']) && ($_POST['chVt']=='on')){ $shopData['timeWork']['Vt']['s']=$_POST['sVt']; $shopData['timeWork']['Vt']['do']=$_POST['doVt'];}else $shopData['timeWork']['Vt']=null;
   if(isset($_POST['chSr']) && ($_POST['chSr']=='on')){ $shopData['timeWork']['Sr']['s']=$_POST['sSr']; $shopData['timeWork']['Sr']['do']=$_POST['doSr'];}else $shopData['timeWork']['Sr']=null;
   if(isset($_POST['chCh']) && ($_POST['chCh']=='on')){ $shopData['timeWork']['Ch']['s']=$_POST['sCh']; $shopData['timeWork']['Ch']['do']=$_POST['doCh'];}else $shopData['timeWork']['Ch']=null;
   if(isset($_POST['chPt']) && ($_POST['chPt']=='on')){ $shopData['timeWork']['Pt']['s']=$_POST['sPt']; $shopData['timeWork']['Pt']['do']=$_POST['doPt'];}else $shopData['timeWork']['Pt']=null;
   if(isset($_POST['chSb']) && ($_POST['chSb']=='on')){ $shopData['timeWork']['Sb']['s']=$_POST['sSb']; $shopData['timeWork']['Sb']['do']=$_POST['doSb'];}else $shopData['timeWork']['Sb']=null;
   if(isset($_POST['chVs']) && ($_POST['chVs']=='on')){ $shopData['timeWork']['Vs']['s']=$_POST['sVs']; $shopData['timeWork']['Vs']['do']=$_POST['doVs'];}else $shopData['timeWork']['Vs']=null;
   $shopData['timeWork']=json_encode($shopData['timeWork']);
      
   if(isset($_POST['urName']) && $_POST['urName']!="") $shopData['urName']=$_POST['urName']; else $err[]='Не заполнено обязательное поле Юридическое название.';
   if(isset($_POST['unp']) && $_POST['unp']!="") $shopData['unp']=$_POST['unp']; else $err[]='Не заполнено обязательное поле УНП.';
   if(isset($_POST['svidGosReg']) && $_POST['svidGosReg']!="") $shopData['svidGosReg']=$_POST['svidGosReg']; else $err[]='Не заполнено обязательное поле Свидетельство о гос. регистрации.';
   if(isset($_POST['uradress']) && $_POST['uradress']!="") $shopData['uradress']=$_POST['uradress']; else $err[]='Не заполнено обязательное поле Юридический адрес.';
   if(isset($_POST['iselSogl']) && $_POST['iselSogl']=='on') $shopData['iselSogl']=$_POST['iselSogl']; else $err[]='Вы не приняли пользовательское соглашение';
   $shopData['active']=0;
   $shopData['id_region']=0;
   if (isset($_FILES['logo']) && $_FILES['logo']['name']!="") {        
       validFileP($_FILES['logo'],$err);
   }    
   if(count($err)!=0){$shopData['err']=$err; return procat_registration($shopData);}   
   $procat=new ProcatDAO();
   $idProcat=$procat->saveProcat($shopData);    
   saveFileP($_FILES['logo'],$idProcat);
   global $user;
   $procat->setUserForProcat($user->uid,$idProcat);
   drupal_set_message('Вы зарегистрировали прокат '.$shopData['named'].'!');
   return drupal_goto('procat/manager/controlProcat');
}

function validFileP($file,&$err){
    $imageinfo=getimagesize($file['tmp_name']);	
    if(!$imageinfo && ($imageinfo['mime'] != 'image/gif' && $imageinfo['mime'] != 'image/jpeg' &&  $imageinfo['mime'] != 'image/png')){$err[]='Проверьте формат файла логотипа, он может быть (gif, jpeg, png)'; return 0;}	
	if($file['size']>307200){$err[]='Размер файла превышает 300кб'; return 0;}	
    return 1;	   
}

function saveFileP($file,$idShop){
  $validators = array();  
  $dir = file_directory_path() . '/shoplogo';
  if(file_check_directory($dir, 1)) {    
      if(is_uploaded_file($file['tmp_name'])){             
	   move_uploaded_file($file["tmp_name"],  $dir."/logo_".$idShop.strtolower(strrchr($file['name'],'.')));         
      }else return 0;
 }else return 0;  
}