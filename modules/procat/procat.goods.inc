<?php
require_once 'org/nulay/procat/nsi/CategProcatDAO.php';
require_once 'org/nulay/procat/nsi/ProcatDAO.php';

function procat_manager_createGoods($procatData=NULL){      
	drupal_add_js('','inline');  
	$catProcat=new CategProcatDAO();
	$arrCatP=$catProcat->getListType();
	return theme('procat_manager_createGoods',$arrCatP, $procatData);
}

function procat_preprocess_page($variables) {  
	//меняем на более новую версию jquery	    
	//print_r(arg(0) == 'procat' &&   arg(1)=='concretGoods');
	if (arg(0) == 'procat' &&  ((arg(1) == 'manager' && (arg(2)== 'createGoods' | arg(2)=='redactgoods' )) | arg(1)=='concretGoods')) {	    
		$scripts = drupal_add_js();
		$new_jquery = array(drupal_get_path('module', 'procat') .'/js/jquery-1.7.1.min.js' => $scripts['core']['misc/jquery.js']);
		$scripts['core'] = array_merge($new_jquery, $scripts['core']);
		unset($scripts['core']['misc/jquery.js']);
		$variables['scripts'] = drupal_get_js('header', $scripts);		
	}
}

function procat_manager_controlProcat(){
	global $user;
	$obS=new ProcatDAO();
	$res=$obS->getProcatForUser($user->uid);	
	$resSh=array();
	if(count($res)>0){
		for($i=0;$i<count($res);$i++){
			$sh=$obS->getProcatByID($res[$i]->id_procat);			
			$sh->countGoods=$obS->getCountGoodsInProcat($res[$i]->id_procat);
			$resSh[]=theme("redact_item_procat",$sh);
		}
	}
	return theme("redact_procat",$resSh);
}

function procat_manager_redactgoods($id_procat){
	drupal_add_css(drupal_get_path('module', 'catalog') .'/js/jqGrid/ui.jqgrid.css', 'module');  
	drupal_add_css(drupal_get_path('module', 'catalog') .'/css/jquery-ui-1.7.3.custom.css', 'module');  
	drupal_add_js(drupal_get_path('module', 'catalog') .'/js/jqGrid/grid.locale-ru.js', 'module'); 	
	drupal_add_js(drupal_get_path('module', 'catalog') .'/js/jqGrid/jquery.jqGrid.min.js', 'module'); 
	drupal_add_js(drupal_get_path('module', 'catalog') .'/js/jquery-ui-1.8.20.custom.min.js', 'module'); 
	//drupal_add_js(drupal_get_path('module', 'catalog') .'/js/jquery.form.js', 'module'); 
	//drupal_add_js(drupal_get_path('module', 'catalog') .'/js/jquery.ui.tabs.js', 'module'); 
	
	
	//проверить прокат может ли этот пользователь его корректирвоать
	//if(!isRedactShopCurentUser($id_procat)) return 'Вы не имеете права корректировать данные этого магазина.';
	
	$_SESSION['id_procat']=$id_procat;
	//   print_r($_SESSION['id_procat']);	
	$catProcat=new CategProcatDAO();
	$arrCatP=$catProcat->getListType();
	
	return theme('manager_redactgoods',$arrCatP);
	//echo json_encode($prD->getListGoods($sC));
	//return theme("manager_redactgoods", $arrCatP);    
}


