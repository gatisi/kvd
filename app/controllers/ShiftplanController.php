<?php

class ShiftplanController extends BaseController {
	public function getIndex(){
		return View::make('shiftplan/index');
	}
	public function getCreate(){
		return View::make('shiftplan/create');
	}
	public function postCreate(){
		var_dump($_POST);
		$shiftplan = new Shiftplan;
		$shiftplan->name = Input::has('name') ? Input::get('name') : 'unspecified';
		$shiftplan->workers = Input::has('workers') ? json_encode(Input::get('workers')) : '';
		$shiftplan->pattern = Input::has('shifts') ? json_encode(Input::get('shifts'), JSON_FORCE_OBJECT) : '';
		$shiftplan->save();

	}	
	public function getPartials($view){
		return View::make('shiftplan/partials/'.$view);
	}	

}