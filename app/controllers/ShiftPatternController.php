<?php

class ShiftPatternController extends BaseController {
	public function getIndex(){
		return View::make('shiftPattern/index');
	}
	public function getCreate(){
		return View::make('shiftPattern/create');
	}
	public function postCreate(){
		var_dump($_POST);
		$shiftPattern = new ShiftPattern;
		$shiftPattern->user_id = Sentry::getUser()->id;
		$shiftPattern->name = Input::has('name') ? Input::get('name') : 'unspecified';
		$shiftPattern->workers = Input::has('workers') ? json_encode(Input::get('workers')) : '';
		$shiftPattern->pattern = Input::has('shifts') ? json_encode(Input::get('shifts'), JSON_FORCE_OBJECT) : '';
		$shiftPattern->save();

	}		
	public function getPartials($view){
		return View::make('shiftPattern/partials/'.$view);
	}	
		public function getTest(){
		var_dump(Sentry::getUser()->id);

	}

}