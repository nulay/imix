<?php
require_once('ObjectDAO.php');

class GoodsDAO extends ObjectDAO{
	public function ShopDAO() { 
		
	}
	
	public function getGlobalSearch($lS,$sC){	        
		$result=db_query("SELECT st.id ".$this->getSqlForGlSerch($lS),$lS);						
		return $this->getAllGFromId($result,$sC);			
	}
	
	public function getSqlForGlSerch($lS){
	    $sql='FROM (SELECT lg.id, concat(cg.value," ",c.name," ", lg.model) model FROM {nc_listgoods} lg INNER JOIN {nc_company} c on c.id=lg.id_company INNER JOIN {nc_cataloggoods} cg on cg.id=lg.id_catalog) st WHERE ';	
		for($i=0;$i<count($lS);$i++){
		   if($i!=0) $sql.='AND ';
		      $sql.='st.model LIKE "%%%s%%" ';
		}	        
		return $sql;
	}
	
	//Возвращает список товаров в конкретном каталоге, без свойств.
	public function getGoodsFromCatalog($id_catalog){		
	    $sql='SELECT lg.id, lg.id_company, c.name, lg.model FROM {nc_listgoods} lg INNER JOIN {nc_company} c on c.id=lg.id_company WHERE lg.id_catalog=%d';		
		$obj=$this->getListObject(db_query($sql,$id_catalog));
		return $obj; 
	}
	
	public function getCountGoodsForGlSerch($lS,$forRed=true){         
		$query=db_query("SELECT count(*) ".$this->getSqlForGlSerch($lS,true,!$forRed),$lS);
		return db_result($query);    	
	}	
	
	public function getAllGFromId($resultId,$sC){
	    $par=array();
	    $objs = db_fetch_array($resultId);//избавимся от лишних проверак внутри цикла
		$par[]=$objs['id'];
		$whereSQL.="lg.id=%d";
		while ($obj = db_fetch_array($resultId)) {
			$whereSQL.=" OR";
			$par[]=$obj['id'];
			$whereSQL.=" lg.id=%d";
		}				
		$sql='SELECT lg.id, cg.id id_catalog, cg.value type, c.name company, lg.model model, lsh.min min, lsh.max max, lsh.count count, cg.label,(CASE WHEN min IS NULL THEN 1 ELSE 0 END) weight1 FROM {nc_listgoods} lg INNER JOIN {nc_company} c ON c.id=lg.id_company INNER JOIN {nc_cataloggoods} cg ON lg.id_catalog=cg.id LEFT JOIN (SELECT MIN(lsg.price) min, MAX(lsg.price) max, count(lsg.id) count, lsg.id_goods FROM {nc_linkshop_goods} lsg INNER JOIN {nc_listshops} sh ON sh.id=lsg.id_shop WHERE active=1 GROUP BY id_goods) lsh ON lsh.id_goods=lg.id WHERE ('.$whereSQL.') '.(($sC->orderName=='min')?' AND min IS NOT NULL':'')." ORDER BY ".((db_escape_string($sC->orderName)=='min')?"":"weight1, ").db_escape_string($sC->orderName).(($sC->sord)?" ASC":" DESC");		
		$result=db_query_range($sql,$par,($sC->numPage-1)*$sC->countElInP,$sC->countElInP);		
		return $this->getListArray($result);
	}

	//список товаров для редактирвоания прайса
	public function getListGoodsForRedPrice($sC){
		//print_r('sdfsdf');	
		$par= $this->getArrayParameters($sC);
		$result=db_query("SELECT lg.id ".$this->createQuery($sC,true,false),$par);
		$par=array();		
		$obj = db_fetch_array($result);//избавимся от лишних проверак внутри цикла
		$par[]=$obj['id'];
		$whereSQL.="lg.id=%d";
		while ($obj = db_fetch_array($result)) {
			$whereSQL.=" OR";
			$par[]=$obj['id'];
			$whereSQL.=" lg.id=%d";
		}
			
		$sql='SELECT lg.id, cg.value type, c.name company, lg.model model FROM {nc_listgoods} lg INNER JOIN {nc_company} c ON c.id=lg.id_company INNER JOIN {nc_cataloggoods} cg ON lg.id_catalog=cg.id WHERE ('.$whereSQL.') ORDER BY '.db_escape_string($sC->orderName).(($sC->sord)?" ASC":" DESC");
		
		$result=db_query_range($sql,$par,($sC->numPage-1)*$sC->countElInP,$sC->countElInP);
		return $this->getListArray($result);
	}
	
