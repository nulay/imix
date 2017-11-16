<?php

require_once 'org/nulay/catalog/nsi/ShopDAO.php';
require_once 'org/nulay/catalog/nsi/GoodsDAO.php';
require_once 'org/nulay/catalog/nsi/CatalogGoodsDAO.php';
require_once 'org/nulay/catalog/nsi/CompanyDAO.php';
require_once 'org/nulay/catalog/nsi/ElementSearch.php';
require_once 'org/nulay/catalog/nsi/GeneralDataDAO.php';

function parseRequestInCriteria(){
	$countElInP=10;	
	$goodsO=new GoodsDAO();
	//$newURL=preg_replace('/(\?.[^&]+.null$)|(&.[^&]+null)|(&.[^&]+=$)|(&.[^&]+=null$)/','',$_SERVER['REQUEST_URI']);
	//$newURL=preg_replace('/(\?.[^&]+.null&)/','?',$newURL);
	//dpm($newURL);	
	if(isset($_REQUEST['id_Catal'])) $sC->listIdCatG=$_REQUEST['id_Catal'];
	if(isset($_REQUEST['model'])) $sC->model=$_REQUEST['model'];
	if(isset($_REQUEST['id_Comp'])) $sC->listIdComp=$_REQUEST['id_Comp'];
	$sC->listIdComp=removeEmpty($sC->listIdComp);
	if(isset($_REQUEST['orderName'])) $sC->orderName=$_REQUEST['orderName']; else $sC->orderName='company';
	if(isset($_REQUEST['sord'])) $sC->sord=($_REQUEST['sord']=='true'); else $sC->sord=true;
	if(isset($_REQUEST['minPrice'])) $sC->minPrice=$_REQUEST['minPrice'];
	if(isset($_REQUEST['maxPrice'])) $sC->maxPrice=$_REQUEST['maxPrice'];
	$sC->listDopProp=getDopProp($_REQUEST);	
	$sC->resCount=$goodsO->getCountGoods($sC);	
	if(isset($_REQUEST['numPage'])) $sC->numPage=$_REQUEST['numPage']; else $sC->numPage=1;
	if($sC->numPage==0) $sC->numPage=1;
	if(isset($_REQUEST['countElInP'])) $sC->countElInP=$_REQUEST['countElInP']; else $sC->countElInP=$countElInP;
	if( $sC->resCount >0 ) { $total_pages = ceil($sC->resCount/$sC->countElInP); } else { $total_pages = 0; }	
	if ($sC->numPage > $total_pages) $sC->numPage=$total_pages;
	$sC->totalPages=$total_pages;
	return $sC;
}

//Блок с отображением товаров в виде друг над другом с описанием
function catalog_listGoods(){        
	$sC=parseRequestInCriteria();  	
	drupal_add_css(drupal_get_path('module', 'catalog') .'/css/catalogGoods.css', 'module');        	
	$catG=new CatalogGoodsDAO();
	$arCG=$catG->getListTypeGoodsFromIdArr($sC->listIdCatG);
	$strP='listGoods?'.getParameterURL($sC); 	
	//$strP=getURLNopar('numPage',strstr($_SERVER['REQUEST_URI'],listGoods));		
	$strB=getURLNopar('orderName',$strP);
	$strB=getURLNopar('sord',$strB);	
	$sdP=getStrDopProp($sC);
	$titleLG=theme('title_goods',$arCG,array('urlRoot'=>$strB.$sdP,'orderName'=>$sC->orderName,'sord'=>$sC->sord,));	
	if($sC->listIdCatG==null) drupal_goto('catalog/category');		
	if($sC->resCount>0){
	$goodsO=new GoodsDAO();
	$res=$goodsO->getListGoods($sC);
	$arrIt=array();	
	for ($i=0;$i<count($res);$i++) {	    
		if(!$res[$i]['discription'] | $res[$i]['discription']==''){	
			//dpm($res[$i]);		
			$res[$i]['discription']=$goodsO->interpritateProp($res[$i]);			
		}
		$res[$i]['image']=getRootImage($res[$i]);
		$arrIt[]=theme('item_goods',$res[$i]);			
	}		
	}
	$compO=new CompanyDAO();
	$plg->listCompany=$compO->getListAllCompanyByCatalog($sC->listIdCatG);	
	//dpm($plg->listCompany);
	//dpm($compO->getListAllCompanyByCatalog($sC->listIdCatG))
	if($sC->listIdCatG && count($sC->listIdCatG)==1){
		$catG=new CatalogGoodsDAO();
		$sC->phfs=$catG->getPropForSearch($sC->listIdCatG[0]);
	}
	variable_set('catalog_critSearch',theme('critSearch',$sC,$plg));
	if($sC->resCount==0) return theme('noGoodsInSelect',$titleLG);		
	$numPage=theme('item_goods_pager',$strP.$sdP,$sC);		
	return theme('page_listGoods',$titleLG,$arrIt,$numPage);
}

