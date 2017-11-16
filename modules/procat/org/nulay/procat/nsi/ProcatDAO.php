<?php
require_once('ObjectDAO2.php');

class ProcatDAO extends ObjectDAO{

	public function ProcatDAO() {}
	
	//сохранить товар проката
	public function saveProcatData($pD){
	   if($pD['id']!=null){
	       $res=db_query('UPDATE {np_listgoods} SET id_procat=%d, id_catalog=%d, company="%s", model="%s", keyword="%s", additiontext="%s", priceday=%d, priceweek=%d, pricemonth=%d, priceestimated=%d WHERE id=%d',$pD['id_procat'], $pD['idcateg'],$pD['company'],$pD['model'],$pD['keyword'],$pD['additiontext'],$pD['priceday'],$pD['priceweek'],$pD['pricemonth'],$pD['priceestimated'],$pD['id']);
		   $this->savePropertyProcatData($pD);
	   }else{
	       $res=db_query('INSERT INTO {np_listgoods} (id_procat, id_catalog, company, model, keyword, additiontext, priceday, priceweek, pricemonth, priceestimated) VALUES (%d,%d,"%s","%s","%s","%s",%d,%d,%d,%d)',$pD['id_procat'], $pD['idcateg'],$pD['company'],$pD['model'],$pD['keyword'],$pD['additiontext'],$pD['priceday'],$pD['priceweek'],$pD['pricemonth'],$pD['priceestimated']);
		   $pD['id']=db_last_insert_id("np_listgoods","id");;
		   $this->savePropertyProcatData($pD);		   
	   }
	   return $pD;
	}
	
	public function removeGoods($idG){
	   if($idG==null) return false;
       $res=db_query('DELETE FROM {np_goodsproperty} WHERE id_goods=%d',$idG);	
       if(!res)	return res;   
	   $res=db_query('DELETE FROM {np_listgoods} WHERE id=%d',$idG);	   
	   return res;		
	}	
	
	//сохранить свойства товара проката
	public function savePropertyProcatData($pD){
	      $res=db_query('DELETE FROM {np_goodsproperty} WHERE id_goods = %d',$pD['id']);
		  for($i=0;$i<count($pD['prop']);$i++){
		      $res=db_query('INSERT INTO {np_goodsproperty} (id_goods, property, value) VALUES (%d,"%s","%s")',$pD['id'],$pD['prop'][$i],$pD['value'][$i]);
		  }
	}
	
	//сохранить или обновить данные о пракате.
	public function saveProcat($shopAr){
		if($shopAr['id']!=null){
			$sql='UPDATE {np_listprocat} SET named="%s", adres="%s", uradress="%s", indext="%s", tel="%s", eladres="%s", fname="%s", lname="%s", pname="%s", email="%s", aboutshop="%s", urName="%s", vidWork=%d, unp="%s", svidGosReg="%s", active=%d WHERE id=%d';
			$result=db_query($sql,$shopAr['named'],$shopAr['adres'],$shopAr['uradress'],$shopAr['indext'],$shopAr['tel'],$shopAr['eladres'],$shopAr['fname'],$shopAr['lname'],$shopAr['pname'],$shopAr['email'],$shopAr['aboutshop'],$shopAr['urName'],$shopAr['vidWork'],$shopAr['unp'],$shopAr['svidGosReg'],$shopAr['active'],$shopAr['id']);			
		}else{
			//insert
			$sql='INSERT INTO {np_listprocat} (named, adres, uradress, indext, tel, eladres, fname, lname, pname, email, aboutshop, urName, vidWork, unp, svidGosReg, active, tel1, tel2, tel3, tel4, tel5, timeWork) VALUES("%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s",%d,"%s","%s",%d,"%s","%s","%s","%s","%s","%s")';
			$result=db_query($sql,$shopAr['named'],$shopAr['adres'],$shopAr['uradress'],$shopAr['indext'],$shopAr['tel'],$shopAr['eladres'],$shopAr['fname'],$shopAr['lname'],$shopAr['pname'],$shopAr['email'],$shopAr['aboutshop'],$shopAr['urName'],$shopAr['vidWork'],$shopAr['unp'],$shopAr['svidGosReg'],$shopAr['active'],$shopAr['tel1'],$shopAr['tel2'],$shopAr['tel3'],$shopAr['tel4'],$shopAr['tel5'],$shopAr['timeWork']);
			$result = db_last_insert_id("np_listprocat","id");
		}		
		return $result;
    }
	
	//установить конкретного пользователя $idUser для проката с id $idShop
	public function setUserForProcat($idShop,$idUser){
		$sql='INSERT INTO {np_linkuser_procat} (id_user, id_procat) VALUES(%d,%d)';
		$result=db_query($sql,$idShop,$idUser);
		return $result;	
	}
	
	//получить все прокаты управляемые пользователем c id $id_user
	public function getProcatForUser($id_user){
		$sql='SELECT id_procat FROM {np_linkuser_procat} WHERE id_user=%d';
		$result=db_query($sql, $id_user);
		return $this->getListObject($result);
	}
	
	//получить все прокаты управляемые пользователем c id $id_user
	public function isUserManagerProcat($id_user,$idProcat){
		$sql='SELECT id_procat FROM {np_linkuser_procat} WHERE id_user=%d AND id_procat=%d';
		$result=db_query($sql, $id_user, $idProcat);		
		return db_fetch_array($result);
	}
	