	/*Получить список товаров по критерию*/
	public function getListGoodsModel($sC){ 
		$par= $this->getArrayParameters($sC);		
		$result=db_query("SELECT lg.id, lg.model ".$this->createQuery($sC),$par);
		return $this->getListArray($result);	
	}
	
	/*Получить список товаров по критерию*/
	public function getListGoods($sC){ 
		$par= $this->getArrayParameters($sC);		
		$result=db_query("SELECT lg.id ".$this->createQuery($sC),$par);
		return $this->getAllGFromId($result,$sC);	
	}
	
	public function getCountGoods($sC,$forRed=false){ 
	   //print_r($this->createQuery($sC,false));
		$par= $this->getArrayParameters($sC);		
		$query=db_query("SELECT COUNT(*) ".$this->createQuery($sC,false,!$forRed),$par);	
		return db_result($query);    	
	}

	public function getGoodsF($id){
		$goods=$this->getGoods($id);
		$goods->listPr=$this->getPropertiesGoods($id);
		$goods->listRootPr=$this->getRootPropertiesGoods($goods->listPr);
		$goods->listSh=$this->getListShop($id);
		return $goods; 
	}
	
	public function interpritateProp($goods){
		if(isset($goods['label'])){
			$listPrG=$this->getPropertiesGoods($goods['id']);             			
			$op=json_decode($goods['label'],true);             		
			$strB='';		   
			for($i=0;$i<count($op);$i++){
				//dpm();	
				$strB.=$this->getStringPropD($op[$i],$listPrG);
				//dpm($strB);
			}
			return $strB;
		}else  
		return 'не параметризованы';		
	}
	
	public function interpritatePropOb($goods){
		if(isset($goods->label)){
			$listPrG=$this->getPropertiesGoods($goods->id);             			
			$op=json_decode($goods->label,true);             		
			$strB='';		   
			for($i=0;$i<count($op);$i++){
				//dpm();	
				$strB.=$this->getStringPropD($op[$i],$listPrG);
				//dpm($strB);
			}
			return $strB;
		}else  
		return 'не параметризованы';		
	}
	
	public function getStringPropD($g,$listPrG){
		$key=array_keys($g);        		
		switch($key[0]){
				case 'string': return $g[$key[0]]; 
				case 'param': $fg=$this->getPropertyInPrH($listPrG, $g[$key[0]]); return $fg['value'];
				case 'name': $fg=$this->getPropertyInPrH($listPrG, $g[$key[0]]); return $fg['name'];
				case 'ifparam': $fg=$this->getPropertyInPrH($listPrG, $g[$key[0]]); if($fg['value']=="Да"){return $fg['name'];} else return "";
                case 'arrB': $strB="";
				             $p=0;
				            for($i=0;$i<count($g[$key[0]]);$i++){
								$str=$this->getStringPropD($g[$key[0]][$i],$listPrG);
								if($p!=0 & $str!='') $str=', '.$str;
								if($str!='') $p++;								
								$strB.=$str;
				            } return $strB;
                case 'arrK': $strB="";
				            for($i=0;$i<count($g[$key[0]]);$i++){
								$str=$this->getStringPropD($g[$key[0]][$i],$listPrG);
								if($str=="") return "";								
				               	$strB.=$str;
				            } return $strB;							
		}	
		return "";
	}
	
	public function getPropertyInPrH($listPrG,$id){
		for($i=0;$i<count($listPrG);$i++){
			if($listPrG[$i]['id_PrHierar']==$id) return $listPrG[$i];
		}
		return null;
	}

