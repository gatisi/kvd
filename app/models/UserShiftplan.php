<?php
class UserShiftplan extends Eloquent {
	protected $table = 'users_shiftplans';

	public function isAllowed($pattern){
		$res = $this->
		where('user_id','=',Sentry::getUser()->id)->
		where('shiftpattern_id','=',$pattern)->
		get()->
		count();
		if($res<1){$res = false;}
		return $res;
	}

	public function allUsers($pattern_id){
		$res = $this->
		where('shiftpattern_id','=',$pattern_id)->
		where('uses','=',1)->
		get(array('user_id'))->
		toArray();
		$arrOfIds = array();
		foreach ($res as $id) {
			$arrOfIds[]=$id['user_id'];
		}
		return $arrOfIds;
	}

	public function pattern(){
		return $this->hasOne('ShiftPattern',  'id', 'shiftpattern_id');
	}

	public function checkAccessById($id){
		$user_id = Sentry::getUser()->id;
		$alowed = $this->whereIdAndUser_id($id, $user_id)->count()>0 ? true : false;
		return $alowed;
	}

	public function getByIdIfAllowed($id){
		$user_id = Sentry::getUser()->id;
		$found = $this->whereIdAndUser_id($id, $user_id)->first();
		//$alowed = $this->whereIdAndUser_id($id, $user_id)->count()>0 ? true : false;
		return $found;
	}
}