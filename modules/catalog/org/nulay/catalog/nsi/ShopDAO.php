<?php
require_once('ObjectDAO.php');

class ShopDAO extends ObjectDAO{

	public function ShopDAO() { 
		
	}

	/*Получить список магазинов*/
	public function getListShop(){
		$sql='SELECT id, named FROM {nc_listshops}';
		$result=db_query($sql);
		$arr=$this->getListArray($result);
		return $arr;
	}

	/*Получить всю инфу о магазине по id*/
	public function getShopByID($id){
		$sql='SELECT * FROM {nc_listshops} WHERE id=%d';
		$result=db_query($sql,$id);
		return db_fetch_object($result);
	}

	/*Сохранить магазин*/
	public function saveShop($shopAr){
		if($shopAr['id']!=null){
			//update
			//  UPDATE t1 SET col1 = col1 + 1;
			$sql='UPDATE {nc_listshops} SET named="%s", id_region=%d, adres="%s", uradress="%s", indext="%s", tel="%s", eladres="%s", fname="%s", lname="%s", pname="%s", email="%s", aboutshop="%s", urName="%s", vidWork=%d, unp="%s", svidGosReg="%s", priceDollar="%s", active=%d WHERE id=%d';
			$result=db_query($sql,$shopAr['named'],$shopAr['id_region'],$shopAr['adres'],$shopAr['uradress'],$shopAr['indext'],$shopAr['tel'],$shopAr['eladres'],$shopAr['fname'],$shopAr['lname'],$shopAr['pname'],$shopAr['email'],$shopAr['aboutshop'],$shopAr['urName'],$shopAr['vidWork'],$shopAr['unp'],$shopAr['svidGosReg'],$shopAr['priceDollar'],$shopAr['active'],$shopAr['id']);			
		}else{
			//insert
			$sql='INSERT INTO {nc_listshops} (named, id_region, adres, uradress, indext, tel, eladres, fname, lname, pname, email, aboutshop, urName, vidWork, unp, svidGosReg, active, tel1, tel2, tel3, tel4, tel5, priceDollar,timeWork) VALUES("%s",%d,"%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s",%d,"%s","%s",%d,"%s","%s","%s","%s","%s","%s","%s")';
			$result=db_query($sql,$shopAr['named'],$shopAr['id_region'],$shopAr['adres'],$shopAr['uradress'],$shopAr['indext'],$shopAr['tel'],$shopAr['eladres'],$shopAr['fname'],$shopAr['lname'],$shopAr['pname'],$shopAr['email'],$shopAr['aboutshop'],$shopAr['urName'],$shopAr['vidWork'],$shopAr['unp'],$shopAr['svidGosReg'],$shopAr['active'],$shopAr['tel1'],$shopAr['tel2'],$shopAr['tel3'],$shopAr['tel4'],$shopAr['tel5'],$shopAr['priceDollar'],$shopAr['timeWork']);
			$result = db_last_insert_id("nc_listshops","id");
		}		
		return $result;
	}
	
	public function setUserForShop($idShop,$idUser){
		$sql='INSERT INTO {nc_linkuser_shop} (id_user, id_shop) VALUES(%d,%d)';
		$result=db_query($sql,$idShop,$idUser);
		return $result;	
	}

	public function createINSERT($array){
		$arrayK=array_keys($array);
		for($i=0;$i<count($arrayK);$i++){
			$arrayK[$i];
		}
	}

	public function createUPDATE(){
	}
	
	//цену конкретного товара в конкретном магазине
	public function getPriceGoodsInShop($idGoods, $idShop){
		$sql='SELECT price FROM {nc_linkshop_goods} WHERE id_goods=%d AND id_Shop=%d';
		$result=db_query($sql, $idGoods, $idShop);
		return db_fetch_object($result);
	}
	
	//магазины управляемые конкретным пользователем
	public function getShopForUser($id_user){
		$sql='SELECT id_shop FROM {nc_linkuser_shop} WHERE id_user=%d';
		$result=db_query($sql, $id_user);
		return $this->getListObject($result);
	}
	
	//количество продуктов в магазине
	public function getCountGoodsInShop($idShop){
		$sql='SELECT count(*) count FROM {nc_linkshop_goods} WHERE id_shop=%d';
		$result=db_query($sql, $idShop);
		return db_fetch_object($result);
	}
	
    //активные магазины продающие конкретный товар
	public function getListShopInGoods($idGoods,$full=false){
		$sql='SELECT sh.id, sh.named name, sh.tel tel, lshg.price price';
		if($full) $sql.=', sh.eladres eladres, sh.email email, lshg.description description';
		$sql.=' FROM {nc_listshops} sh INNER JOIN {nc_linkshop_goods} lshg on lshg.id_Shop=sh.id WHERE lshg.id_goods=%d  AND sh.active=1';
		$result=db_query($sql, $idGoods);
		return $this->getListObject($result);
	}
}
?>