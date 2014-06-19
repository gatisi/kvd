<?php



class UserController extends BaseController {

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
		return View::make('users/login');
	}
	function postLogin(){		
		try
		{
			$credentials = array(
				'email'    => Input::has('email') ? Input::get('email') : null,
				'password' => Input::has('password') ? Input::get('password') : null,
				);

			$user = Sentry::authenticate($credentials, Input::has('remember_me') and Input::get('remember_me') == 'checked');

			echo Sentry::Check();
			return;
		}
		catch (Cartalyst\Sentry\Users\LoginRequiredException $e){
			$error_message = 'Login field is required.';
		}
		catch (Cartalyst\Sentry\Users\PasswordRequiredException $e){
			$error_message = 'Password field is required.';
		}
		catch (Cartalyst\Sentry\Users\WrongPasswordException $e){
			$error_message = 'Wrong password, try again.';
		}
		catch (Cartalyst\Sentry\Users\UserNotFoundException $e){
			$error_message = 'User was not found.';
		}
		catch (Cartalyst\Sentry\Users\UserNotActivatedException $e){
			$error_message = 'User is not activated.';
		}
		catch (Cartalyst\Sentry\Throttling\UserSuspendedException $e){
			$error_message = 'User is suspended.';
		}
		catch (Cartalyst\Sentry\Throttling\UserBannedException $e){
			$error_message = 'User is banned.';
		}
		if (!Sentry::Check()) {
			return Redirect::to('users/login')->with('error_message', $error_message);
		}

	}

	public function getLogout(){
		Sentry::logout();
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
				echo "Activated";
			}else{
				echo "Not activated";
			}
		}
		catch (Cartalyst\Sentry\Users\UserNotFoundException $e){
			echo 'User was not found.';
		}
		catch (Cartalyst\Sentry\Users\UserAlreadyActivatedException $e){
			echo 'User is already activated.';
		}
	}

	public function getInvite(){
		$user = Sentry::getUser();
		return View::make('users/invite')->with('user', $user);
	}

	public function postInvite(){
		try{
			Sentry::findUserByLogin(Input::get('email'));
				//  add new shifts to user here
			echo 'add new shifts to existing user';
		}
		catch (Cartalyst\Sentry\Users\UserNotFoundException $e){

			$key = sha1(microtime(true).mt_rand(10000,90000));
			$user = Sentry::getUser();
			$invite = new Invite;

			$invite->email = Input::has('email') ? Input::get('email') : null;
			$invite->message = Input::has('message') ? Input::get('message') : null;
			$invite->manager = $user->id;
			$invite->accepted = false;
			$invite->code = $key;

			$invite->save();
			$activateLink = URL::to('/users/accept').'/'.$key.'/'.$invite->id;
			echo $activateLink;

		}
	}

	public function getAccept($code, $id){

		$invite = Invite::find($id);

		if ($invite->check($code)){
			return View::make('users/accept')->with('invite', $invite);
		}else{
			echo "Something has gon Wrong!!";
		}
	}

	public function postAccept(){
		
		$invite = Invite::find(Input::get('invite'));
		if ($invite->check(Input::get('key'))) {
			

			

			try
			{
				$credentials = array(
					'email'    => $invite->email,
					'password' => Input::has('password') ? Input::get('password') : null,
					'first_name' => Input::has('firstname') ? Input::get('fristname') : null,
					'last_name' => Input::has('lastname') ? Input::get('lastname') : null,
					'organization' => "!!get organization users@postAccept!!",
					'role' => 'user',
					'activated' => true
					);
				$user = Sentry::createUser($credentials);
				$adminGroup = Sentry::findGroupById(3);
				$user->addGroup($adminGroup);

					
			}
			catch (Cartalyst\Sentry\Users\LoginRequiredException $e){
				echo 'Login field is required.';
			}
			catch (Cartalyst\Sentry\Users\PasswordRequiredException $e){
				echo 'Password field is required.';
			}
			catch (Cartalyst\Sentry\Users\UserExistsException $e){

				//If user already registred
				//  add new shifts to user here

				echo 'User with this login already exists.';
			}
			catch (Cartalyst\Sentry\Groups\GroupNotFoundException $e){
				echo 'Group was not found.';
			}

		}else{
			echo "error";
		}
	}



}
