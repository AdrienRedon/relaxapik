<?php

require_once(ROOT . 'libs/interfaces/session_interface.php');

class Auth 
{
	protected $session;
	protected $user;
	const KEY = 'user';

	public function __construct(SessionInterface $session)
	{
		$this->user = Model::load('user');
		$this->session = $session;
	}

	/**
	 * Determine si l'utilisateur est connecté
	 * @return bool
	 */
	public function check()
	{
		$id = $this->session->get(self::KEY);
		return isset($id) && $this->user->find(['conditions' => 'id = ' . $id]);
	}

	/**
	 * Determine si l'utilisateur est un visiteur
	 * @return bool
	 */
	public function guest()
	{
		return !$this->check();
	}

	/**
	 * Récupère l'utilisateur actuellement connecté
	 * @return User
	 */
	public function user()
	{
		$id = $this->id();
		return $this->user->find(['conditions' => 'id = ' . $id]);
	}

	/**
	 * Récupère l'id de l'utilisateur actuellement connecté
	 * @return id | false
	 */
	public function id()
	{
		$id = $this->session->get(self::KEY);
		return $id;
	}

	/**
	 * Essaie de connecter un utilisateur avec les informations fournies
	 * @param  string $login    Login de l'utilisateur
	 * @param  string $password Mot de passe de l'utilisateur
	 * @return bool
	 */
	public function attempt($login, $password)
	{
		$user = $this->user->find(['conditions' => "login = '$login' AND password = '$password'"]);

		if(isset($user))
		{
			$this->login($user);
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Connecte l'utilisateur
	 * @param  User $user
	 */
	public function login($user)
	{
		$this->session->set(self::KEY, $user->id);
	}

	/**
	 * Déconnecte l'utilisateur actuellement connecté
	 */
	public function logout()
	{
		$this->session->destroy(self::KEY);
	}


}