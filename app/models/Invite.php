<?php
class Invite extends Eloquent {
	
	public function check($key){
		if($this->code == $key && $this->accepted == false){
			return true;
		}else{
			return false;
		}
	}	
	public function accept($key){
			$this->accepted = true;
			$this->save();
			return true;
	}
}