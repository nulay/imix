<?php
require_once('ObjectDAO.php');

class CompanyDAO extends ObjectDAO{

	public function CompanyDAO() {}
	
	/*Получить список типов товаров*/
	public function getListCompany(){
		$sql='SELECT * FROM {nc_company} ORDER BY name';		
		$result=db_query($sql);		
		return $this->getListObject($result);
	}
	
	/*Получить список типов по списку категорий*/
	public function getListCompanyInCatalog($lostIdCat){
		$wr='';
		for($i=0;$i<count($lostIdCat);$i++){
			if($i!=0) $wr.=' OR ';
			$wr.='id_catalog=%d';
		}
		$sql='SELECT * FROM {nc_company} c
              LEFT JOIN {nc_listgoods} lg ON lg.id_company=c.id
              WHERE '.$wr.' GROUP BY c.id ORDER BY c.name';
		$result=db_query($sql,$lostIdCat);		
		return $this->getListObject($result);	  
	}
	
	public function getListCompanyByEx($str){		
		$sql='SELECT * FROM {nc_company} c             
              WHERE name LIKE "%%%s%%" ORDER BY c.name';
		$result=db_query($sql,$str);		
		return $this->getListObject($result);	  
	}
	
	//достает компании производители из конкретной категории товара дублирует getListCompanyInCatalog только сокращенно
	public function getListAllCompanyByCatalog($listId_comp){	
	    $wr='';
        for($i=0;$i<count($listId_comp);$i++){
			if($i!=0) $wr.=' OR ';
			$wr.='lg.id_catalog=%d';
		}	
		$sql='SELECT lg.id_company, c.name FROM {nc_listgoods} lg JOIN {nc_company} c ON c.id=lg.id_company WHERE '.$wr.' GROUP BY c.id ORDER BY c.name';
		$result=db_query($sql,$listId_comp);		
		return $this->getListObject($result);	  
	}
}