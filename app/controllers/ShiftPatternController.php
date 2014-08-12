<?php

class ShiftPatternController extends BaseController {
	public function getIndex(){
		return View::make('shiftPattern/index');
	}
	public function getCreate(){
		return View::make('shiftPattern/create');
	}
	public function postCreate(){
		$workers = Input::get('workers');
		$shiftPattern = new ShiftPattern;
		$manager = Sentry::getUser()->id;
		$shiftPattern->user_id = $manager;
		$shiftPattern->name = Input::has('name') ? Input::get('name') : 'unspecified';
		$shiftPattern->workers = json_encode($workers);
		$shiftPattern->pattern = Input::has('shifts') ? json_encode(Input::get('shifts'), JSON_FORCE_OBJECT) : '';

		$shiftPattern->save();
		$syncData = array();
		foreach ($workers as $key => $id) {
			$syncData[$id]=array('manager'=>0, 'accepted'=>0);
		}
		$syncData[$manager] = array('manager'=>1, 'accepted'=>1);
		$shiftPattern->users()->sync($syncData);
		echo "done";

	}		
	public function getPartials($view){
		return View::make('shiftPattern/partials/'.$view);
	}	
		public function getTest(){
		var_dump(Sentry::getUser()->id);

	}

}