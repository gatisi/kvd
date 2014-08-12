<?php
class ShiftPattern extends Eloquent {
	protected $table = 'shift_patterns';

	public function available(){
		return $this->where('user_id','=',Sentry::getUser()->id)->get()->all();
	}

	public function users(){
		return $this->belongsToMany('User', 'users_shiftplans', 'shiftpattern_id', 'user_id')->withPivot(array('manager'));
	}
}