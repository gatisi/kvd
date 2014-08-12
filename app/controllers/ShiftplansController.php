<?php

class ShiftplansController extends \BaseController {

	public function getIndex()
	{
		return View::make('shiftplans/index');
	}

	public function getWishes()
	{
		return View::make('shiftplans/wishes');
	}

	public function getList()
	{
		$user = User::find(Sentry::getUser()->id);
		$plans = $user->ShiftPattern()->where('accepted','=',1)->get(array('name', 'shift_patterns.id'))->all();

		foreach ($plans as $p) {
			$list[$p->id]=array(
				'name'=>$p->name,
				'id'=>$p->id,
				);
		}
		return $list;

	}

	public function getShiftplan($pattern, $year, $month){
		$access = new UserShiftplan;
		$plan = new stdClass();
		$plan = '{"day":{}}';
		if($access->isAllowed($pattern)){
			$shiftplan = new Shiftplan;
			$found = $shiftplan
			->where('pattern_id','=',$pattern)
			->where('month','=',$year.$month)
			->get()
			->first();
		}
		if($found){
			$plan = $found->plan;
		}


		return $plan;

	}

	public function getPartials($view){
		return View::make('shiftplans/partials/'.$view);
	}	

}