//страница с отображением всех товаров конкретного тип виде название фирмы производителя, список товаров
function catalog_listfullGoods(){
    $sC=parseRequestInCriteria(); 
	$compO=new CompanyDAO();	
	$lC[]=$id_catalog;
	$listCompany=$compO->getListAllCompanyByCatalog($sC->listIdCatG);
	$goodsO=new GoodsDAO();	
	$sC->listIdComp=array();
	$sC->countElInP=$sC->resCount;
	$sC->totalPages=1;
    for($i=0;$i<count($listCompany);$i++){
	    $sC->listIdComp[0]=$listCompany[$i]->id_company;
	    $listCompany[$i]->listGoods=$goodsO->getListGoodsModel($sC);  		
	}
 	//dpm($listCompany);
	return theme('page_listAllGoods', $listCompany);
}

//отображение главного изображения товара
function getRootImage($goods){
    $dirct='sites/default/files/catalog/';
    if(is_object($goods)) $dirct.=$goods->id_catalog.'/'.$goods->company.' '.$goods->model;
    else $dirct.=$goods['id_catalog'].'/'.$goods['company'].' '.$goods['model'];
	if(!file_exists($dirct)){
		$dirct='sites/default/files/catalog/';
		if(is_object($goods)) $dirct.=$goods->id_catalog.'/'.$goods->id;
		else $dirct.=$goods['id_catalog'].'/'.$goods['id'];	
		if(!file_exists($dirct)) return drupal_get_path('module', 'catalog').'/images/noImage.jpg';
	}	
    $hdl=opendir($dirct);
    $f=null ;   
   while ($file = readdir($hdl)){	
       if (($file!=".")&&($file!="..")){	     
	     if(preg_match('/^root/',$file)) return $dirct.'/'.$file;
                if($f==null)	$f= $dirct.'/'.$file;	 
	  }
    }   
	
  if($f!=null) return $f;
  return drupal_get_path('module', 'catalog').'/images/noImage.jpg';
}

//отображение всех изображений товара
function getAllImages($goods){
    $dirct='sites/default/files/catalog/';    	
    if(is_object($goods)) $dirct.=$goods->id_catalog.'/'.$goods->company.' '.$goods->model;
    else $dirct.=$goods['id_catalog'].'/'.$goods['model'];	
	if(!file_exists($dirct)){ 
		$dirct='sites/default/files/catalog/';   
		if(is_object($goods)) $dirct.=$goods->id_catalog.'/'.$goods->id;
		else $dirct.=$goods['id_catalog'].'/'.$goods['id'];
		if(!file_exists($dirct)){ 
			$a[]=drupal_get_path('module', 'catalog').'/images/noImage.jpg'; return $a;
		}
	}
    $hdl=opendir($dirct);	
	while ($file = readdir($hdl)){
       if (($file!=".")&&($file!=".."))
	       $a[]=$dirct.'/'.$file;
    }
	if(count($a)==0) $a[]=drupal_get_path('module', 'catalog').'/images/noImage.jpg';
	return $a;
}

//строка дополнительных свойств товара для адреса
function getStrDopProp($sC){
	$str='';
	for($i=0;$i<count($sC->listDopProp);$i++){  $dP=$sC->listDopProp[$i];          
		$str.='&ref_'.$dP->type.'_'.$dP->id.'='.$dP->value1;
		if($dP->type==5)
		$str.='&dref_'.$dP->type.'_'.$dP->id.'='.$dP->value2;	
	}
	return $str;
}

