<?php
class ShiftPattern extends Eloquent {
	protected $table = 'shift_patterns';

	public function available(){
		return $this->where('user_id','=',Sentry::getUser()->id)->get()->all();
	}
}