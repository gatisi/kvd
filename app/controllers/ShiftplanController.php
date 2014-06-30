<?php

class ShiftplanController extends BaseController {
	public function getIndex(){
		return View::make('shiftplan/index');
	}
}