//Страница конкретного одного товара, (например телефон ЛЖ модель 234)
function catalog_getGoods($id){
	drupal_add_css(drupal_get_path('module', 'catalog') .'/css/catalogGoods.css', 'module');
	drupal_add_css(drupal_get_path('module', 'catalog') .'/js/fancybox/jquery.fancybox.css', 'module');   
	drupal_add_js(drupal_get_path('module', 'catalog') .'/js/fancybox/jquery.fancybox.pack.js', 'module');	
	
	//drupal_add_js('scripts/jquery.lightbox-0.5.min.js');     
         	
	$goodsO=new GoodsDAO();
	$res=$goodsO->getGoodsF($id);
	//метод для пересчета количества посетителей в месяц
	{
	  if($res->reitingcount==null){
	      $res->reitingcount=array();        
	  }else{
	      $res->reitingcount=json_decode($res->reitingcount,true);
	  }		
	  $k=getdate();
	  if($res->reitingcount[$k[mday]]!=null){	           	
           if($res->reitingcount[$k[mday]][m]!=$k[mon]){
		        $res->reitingcount[$k[mday]][m]=$k[mon];
		        $res->reitingcount[$k[mday]][c]=0;
		   }	   
	  }else{
	     $res->reitingcount[$k[mday]][m]=$k[mon];
	  }
	  $res->reitingcount[$k[mday]][c]+=1;
	  $count=0;
	  foreach($res->reitingcount as $value){
	       $count+=$value[c];
	  }	
	  $res->reitingsight=$count;
	  $goodsO->saveReiting($res,json_encode($res->reitingcount));
	}
	
	
	$shopO=new ShopDAO();
	$resSh=$shopO->getListShopInGoods($res->id);
	//dpm($resSh);
	if(count($resSh)>0) variable_set('catalog_concretGoods',theme('block_shop_goods',$resSh)); 
	else 	variable_set('catalog_concretGoods','');		
    $res->images=getAllImages($res);
    //dpm($res);  
    drupal_set_html_head('<meta content="'.$res->type." ".$res->company." ".$res->model.', описание '.$res->company." ".$res->model.', характеристики '.$res->company." ".$res->model.'" name="keywords">');	
	
	drupal_set_html_head('<meta content="Технические характеристики '.$res->company." ".$res->model.' в каталоге товаров города Гомель imix.by! Мы покажем Вам самую актуальную информацию и техническое описание '.$res->company." ".$res->model.', самую лучшую цену на '.$res->company." ".$res->model.' в Гомеле и Гомельской области." name="description">');	
	
	drupal_set_html_head('<meta content="'.$res->type.' '.$res->company.' '.$res->model.'" property="og:title">');	
	
	drupal_set_html_head('<meta content="'.$res->type.' '.$res->company.' '.$res->model.' '.$goodsO->interpritatePropOb($res).'" property="og:description">');
	//drupal_set_html_head('<meta content="http://imix.by" property="og:url">');
	drupal_set_title($res->type.' '.$res->company.' '.$res->model.' по самой низкой цене в городе Гомеле и Гомельской области в каталоге товаров и услуг imix.by');
	return theme('catalog_concretGoods',$res);
}

