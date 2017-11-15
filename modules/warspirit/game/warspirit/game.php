<?php
//error 001 - ошибка передачи json запроса - json==null
//error 002 - ошибка работы с json либо он не верный либо такого метода в json запросе не существует
//error 003 - ошибка регистрации пользователя, попытка обращения в систему без авторизации
//error 004 - ошибка игровой пользователь еще не создан 

//mh-изменение хелсов, eh-изменение хелсов врага, re-изменение энергона, rp-изменение позитрона, ue-изменение энергона у врага, up-изменение позитрона у врага, 
//ae-изменение аккумулирования энергона на кол единиц, ap-изменение аккумулирования позитрона на кол единиц, aew-изменение аккумулирования энергона на кол единиц у врага,
//apw-изменение аккумулирования позитрона на кол единиц у врага
//uroven-уровень карты, tp-тип поражения (1-спереди, 2-сверху, 3-глобальное)
$arrCart=array (
array("name"=>"выстрел","mh"=>0,"eh"=>-5,"re"=>5,"rp"=>0,"ue"=>0,"up"=>0,"ae"=>0,"ap"=>0,"aew"=>0,"apw"=>0,"tp"=>1,"uroven"=>1),
array("name"=>"граната","mh"=>0,"eh"=>-10,"re"=>10,"rp"=>0,"ue"=>0,"up"=>0,"ae"=>0,"ap"=>0,"aew"=>0,"apw"=>0,"tp"=>2,"uroven"=>1),
array("name"=>"атомат","mh"=>0,"eh"=>-15,"re"=>15,"rp"=>0,"ue"=>0,"up"=>0,"ae"=>0,"ap"=>0,"aew"=>0,"apw"=>0,"tp"=>1,"uroven"=>1),
array("name"=>"гаубица","mh"=>0,"eh"=>-20,"re"=>20,"rp"=>0,"ue"=>0,"up"=>0,"ae"=>0,"ap"=>0,"aew"=>0,"apw"=>0,"tp"=>2,"uroven"=>1),
array("name"=>"пулемет","mh"=>0,"eh"=>-30,"re"=>30,"rp"=>0,"ue"=>0,"up"=>0,"ae"=>0,"ap"=>0,"aew"=>0,"apw"=>0,"tp"=>1,"uroven"=>1),
array("name"=>"ракета","mh"=>0,"eh"=>40,"re"=>40,"rp"=>0,"ue"=>0,"up"=>0,"ae"=>0,"ap"=>0,"aew"=>0,"apw"=>0,"tp"=>2,"uroven"=>1),
array("name"=>"бинт","mh"=>5,"eh"=>0,"re"=>0,"rp"=>5,"ue"=>0,"up"=>0,"ae"=>0,"ap"=>0,"aew"=>0,"apw"=>0,"tp"=>0,"uroven"=>1),
array("name"=>"вакцина","mh"=>12,"eh"=>0,"re"=>0,"rp"=>10,"ue"=>0,"up"=>0,"ae"=>0,"ap"=>0,"aew"=>0,"apw"=>0,"tp"=>0,"uroven"=>1),
array("name"=>"аптечка","mh"=>20,"eh"=>0,"re"=>0,"rp"=>15,"ue"=>0,"up"=>0,"ae"=>0,"ap"=>0,"aew"=>0,"apw"=>0,"tp"=>0,"uroven"=>1),
array("name"=>"наноботы","mh"=>30,"eh"=>0,"re"=>0,"rp"=>30,"ue"=>0,"up"=>0,"ae"=>0,"ap"=>0,"aew"=>0,"apw"=>0,"tp"=>0,"uroven"=>1),
array("name"=>"перегрузка систем","mh"=>40,"eh"=>0,"re"=>0,"rp"=>40,"ue"=>0,"up"=>0,"ae"=>0,"ap"=>0,"aew"=>0,"apw"=>0,"tp"=>0,"uroven"=>1),
array("name"=>"лобовой щит","mh"=>0,"eh"=>0,"re"=>0,"rp"=>11,"ue"=>0,"up"=>0,"ae"=>0,"ap"=>0,"aew"=>0,"apw"=>0,"tp"=>0,"uroven"=>1),
array("name"=>"верхний щит","mh"=>0,"eh"=>0,"re"=>0,"rp"=>17,"ue"=>0,"up"=>0,"ae"=>0,"ap"=>0,"aew"=>0,"apw"=>0,"tp"=>0,"uroven"=>1),
array("name"=>"жук","mh"=>0,"eh"=>0,"re"=>11,"rp"=>0,"ue"=>"-40%","up"=>"-40%","ae"=>0,"ap"=>0,"aew"=>0,"apw"=>0,"tp"=>0,"uroven"=>1),
array("name"=>"оса","mh"=>0,"eh"=>0,"re"=>"17,+20%","rp"=>"+20%","ue"=>"-20%","up"=>"-20%","ae"=>0,"ap"=>0,"aew"=>0,"apw"=>0,"tp"=>0,"uroven"=>1),
array("name"=>"добавить батарею энергона","mh"=>0,"eh"=>0,"re"=>0,"rp"=>0,"ue"=>2,"up"=>0,"ae"=>0,"ap"=>0,"aew"=>0,"apw"=>0,"tp"=>0,"uroven"=>1),
array("name"=>"добавить батарею позитрона","mh"=>0,"eh"=>0,"re"=>0,"rp"=>0,"ue"=>0,"up"=>2,"ae"=>0,"ap"=>0,"aew"=>0,"apw"=>0,"tp"=>0,"uroven"=>1),
array("name"=>"сломать батарею энергона врага","mh"=>0,"eh"=>0,"re"=>0,"rp"=>0,"ue"=>0,"up"=>0,"ae"=>0,"ap"=>0,"aew"=>-2,"apw"=>0,"tp"=>0,"uroven"=>1),
array("name"=>"сломать батарею позитрона врага","mh"=>0,"eh"=>0,"re"=>0,"rp"=>0,"ue"=>0,"up"=>0,"ae"=>0,"ap"=>0,"aew"=>0,"apw"=>-2,"tp"=>0,"uroven"=>1),
array("name"=>"сбить с ног","mh"=>0,"eh"=>0,"re"=>8,"rp"=>0,"ue"=>0,"up"=>0,"ae"=>0,"ap"=>0,"aew"=>0,"apw"=>0,"tp"=>0,"uroven"=>1));

