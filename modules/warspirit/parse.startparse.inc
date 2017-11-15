<?php

require_once 'simple_html_dom.php';
require_once 'includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

function startParse2(){
          $text = file_get_html('http://kinotavr.by/vprokate');
	// $text =iconv('utf-8','windows-1251',$text);
	$temp=$text->find('.news-item-content') ;
	$str='';
	foreach($temp as $tempk){
	        $str.='<div class="onefilm">';
	         $str.='<div class="title">'.convertUTF($tempk->find('h2 a', 0)->innertext).'</div>';
	         $str.='<div class="time">'.convertUTF($tempk->find('div.news-item-content-date', 0)->innertext).'</div>';
	        $str.='<div class="description">'.($tempk->find('div.news-item-text', 0)->innertext).'</div>';
		
		$str.='</div>';
	}
       //   dpm($temp);
          $text->clear();
         unset($text); 
          return $str;		   
}

function startParseAnekdot(){       
        $text = file_get_html('http://anekdotov.net/anekdot/today.html');
	// $text =iconv('utf-8','windows-1251',$text);
	$temp=$text->find('form[action=./golosui.cgi]',0)->find('div[align=justify]');
	//dpm($temp);
	//$temp=$temp->find('p [align=justify]') ;
	$str='';
	foreach($temp as $tempk){
	   $tempk->find('table',0)->outertext='';
	   $tempk->find('input',0)->outertext='';
	   $tempk->find('font',0)->outertext='';
	   $temps=str_get_html($tempk->outertext);
	   //dpm($tempk->find('a[name]'));
	   if(!$temps->find('a[name]') & !$temps->find('center')){
	       $body = ereg_replace("<a(/?[^\>]+)\>", "", $tempk->outertext);
	       $body = ereg_replace("</a\>", "", $body);
	       $body = ereg_replace("<br\>", "\r\n", $body);
               saveNodeAnekdot($body);
	      // $str.='<div class="an">'.$body.'</div>';
	   }
	   $temps->clear();
           unset($temps);
	   //  saveNodeAnekdot();	 
	}
     
        $text->clear();
        unset($text); 
        $str.='<div><a href="/anekdots">Перейти на список анекдотов</a></div>'; 
        $str.='<div><a href="/admin/content/node">Перейти на страницу редактирования материалов</a></div>';
        return $str;	          
}

function startParseAfisha(){       
          $text = file_get_html('http://kinotavr.by/');
	// $text =iconv('utf-8','windows-1251',$text);
	$str='';
	
	$textt1=$text->find('.tt-panel2');
	//dpm($textt1);
	//if($textt1!=null)
	$str.='<div>';
	$cou=0;
	$allCinema=array();
	foreach($textt1 as $temp){
	    $data=new stdClass();
	    $allFilms=array();
	    $temp2=$temp->find('.news-item');
	  //  dpm($temp2);
	    foreach($temp2 as $temp3){
	       $dataN=new stdClass(); 
	       $dataN->name=convertUTF($temp3->find('.news-item-content a',0)->innertext);
	       $dataN->link=$temp3->find('.news-item-content a',0)->attr['href'];
	       $dataN->img=$temp3->find('.news-item-img img',0)->attr['src'];
	       $dataN->date=convertUTF($temp3->find('.news-item-content-date',0)->innertext);
	       $dataN->discr=convertUTF($temp3->find('.news-item-text',0)->innertext);
	       
	       preg_match('/http:.[^"]*/', $dataN->discr, $dataN->discrs);
	       preg_match('/film\/(.[^\/]*)/', $dataN->discrs[0], $dataN->image);
	       $dataN->image='<img src="http://st.kinopoisk.ru/images/film/'.$dataN->image[1].'.jpg">';
	       $dataN->image1='<img src="http://kinotavr.by/'.$dataN->img.'">';
	       //$dataN->discrs=$dataGG;
		 //  
		 
	        if(!filmIssue($dataN)) {
			saveNodeFilm($dataN);
			$cou+=1;
			//dpm($dataN->name);
		}
	        $allFilms[]=$dataN; 
		   $str.=$dataN->image;
		//$str.=$dataN->image1;
	    }
	    $data->allFilms=$allFilms;
	    $allCinema[]=$data;
	}
	
	$str.='<div>Сохранено '.$cou.' фильмов</div></div>';
	dpm($allCinema);
	
	//$allFilmsS=$text->find('.news-item') ;
	
	
	//foreach($allFilmsS as $temp){
	//   $name=$temp->find('.news-item-content a',0)->innertext;
	//   $allFilms[$name]=array();
	//   $allFilms[$name][name]=convertUTF($temp->find('.news-item-content a',0)->innertext);
	//   $allFilms[$name][img]=$temp->find('.news-item-img img',0)->attr['src'];
	//   $allFilms[$name][date]=convertUTF($temp->find('.news-item-content-date',0)->innertext);
	//   $allFilms[$name][discr]=convertUTF($temp->find('.news-item-text',0)->innertext);
	//   //dpm($temp->find('.news-item-content a'));	    
	//   $str.='<div>'.$allFilms[$name][name].' - '.$allFilms[$name][img].' - '.'</div>';
	//}
	$str.='<br><br><br>';
	//foreach($allFilms as $temp){
	//        $str.='<div><div>'.$temp[name].'</div><div>'.$temp[img].'</div><div>'.$temp[date].'</div><div>'.$temp[discr].'</div></div>';
        // }
	   //   dpm($temp);
          $text->clear();
         unset($text); 
        return $str;	          
}

