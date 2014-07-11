<?php



class UserController extends BaseController {

	public function __construct() {
		$this->beforeFilter('auth', array('except'=>array(
			'getRegister', 
			'postRegister', 
			'getLogin', 
			'postLogin',
			'getStatus',
			'getTest',
			'getActivate',
			'getAccept',
			'postAccept'
			)));
		$this->beforeFilter('guest', array('only'=>array(
			'getRegister', 
			'postRegister', 
			'getLogin', 
			'postLogin',
			)));
	}



	public function getRegister(){
		return View::make('users/register');
	}


	public function postRegister(){
		try
		{
			$credentials = array(
				'email'    => Input::has('email') ? Input::get('email') : null,
				'password' => Input::has('password') ? Input::get('password') : null,
				'first_name' => Input::has('name') ? Input::get('name') : null,
				'last_name' => Input::has('name') ? Input::get('name') : null,
				'organization' => Input::has('organization') ? Input::get('organization') : null,
				'role' => 'Maneger'
				);

			$user = Sentry::createUser($credentials);
			$adminGroup = Sentry::findGroupById(2);
			$user->addGroup($adminGroup);
			$activateLink = URL::to('/users/activate/').'/'.$user->getActivationCode().'/'.$user->getId();
			echo $activateLink;
		}
		catch (Cartalyst\Sentry\Users\LoginRequiredException $e){
			echo 'Login field is required.';
		}
		catch (Cartalyst\Sentry\Users\PasswordRequiredException $e){
			echo 'Password field is required.';
		}
		catch (Cartalyst\Sentry\Users\UserExistsException $e){
			echo 'User with this login already exists.';
		}
		catch (Cartalyst\Sentry\Groups\GroupNotFoundException $e){
			echo 'Group was not found.';
		}
	}

	function getLogin(){
		$error = Session::get('login_error');
		Session::forget('login_error');
		return View::make('users/login')->with('error_message', $error);
	}
	function postLogin(){			
		try
		{
			$credentials = array(
				'email'    => Input::has('email') ? Input::get('email') : null,
				'password' => Input::has('password') ? Input::get('password') : null,
				);

			$user = Sentry::authenticate($credentials, Input::has('remember_me') and Input::get('remember_me') == 'checked');
			return URL::to('/shiftplan');
		}

		catch (Cartalyst\Sentry\Users\LoginRequiredException $e)
		{
			Session::put('login_error','Login field is required.');
			return Response::json('Validation failed', 400);
		}
		catch (Cartalyst\Sentry\Users\PasswordRequiredException $e)
		{
			Session::put('login_error','Password field is required.');
			return Response::json('Validation failed', 400);
		}
		catch (Cartalyst\Sentry\Users\WrongPasswordException $e)
		{
			Session::put('login_error','Wrong password, try again.');
			return Response::json('Validation failed', 400);
		}
		catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
		{
			Session::put('login_error','User was not found.');
			return Response::json('Validation failed', 400);
		}
		catch (Cartalyst\Sentry\Users\UserNotActivatedException $e)
		{
			Session::put('login_error','User is not activated.');
			return Response::json('Validation failed', 400);
		}		
		catch (Cartalyst\Sentry\Throttling\UserSuspendedException $e){
			Session::put('login_error','To many login attempts. Try again later.');
			return Response::json('Validation failed', 400);
		}
		catch (Cartalyst\Sentry\Throttling\UserBannedException $e){
			Session::put('login_error','User is banned.');
			return Response::json('Validation failed', 400);
		}
	}


	public function getLogout(){
		Sentry::logout();
		return Redirect::to('/');
	}

	public function getStatus(){
		if (Sentry::Check()){
			echo "logged in";
		}else{
			echo "not in !!";
		}
	}

	public function getActivate($code, $id){
		try
		{
			$user = Sentry::findUserById($id);
			if ($user->attemptActivation($code)){
				$report = "Your account has been activated. You can login now.";
			}else{
				$report =  "Sorry, acctivation failed. Activation link you provided was invalid or account is already activated.";
			}
			return View::make('users/reports/activate')->with('report', $report);
		}
		catch (Cartalyst\Sentry\Users\UserNotFoundException $e){
			$report =  "Sorry, acctivation failed. Activation link you provided was invalid or account is already activated.";
		}
		catch (Cartalyst\Sentry\Users\UserAlreadyActivatedException $e){
			$report =  "Sorry, acctivation failed. Activation link you provided was invalid or account is already activated.";
		}
		return View::make('users/reports/activate')->with('report', $report);
	}

	public function getInvite(){
		$user = Sentry::getUser();
		return View::make('users/invite')->with('user', $user);
	}