//statePlayer - состояние игрока (0-вне игры, 1-готов играть, 2-уже играю)
//myCard-моссив всех карт игрока
//myGameCard - массив карт находящихся на руках во время игры
//myCharacter - начальные характеристики игрока 
//myCharacterGame - текущие характеристики игрока в игре
function getmicrotime(){ 
    list($usec, $sec) = explode(" ", microtime()); 
    return ((float)$usec + (float)$sec); 
} 

Class ConnectDB{
   private $user='latinoby_admin';
   private $password='miha951';
   private $dbname='latinoby_phinc';
   private $hostname='localhost';
   private $connect;
   public function ConnectDB() { } //можно отправлять настройки логина пароля и базы данных
   /*Создаем пользователя и игру для него*/
   public function getConnect(){
      $this->connect=mysql_connect($this->hostname, $this->user, $this->password);      
      mysql_select_db($this->dbname,$this->connect);  
      // в какой кодировке получать данные от клиента
      @mysql_query('set character_set_client="utf8"');

      // в какой кодировке получать данные от БД для вывода клиенту
      @mysql_query('set character_set_results="utf8"');

      // кодировка в которой будут посылаться служебные команды для сервера
      @mysql_query('set collation_connection="utf8_general_ci"');    
      return $this->connect;
   }
}

