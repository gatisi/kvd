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
		$plans = $user->ShiftPattern()->where('accepted','=',1)->get(array('name', 'shift_patterns.id', 'pattern'))->all();

		foreach ($plans as $p) {
			$list[$p->id]=array(
				'name'=>$p->name,
				'id'=>$p->id,
				'pattern'=>$p->pattern
				);
		}
		return $list;

	}

	public function getShiftplan($pattern, $year, $month){
		$access = new UserShiftplan;
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

	public function getWishlist($pattern, $year, $month){
		$access = new UserShiftplan;
		$list = '{}';
		if($access->isAllowed($pattern)){
			$wishlist = new Wishlist;
			$found = $wishlist
			->where('pattern_id','=',$pattern)
			->where('month','=',$year.$month)
			->get()
			->first();
		}
		if($found){
			$list = $found->list;
		}
		return $list;
	}

	public function postWishlist(){

		var_dump($_POST);
		$month = Input::get('month');
		$name = Input::get('name');
		$pattern_id = Input::get('pattern');
		$list = json_encode(Input::get('wishlist'), JSON_FORCE_OBJECT);
		$access = new UserShiftplan;
		if($access->isAllowed($pattern_id)){
			$wishlist = Wishlist::firstOrNew(array('month'=>Input::get('month'), 'pattern_id'=>$pattern_id));
			$wishlist->name = $name;
			$wishlist->pattern_id = $pattern_id;
			$wishlist->month = $month;
			$wishlist->list = $list;
			$wishlist->save();
		}

	}	

}