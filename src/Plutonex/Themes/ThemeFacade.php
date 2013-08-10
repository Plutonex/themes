<?php namespace Plutonex\Themes;

use Illuminate\Support\Facades\Facade;

class ThemeFacade extends Facade {

	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor() { return 'px.theme'; }

}