//JSON Возвращает Список товаров в конкретных магазинах
function catalog_manager_getlistGoods(){
	//drupal_set_header('Content-type: application/json; charset=utf-8');
	$countElInP=50;	
	$goodsO=new GoodsDAO();
	
	if(isset($_REQUEST['sidx'])) $sC->orderName=$_REQUEST['sidx']; else $sC->orderName='model';
	if(isset($_REQUEST['sord'])) $sC->sord=($_REQUEST['sord']=='desc'); else $sC->sord=true;
	
	if(isset($_REQUEST['fast_s']) ){	 
		preg_match_all('/([A-Za-z]+)|([0-9]+)|([а-яА-Я]+)/ui',$_REQUEST['fast_s'],$ls);
		$ls=$ls[0];		
		$sC->resCount=$goodsO->getCountGoodsForGlSerch($ls,$sC);	
		 if(isset($_REQUEST['page'])) $sC->numPage=$_REQUEST['page']; else $sC->numPage=1;
	           if(isset($_REQUEST['rows'])) $sC->countElInP=$_REQUEST['rows']; else $sC->countElInP=$countElInP;
	           if( $sC->resCount >0 ) { $total_pages = ceil($sC->resCount/$sC->countElInP); } else { $total_pages = 0; }	
	     if ($sC->numPage > $total_pages) $sC->numPage=$total_pages;
	     $sC->totalPages=$total_pages;	
	     //$sC=parseRequestInCriteria();	
		 $responce->page = $sC->numPage; 
	     $responce->total =$sC->totalPages; 
	     $responce->records =$sC->resCount;
	     if($sC->resCount==0){
		            echo json_encode($responce);
		            return null;
		 }
		$res=$goodsO->getGlobalSearch($ls,$sC);	
		 	     
		if(isset($_SESSION['id_shop'])) $sC->idShop=$_SESSION['id_shop'];
	    $shD=new ShopDAO();
	    for( $i=0;$i<count($res);$i++){
		$responce->rows[$i]['id']=$res[$i][id];
		$price=$shD->getPriceGoodsInShop($res[$i][id],$sC->idShop);
		if($price) $price=$price->price; else $price='';		$responce->rows[$i]['cell']=array($i+1+(($sC->numPage-1)*$sC->countElInP),$res[$i][type],$res[$i][company],$res[$i][model],theme('redact_price_item',$price));
	}		 
	echo json_encode($responce);
		 return null;
	}
	if(isset($_REQUEST['id_Catal'])) $sC->listIdCatG=$_REQUEST['id_Catal'];
	if(isset($_REQUEST['id_Comp'])) $sC->listIdComp=array($_REQUEST['id_Comp']);
	if(isset($_REQUEST['model'])) $sC->model=$_REQUEST['model'];		
	if($_SESSION['id_shop']==null){
		$err->error='no select shop';
		echo json_encode($err);	
		return null;
	}
	if(isset($_REQUEST['allG'])) 
	   if($_REQUEST['allG']=='false') $sC->idShop=null;
	   else $sC->idShop=$_SESSION['id_shop'];
	
	$sC->resCount=$goodsO->getCountGoods($sC,true);	
	//print_r($sC);
	if(isset($_REQUEST['page'])) $sC->numPage=$_REQUEST['page']; else $sC->numPage=1;
	if(isset($_REQUEST['rows'])) $sC->countElInP=$_REQUEST['rows']; else $sC->countElInP=$countElInP;
	if( $sC->resCount >0 ) { $total_pages = ceil($sC->resCount/$sC->countElInP); } else { $total_pages = 0; }	
	if ($sC->numPage > $total_pages) $sC->numPage=$total_pages;
	$sC->totalPages=$total_pages;	
	
	$responce->page = $sC->numPage; 
	$responce->total =$sC->totalPages; 
	$responce->records =$sC->resCount;
	if($sC->resCount==0){
	           echo json_encode($responce);
		 return null;
	}	
	$res=$goodsO->getListGoodsForRedPrice($sC);	
	
	if(isset($_SESSION['id_shop'])) $sC->idShop=$_SESSION['id_shop'];
	$shD=new ShopDAO();
	for( $i=0;$i<count($res);$i++){
		$responce->rows[$i]['id']=$res[$i][id];
		$price=$shD->getPriceGoodsInShop($res[$i][id],$sC->idShop);
		if($price) $price=$price->price; else $price='';		$responce->rows[$i]['cell']=array($i+1+(($sC->numPage-1)*$sC->countElInP),$res[$i][type],$res[$i][company],$res[$i][model],theme('redact_price_item',$price));
	}		 
	echo json_encode($responce);	
	return null;
}

//JSON Список типов товаров 
function catalog_manager_listCatalogGoods(){
	// header("Content-type: application/json; charset=utf-8");  
	$cg=new CatalogGoodsDAO();
	$res=$cg->getListCatalogGoods();
	echo json_encode($res);	
	return null;
}

//JSON Список компаний
function catalog_manager_listCompany(){
	//header("Content-type: application/json; charset=utf-8");  
	$c=new CompanyDAO();
	$res=$c->getListCompany();
	echo json_encode($res);	
	return null;
}

