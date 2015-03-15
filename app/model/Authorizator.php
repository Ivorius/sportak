<?php

namespace App;

use Nette;
use Nette\Security\Permission;

/**
 * Class Authorizator
 */
class Authorizator implements Nette\Security\IAuthorizator {

	private $acl;

	const CREATE = 'create';
	const READ = 'read';
	const UPDATE = 'update';
	const DELETE = 'delete';
	
	const GUEST = 'guest';
	const TEACHER = 'teacher';
	const ADMIN = 'admin';

	public function __construct() {
		$acl = new Nette\Security\Permission();
// definice rolí
		$acl->addRole(self::GUEST);
		$acl->addRole(self::TEACHER, self::GUEST); // teacher dědí od guest
		$acl->addRole(self::ADMIN, self::TEACHER); // admin od teacher
		//
// seznam zdrojů, ke kterým mohou uživatelé přistupovat
		$acl->addResource('Admin');
		$acl->addResource('Front');
		
// pravidla, určující, kdo co může s čím dělat
		$acl->allow(self::GUEST, 'Front', self::READ);
		$acl->allow(self::TEACHER, 'Front', Permission::ALL);
		$acl->allow(self::ADMIN, Permission::ALL, Permission::ALL);
// Nastaveno!
		$this->acl = $acl;
	}

	/**
	 * @param $role
	 * @param $resource
	 * @param $privilege
	 * @return bool
	 */
	public function isAllowed($role, $resource, $privilege) {
		return $this->acl->isAllowed($role, $resource, $privilege);
	}

	/**
	 * @param $role
	 * @param Nette\Security\User $user
	 * @return bool
	 */
	public function isAtLeastInRole($role, Nette\Security\User $user) {
		$result = TRUE;
		foreach ($user->getRoles() as $userRole) {
			if ($userRole === $role) {
				return TRUE;
			}
			$result &= $this->acl->roleInheritsFrom($userRole, $role);
		}
		return (boolean) $result;
	}
}