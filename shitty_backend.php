<?php

class ShittyBackendMySQL{
	private $con;
	private $temp_array=[];

	public function __construct($db_path,$db_user,$db_password,$db_name){
		$this->con=mysqli_connect($db_path,$db_user,$db_password,$db_name);
	}


	function toFilePaths($initial,$json_array){
    		foreach($json_array as $key=>$value){
        		if(is_array($value)){
             			$this->toFilePaths($initial.'/'.$key,$value);
        		}
        		else{
	     			$this->temp_array[$initial.'/'.$key]=$value;
        		}
    		}
	}

	public function eatJSON($path,$value){
		$this->temp_array=[];
		$this->toFilePaths($path,json_decode($value,true));
		foreach($this->temp_array as $key=>$value){
			mysqli_query($con,"DELETE FROM `shitty_backend` WHERE `key` LIKE '{$key}%';");
		}
		foreach($this->temp_array as $key=>$value){
			mysqli_query($con,"INSERT INTO `shitty_backend` VALUES('{$key}','{$value}');");	
		}
	}

	public function shitJSON($path){
		$this->temp_array=[];
		$res=mysqli_query($con,"SELECT * FROM `shitty_backend` WHERE `key` LIKE '{$key}%' ORDER BY `key` ASC";
		while($row=mysqli_fetch_assoc($res)){
			
		}
	}

	public function getDBReady(){
		mysqli_query($con,"CREATE TABLE `shitty_backend` (`key` varchar(255),`value` text);");
	}
}	

$sbinstance=new ShittyBackendMySQL();
$sbinstance->eatJson('/Bond','{
    "name":{
         "first_name":"James",
         "last_name":"Bond"
     },
     "aliases":["007","Bond"],
     "profiles":[{"0":"unknown"},"007",{"2":"secret agent"}]
}');
$sbinstance->printChewedResult();