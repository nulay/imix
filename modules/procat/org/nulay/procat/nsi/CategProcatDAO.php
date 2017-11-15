<?php
require_once('ObjectDAO2.php');

class CategProcatDAO extends ObjectDAO{

	public function CategProcatDAO() {}
	
	/*Получить список типов товаров проката*/
	public function getListType(){
		$sql='SELECT id, value FROM {np_cataloggoodsprocat} WHERE id_parent=1';		
		$result=db_query($sql);
		$arr=array();
		while ($obj = db_fetch_object($result)) {
			$sql='SELECT id, value FROM {np_cataloggoodsprocat} WHERE id_parent=%d';
			$result2=db_query($sql,$obj->id);
			$obj->listTypeGoods=$this->getListObject($result2);
			$arr[]=$obj;
		}
		return $arr;
	}
	
	//Получение списка категорий по массиву идентификаторов $idArr
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
}
?>