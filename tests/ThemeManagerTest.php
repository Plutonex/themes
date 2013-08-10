<?php

use Plutonex\Themes\ThemeManager;

class ThemeManagerTest extends PHPUnit_Framework_TestCase
{

	public function testGetThemeLayoutPath()
	{
		$this->assertEquals($this->themeManager->getLayoutPath(),'testTheme.layouts.testLayout');
	}

	protected function setUp()
	{
		$themeConfig = $this->getMock("\\Plutonex\\Themes\\ThemeConfig",array('getTheme','getLayout'));

		$themeConfig->expects($this->once())->method('getTheme')->will($this->returnValue('testTheme'));
		$themeConfig->expects($this->once())->method('getLayout')->will($this->returnValue('testLayout'));

		$this->themeManager = new ThemeManager($themeConfig);
	}
}