//меняем на более новую версию jquery
function catalog_preprocess_page($variables) {    
	//меняем на более новую версию jquery
	if (arg(0) == 'catalog' && ( (arg(1) == 'manager' && arg(2)== 'redactprice') |  arg(1)=='concretGoods' | (arg(1) == 'admin' && arg(2)== 'controlCatalog') | (arg(1) == 'admin' && arg(2)== 'redactGoods'))) {		
		$scripts = drupal_add_js();
	$new_jquery = array(drupal_get_path('module', 'catalog') .'/js/jquery-1.7.1.min.js' => $scripts['core']['misc/jquery.js']);
	$scripts['core'] = array_merge($new_jquery, $scripts['core']);
	unset($scripts['core']['misc/jquery.js']);
	$variables['scripts'] = drupal_get_js('header', $scripts);
		changeNewJQuery();
	}
}

function changeNewJQuery(){
         
}

//Здесь удаляем параметр namePar с запроса
function getURLNopar($namePar,$url){		
	$pos = strpos($url,"&".$namePar."=");     
	if ($pos !== false){
		$strB=substr($url,$pos+9,strlen($url));   		
		$url=substr($url,0, $pos);			
		$pos2 = strpos($strB,"&");	
		if ($pos2 !== false)	
		$url.=substr($strB,$pos2,strlen($strB));  
	}
	return $url;	
}

//редактирование прайса конкретного магазана
function catalog_manager_redactprice(){
	drupal_add_css(drupal_get_path('module', 'catalog') .'/js/jqGrid/ui.jqgrid.css', 'module');  
	drupal_add_css(drupal_get_path('module', 'catalog') .'/css/jquery-ui-1.7.3.custom.css', 'module');  
	drupal_add_js(drupal_get_path('module', 'catalog') .'/js/jqGrid/grid.locale-ru.js', 'module'); 	
	drupal_add_js(drupal_get_path('module', 'catalog') .'/js/jqGrid/jquery.jqGrid.min.js', 'module'); 
	drupal_add_js(drupal_get_path('module', 'catalog') .'/js/jquery-ui-1.8.20.custom.min.js', 'module'); 
	//из реквеста забрать магазин и проверить может ли данный пользователь им управлять.
	$id_shop=$_REQUEST['id_shop'];
	if(!isRedactShopCurentUser($id_shop)) return 'Вы не имеете права корректировать данные этого магазина.';
	$_SESSION['id_shop']=$id_shop;	
	return theme('redact_price');
}

//JSON Сохранение цены конкретного товара в конкретном магазине
function catalog_manager_savePrice(){
	$sC->idGoods=$_REQUEST['id_goods'];
	$sC->price=$_REQUEST['price'];
	$sC->idShop=$_SESSION['id_shop'];	
	//проверку на возможность управлять этим магазином.
	$obG=new GoodsDAO();
	$res=$obG->savePrice($sC);
	if($res)
	$resul->save='complete';
	else
	$resul->save='error';
	echo json_encode($resul);	
	return null;
}
function catalog_user_selectShop(){
    global $user;		  
	if(user_access('catalog manager')){
	    drupal_goto('catalog/manager/selectShop');	
	}else
	    drupal_goto('user/information');		
}
function catalog_manager_selectShop(){
	global $user;
	$obS=new ShopDAO();
	$res=$obS->getShopForUser($user->uid);
	$resSh=array();
	if(count($res)>0){
		for($i=0;$i<count($res);$i++){
			$sh=$obS->getShopByID($res[$i]->id_shop);
			$sh->countGoods=$obS->getCountGoodsInShop($res[$i]->id_shop);
			$resSh[]=theme("redact_item_shop",$sh);
		}
	}
	return theme("redact_shop",$resSh);
}

function isRedactShopCurentUser($idShop){
	global $user;
	$obS=new ShopDAO();
	$res=$obS->getShopForUser($user->uid);
	if(count($res)>0)
	for($i=0;$i<count($res);$i++)
	if($res[$i]->id_shop==$idShop) return true;	
	return false;
}

function removeEmpty($array){
	for($i=0;$i<count($array);$i++) {
		if ($array[$i] == 'null')
		unset($array[$i]);
	}	
	return $array;
}

