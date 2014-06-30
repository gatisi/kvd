<?php

class WelcomeController extends BaseController {

	public function getHome()
	{
		return View::make('hello');
	}

}
