<?php

class ShiftplanController extends BaseController {
	public function getIndex(){
		return View::make('shiftplan/index');
	}
	public function getCreate(){
		return View::make('shiftplan/create');
	}	
	public function getPartials($view){
		return View::make('shiftplan/partials/'.$view);
	}	

}