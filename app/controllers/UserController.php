<?php



class UserController extends BaseController {

	public function getCadmin(){
		try
		{
			$group = Sentry::createGroup(array(
				'name'        => 'Admin',
				));
		}
		catch (Cartalyst\Sentry\Groups\NameRequiredException $e)
		{
			echo 'Name field is required';
		}
		catch (Cartalyst\Sentry\Groups\GroupExistsException $e)
		{
			echo 'Group already exists';
		}
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
    // Create the user
			$user = Sentry::createUser($credentials);

    // Find the group using the group id
			$adminGroup = Sentry::findGroupById(2);

    // Assign the group to the user
			$user->addGroup($adminGroup);

			$activateLink = URL::to('/users/activate/').'/'.$user->getActivationCode().'/'.$user->getId();
			echo $activateLink;
		}
		catch (Cartalyst\Sentry\Users\LoginRequiredException $e)
		{
			echo 'Login field is required.';
		}
		catch (Cartalyst\Sentry\Users\PasswordRequiredException $e)
		{
			echo 'Password field is required.';
		}
		catch (Cartalyst\Sentry\Users\UserExistsException $e)
		{
			echo 'User with this login already exists.';
		}
		catch (Cartalyst\Sentry\Groups\GroupNotFoundException $e)
		{
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
		catch (Cartalyst\Sentry\Users\LoginRequiredException $e)
		{
			$error_message = 'Login field is required.';
		}
		catch (Cartalyst\Sentry\Users\PasswordRequiredException $e)
		{
			$error_message = 'Password field is required.';
		}
		catch (Cartalyst\Sentry\Users\WrongPasswordException $e)
		{
			$error_message = 'Wrong password, try again.';
		}
		catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
		{
			$error_message = 'User was not found.';
		}
		catch (Cartalyst\Sentry\Users\UserNotActivatedException $e)
		{
			$error_message = 'User is not activated.';
		}

// The following is only required if the throttling is enabled
		catch (Cartalyst\Sentry\Throttling\UserSuspendedException $e)
		{
			$error_message = 'User is suspended.';
		}
		catch (Cartalyst\Sentry\Throttling\UserBannedException $e)
		{
			$error_message = 'User is banned.';
		}
		echo Sentry::Check();
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
	//	echo $code.' - '.$id;
		try
		{
    // Find the user using the user id
			$user = Sentry::findUserById($id);

    // Attempt to activate the user
			if ($user->attemptActivation($code))
			{
				echo "Activated";
			}
			else
			{
				echo "Not activated";
			}
		}
		catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
		{
			echo 'User was not found.';
		}
		catch (Cartalyst\Sentry\Users\UserAlreadyActivatedException $e)
		{
			echo 'User is already activated.';
		}
	}

	public function getInvite(){
		$user = Sentry::getUser();
		return View::make('users/invite')->with('user', $user);
	}
	public function postInvite(){

			$key = sha1(microtime(true).mt_rand(10000,90000));
			$user = Sentry::getUser();
			$invite = new Invite;
			
			$invite->email = Input::has('email') ? Input::get('email') : null;
			$invite->message = Input::has('message') ? Input::get('message') : null;
			$invite->manager = $user->id;
			$invite->accepted = false;
			$invite->code = $key;

			$invite->save();

			echo "done";

				/*
				'manager_email' => $user->email,
				'manager_firs_tname' => $user->first_name,
				'manager_last_name' => $user->last_name,
				*/



		
	}

}