	/*Получить конкретный товар*/
	public function getGoods($id){
		$sql='SELECT lg.id, cg.id id_catalog, cg.label, cg.value type, c.name company, lg.model, lsh.min, lsh.max, lsh.count, lg.reitingcount FROM {nc_listgoods} lg INNER JOIN {nc_company} c ON c.id=lg.id_company INNER JOIN {nc_cataloggoods} cg ON lg.id_catalog=cg.id LEFT JOIN (SELECT MIN(lsg.price) min, MAX(lsg.price) max, count(lsg.id) count, lsg.id_goods FROM {nc_linkshop_goods} lsg INNER JOIN {nc_listshops} sh ON sh.id=lsg.id_shop WHERE active=1 GROUP BY id_goods) lsh ON lsh.id_goods=lg.id WHERE lg.id=%d';
		$result=db_query($sql,$id);
		return db_fetch_object($result);
	}

	public function getPropertiesGoods($idGoods){
		$sql='SELECT prH.name, gp.value, gp.id_PrHierar, prH.id_parent FROM {nc_goodsproperty} gp LEFT JOIN {nc_prhierarchy} prH ON prH.id=gp.id_PrHierar WHERE gp.id_Goods=%d';
		$result=db_query($sql,$idGoods);		
		return $this->getListArray($result);
	}
	
	public function getRootPropertiesGoods($listPr){
		$ar=array();
		foreach ($listPr as $obj){
			$ar[]=$obj['id_parent'];
		}
		$ar=array_values(array_unique($ar));
		$strB='prH.id='.$ar[0];
		for($i=1;$i<count($ar);$i++){
			$strB.=' OR prH.id='.$ar[$i];
		}
		//print_r($ar);
		//prH.id=%d
		$sql='SELECT prH.id, prH.name FROM {nc_prhierarchy} prH WHERE '.$strB;
		$result=db_query($sql,$idGoods);		
		return $this->getListArray($result);
	}

	public function getListShop($idGoods){
		$sql='SELECT sh.named, lsh.price FROM {nc_listshops} sh INNER JOIN {nc_linkshop_goods} lsh ON lsh.id_shop=sh.id WHERE lsh.id_Goods=%d';
		$result=db_query($sql,$idGoods);
		return $this->getListArray($result);
	}
	