function getParameterURL($sC){
	$parURL='';
	for($i=0;$i<count($sC->listIdCatG);$i++){
		$parURL.='id_Catal[]='.$sC->listIdCatG[$i].'&'; 
	}
	for($i=0;$i<count($sC->listIdComp);$i++){
		$parURL.='id_Comp[]='.$sC->listIdComp[$i].'&'; 
	}
	if($sC->orderName!=model){
		$parURL.='orderName='.$sC->orderName.'&';
	}
	if($sC->sord!=true){
		$parURL.='sord=false&';
	}
	if($sC->minPrice && $sC->minPrice!=null){
		$parURL.='minPrice='.$sC->minPrice.'&';
	}
	if($sC->maxPrice && $sC->maxPrice!=null){
		$parURL.='maxPrice='.$sC->maxPrice.'&';
	}
	if($sC->countElInP && $sC->countElInP!=10){
		$parURL.='countElInP='.$sC->countElInP.'&';
	}
	return substr($parURL,0,strlen($parURL)-1);
}

function getDopProp($req){
	$listDopPr=array();
	foreach(array_keys($req) as $str){
		if(preg_match('/^ref_[0-9]/',$str)){
			$arIt=preg_split('/_/',$str);			
			$el=null;
			$el->type=$arIt[1];
			$el->id=$arIt[2];
			$el->value1=$req[$str];			   
			if($el->type==ElementSearch::$EL_DBLINP){
				$el->value2=$req['d'.$str];				   
			}				   
			if($el->value1!="" || $el->value2!="")			   
			$listDopPr[]=$el;						
		}
	}  
	return $listDopPr;
}

function catalog_shopsGoods($idGoods){
	$shopO=new ShopDAO();
	$resSh=$shopO->getListShopInGoods($idGoods,true);
	return theme('catalog_shopsGoods',$resSh);
}

function catalog_concretShop($idShop){
	$shopO=new ShopDAO();
	$resSh=$shopO->getShopByID($idShop);
	return theme('catalog_concretShop',$resSh);
}

function catalog_admin_redactGoods($id_categ,$id){
	drupal_add_css(drupal_get_path('module', 'catalog') .'/css/jquery-ui-1.7.3.custom.css', 'module');	
	drupal_add_js(drupal_get_path('module', 'catalog') .'/js/jquery-ui-1.8.20.custom.min.js', 'module'); 
	drupal_add_js(drupal_get_path('module', 'catalog') .'/js/jquery.json-2.3.min.js', 'module'); 
	drupal_add_js(drupal_get_path('module', 'catalog') .'/js/myRedactGoods.js', 'module'); 
	drupal_add_css(drupal_get_path('module', 'catalog') .'/js/fancybox/jquery.fancybox.css', 'module');   
	drupal_add_js(drupal_get_path('module', 'catalog') .'/js/fancybox/jquery.fancybox.pack.js', 'module');
	$res->idG=$id;$res->id_categ=$id_categ;	
	$categO=new CatalogGoodsDAO();
	$res->fullCateg=$categO->getCategoryFull($id_categ);                                 								
	$res->fullCateg->listPr=$categO->getListPrHFull($id_categ);
	$res->goods=null;
	$c=new CompanyDAO();
	$res->listCompany=$c->getListAllCompanyByCatalog($id_categ);
	if($id!=-1){
	  $goodsO=new GoodsDAO(); $res->goods=$goodsO->getGoodsF($id);
	}
	$linkList=new GeneralDataDAO();
	for($i=0;$i<count($res->fullCateg->listPr);$i++){	   
	   if($res->fullCateg->listPr[$i]->id_linklist!=null){	   
	        $res->linkList[$res->fullCateg->listPr[$i]->id_linklist]=$linkList->getLinkList($res->fullCateg->listPr[$i]->id_linklist);
	   }
	}	
	$res->images=getListImgС($id,$id_categ);
	return theme('catalog_admin_redactGoods',$res);
}

//страница с формой для редактирования товаров и их свойств
function catalog_admin_controlCatalog(){
    drupal_add_css(drupal_get_path('module', 'catalog') .'/css/jquery-ui-1.7.3.custom.css', 'module');	
	drupal_add_js(drupal_get_path('module', 'catalog') .'/js/jquery-ui-1.8.20.custom.min.js', 'module'); 
	drupal_add_js(drupal_get_path('module', 'catalog') .'/js/jquery.json-2.3.min.js', 'module');
	return theme('catalog_admin_controlCatalog');
}

