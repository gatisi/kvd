<?php

class ShiftPlanerController extends \BaseController {

	public function getIndex()
	{
		return View::make('shiftPlaner/index');
	}

	public function getManage()
	{
		return View::make('shiftPlaner/manage');

	}


	public function getList()
	{
		$pattern = new ShiftPattern;
		$list = array();
		foreach ($pattern->available() as $p) {
			$list[$p->id]=array(
				'name'=>$p->name,
				'id'=>$p->id,
				'workers'=>$p->workers,
				'pattern'=>$p->pattern
				);
		}
		return $list;

	}

	public function postCreate(){
		var_dump($_POST);
		$user = User::find(Sentry::getUser()->id);
		$plan = Shiftplan::firstOrNew(array('month'=>Input::get('month')));
		$plan->month = Input::get('month');
		$plan->name = Input::has('name') ? Input::get('name') : 'unspecified';
		$plan->pattern_id = Input::has('pattern') ? Input::get('pattern') : 0;
		$plan->plan = json_encode(Input::get('plan'), JSON_FORCE_OBJECT);
		$plan->save();
	}	

	public function getPartials($view){
		return View::make('shiftPlaner/partials/'.$view);
	}

}