<?php namespace Plutonex\Themes;

Interface ThemeConfigInterface
{

	public function setLayout($layout);

	public function getLayout();

	public function getDefaultTheme();

	public function getDefaultLayout();

	public function setTheme($theme = null);

	public function hasRulesApplied();

	public function setUriRule($pattern, $layout, $theme = null);

	public function getThemeUriRules();

	public function rulesApplied($bool);

}