function catalog_admin_changeData(){
    $method='';
    if(isset($_REQUEST['method'])) $method=$_REQUEST['method'];  
    switch($method){
	    case 'getListCatalog':$categO=new CatalogGoodsDAO();
	                            $res=$categO->getListCatalogGoods();	echo drupal_json($res); break;
	    case 'getListPropG': if(isset($_REQUEST['id_categ'])) $id_categ=$_REQUEST['id_categ'];		                        
		                        $categO=new CatalogGoodsDAO();
	                            $res=$categO->getCategoryFull($id_categ);                                 								
	                            $res->listPr=$categO->getListPrHFull($id_categ); echo drupal_json($res);break;
	    case 'getListGenList': $linkList=new GeneralDataDAO();
		                        $res=$linkList->getLinkList(0); echo drupal_json($res);break;
		case 'saveGodsProp': $objSave=json_decode($_REQUEST['objSave']); 
		                        $categO=new CatalogGoodsDAO(); $categO->redactCategory($objSave); break;
		case 'savePropHier': $objSave=json_decode($_REQUEST['objSave']); 
		                        $categO=new CatalogGoodsDAO(); $categO->redactPrHier($objSave); break;
        case 'getGoodsProprty': $goodsO=new GoodsDAO(); $res=$goodsO->getGoodsF($id); echo drupal_json($res);break;	
        case 'getListAllCompany': $c=new CompanyDAO(); $res=$c->getListCompany(); echo json_encode($res);break;	
        case 'getListCompanyByEx': $c=new CompanyDAO(); $res=$c->getListCompanyByEx($_REQUEST['str']); echo drupal_json($res);break;
		case 'removeImgGoods': $res=deleteImg($_REQUEST['fileName']); echo drupal_json($res);break;	
		case 'getListGoodsFromCatalog':$goodsO=new GoodsDAO(); $res=$goodsO->getGoodsFromCatalog($_REQUEST['id_catalog']); echo drupal_json($res);break;
		case 'saveOrUpdateCatalog':$categO=new CatalogGoodsDAO(); echo drupal_json($categO->redactRazdel(json_decode($_REQUEST['objRazd'])));break;
	}
	return null;
}

function catalog_admin_saveGoods(){		
    //dpm($_REQUEST);
	$error=array();
	$goods=new stdClass();
	$goods->id=($_REQUEST['idG']=='')?null:$_REQUEST['idG'];
	(isset($_REQUEST['id_catalog']))?$goods->id_catalog=$_REQUEST['id_catalog']:$error[]='Не указана категория товара';
	(isset($_REQUEST['id_company']))?$goods->id_company=$_REQUEST['id_company']:$error[]='Не указана компания производитель';
	(isset($_REQUEST['model']) && trim($_REQUEST['model'])!='')?$goods->model=$_REQUEST['model']:$error[]='Не указана модель товара';
	$goods->listPr=array();
	$prK=null;
	foreach($_REQUEST as $k=>$v){
	  if(substr($k,0,2)=='on'){
	      //dpm($_REQUEST['pr_'.substr($k,3)]);
		  $pr=new stdClass();
		  $pr->id_PrHierar=substr($k,3);
          if(!isset($_REQUEST['pr_'.substr($k,3)])){		  
              $pr->value='Нет';
          }else{
		      $pr->value=($_REQUEST['pr_'.substr($k,3)]=='on')?'Да':$_REQUEST['pr_'.substr($k,3)];
          }		
          $prK=$pr;		  
		  $goods->listPr[]=$pr;
	  }	   
	}
	$gD=new GoodsDAO();
	$goods=$gD->saveOrUpdateGoods($goods);
	
	//dpm($goods);
	//dpm($error);
	echo('<ul>');
	for($i=0;$i<count($error);$i++){
		echo('<li>'.$error[$i].'</li>');
	}
	echo('</ul>');
	if(count($error)==0){
	$err=array();
	if (isset($_FILES['goodsImg']) && $_FILES['goodsImg']['name'] && count($_FILES['goodsImg']['name'])>0) { 
		$fileR=getFiles($_FILES);			
		for($i=0;$i<count($fileR);$i++){		    
			if($fileR[$i]['name']!=""){	
				validFileGIС($fileR[$i],$err);				
			}
		}
	} 
	$arrIm=getListImgС($goods->id,$goods->id_catalog);	
	if(count($arrIm)+count($fileR)>10) $err[]='Сохранять больше 10 файлов нельзя.'; 	
	
	if(count($err)==0){
		for($i=0;$i<count($fileR);$i++){
			$obj->fileI=saveFileGIn($fileR[$i],$goods->id,$goods->id_catalog);
			if($obj->fileI=='0'){$err[]='Не удалось сохранить файл.';} 
		}
	}	
	//dpm($err);
	echo('<ul>');
	for($i=0;$i<count($err);$i++){
		echo('<li>'.$err[$i].'</li>');
	}
	echo('</ul>');
	}
	$urlk='catalog/admin/redactGoods/'.$goods->id_catalog.'/'.$goods->id;
	drupal_goto($urlk);
}

