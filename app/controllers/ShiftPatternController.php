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
		foreach ($workers as $id) {
			$syncData[$id]=array('manager'=>0, 'accepted'=>0, 'uses'=>1);
		}
		$syncData[$manager]['manager'] = 1;
		$syncData[$manager]['accepted']=1;
		$shiftPattern->users()->sync($syncData);
		echo "done";

	}
	public function getNew(){
		$user_id = Sentry::getUser()->id;
		$new = [];
		$accessible =UserShiftplan::where('user_id', '=', $user_id)->
		where('accepted', '=', 0)->
		where('rejected', '=', 0)->
		get()->all();
		foreach ($accessible as $a) {
			$new[$a->id]=array(
				'id'=>$a->id,
				'name'=>$a->pattern()->get(array('name'))->first()->name
				);		
		}
		return $new;
	}
	public function getAcceptnew($id=false, $reply=false){
	//public function getTest($id=false, $reply=false){
		try{
			if(!is_numeric($id)||(!($reply=='accept')&&!($reply=='reject'))){
				throw new Exception("Error Processing Request", 1);
			}
			$access = new UserShiftplan;
			$user = User::find(Sentry::getUser()->id);
			$record = $access->getByIdIfAllowed($id);
			if(!$record){
				throw new Exception("Accsess denied", 1);
			}
			if($reply=='accept'){
				$record->accepted = 1;
				$newContacts = json_decode($record->pattern()->get(array('workers'))->first()->workers);
				foreach ($newContacts as $contactId) {
					$user->saveContact($contactId);
				}
			}			
			if($reply=='reject'){
				$record->rejected = 1;
			}
			$saved = $record->save();
			return Response::json(array('error' => !$saved, 'id' => $id));

		}catch(Exception $e){
			return Response::json(array('error' => true, 'message' => $e->getMessage()));
		}
	}

	public function getPartials($view){
		return View::make('shiftPattern/partials/'.$view);
	}	

}