Class GameWarSpirit{
    private $userGame;
    private $messanger;    
    private $connectDBNulay; 
    public function GameWarSpirit($user) {
         $this->connectDBNulay = new ConnectDB();
         $this->userGame=new UserGame($user,$this->connectDBNulay);
         $this->messanger=new Messanger($user);         
    } 
    public function getData($jsonQuery){
        if($jsonQuery==''){ print("{\"error\":\"001\"}");return;}          
        $query=json_decode($jsonQuery); 
        if($query==null) print("{\"error\":\"002\"}");             
        switch ($query->data){
          case "getUser": $this->userGame->printUser();break;
          case "startGame": $this->userGame->startGame();break;
          case "serchGame": $this->userGame->serchGame();break;
          case "nextHod": $this->userGame->nextHod();break;
          case "messanger": $this->messanger->getData($jsonQuery);break;
          case "getCurentRes": $this->getCurentRes();break;
          case "nextDialog": $this->nextDialog();break;
          case "setValue": $this->setValue($query);break;
		  case "getDialog": $this->getDialog($query);break;
          default: print("{\"error\":\"002\"}");
        }        
    }
    //Method for get curent state resurs for curent user
    public function getCurentRes(){       
        $time=time();        
        $res=$this->getProperty("resurses",'user',$this->userGame->gameuser["id"]);
        if($res==null){
           print("{\"resourses\":\"null\"}");
        }else
          $res[0]["curtime"]= $time;
        $d_res=$this->getProperty("d_res",'user',$this->userGame->gameuser["id"]);
        if($d_res!=null){
           $resO=json_decode($res[0]["value"]);
           $d_res=json_decode($d_res[0]["value"]); 
           $resO->zarg=$resO->zarg+($d_res->d_zarg*($res[0]["curtime"]-$res[0]["date"]));
           $resO->kvad=$resO->kvad+($d_res->d_kvad*($res[0]["curtime"]-$res[0]["date"]));
           $resO->kred=$resO->kred+($d_res->d_kred*($res[0]["curtime"]-$res[0]["date"]));
           $res[0]["value"]=json_encode($resO);
        }
        print(json_encode($res[0]));
    }
    public function getBildings(){
        $time=time();        
        $res=$this->getProperty("bildings",'user',$this->userGame->gameuser["id"]); 
    }
	public function getDialog(){	
              $usSett=$this->getProperty("userSett","user",$this->userGame->gameuser["id"]);		 
	      $usSett=json_decode($usSett[0]["value"]);
              $res=$this->getProperty("dialog_".$usSett->numD,"game",0);
	      //print(json_encode($prarr));
	      print(json_encode($res));
	}
        public function nextDialog(){
              $usSett=$this->getProperty("userSett","user",$this->userGame->gameuser["id"]);
              $usSett=json_decode($usSett[0]["value"]);
              $usSett->numD=$usSett->numD+1;		
	      $prarr=array(array("type_class"=>"user","value_type"=>$this->userGame->gameuser["id"],"property"=>"userSett","value"=>json_encode($usSett)));
              $this->setProperty($prarr);
              $this->getDialog();
        }
	public function setProperty($prarr){
	   $c=$this->connectDBNulay->getConnect();
	   for($i=0;$i<count($prarr);$i++){
	     $sql="SELECT id FROM property_game WHERE (type_class='".$prarr[$i]["type_class"]."' AND value_type=".$prarr[$i]["value_type"]." AND property='".$prarr[$i]["property"]."')";
	     $res = mysql_query($sql,$c);              
             $num=mysql_num_rows ($res);             
             // print($num."<br>");
             if($num>0){
                $asRes=mysql_fetch_array($res);
                mysql_query("UPDATE property_game SET value = '".$prarr[$i]["value"]."', date='".gmdate('Y-m-d H:i:s', time())."' WHERE id=".$asRes[0],$c);
             } else{
                $sqlIn="INSERT INTO property_game(type_class, value_type, property, value, date) VALUES ('".$prarr[$i]["type_class"]."',".$prarr[$i]["value_type"].",'".$prarr[$i]["property"]."','".$prarr[$i]["value"]."','".gmdate('Y-m-d H:i:s', time())."')";                       
                mysql_query($sqlIn,$c);
             }              
        } 
        mysql_close ($c);
        return true;    
	}
    public function getProperty($property,$type_class='user', $value_type=0){
       //запрос в БД и при помощи $user - получаем $gameuser
          $c=$this->connectDBNulay->getConnect();
          $res=mysql_query("SELECT value,UNIX_TIMESTAMP(date) AS date FROM property_game WHERE type_class='$type_class' AND value_type='$value_type' AND property='$property'",$c);          
          $numR=mysql_num_rows ($res);
          if($numR==0) return null;   
          $usprop=array();
          while ($row = mysql_fetch_array($res, MYSQL_ASSOC)) {
             $usprop[count($usprop)]=$row;
          }                                 
          mysql_close ($c);
          return $usprop; 
    }
    public function setValue($data){
           //print(json_encode($data));return;
           $prarr=array(array("type_class"=>$data->type_class,"value_type"=>$data->value_type,"property"=>$data->property,"value"=>$data->value));
           $this->setProperty($prarr);
    }
}