function startParse(){       
        $str.='<div><a href="startparse/anekdot">Запустить парсер анекдотов</a></div>'; 
        $str.='<div><a href="startparse/afisha">Запустить парсер афишы</a></div>';
      
        return $str;		
}

function filmIssue($film){
          $sql='SELECT n.nid FROM {node} n WHERE title="%s"';
	$result=db_query($sql,$film->name);
	$o=db_fetch_array($result);	
	return $o;
}
function saveNodeFilm($film){
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
$node->title = $film->name;
//$node->teaser = '';
$node->created = time();
$node->changed = $node->created;
$node->body = $film->discr;
$node->uid = 1;
$node->type = 'film';
$node->status = 1;
$node->promote = 0;
$node->sticky = 0;
$node->comment =2;
$node->format = 1;
$node->language = 'ru';
//$vid = 2;
//$node->taxonomy['tags'][3] = 3;
node_save($node);
}


function saveNodeAnekdot($cont){
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
$node->format = 1;
$node->language = 'ru';
//$vid = 2;
//$node->taxonomy['tags'][3] = 3;
node_save($node);
}


function convertUTF($text){
         return iconv('windows-1251','utf-8',$text);
}

function parseIPage(){      
     // dpm($_REQUEST['url']);
	 $urlS=$_REQUEST['cosl'];//'http://anekdotov.net/anekdot/today.html';
	// str_replace('~', '/', $urlS);
      $text = file_get_html($urlS);
	$nums=$_REQUEST['num'];
	  
	// $text =iconv('utf-8','windows-1251',$text);
	$temp=$text->find('form[action=./golosui.cgi]',0)->find('p[align=justify]');
	//dpm($temp);
	//$temp=$temp->find('p [align=justify]') ;
	$str='';
	$allCinema=array();
	foreach($temp as $tempk){
	   $tempk->find('table',0)->outertext='';
	   $tempk->find('input',0)->outertext='';
	   $tempk->find('font',0)->outertext='';
	   $temps=str_get_html($tempk->outertext);
	   //dpm($tempk->find('a[name]'));
	   if(!$temps->find('a[name]') & !$temps->find('center')){
	       $body = ereg_replace("<a(/?[^\>]+)\>", "", $tempk->outertext);
	       $body = ereg_replace("</a\>", "", $body);
	       $body = ereg_replace("<br\>", "\r\n", $body);
         //      saveNodeAnekdot($body);
	      // $str.='<div class="an">'.$body.'</div>';
		  $allCinema[]=$body;
	   }
	   }
	  //dpm($allCinema);
     // $text='sdfsdfsdfsfsdf';  
	 drupal_set_header('Content-type: text/javascript; charset=utf-8');
     echo 'var content'.$nums.'=\''.json_encode($allCinema).'\'';
	 //echo 'var content'.$nums.'=\''.json_encode($allCinema).'\'';
     return null;
}