function procat_manager_rgdata(){     
	//  if($_SESSION['id_procat']==NULL) return null;
	// $sC->$id_procat=$_SESSION['id_procat'];
	
	$prD=new ProcatDAO();	
	$countElInP=50;		 
	if(isset($_REQUEST['id_catalog'])){ $sC->listIdCateg=array(); $sC->listIdCateg[]=$_REQUEST['id_catalog'];}
	if(isset($_REQUEST['model'])) $sC->model=$_REQUEST['model'];
	if(isset($_REQUEST['company'])) $sC->company=$_REQUEST['company'];
	if(isset($_REQUEST['orderName'])) $sC->orderName=$_REQUEST['orderName']; else $sC->orderName='model';
	if(isset($_REQUEST['sord'])) $sC->sord=($_REQUEST['sord']=='true'); else $sC->sord=true;
	$ar=array();
	$ar[0]=$_SESSION['id_procat'];
	$sC->listIdProcat=$ar;
	$sC->resCount=$prD->getCountGoods($sC);		
	if(isset($_REQUEST['page'])) $sC->numPage=$_REQUEST['page']; else $sC->numPage=1;
	if(isset($_REQUEST['countElInP'])) $sC->countElInP=$_REQUEST['countElInP']; else $sC->countElInP=$countElInP;
	if( $sC->resCount >0 ) { $total_pages = ceil($sC->resCount/$sC->countElInP); } else { $total_pages = 0; }	
	if ($sC->numPage > $total_pages) $sC->numPage=$total_pages;
	$sC->totalPages=$total_pages;
	if($sC->numPage==0)$sC->numPage=1;
	//print_r($sC);
	$res=$prD->getListGoods($sC);	
	$responce->page = $sC->numPage; 
	$responce->total =$sC->totalPages; 
	$responce->records =$sC->resCount;
	for( $i=0;$i<count($res);$i++){
		//    print_r($listG);
		$responce->rows[$i]['id']=$res[$i][Id];	$responce->rows[$i]['cell']=array($i+1+(($sC->numPage-1)*$sC->countElInP),$res[$i][id_catalog],$res[$i][company],$res[$i][model],$res[$i][priceday]);
	}		
	echo json_encode($responce);	
	return null;	
}

function procat_manager_redactgoods2($id_procat){
	
	//echo json_encode($prD->getListGoods($sC));
	//return theme("manager_redactgoods", $arrCatP);    
}

function procat_manager_rgdatach(){   
	if(isset($_REQUEST['meth'])) $sC->meth=$_REQUEST['meth'];
	if(isset($_REQUEST['id'])) $sC->id=$_REQUEST['id'];	
	
	$prD=new ProcatDAO();
	if($sC->id!=null){
		$sC->goods=$prD->getGoodsById($sC->id);
		if($sC->goods['id_procat']!=$_SESSION['id_procat']){ echo '{"err":["Вы не имеете права корректировать данный товар."]}'; return null;}
	}
	if($sC->meth=='getData'){
		$prData=$prD->getGoodsById($sC->id);
		$prData['listImg']=getListImg($sC->id);	  
		echo json_encode($prData);
	}
	if($sC->meth=='edit'){		
	    $prData=saveGoods();
		$prData['listImg']=getListImg($prData[id]);	  
		echo json_encode($prData);
	}
	if($sC->meth=='del'){
	    if(!isCorrectGoods($sC->id)) {echo '{"err":"Вы не имеете права корректировать данный товар."}'; return null;}
	    $res=$prD->removeGoods($sC->id);
		$imG=getListImg($sC->id);
		for($i=0;$i<count($imG);$i++){
		   if(!file_delete($imG[$i])){echo '{"err":"Не удалось удалить товар."}'; return null;}
		}
		if(!$res) {echo '{"err":"Не удалось удалить товар из базы данных."}'; return null;}		
		echo '{"mess":"Товар удален."}';
	}
	if($sC->meth=='delIm'){
	    if(!isCorrectGoods($sC->id)) {echo '{"err":["Вы не имеете права корректировать данный товар."]}'; return null;}
		//$fileSave=$dir."/goods_".$idGoods.strtolower(strrchr($file['name'],'.'));
		if(isset($_REQUEST['num'])) $sC->num=$_REQUEST['num'];
        if($sC->num=='null') $patt=$sC->id.'.';	
        else $patt=$sC->id.'_'.$sC->num;		
		$imG=getListImg($patt);		
		if(!file_delete($imG[0])){echo '{"err":["Не удалось удалить изображение."]}'; return null;}
		echo '{"mess":["Изображение удалено."]}';
	}
	//echo json_encode('error');	
	return null;
}

function isCorrectGoods($idGoods){
    $prD=new ProcatDAO();
	$g=$prD->getGoodsById($idGoods);	
	return ($g['id_procat']==$_SESSION['id_procat']);
}