Class UserGame{
   private $user;
   public $gameuser=null;
   private $hellop;
   private $connectDBNulay;
   public function UserGame($user,$connectDBNulay) { 
       $this->connectDBNulay = $connectDBNulay;       
       $this->user=$user; 
       $this->gameuser=$this->getUser();   
   }
   /*Создаем пользователя и игру для него*/
   public function createUser(){
     //создаем  $gameuser и устанавливаем в него $user->uid     
        $c=$this->connectDBNulay->getConnect();
        mysql_query("INSERT INTO `game_user`(`uid_user`, `name`) VALUES (".$this->user->uid.",'".$this->user->name."')",$c); 
        $id=mysql_insert_id($c);	
        mysql_close ($c);
        return $id;    
   }
   public function serchGame(){
        $this->gameuser=$this->getUser();  
        if($this->gameuser==null){ 
            print("{\"error\":\"004\"}");  
            return;
        } 
        $c=$this->connectDBNulay->getConnect();        
      //  $sql="SELECT gu.id, gu.name FROM game_user gu INNER JOIN property_game pg ON gu.id = pg.value_type WHERE pg.type_class='user' AND property='statePlayer' AND value='1' AND date>'".gmdate('Y-m-d H:i:s', time()-60)."' AND gu.id <>".$this->gameuser["id"]." GROUP BY gu.id"; 
        $sql="SELECT gu.id, gu.name FROM game_user gu INNER JOIN property_game pg ON gu.id = pg.value_type WHERE pg.type_class='user' AND property='statePlayer' AND value='1' AND date>'".gmdate('Y-m-d H:i:s', time()-60)."' GROUP BY gu.id";               
        $res = mysql_query($sql,$c); 
        $numR=mysql_num_rows ($res);
        if($numR==0){print("[]"); return;} 
        $arr=array();  
        while ($row = mysql_fetch_array($res, MYSQL_ASSOC)) {
            $arr[count($arr)]=$row;
        }                                 
        mysql_close ($c);
        $json=json_encode($arr);      
        print($json); 
   }
   public function startGame(){
        $this->gameuser=$this->getUser();  
        if($this->gameuser==null){ 
            print("{\"error\":\"004\"}");              
        } 
        $prarr=array(array("type_class"=>"user","value_type"=>$this->gameuser["id"],"property"=>"statePlayer","value"=>"1"));
        $this->setPropertyUser($prarr);
        print("{\"query\":\"true\"}");
   }
   public function printCreateUser(){
       print("{\"userid\":\"".$this->createUser()."\"}");  
   }
   /*Получаем игрового пользователя */
   public function getUser(){       
          //запрос в БД и при помощи $user - получаем $gameuser
          $c=$this->connectDBNulay->getConnect();
          $res=mysql_query("SELECT id, name FROM game_user WHERE uid_user=".$this->user->uid,$c);          
          $numR=mysql_num_rows ($res);
          if($numR==0) return null;          
          $this->gameuser=mysql_fetch_array($res, MYSQL_ASSOC);                  
          mysql_close ($c);
          return $this->gameuser;       
   }
   public function printUser(){
      if($this->user->uid==0){
          print("{\"error\":\"003\"}");  
          return;
      } 
      $this->gameuser=$this->getUser();      
      if($this->gameuser==null){
          $this->createUser();
          $this->gameuser=$this->getUser();  
                     
          if($this->gameuser==null){  
             print("{\"error\":\"004\"}");  
             return;  
          }  
          $prarr=array(array("type_class"=>"user","value_type"=>$this->gameuser["id"],"property"=>"userSett","value"=>"{\"numD\":1}"));
          $this->setPropertyUser($prarr);     
      }	  
      //
      $prarr=array(array("type_class"=>"user","value_type"=>$this->gameuser["id"],"property"=>"statePlayer","value"=>"0"));
    //                   array("type_class"=>"user","value_type"=>$this->gameuser["id"],"property"=>"myCard","value"=>""),					   
    //                   array("type_class"=>"user","value_type"=>$this->gameuser["id"],"property"=>"resurses","value"=>"{\"zarg\":\"10000\",\"kvad\":\"10000\",\"kred\":\"10000\",\"krist\":\"500\"}"),
    //                   array("type_class"=>"user","value_type"=>$this->gameuser["id"],"property"=>"d_res","value"=>"{\"d_zarg\":\"0\",\"d_kvad\":\"0\",\"d_kred\":\"0\",\"u_d_zarg\":[\"0\",\"0\"],\"u_d_kvad\":[\"0\",\"0\"],\"u_d_kred\":[\"0\",\"0\"]}")); 
    
      $this->setPropertyUser($prarr);      
      $prop=$this->getPropertyUser();
      $this->gameuser["prop"]=$prop;
      $id=$this->gameuser["id"]; unset($this->gameuser["id"]);
      $json=json_encode($this->gameuser);      
      print($json);      
   }
   public function getPropertyUser(){       
          //запрос в БД и при помощи $user - получаем $gameuser
          $c=$this->connectDBNulay->getConnect();
		  $idu=$this->gameuser["id"];
          $res=mysql_query("SELECT property,value FROM property_game WHERE type_class=\"user\" AND value_type=$idu",$c);          
          $numR=mysql_num_rows ($res);
          if($numR==0) return null;   
          $usprop=array();
          while ($row = mysql_fetch_array($res, MYSQL_ASSOC)) {
             $usprop[count($usprop)]=$row;
          }                                 
          mysql_close ($c);
          return $usprop;       
   }
   public function setPropertyUser($prarr){        
        $c=$this->connectDBNulay->getConnect();
	for($i=0;$i<count($prarr);$i++){
	     $sql="SELECT id FROM property_game WHERE (type_class='".$prarr[$i]["type_class"]."' AND value_type=".$prarr[$i]["value_type"]." AND property='".$prarr[$i]["property"]."')";
	     $res = mysql_query($sql,$c);              
             $num=mysql_num_rows ($res);             
             // print($num."<br>");
             if($num>0){
                $asRes=mysql_fetch_array($res);
                mysql_query("UPDATE property_game SET value = '".$prarr[$i]["value"]."', date='".gmdate('Y-m-d H:i:s', time())."' WHERE id=".$asRes[0],$c);
             } else{
                $sqlIn="INSERT INTO property_game(type_class, value_type, property, value, date) VALUES ('".$prarr[$i]["type_class"]."',".$prarr[$i]["value_type"].",'".$prarr[$i]["property"]."','".$prarr[$i]["value"]."','".gmdate('Y-m-d H:i:s', time())."')";                       
                mysql_query($sqlIn,$c);
             }              
        } 
        mysql_close ($c);
        return true;    
   }
   public function getHellop(){
      //запрос в БД и при помощи $user - получаем $gameuser
      return $this->hellop;
   }
   public function nextHod(){  
       $this->gameuser=$this->getUser();  
       if($this->gameuser==null){ 
           print("{\"error\":\"004\"}");  
           return;
       } 
       $this->otvet(0); 
   }
   public function otvet($i){  
       $c=$this->connectDBNulay->getConnect();
       $sql="SELECT id FROM property_game WHERE type_class='user' AND property='statePlayer' AND value='1' AND value_type=14"; 
       $res=mysql_query($sql,$c);          
       $numR=mysql_num_rows ($res);
       if($numR>0) $i=300;
    
       if($i<100){
          sleep (1);
          $i=$i+1;       
          $this->otvet($i);
          return;                    
       }
       print("{\"ura\":\"Мы вышли i=".$i."\"}");
   }
}

