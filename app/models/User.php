<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;
use Cartalyst\Sentry\Users\Eloquent\User as SentryUserModel;

class User extends SentryUserModel implements UserInterface, RemindableInterface {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password');

	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	public function getAuthIdentifier()
	{
		return $this->getKey();
	}

	/**
	 * Get the password for the user.
	 *
	 * @return string
	 */
	public function getAuthPassword()
	{
		return $this->password;
	}

	/**
	 * Get the token value for the "remember me" session.
	 *
	 * @return string
	 */
	public function getRememberToken()
	{
		return $this->remember_token;
	}

	/**
	 * Set the token value for the "remember me" session.
	 *
	 * @param  string  $value
	 * @return void
	 */
	public function setRememberToken($value)
	{
		$this->remember_token = $value;
	}

	/**
	 * Get the column name for the "remember me" token.
	 *
	 * @return string
	 */
	public function getRememberTokenName()
	{
		return 'remember_token';
	}

	/**
	 * Get the e-mail address where password reminders are sent.
	 *
	 * @return string
	 */
	public function getReminderEmail()
	{
		return $this->email;
	}


	public function contacts()
	{
		$contact = new Contact;
		$contact_pivot = $contact->whereUser_id($this->id)->get()->all();
		foreach ($contact_pivot as $p) {
			$contacts[]=Sentry::findUserById($p['contact_id']);
		}
		return $contacts;
	}

	/* ads new contact to curent user - if pivot not found, pivot added. */
	public function saveContact($id){
		//$contact = Contact::firstOrNew(array('user_id' => $this->id, 'contact_id'=>$id));
		$contact = new Contact;
		if(!$contact->whereUser_idAndContact_id($this->id, $id)->get()->first()&&($this->id!=$id)){
			$contact->user_id = $this->id;
			$contact->contact_id = $id;
			$contact->save();
			return true;
		}else{
			return false;
		}
	}

	public function ShiftPattern(){
		return $this->belongsToMany('ShiftPattern', 'users_shiftplans', 'user_id', 'shiftpattern_id')->withPivot(array('manager'));
	}
}