function saveGoods(){      
	$err=array();
	if(isset($_POST['id']) && $_POST['id']!="") $procatGoodsData['id']=$_POST['id'];
	if(isset($_POST['idcateg']) && $_POST['idcateg']!="") $procatGoodsData['idcateg']=$_POST['idcateg']; else $err[]='Не заполнено обязательное поле категория товаров.';
	if(isset($_POST['keyword']) && $_POST['keyword']!="") $procatGoodsData['keyword']=$_POST['keyword'];
	if(isset($_POST['company']) && $_POST['company']!="") $procatGoodsData['company']=$_POST['company']; else $err[]='Не заполнено обязательное поле Фирма производитель';
	if(isset($_POST['model']) && $_POST['model']!="") $procatGoodsData['model']=$_POST['model']; else $err[]='Не заполнено обязательное поле Модель'; 
	if(isset($_POST['prop']) && $_POST['prop']!="") $procatGoodsData['prop']=$_POST['prop']; 
	if(isset($_POST['value']) && $_POST['value']!="") $procatGoodsData['value']=$_POST['value']; 
	if(isset($_POST['additiontext']) && $_POST['additiontext']!="") $procatGoodsData['additiontext']=$_POST['additiontext']; 
	if(isset($_POST['priceday']) && $_POST['priceday']!="") $procatGoodsData['priceday']=$_POST['priceday']; else $err[]='Не заполнено обязательное поле Стоимость в сутки'; 
	if(isset($_POST['priceweek']) && $_POST['priceweek']!="") $procatGoodsData['priceweek']=$_POST['priceweek']; 
	if(isset($_POST['pricemonth']) && $_POST['pricemonth']!="") $procatGoodsData['pricemonth']=$_POST['pricemonth']; 
	if(isset($_POST['priceestimated']) && $_POST['priceestimated']!="") $procatGoodsData['priceestimated']=$_POST['priceestimated']; else $err[]='Не заполнено обязательное поле Оценочная стоимость'; 	
	$prD=new ProcatDAO();
	if($procatGoodsData['id']!=null && !isCorrectGoods($procatGoodsData['id'])){
	    $err[]="Данный товар не соответствует корректируемому прокату";
	}
	
	if(count($err)!=null){ $procatGoodsData['err']=$err; return $procatGoodsData;}
			
	$procatGoodsData['id_procat']=$_SESSION['id_procat'];   
	$procatGoodsData=$prD->saveProcatData($procatGoodsData);
	
	//print_r(file_directory_temp());
	//print_r($prD);
	
	return  $procatGoodsData;
}

function procat_list_goods($id_catalog){
          drupal_add_js('','inline');  
	$catProcat=new CategProcatDAO();
	$arrCatP=$catProcat->getListType();
	//проверяем прокат может ли этот пользователь его корректирвоать
	
	$prD=new ProcatDAO();			
	$countElInP=15;	
	$sC->listIdCateg=array();
	$sC->listIdCateg[]=$id_catalog;	
	if(isset($_REQUEST['id_procat'])) $sC->listIdProcat=$_REQUEST['id_procat'];
	if(isset($_REQUEST['model'])) $sC->model=$_REQUEST['model'];
	if(isset($_REQUEST['company'])) $sC->company=$_REQUEST['company'];
	if(isset($_REQUEST['orderName'])) $sC->orderName=$_REQUEST['orderName']; else $sC->orderName='model';
	if(isset($_REQUEST['sord'])) $sC->sord=($_REQUEST['sord']=='true'); else $sC->sord=true;
	$ar=array();		
	$sC->resCount=$prD->getCountGoods($sC);		
	if(isset($_REQUEST['numPage'])) $sC->numPage=$_REQUEST['numPage']; else $sC->numPage=1;
	if(isset($_REQUEST['countElInP'])) $sC->countElInP=$_REQUEST['countElInP']; else $sC->countElInP=$countElInP;
	if( $sC->resCount >0 ) { $total_pages = ceil($sC->resCount/$sC->countElInP); } else { $total_pages = 0; }	
	if ($sC->numPage > $total_pages) $sC->numPage=$total_pages;
	if ($sC->numPage==0) $sC->numPage=1;
	$sC->totalPages=$total_pages;	
	$numPage=theme('item_goods_pager',$strP.$sdP,$sC);	
	$listG=$prD->getListGoods($sC);
	//print_r($listG);
	$listItTh=array();
	for($i=0;$i<count($listG);$i++){	          
	          $listG[$i][image]=getListImg($listG[$i][Id]);
		if(count($listG[$i][image])==0)
		       $listG[$i][image]=drupal_get_path('module', 'catalog').'/images/noImage.jpg';
		else
		        $listG[$i][image]=$listG[$i][image][0]; 
		$listItTh[]=theme('procat_item_goods',$listG[$i]);	   
	}
	//print_r($listItTh);
	return theme('procat_listgoods','',$listItTh,$numPage);
	//drupal_add_js('','inline');  
	//$catProcat=new CategProcatDAO();
	//$arrCatP=$catProcat->getListType();
	
	//return  
}

