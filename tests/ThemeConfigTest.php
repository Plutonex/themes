<?php
use Plutonex\Themes\ThemeConfig;

class ThemeConfigTest extends PHPUnit_Framework_TestCase
{

	public function testGetDefaultTheme()
	{
		$this->assertEquals($this->themeConfig()->getDefaultLayout(), 'default');
	}


	public function testCheckRulesApplied()
	{	
		$themeConfig = $this->themeConfig();
		$themeConfig->rulesApplied(true);

		$this->assertEquals($themeConfig->hasRulesApplied(),true);
	}


	public function testSetAndGetThemeUriRules()
	{
		$themeConfig = $this->setThemeUriRules($this->themeConfig());
		
		$expected = array('*' => array('layout' => 'test', 'theme' => 'testTheme'), '/admin' => array('layout' => 'testAdmin', 'theme' => 'testAdminTheme'));
		$this->assertEquals($themeConfig->getThemeUriRules(), $expected);
	}	


	public function testSetThemeShouldReturnEmptyArrayOfUriRules()
	{
		$themeConfig = $this->themeConfig();

		$themeConfig->setTheme('testTheme');

		$themeConfig = $this->setThemeUriRules($themeConfig);

		$this->assertEquals($themeConfig->getThemeUriRules(), array());
	}


	public function testSetAndGetTheme()
	{
		$themeConfig = $this->themeConfig();

		$themeConfig->setTheme('testTheme');

		$this->assertEquals($themeConfig->getTheme(), 'testTheme');

	}

	public function testSetAndGetLayout()
	{
		$themeConfig = $this->themeConfig();

		$themeConfig->setLayout('testLayout');

		$this->assertEquals($themeConfig->getLayout(), 'testLayout');
	}


	public function testGetDefaultThemeAfterThemeIsSet()
	{
		//first get default theme without setting a theme
		$themeConfig = $this->themeConfig();
		$defaultTheme = $themeConfig->getDefaultTheme();
		$this->assertEquals($defaultTheme,'default');

		//now we set a theme and default theme should also return the new theme set
		$themeConfig->setTheme('testTheme');
		$defaultTheme = $themeConfig->getDefaultTheme();
		$this->assertEquals($defaultTheme,'testTheme');
	}


	protected function setThemeUriRules(ThemeConfig $themeConfig)
	{
		$themeConfig->setUriRule('*','test','testTheme');
		$themeConfig->setUriRule('/admin','testAdmin','testAdminTheme');
		return $themeConfig;
	}



	protected function themeConfig()
	{
		return new ThemeConfig;
	}
}