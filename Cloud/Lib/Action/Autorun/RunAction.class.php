<?php
class RunAction extends RunmainAction {
    public function index(){
    	$this->cloudsendwechat();
    	$this->cloudsendsms();
    	$this->cloudsendemail();
    	$this->cloud(); 	  	
    }
 
  
	
}