	//получить всю информацию о прокате с id=$id
	public function getProcatByIDA($id){
		$sql='SELECT * FROM {np_listprocat} WHERE id=%d';
		$result=db_query($sql,$id);
		return db_fetch_array($result);
	}
	
	//получить всю информацию о прокате с id=$id
	public function getProcatByID($id){
		$sql='SELECT * FROM {np_listprocat} WHERE id=%d';
		$result=db_query($sql,$id);
		return db_fetch_object($result);
	}
	
	//получить количество товаров в прокате с id=$idProcat
	public function getCountGoodsInProcat($idProcat){
		$sql='SELECT count(*) count FROM {np_listgoods} WHERE id_procat=%d';
		$result=db_query($sql, $idProcat);
		return db_fetch_object($result);
	}
	
	//получить товар по id
	public function getGoodsById($id){
		$sql='SELECT * FROM {np_listgoods} WHERE id=%d';
		$result=db_query($sql, $id);
		$obj=db_fetch_array($result);
		if($obj['Id']==null) return null;
		$obj[listPr]=$this->getGoodsPropById($id);		
		return $obj;
	}
	
	//получить свойства товара по id
	public function getGoodsPropById($id){
		$sql='SELECT * FROM {np_goodsproperty} WHERE id_goods=%d';
		$result=db_query($sql, $id);
		return $this->getListArray($result);
	}
	
	//-----------------------------------Функции поиска товаров проката---------------------------
	
	public function getListGoods($sC){ 
	   //print_r($this->createQuery($sC,false));
		$par= $this->getArrayParameters($sC);		
		$sql="SELECT * ".$this->createQuery($sC);		
        $query=db_query_range($sql,$par,($sC->numPage-1)*$sC->countElInP,$sC->countElInP);		
		return $this->getListArray($query);    	
	}
	
	public function getCountGoods($sC){ 
	   //print_r($this->createQuery($sC,false));
		$par= $this->getArrayParameters($sC);		
		//print_r("SELECT COUNT(*) ".$this->createQuery($sC));
		$query=db_query("SELECT COUNT(*) ".$this->createQuery($sC),$par);	
		return db_result($query);    	
	}
	
	protected function getArrayParameters($sC){
		$par=array();
		if($sC->listIdProcat!=null && count($sC->listIdProcat)!=0){
			for($i=0;$i<count($sC->listIdProcat);$i++){
				$par[]=$sC->listIdProcat[$i];
			}
		}
		if($sC->listIdCateg!=null && count($sC->listIdCateg)!=0){
			for($i=0;$i<count($sC->listIdCateg);$i++){
				$par[]=$sC->listIdCateg[$i];
			}
		}		
		if($sC->minPrice!=null) $par[]=$sC->minPrice;
		if($sC->maxPrice!=null) $par[]=$sC->maxPrice;
		return $par;
	}
	
	public function createQuery($sC){
		$conditionFound = false;         
		if($sC->listIdProcat!=null && count($sC->listIdProcat)!=0){
			$outsql.=" (";
			for($i=0;$i<count($sC->listIdProcat);$i++){
				if($i!=0) $outsql.=" OR ";
				$outsql.="lg.id_procat=%d";
			}
			$outsql.=" ) ";
			$conditionFound=true;
		}
				
		if($sC->listIdCateg!=null && count($sC->listIdCateg)!=0){
			if ($conditionFound) $outsql.="AND (";
			else $outsql.=" (";
			for($i=0;$i<count($sC->listIdCateg);$i++){
				if($i!=0) $outsql.=" OR ";
				$outsql.="lg.id_catalog=%d";
			}
			$outsql.=") ";
			$conditionFound=true;
		}
		
		if($sC->company!=null){
		    if ($conditionFound) $outsql.="AND (";
			else $outsql.=" (";
			$outsql.="lg.company LIKE '%%".db_escape_string($sC->company)."%%'";
			$outsql.=") ";
			$conditionFound=true;
		}
		
		if($sC->model!=null){
		    if ($conditionFound) $outsql.="AND (";
			else $outsql.=" (";
			$outsql.="lg.model LIKE '%%".db_escape_string($sC->model)."%%'";
			$outsql.=") ";
			$conditionFound=true;
		}

		if($sC->minPrice!=null || $sC->maxPrice!=null){
			if ($conditionFound) $outsql.="AND (";
			else $outsql.="(";
			$conditionFoundLSGF = false;
			//от до
			if($sC->minPrice!=null & $sC->maxPrice!=null){
				$outsql.="(lg.priceday BETWEEN %d AND %d)".(($sC->activeShop)?" AND lp.active=1 ":" ");
				$conditionFoundLSGF=true;
			}else{
				//от
				if($sC->minPrice!=null){
					$outsql.="lg.priceday>=%d".(($sC->activeShop)?" AND lp.active=1 ":" ");
					$conditionFoundLSGF=true;
				}
				//до
				if($sC->maxPrice!=null){
					$outsql.="lg.priceday<=%d".(($sC->activeShop)?" AND lp.active=1 ":" ");
					$conditionFoundLSGF=true;
				}
			}			
			$outsql.=") ";
			$conditionFound=true;
		}

		if($sC->minPrice!=null | $sC->maxPrice!=null){            
			$oSql=" INNER JOIN {np_listprocat} lp ON lg.id_procat=lp.id ";     
		}
		//print_r("FROM {np_listgoods} lg ".$oSql."WHERE ".$outsql);
		return "FROM {np_listgoods} lg ".$oSql."WHERE ".$outsql;
	}
}
?>