<?php

namespace App;

use Nette,
	Nette\Application\Routers\RouteList,
	Nette\Application\Routers\Route,
	Nette\Application\Routers\SimpleRouter;


/**
 * Router factory.
 */
class RouterFactory {

	/**
	 * @return \Nette\Application\IRouter
	 */
	public static function createRouter() {
		$router = new RouteList();
		$router[] = new Route('<presenter>/<action>[/<id>]', array(
			'presenter' => array(
				Route::VALUE => 'Homepage',
				Route::FILTER_TABLE => array(
					// řetězec v URL => presenter
					'kontakt' => 'contact',
					'skola' => 'school',
					'trida' => 'group',
					'vysledky' => 'result'
				),
			),
			'action' => 'default',
			'id' => NULL,
		));
		//$router[] = new Route('<presenter>/<action>[/<id>]', 'Homepage:default');
		return $router;
	}

}