	//$dp1->type=1; $dp1->idPrH=154; $dp1->val1=154; $dp1->val1=null; 
	//$dp2->type=1; $dp2->idPrH=155; $dp2->val1=155; $dp2->val1=null; 
	//$dp3->type=1; $dp3->idPrH=156; $dp3->val1=156; $dp3->val1=null; 
	//$dp4->type=1; $dp4->idPrH=157; $dp4->val1=157; $dp4->val1=null; 
	//$sC->listIdCatG=array(10, 11, 12); $sC->listIdComp=array(5,6); $sC->idShop=6; $sC.listDopProp=array($dp2, $dp3, $dp1, $dp4); $sC->orderNum='priceMin';
	//$sC->sord=true;$sC->numPage=1;$sC->counElInP=50;
	public function createQuery($sC,$keyGr=true,$activeShop=true){
		$outsql="";
		$conditionFound = false;       
		if($sC->listIdCatG!=null && count($sC->listIdCatG)!=0){
			$outsql.=" (";
			for($i=0;$i<count($sC->listIdCatG);$i++){
				if($i!=0) $outsql.=" OR ";
				$outsql.="lg.id_catalog=%d";
			}
			$outsql.=" ) ";
			$conditionFound=true;
		}
		
		if($sC->model!=null){
		    if ($conditionFound) $outsql.="AND (";
			else $outsql.=" (";
			$outsql.="lg.model LIKE '%%".db_escape_string($sC->model)."%%'";
			$outsql.=") ";
			$conditionFound=true;
		}
		
		if($sC->listIdComp!=null && count($sC->listIdComp)!=0){
			if ($conditionFound) $outsql.="AND (";
			else $outsql.=" (";
			for($i=0;$i<count($sC->listIdComp);$i++){
				if($i!=0) $outsql.=" OR ";
				$outsql.="lg.id_company=%d";
			}
			$outsql.=") ";
			$conditionFound=true;
		}

		if($sC->idShop!=null || $sC->minPrice!=null || $sC->maxPrice!=null){
			if ($conditionFound) $outsql.="AND (";
			else $outsql.="(";
			$conditionFoundLSGF = false;
			//от до
			if($sC->minPrice!=null & $sC->maxPrice!=null){
				$outsql.="(lsg.price BETWEEN %d AND %d)".(($activeShop)?" AND sh.active=1 ":" ");
				$conditionFoundLSGF=true;
			}else{
				//от
				if($sC->minPrice!=null){
					$outsql.="lsg.price>=%d".(($activeShop)?" AND sh.active=1 ":" ");
					$conditionFoundLSGF=true;
				}
				//до
				if($sC->maxPrice!=null){
					$outsql.="lsg.price<=%d".(($activeShop)?" AND sh.active=1 ":" ");
					$conditionFoundLSGF=true;
				}
			}
			if($sC->idShop!=null){
				if ($conditionFoundLSGF) $outsql.="AND ";
				$outsql.="lsg.id_Shop=%d".(($activeShop)?" AND sh.active=1 ":" ");
				$conditionFoundLSGF=true;
			}
			$outsql.=") ";
			$conditionFound=true;
		}

		if($sC->listDopProp!=null && count($sC->listDopProp)!=0){
			if ($conditionFound) $outsql.="AND (";
			else $outsql.="(";
			$conditionFoundGP = false;
			for($i=0;$i<count($sC->listDopProp);$i++){
				$dP=$sC->listDopProp[$i];
				if ($conditionFoundGP) $outsql.="AND (";
				else $outsql.="(";
				$outsql.=ElementSearch::getElementSearch($dP->type)->getElementsSQLSearch($i,$dP);
				$outsql.=") ";
				$conditionFoundGP=true;
			}
			$outsql.=") ";
			$conditionFound=true;
		}
		//   qs.insert(0," ORDER BY "+sC.getOrderNum()+" "+((sC.isSord())?"ASC ":"DESC "));
		$oSql.=($conditionFound)?" WHERE ":" ";
		if($sC->listDopProp && count($sC->listDopProp)>0){
			for($i=0;$i<count($sC->listDopProp);$i++){               
				$oSql=" INNER JOIN {nc_goodsproperty} gp".$i." ON gp".$i.".id_goods=lg.id".$oSql;               
			}
		}
		if($sC->idShop!=null | $sC->minPrice!=null | $sC->maxPrice!=null){            
			$oSql=" INNER JOIN {nc_linkshop_goods} lsg ON lsg.id_Goods=lg.id ".(($activeShop)?" INNER JOIN {nc_listshops} sh ON lsg.id_shop=sh.id  ":" ").$oSql;     
		}
		if($sC->orderName=='min'){            
			$oSql=" INNER JOIN (SELECT id_goods FROM {nc_linkshop_goods} WHERE price IS NOT NULL GROUP BY id_goods) lsh ON lg.id = lsh.id_goods ".$oSql;
		}	
		$oSql="FROM {nc_listgoods} lg ".$oSql; 		
		$oSqlGB='';
		if(($sC->idShop!=null | $sC->minPrice!=null | $sC->maxPrice!=null | ($sC->listDopProp && count($sC->listDopProp)>0)) & $keyGr){   
		  $oSqlGB=" GROUP BY lg.id";
		}
		//dpm($oSql.$outsql.$oSqlGB);
		//print_r($oSql.$outsql.$oSqlGB);
		return $oSql.=$outsql.$oSqlGB;		
	}
	
	protected function getArrayParameters($sC){
		$par=array();
		if($sC->listIdCatG!=null && count($sC->listIdCatG)!=0){
			for($i=0;$i<count($sC->listIdCatG);$i++){
				$par[]=$sC->listIdCatG[$i];
			}
		}
		if($sC->listIdComp!=null && count($sC->listIdComp)!=0){
			for($i=0;$i<count($sC->listIdComp);$i++){
				$par[]=$sC->listIdComp[$i];
			}
		}
		if($sC->minPrice!=null) $par[]=$sC->minPrice;
		if($sC->maxPrice!=null) $par[]=$sC->maxPrice;
		if($sC->idShop!=null) $par[]=$sC->idShop;
		
		return $par;
	}
	
