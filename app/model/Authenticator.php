<?php

namespace App;

use Kdyby;
use Nette\Security\Passwords;
use Nette;
use Nette\Utils\Strings;

/**
 * Class Authenticator
 */
class Authenticator extends Nette\Object implements Nette\Security\IAuthenticator {

	private $users;

	/**
	 * @param Users $users
	 */
	public function __construct(UsersFacade $users) {
		$this->users = $users;
	}

	/**
	 * @param array $credentials
	 * @return Nette\Security\Identity|Nette\Security\IIdentity
	 * @throws \Nette\Security\AuthenticationException
	 */
	public function authenticate(array $credentials) {
		list($email, $password) = $credentials;
		$user = $this->users->findOneBy(['email' => $email]);

		if (!$user) {
			throw new Nette\Security\AuthenticationException('Email nebyl nalezen.', self::IDENTITY_NOT_FOUND);
		} elseif (!Passwords::verify($password, $user->password)) {
			throw new Nette\Security\AuthenticationException('Zadané heslo není správné.', self::INVALID_CREDENTIAL);
		} elseif (Passwords::needsRehash($user->password)) {
			$user->password = $password; // Passwords::hash($password); -> in entity User
			$this->users->save($user);
		} else {
			$myUser = Nette\Utils\ArrayHash::from(array(
				"firstname" => $user->firstname,
				"name" => $user->firstname . " " . $user->lastname,
				"email" => $user->email,
				"school_id" => $user->school->id
			));
			return new Nette\Security\Identity($user->getId(), $user->role, $myUser);
		}
	}

}