	private function InviteUser($email, $first_name, $last_name, $message){
		try{
			$user = Sentry::getUser();
			$contact = Sentry::findUserByLogin($email);
			$added = $user->saveContact($contact->id);
			$contact->saveContact($user->id);
			if($added){
				$report = 'Contact added.';
			}else{
				$report = 'Contact already exists.';
			}
			return $report;
		}
		catch (Cartalyst\Sentry\Users\UserNotFoundException $e){

			$key = sha1(microtime(true).mt_rand(10000,90000));
			$user = Sentry::getUser();
			$invite = new Invite;

			$contact = Sentry::createUser(array(
				'email'     => $email,
				'activated' => false,
				'first_name' => $first_name,
				'last_name' => $last_name,
				'password' => $key
				));

			$code = $contact->getResetPasswordCode();

			$invite->email = $email;
			$invite->message = $message;
			$invite->manager = $contact->id;
			$invite->accepted = false;
			$invite->code = $code;
			$invite->save();

			$activateLink = URL::to('/users/accept').'/'.$code.'/'.$invite->id;
			echo $activateLink;

			if($user->saveContact($contact->id)){
				$result = 'Invite sent and contact added.';
			}
			else{
				$result = $email.' is already in your contacts.';
			}
			return $result;
		}
	}

	public function postInvite(){
		$emails = Input::has('email') ? Input::get('email') : null;
		$first_names = Input::has('first_name') ? Input::get('first_name') : null;
		$last_names = Input::has('last_name') ? Input::get('last_name') : null;
		$message = Input::has('message') ? Input::get('message') : null;
		foreach ($emails as $key => $email) {
			$validator = Validator::make(
				array('email' => $email),
				array('email' => 'required|email')
				);
			if($validator->passes()){
				$result = $this->InviteUser($email, $first_names[$key], $last_names[$key], $message);
				$report['success'][]=array($email, $result);
			}else{
				$report['faile'][] = array($email, $first_names[$key], $last_names[$key]);
			}
		}
		return View::make('users/reports/invite')->with('report', $report);
	}

	public function getAccept($code, $id){

		if($invite = Invite::find($id)){
			if ($invite->check($code)){
				return View::make('users/accept')
					->with('invite', $invite)
					->with('code', $code);
			}else{
				$report =  "Sorry, the link you provided was invalid.";
			}
		}
		else{
			$report =  "Sorry, the link you provided was invalid.";
		}
		return View::make('users/reports/invite')->with('report', $report);
	}

	public function postAccept(){
		try
		{
			$user = Sentry::findUserByLogin(Input::get('email'));
			$code = Input::get('code');
			$invite_id = Input::get('invite');
			var_dump($invite_id);
			$invite = Invite::find($invite_id);
			
			if ($user->checkResetPasswordCode($code)&&$invite->check($code))
			{
				if ($user->attemptResetPassword($code, Input::get('password')))
				{
					$invite->accept($code);
					$user->activated = true;
					$user->first_name = Input::has('firstname') ? Input::get('firstname') : null;
					$user->last_name = Input::has('lastname') ? Input::get('lastname') : null; 
					$user->save();
					$report = "Your account has been activated. You can login now.";        		
				}
				else
				{
					$report =  "Sorry, the password you provided was invalid.";
				}
			}
			else
			{
				$report =  "Sorry, acctivation failed. Activation link you provided was invalid.";
			}
			return View::make('users/reports/invite')->with('report', $report);

		}
		catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
		{
			$report =  "Sorry, acctivation failed. Activation link you provided was invalid.";
			return View::make('users/reports/invite')->with('report', $report);
		}

	}

	public function getTest(){
		$user = Sentry::getUser();
		$contacts = $user->contacts();
		$user_obj = new stdClass();
		foreach ($contacts as $c) {
			$user_obj->email = $c->email;
			$user_obj->first_name = $c->first_name;
			$user_obj->last_name = $c->last_name;
			$user_obj->organization = $c->organization;
			$user_obj->activated = $c->activated;
			$user_obj->last_login = $c->last_login;
			$list[]=$user_obj;
		}
		return json_encode($list);

	}	
	public function getContacts(){
		$user = Sentry::getUser();
		$contacts = $user->contacts();
		
		foreach ($contacts as $c) {
			$user_obj = new stdClass();
			$user_obj->id = $c->id;
			$user_obj->email = $c->email;
			$user_obj->first_name = $c->first_name;
			$user_obj->last_name = $c->last_name;
			$user_obj->organization = $c->organization;
			$user_obj->activated = $c->activated;
			$user_obj->last_login = $c->last_login;
			$list[]=$user_obj;
		}
		return json_encode($list);

	}



}
