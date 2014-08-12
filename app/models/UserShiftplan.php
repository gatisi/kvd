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
}