function getFiles($fgh){
	$arr=array();
	for($i=0;$i<count($fgh['goodsImg']['name']);$i++){
	    $file=array();
		foreach($fgh['goodsImg'] as $k=>$v){			
			$file[$k]=$fgh['goodsImg'][$k][$i];
		}
		$arr[]=$file;
	}
	return $arr;
}

function validFileGIС($file,&$err){
	$imageinfo=getimagesize($file['tmp_name']);	
	if(!$imageinfo && ($imageinfo['mime'] != 'image/gif' && $imageinfo['mime'] != 'image/jpeg' &&  $imageinfo['mime'] != 'image/png')){$err[]='Размер изображения очень большой или не верный формат файла, он может быть (gif, jpeg, png)'; return 0;}	
	if($file['size']>307200){$err[]='Размер файла превышает 300кб'; return 0;}	
	return 1;	   
}

//не рабочий!!!!
function catalog_manager_savegoodsimg(){
	$err=array();
	if (isset($_FILES['goodsImg']) && $_FILES['goodsImg']['name']!="") {        
		validFileGI($_FILES['goodsImg'],$err);
		$obj->err=$err;
	} 
	$arrIm=getListImgС($goods->id,$goods->id_catalog);//?????? откуда возьмем?	
	if(count($arrIm)>10) $obj->err[]='Сохранять больше 10 файлов нельзя.'; 	
	
	if(count($obj->err)==0){
	    $obj->fileI=saveFileGIn($_FILES['goodsImg'],$idGods);
	    if($obj->fileI=='0'){$obj->err[]='Не удалось сохранить файл.';} 
	}	
	
	$res = '<script type="text/javascript">'; 
	$res .= "window.parent.handleResponse('";
	$res .= json_encode($obj)."');";	
	$res .= '</script>';
	
	echo $res;
	return null;
}

function saveFileGIn($file,$idGoods,$idCatalog,$num = '1'){	
	$validators = array();  
	$dir = file_directory_path() . '/catalog/'.$idCatalog;
	if(file_check_directory($dir, 1)) {
	$dir .= '/'.$idGoods;
	//$fileSave=$dir."/goods_".$idGoods.strtolower(strrchr($file['name'],'.'));
	$fileSave=$dir."/0.jpg";
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
	}}else return 0;  
}

function getListImgС($idGoods,$idCatalog){	
	$dir = file_directory_path() . '/catalog/'.$idCatalog.'/'.$idGoods;
	$ar=file_scan_directory($dir, '0', array('.', '..', 'CVS'), 0, TRUE, 'basename');	
	$arRes=array();
	foreach ($ar as $key) {	  
	   $arRes[]=$key->filename;
	}	
	return $arRes;
}

function catalog_admin_redactConcreteGoods(){
      
     return theme('catalog_admin_controlCatalog');
}

function deleteImg($name){		
	if(!file_delete($name)){
		echo '{"err":"Не удалось удалить файл.".$name}'; 
		return null;
	}		
}
//http://test2/catalog/admin/changeData?data={%22method%22:%22getListCatalog%22,%22id_categ%22:13}