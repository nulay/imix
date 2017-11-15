<?php
require_once('ObjectDAO.php');

class CatalogGoodsDAO extends ObjectDAO{

	public function CatalogGoodsDAO() {}
	
	/*ѕолучить список типов товаров*/
	public function getListTypeGoods(){
		$sql='SELECT id, value FROM {nc_cataloggoods} WHERE id_parent=10';		
		$result=db_query($sql);
		$arr=array();
		while ($obj = db_fetch_object($result)) {
			$sql='SELECT id, value FROM {nc_cataloggoods} WHERE id_parent=%d';
			$result2=db_query($sql,$obj->id);
			$obj->listTypeGoods=$this->getListObject($result2);
			$arr[]=$obj;
		}
		return $arr;
	}
	
	//ѕолучение списка категорий по массиву идентификаторов $idArr
	public function getListTypeGoodsFromIdArr($idArr){
		$whereSQL="cg.id=%d";
		$i=1;		
		while ($obj = $idArr[$i++]) {
			$whereSQL.=" OR";
			$par[]=$obj['id'];
			$whereSQL.=" cg.id=%d";
		}		
		$sql='SELECT cg.id, cg.value FROM {nc_cataloggoods} cg WHERE '.$whereSQL;
		$result=db_query($sql,$idArr);		
		return $this->getListObject($result);
	}
	
	public function getListCatalogGoods(){
		$sql='SELECT id, id_parent, value FROM {nc_cataloggoods}';	
		$result=db_query($sql,$idArr);		
		return $this->getListObject($result);
	}
	
	public function getPropForSearch($idCatal){
		$sql="SELECT id, name, type, id_linklist, uper, downer, inners FROM {nc_prhierarchy} WHERE id_Catalog=%d AND usesearch=1";	
		$result=db_query($sql,$idCatal);		
		return $this->getListObject($result);
	}
	
	public function getCategoryFull($idCateg){
		$sql='SELECT * FROM {nc_cataloggoods} WHERE id=%d';	
		$result=db_query($sql,$idCateg);		
		return db_fetch_object($result);
	}
	
	public function getListPrHFull($idCat){
		$sql='SELECT * FROM {nc_prhierarchy} WHERE id_Catalog=%d';	
		$result=db_query($sql,$idCat);		
		return $this->getListObject($result);
	}
	
	//state 0-update, 1-add, 2-delete
	public function redactRazdel($objRazd){		
		if($objRazd->state=='0'){
			$sql='UPDATE {nc_cataloggoods} SET id_parent=%d, name_prop="%s", value="%s", label="%s" WHERE id=%d';		
			$result=db_query($sql,$objRazd->id_parent,$objRazd->name_prop,$objRazd->value,$objRazd->value,$objRazd->id);		
			return $objRazd;
		}
		if($objRazd->state=='1'){
			$sql='INSERT INTO {nc_cataloggoods} (id_parent, name_prop, value, label) VALUES ("%d","%s","%s","%s")';
			$result=db_query($sql,$objRazd->id_parent,$objRazd->name_prop,$objRazd->value,$objRazd->label);
			$objRazd->id = db_last_insert_id("nc_cataloggoods","id");
			return $objRazd; 
		}
		if($objRazd->state=='2'){
			$result=db_query("DELETE FROM {nc_cataloggoods} WHERE id = %d",$objRazd->id);
			//ѕрописать удаление всех свойств в товарах!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
			//и всех товаров
			return $result;
		}
	}
	
	//state 0-update, 1-add, 2-delete
	public function redactCategory($objCat){
		for($i=0;$i<count($objCat->listPr);$i++){
			$objHier=$objCat->listPr[$i];
			if($objHier->state=='0'){
				$this->changePrHier($objHier);
			}
			if($objHier->state=='1'){
				$this->insertPrHier($objHier);
			}
			if($objHier->state=='2'){
				$this->deletePrHier($objHier);
			}
		}
		//$result=db_query($sql,$idCat);		
		return true;
	}
	
	//state 0-update, 1-add, 2-delete
	public function redactPrHier($objPr){		
			if($objPr->state=='0'){
				$this->changePrHier($objPr);
			}
			if($objPr->state=='1'){
				$this->insertPrHier($objPr);
			}
			if($objPr->state=='2'){
				$this->deletePrHier($objPr);
			}
		
		//$result=db_query($sql,$idCat);		
		return true;
	}
	
	public function changePrHier($objHier){
		//db_query("UPDATE {nc_linkshop_goods} SET price=%d WHERE id=%d",$req->price,$idlSG);
		$sql='UPDATE {nc_prhierarchy} SET name="%s", readt=%d, type=%d, id_linklist=%d, uper="%s", inners="%s", downer="%s", usesearch=%d  WHERE id=%d ';
		
		$result=db_query($sql,$objHier->name,$objHier->readt,$objHier->type,$objHier->id_linklist,$objHier->uper,$objHier->inners,$objHier->downer,$objHier->usesearch,$objHier->id);		
		return $result;
	}
	
	public function insertPrHier($objHier){		
		$sql='INSERT INTO {nc_prhierarchy} (id_parent,id_Catalog,name, readt, type, id_linklist, uper, inners, downer, usesearch) VALUES ("%d","%d","%s","%d","%d","%d","%s","%s","%s","%d")';
		$result=db_query($sql,$objHier->id_parent,$objHier->id_Catalog,$objHier->name,$objHier->readt,$objHier->type,$objHier->id_linklist,$objHier->uper,$objHier->inners,$objHier->downer,$objHier->usesearch);
		$result = db_last_insert_id("nc_prhierarchy","id");
		return $result; 
	}
	
	public function deletePrHier($objHier){
		if($objHier->id_parent==0){
			$result=db_query("DELETE FROM {nc_prhierarchy} WHERE id_parent = %d",$objHier->id);
		}
		$result=db_query("DELETE FROM {nc_prhierarchy} WHERE id = %d",$objHier->id);
		//ѕрописать удаление всех свойств в товарах!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
		return $result;
	}
}
?>