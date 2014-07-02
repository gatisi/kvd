<?php



class UserController extends BaseController {

	public function __construct() {
		$this->beforeFilter('auth', array('only'=>array('getLogout', 'getStatus', 'getInvite', 'postInvite')));
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

	private function InviteUser($email, $first_name, $last_name, $message){
		try{
			$user = Sentry::findUserByLogin($email);
				//  add new shifts to user here
			echo 'add new shifts to existing user';
		}
		catch (Cartalyst\Sentry\Users\UserNotFoundException $e){

			$key = sha1(microtime(true).mt_rand(10000,90000));
			$user = Sentry::getUser();
			$invite = new Invite;

			$user = Sentry::createUser(array(
				'email'     => $email,
				'activated' => false,
				'first_name' => $first_name,
				'last_name' => $last_name,
				'password' => $key
				));

			$code = $user->getResetPasswordCode();

			$invite->email = $email;
			$invite->message = $message;
			$invite->manager = $user->id;
			$invite->accepted = false;
			$invite->code = $code;
			$invite->save();

			$activateLink = URL::to('/users/accept').'/'.$code.'/'.$invite->id;
			echo $activateLink;
			//email to user
		}
	}

	public function postInvite2(){
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
				$this->InviteUser($email, $first_names[$key], $last_names[$key], $message);
			}else{

			}
		}
	}

	public function getAccept($code, $id){

		if($invite = Invite::find($id)){
			if ($invite->check($code)){
				return View::make('users/accept')->with('invite', $invite)->with('code', $code);
			}else{
				echo "Something has gon Wrong!!";
			}
		}
		else{
			echo "Something has gon Wrong!!";
		}

	}

	public function postAccept(){
		try
		{
			$user = Sentry::findUserByLogin(Input::get('email'));
			$code = Input::get('code');
			if ($user->checkResetPasswordCode($code))
			{
				if ($user->attemptResetPassword($code, Input::get('password')))
				{
            		$user->activated = true;
            		$user->first_name = Input::has('firstname') ? Input::get('firstname') : null;
            		$user->last_name = Input::has('lastname') ? Input::get('lastname') : null; 
            		$user->save();
            		echo 'you can log in now!';         		
				}
				else
				{
            		echo "faile";
				}
			}
			else
			{
        		echo "wrong code";
			}
		}
		catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
		{
			echo 'User was not found.';
		}

	}

	public function getTest(){
		$contacts = User::find(2)->contacts;
		foreach ($contacts as $key => $value) {
			echo  $value->email.'<br>';
		}
	}



}
