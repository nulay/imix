<?php

require_once 'phpQuery-onefile.php';
require_once 'includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

function startParseAnekdotFromVK(){
    _startParseAnekdotFromVK('http://vk.com/anekdot?_fm=group',300); 
    //$(':contains(час назад)').parents('.post').find('.post_like_count').filter(function(){return $(this).text()>500}).parents('.post_full_like_wrap').prevAll('.wall_text').find('.wall_post_text')
    echo 'start'; 
}

function _startParseAnekdotFromVK($urlgr,$count_like){     
   $content = file_get_contents($urlgr);
   $doc = phpQuery::newDocumentHTML($content);
   $doc->find('script')->remove();
   $doc->find('img')->remove();
   echo $doc;           
}

function startParseAnekdotFromVK2(){	
        $sC=array();
        if(isset($_REQUEST['callback'])){
           $sC['callback']=$_REQUEST['callback'];
        }
        
	if(isset($_REQUEST['id'])){
	   $sC['id']=$_REQUEST['id'];
        }else{
	     $sC['id']='helloId';
	   $sC['jkl']='sdfsdfsdf';
	   echo $sC['callback'].'('.json_encode($sC).')';	   
	 }
	//if(isset($_REQUEST['anek'])) $sC->anek=$_REQUEST['anek'];
	//$sql='SELECT id, value FROM {no_propertyother} WHERE id=%d';
	//$result2=db_query($sql,$sC->id);
	//$listobj=getListObject1k($result2);					
	//$res=db_query("INSERT INTO {nc_linkshop_goods} (id_Shop, id_Goods, price) VALUES(%d,%d,%d)",$req->idShop,$req->idGoods,$req->price);
	//db_query("UPDATE {nc_listgoods} SET reitingsight='%d', reitingcount='%s' WHERE id=%d",$pr->reitingsight,$strrc,$pr->id);
	//echo json_encode($responce);
	return null;
}

function getListArray1k($result){
		$arr=array();
		while ($obj = db_fetch_array($result)) {
			$arr[]=$obj;
		}
		return $arr;
	}
function getListObject1k($result){
		$arr=array();
		while ($obj = db_fetch_object($result)) {
			$arr[]=$obj;
		}
		return $arr;
	}

function saveAnekdot(){     
   header("Access-Control-Allow-Origin: *");
   $anek=$_REQUEST['anek'];
   saveNodeAnekdotp($anek);
   echo 'imix.by анекдот получен';           
}

function saveNodeAnekdotp($cont){
  //$node = new stdClass();
  //$node->title = Заголовок;
  //$node->teaser = Тизер;
  //$node->body = Тело;
  //$node->uid = 1;-От чьего имени
  //$node->type = 'story'; - тип материала
  //$node->status = 1; - опубликован или нет
  //$node->promote = 1; на главной
  //$node->sticky = 0; прикреплен
  //$node->comment = 2; включить камменты
  //$node->format = 1; // Filtered HTML
  //$node->language = 'ru';
  //$vid = 2; - таксономия словарь
  //$node->taxonomy['tags'][$vid] = Термин таксономии;
  //node_save($node); - сохраняем. 

  $node = new stdClass();
  $node->title = '***';
  //$node->teaser = '';
  $node->created = time();
  $node->changed = $node->created;
  $node->body = $cont;
  $node->uid = 1;
  $node->type = 'anekdot';
  $node->status = 1;
  $node->promote = 0;
  $node->sticky = 0;
  $node->comment = 0;
  $node->format = 2;
  $node->language = 'ru';
  //$vid = 2;
  //$node->taxonomy['tags'][3] = 3;
  node_save($node);
}