Class Messanger{
   private $user;
   private $dellayed=1;//Задержка в секундах между проверками сообщений
   public function Messanger($user) { 
       $this->connectDBNulay = new ConnectDB();       
       $this->user=$user;       
   }   
   public function getData($jsonQuery){
        if($jsonQuery==''){ print("{\"error\":\"001\"}");return;}          
        $query=json_decode($jsonQuery); 
        if($query==null) print("{\"error\":\"002\"}");       
        switch ($query->dataM){
          case "getLMessageNR" : $this->getListMessageNoRead($query->dataMOb);break;//получаем не прочитанные сообщения
          case "sendMessage"   : $this->sendMessage($query->dataMOb);break;//Отправка сообщения пользователю
          case "delayMessage"  : $this->delayMessage();break;//Ожидание сообщения
          case "getListUser"   : $this->getListUser();break;//Получение списка пользователей
          case "messageRead"   : $this->messageRead();break;//Пометить сообщение как прочитаное
          case "getListMFU"    : $this->getListMFU($query->dataMOb);break;//Получить непрочитанные сообщения от конкретного пользователя
          default: print("{\"error\":\"002\"}");
        }        
   }
   public function getListUser(){
       $arr=$this->getListUsersql();
       $json=json_encode($arr);      
       print($json);
   }
   public function getListMessageNoRead($first="0"){                     
        print(json_encode($this->getListMessageNoReadsql($first)));
   }
   public function sendMessage($dataMOb){
       print("{\"sendmessage\":\"".$this->sendMessagesql($dataMOb)."\"}");
   }
   public function messageRead($dataMOb){       
       print("{\"read\":\"".$this->messageReadsql($dataMOb)."\"}");  
   }
   public function getListMFU($dataMOb){        
       print(json_encode($this->getListMessageFromUsersql($dataMOb)));  	   
   }
   public function delayMessage(){ 
       print("{\"message\":\"".$this->_delayMessagesql(0)."\"}");  	   
   }
   public function _delayMessagesql($tout){              
       $c=$this->connectDBNulay->getConnect();
       $sql="SELECT id FROM messanger WHERE id_user=".$this->user->uid." AND key_read=0"; 
       $res=mysql_query($sql,$c);          
       $numR=mysql_num_rows ($res);
       if($numR>0) $tout=300;    
       if($tout<100){
          sleep ($this->dellayed);
          $tout=$tout+1;       
          return $this->_delayMessagesql($tout);                             
       }      
       return $numR;	   
   }   
   
   //for drupal
   public function getListUsersql(){
       $c=$this->connectDBNulay->getConnect();
        $sql="SELECT uid, name FROM users"; 
        $res=mysql_query($sql,$c);          
        $numR=mysql_num_rows ($res);
        $arr=array();  
        while($row = mysql_fetch_array($res, MYSQL_ASSOC)) {
            $arr[count($arr)]=$row;
        }                                 
        mysql_close ($c);
        return $arr;
   }   
   public function getListMessageFromUsersql($dataMOb){
        $c=$this->connectDBNulay->getConnect();			
        $sql="SELECT id, message, date FROM messanger WHERE id_user=".$this->user->uid." AND (key_read=0 OR key_read=1) AND id_user_out=".$dataMOb->user_out; 
        $res=mysql_query($sql,$c);          
        $numR=mysql_num_rows ($res);
        $arr=array(); 
        $arrus=array(); 
        while($row = mysql_fetch_array($res, MYSQL_ASSOC)) {
            $arr[count($arr)]=$row["id"]; 
            $arrus[count($arrus)]=$row;                                  
        }  
        $wr="";  		
        for($i=0;$i<count($arr);$i++){
             if($i!=0) $wr=$wr." OR ";
             $wr=$wr."id=".$arr[$i];
        }	
        $sqlIn="UPDATE messanger SET key_read=2 WHERE ".$wr;
        mysql_query($sqlIn,$c);  		
        mysql_close ($c);
		//print("{\"error\":\"".$sqlIn."\"}");        
	return $arrus;
   }
   public function getListMessageNoReadsql($first){
        $c=$this->connectDBNulay->getConnect();
		$wrread="";
		if($first=="1") $wrread=" (key_read=0 OR key_read=1)";
		else $wrread=" key_read=0";
        $sql="SELECT id, id_user_out FROM messanger WHERE id_user=".$this->user->uid." AND ".$wrread; 
        $res=mysql_query($sql,$c);          
        $numR=mysql_num_rows ($res);
        $arr=array(); 
        $arrus=array(); 
        while($row = mysql_fetch_array($res, MYSQL_ASSOC)) {
            $arr[count($arr)]=$row["id"]; 
            $arrus[count($arrus)]=$row["id_user_out"];                                  
        }  
        $wr="";  		
        for($i=0;$i<count($arr);$i++){
             if($i!=0) $wr=$wr." OR ";
             $wr=$wr."id=".$arr[$i];
        }	
        $sqlIn="UPDATE messanger SET key_read=1 WHERE ".$wr;
        mysql_query($sqlIn,$c);  		
        mysql_close ($c);
		//print("{\"error\":\"".$sqlIn."\"}");        
	return array_values(array_unique($arrus));
   }
   public function sendMessagesql($dataMOb){
       $c=$this->connectDBNulay->getConnect();
       $sqlIn="INSERT INTO messanger (id_user, id_user_out, message, key_read, date) VALUES (".$dataMOb->user_out.",".$this->user->uid.",'".$dataMOb->message."',0,'".gmdate('Y-m-d H:i:s', time())."')";                        
       mysql_query($sqlIn,$c);
       mysql_close ($c); 
	   return true;
   }
   public function messageReadsql($dataMOb){
       $c=$this->connectDBNulay->getConnect();
       $wr="";
       for($i=0;$i<count($dataMOb);$i++){
         if($i!=0) $wr=$wr." OR ";
         $wr=$wr."id=".$dataMOb[$i];
       }
       mysql_query("UPDATE messanger SET key_read=2 WHERE ".$wr.$dataMOb,$c);
       mysql_close ($c);
       return true;
   }
}