	public function savePrice($req){		
		$result=db_query("SELECT id FROM {nc_linkshop_goods} WHERE id_Shop=%d AND id_Goods=%d",$req->idShop,$req->idGoods);
		$idlSG=db_fetch_object($result);
		$idlSG=$idlSG->id;
		
		$res=true;
		if($idlSG){
			if($req->price==""){
				$res=db_query("DELETE FROM {nc_linkshop_goods} WHERE id = %d",$idlSG);
			}else{				
				$res=db_query("UPDATE {nc_linkshop_goods} SET price=%d WHERE id=%d",$req->price,$idlSG);
			}
		}else{
			if($req->price!=""){
				$res=db_query("INSERT INTO {nc_linkshop_goods} (id_Shop, id_Goods, price) VALUES(%d,%d,%d)",$req->idShop,$req->idGoods,$req->price);
			}
		}
		return $res;
	}
	
	//метод для сохранения товара не понятный метод
	public function saveGoods($goods){		    
	    if($goods->id!=null){			
			//$res=db_query("UPDATE {nc_listgoods} SET id_hyst=true WHERE id=%d",$goods->id);			
			$result=db_query("SELECT * FROM {nc_listgoods} WHERE WHERE id=%d",$goods->id);
		    $goodsF=db_fetch_object($result);
            if($goodsF->id_parent==null) $goodsF->id_parent=$goods->id;		
			$goodsF->id_catalog=$goods->id_catalog;
			$goodsF->id_company=$goods->id_company;
			$goodsF->model=$goods->model;
			$goods=$goodsF;
		}		
		$this->saveOrUpdateGoods($goods);
	}
	
	//метод для сохранения или обновления товара
	public function saveOrUpdateGoods($goods){		
		//$result=db_query("SELECT id FROM {nc_linkshop_goods} WHERE id_Shop=%d AND id_Goods=%d",$req->idShop,$req->idGoods);
		//$idlSG=db_fetch_object($result);
		//$idlSG=$idlSG->id;
				
		//$res=true;
		if($goods->id==null){
			$res=db_query("INSERT INTO {nc_listgoods} (id_catalog, id_company, model) VALUES(%d,%d,'%s')",$goods->id_catalog,$goods->id_company,$goods->model);	
			$goods->id=db_last_insert_id("nc_listgoods","id");
//dpm($res);			
		}else{
			//$res=db_query("UPDATE {nc_listgoods} SET id_catalog=%d, id_company=%d, model='%s' WHERE id=%d",$goods->id_catalog,$goods->id_company,$goods->model,$goods->id);
		}
		foreach($goods->listPr as $p=>$v){
			$this->savePrGoods($v,$goods->id);
		}
		return $goods;
	}
	
	public function savePrGoods($pr,$idG){
		//dpm($idG);
		if($idG!=null){
			$result=db_query("SELECT id FROM {nc_goodsproperty} WHERE id_Goods=%d AND id_PrHierar=%d",$idG,$pr->id_PrHierar);
		    $idlSG=db_fetch_object($result);			
			if($idlSG->id!=null){
			     $res=db_query("UPDATE {nc_goodsproperty} SET value='%s' WHERE id=%d",$pr->value,$idlSG->id);
				 return;
			}
		}
		$res=db_query("INSERT INTO {nc_goodsproperty} (id_Goods, id_PrHierar, value) VALUES(%d,%d,'%s')",$idG,$pr->id_PrHierar,$pr->value);
	}
	
	//метод сохраняет счетчик посещений за месяц.
	public function saveReiting($pr,$strrc){
		db_query("UPDATE {nc_listgoods} SET reitingsight='%d', reitingcount='%s' WHERE id=%d",$pr->reitingsight,$strrc,$pr->id);
	}	
}
?>