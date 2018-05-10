<?php

class ShittyBackendMySQL{
	
	//TODO: Commenting
	
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
			mysqli_query($this->con,"DELETE FROM `shitty_backend` WHERE `key` LIKE '{$key}%';");
		}
		foreach($this->temp_array as $key=>$value){
			mysqli_query($this->con,"INSERT INTO `shitty_backend` VALUES('{$key}','{$value}');");	
		}
	}

	public function shitJSON($path){
		$this->temp_array=[];
		$res=mysqli_query($this->con,"SELECT * FROM `shitty_backend` WHERE `key` LIKE '{$key}%' ORDER BY `key` ASC");
		while($row=mysqli_fetch_assoc($res)){
			$current=& $this->temp_array;
			$pivot=strlen($path);
			if($pivot===strlen($row["key"]))return $row["value"];
			foreach (explode("/",substr($row['key'],$pivot+($row["key"][$pivot]==='/'?1:0))) as $node) {
				$current=& $current[$node];
			}
			$current=$row["value"];


		}
		return json_encode($this->temp_array);
	}

	public function query($sql){
		$res=mysqli_query($this->con,$sql);
		if(is_bool($res)) return $res;
		$this->temp_array=[];
		$i=0;
		while($row=mysqli_fetch_assoc($res))
			$this->temp_array[$i++]=$row;
		echo json_encode($this->temp_array);
	}

	public function getDBReady(){
		return mysqli_query($this->con,"CREATE TABLE `shitty_backend` (`key` varchar(255),`value` text);");
	}
}	