function procat_concretGoods($id_goods){
          if(!is_numeric($id_goods)) return '<div>Такого товара не существует</div>';
          $prD=new ProcatDAO();
          $goods=$prD->getGoodsById($id_goods);			
           if($goods==null) return '<div>Такого товара не существует</div>';	
	drupal_add_css(drupal_get_path('module', 'catalog') .'/css/catalogGoods.css', 'module');
	drupal_add_css(drupal_get_path('module', 'catalog') .'/js/fancybox/jquery.fancybox.css', 'module');   
	drupal_add_js(drupal_get_path('module', 'catalog') .'/js/fancybox/jquery.fancybox.pack.js', 'module');	   
          $goods['procat']=$prD->getProcatByID($goods[id_procat]);	  
          $goods['images']=getListImg($id_goods);		  
	return  theme("procat_concretGoods", $goods);   
}

function procat_manager_savegoodsimg(){
	global $user;	 
	$err=array();
	if (isset($_FILES['goodsImg']) && $_FILES['goodsImg']['name']!="") {        
		validFileGI($_FILES['goodsImg'],$err);
		$obj->err=$err;
	}  
    if (isset($_REQUEST['idGoods']) && $_REQUEST['idGoods']!="") {        
		$idGods=$_REQUEST['idGoods'];
		$prD=new ProcatDAO();
	    if($idGods){
	        $g=$prD->getGoodsById($idGods);
		    if($g['id_procat']!=$_SESSION['id_procat']) $obj->err[]="Данный товар не соответствует корректируемому прокату";
	    }
	}
	$arrIm=getListImg($idGods);	
	if(count($arrIm)>2) $obj->err[]='Сохранять больше 3 файлов нельзя.'; 
	if(count($obj->err)==0){
	    $obj->fileI=saveFileGI($_FILES['goodsImg'],$idGods);
	    if($obj->fileI=='0'){$obj->err[]='Не удалось сохранить файл.';} 
	}	
	
	$res = '<script type="text/javascript">'; 
	$res .= "window.parent.handleResponse('";
	$res .= json_encode($obj)."');";	
	$res .= '</script>';
	
	echo $res;
	return null;
}

function validFileGI($file,&$err){
	$imageinfo=getimagesize($file['tmp_name']);	
	//$imageinfo=getimagesize('sites\default\files\procatGoods\goods_20.jpg');	
	if(!$imageinfo && ($imageinfo['mime'] != 'image/gif' && $imageinfo['mime'] != 'image/jpeg' &&  $imageinfo['mime'] != 'image/png')){$err[]='Размер изображения очень большой ил не верный формат файла, он может быть (gif, jpeg, png)'; return 0;}	
	if($file['size']>307200){$err[]='Размер файла превышает 300кб'; return 0;}	
	return 1;	   
}

function saveFileGI($file,$idGoods,$num = '1'){
	$validators = array();  
	$dir = file_directory_path() . '/procatGoods';
	//$fileSave=$dir."/goods_".$idGoods.strtolower(strrchr($file['name'],'.'));
	$fileSave=$dir."/goods_".$idGoods.".jpg";
	if(file_check_directory($dir, 1)) {    
		if(is_uploaded_file($file['tmp_name'])){    
            if (file_exists($fileSave)){			    
			    $fileSave=file_destination($fileSave,'FILE_EXISTS_RENAME');				
            }	
            $imageinfo=getimagesize($file['tmp_name']);	
			$wi=400;
			$hi=400;
			if($imageinfo[0]>$imageinfo[1]){
			    $wi=round($hi/$imageinfo[0]*$imageinfo[1]);               			
			}else{		         
			     $hi=round($wi/$imageinfo[1]*$imageinfo[0]);				 
			}            
			image_resize($file["tmp_name"], $fileSave, $hi,$wi);			
            return $fileSave;		
		}else return 0;
	}else return 0;  
}

function getListImg($idGoods){
	$dir = file_directory_path() . '/procatGoods';
	$ar=file_scan_directory($dir, 'goods_'.$idGoods, array('.', '..', 'CVS'), 0, TRUE, 'basename');		
	$arRes=array();
	foreach ($ar as $key) {	  
	   $arRes[]=$key->filename;
	}	
	return $arRes;
}
