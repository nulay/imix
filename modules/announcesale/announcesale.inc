<?php

require_once 'org/nulay/announcesale/nsi/AnnounceDAO.php';
require_once 'org/nulay/announcesale/nsi/TypeAnnounceDAO.php';

function announcesale_getCategory(){
   return 'Привет новому модулю.';
}

function announce_categ(){
    drupal_add_css(drupal_get_path('module', 'catalog') .'/css/jquery-ui-1.7.3.custom.css', 'module');
	drupal_add_css(drupal_get_path('module', 'catalog') .'/css/listGoodsCateg.css', 'module');	
		
	$ta=new TypeAnnounceDAO();
         $lA=$ta->getListTypeAnnounce();
	
	return theme('announce_categ',$lA);
}


function announce_redact($announceData=NULL){
   
   $ta=new TypeAnnounceDAO();
   $announceData['lA']=$ta->getListTypeAnnounce();
   $announceData['ob']=$announceData;
   return theme('announce_redact',$announceData);
}

function announce_save_complete(){
  $err=array();
  // print_r($_POST);
   if(isset($_POST['actionAn']) && $_POST['actionAn']!="") $announceData['actionAn']=$_POST['actionAn']; 
   if(isset($_POST['ta_id']) && $_POST['ta_id']!="") $announceData['ta_id']=$_POST['ta_id'];
   if(isset($_POST['keyword']) && $_POST['keyword']!="") $announceData['keyword']=$_POST['keyword']; else $err[]='Не заполнено обязательное поле Ключевое слово.';
   if(isset($_POST['discription']) && $_POST['discription']!="") $announceData['discription']=$_POST['discription']; else $err[]='Не заполнено описание товара и услуг.';
   
   if(isset($_POST['torg']) && $_POST['torg']!="") $announceData['torg']=$_POST['torg']; 
   
   if(isset($_POST['price']) && $_POST['price']!="") $announceData['price']=$_POST['price']; 
   if(isset($_POST['tel1']) && $_POST['tel1']!="") $announceData['tel1']=$_POST['tel1']; 
   if(isset($_POST['tel2']) && $_POST['tel2']!="") $announceData['tel2']=$_POST['tel2'];
   if(!($announceData['tel1'] | $announceData['tel2'])) $err[]='Не заполнено обязательное поле телефон.';
   if(isset($_POST['note']) && $_POST['note']!="") $announceData['note']=$_POST['note'];
   
  //  if (isset($_FILES['foto']) && $_FILES['foto']['name']!="") {        
  //     validFile($_FILES['foto'],$err);
   //}    
   if(count($err)!=0){$announceData['err']=$err; return announce_redact($announceData);}   
   $anD=new AnnounceDAO();
   global $user;
   $announceData['uid']=$user->uid;
   $idAnnounce=$anD->saveAnnounce($announceData);    
  // saveFileA($_FILES['foto'],$idShop);
 
   drupal_set_message('Вы сохранили объявление.');
   return drupal_goto('announce/announce_categ');
}

function validFileA($file,&$err){
    $imageinfo=getimagesize($file['tmp_name']);	
    if(!$imageinfo && ($imageinfo['mime'] != 'image/gif' && $imageinfo['mime'] != 'image/jpeg' &&  $imageinfo['mime'] != 'image/png')){$err[]='Проверьте формат файла логотипа, он может быть (gif, jpeg, png)'; return 0;}	
	if($file['size']>307200){$err[]='Размер файла превышает 300кб'; return 0;}	
    return 1;	   
}

function saveFileA($file,$idShop){
  $validators = array();  
  $dir = file_directory_path() . '/shoplogo';
  if(file_check_directory($dir, 1)) {    
      if(is_uploaded_file($file['tmp_name'])){             
	   move_uploaded_file($file["tmp_name"],  $dir."/logo_".$idShop.strtolower(strrchr($file['name'],'.')));         
      }else return 0;
 }else return 0;  
}
