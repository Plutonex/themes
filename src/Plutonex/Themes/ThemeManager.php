<?php namespace Plutonex\Themes;


class ThemeManager 
{

	protected $themeConfig;

	protected $layout = null;

	protected $themeName = null;


	public function __construct(ThemeConfigInterface $config)
	{
		$this->config = $config;
	}


	public function path($shortPath)
	{
		$themeName = $this->getTheme();

		return $themeName . '.'.$shortPath;
	}


	/**
	 * Set the layout for the theme
	 * @param string $layout 
	 */
	public function setLayout($layout)
	{
		$this->getThemeConf()->setLayout($layout);
		return $this;
	}


	/**
	 * set a different theme
	 * This method is used to set a theme before the uri rules are called,
	 * and if this method is used to set a theme, uri theme rules will be skipped
	 * @param string $theme
	 */
	public function setTheme($theme = null)
	{
		$this->getThemeConf()->setTheme($theme);
		return $this;
	}


	/**
	 * This method can be used to easily set theme uri rules, which binds a theme and layout to a uri pattern
	 * When theme is NULL, the current theme or default theme will be loaded 
	 * @param  string $pattern 
	 * @param  string $layout  
	 * @param  string $theme   
	 * @return void          
	 */
	public function when($pattern, $layout, $theme = null)
	{
		$this->addPatternRule($pattern, $layout, $theme);
	}


	/**
	 * Get the string layout view path prepared for laravel
	 * @return string
	 */
	public function getLayoutPath()
	{
		$themName = $this->getTheme();
		$layout = $this->getLayout();
		return $themName . '.layouts.' . $layout;
	}

	/**
	 * get the instance of the theme config object
	 * @return Plutonex\Themes\ThemeConfig
	 */
	protected function getThemeConf()
	{
		return $this->config;
	}

	
	
	/**
	 * Get the layout set for the theme in the theme config
	 * @return string 
	 */
	protected function getLayout()
	{
		return $this->getThemeConf()->getLayout();
	}


	/**
	 * Returns the current theme name
	 * @return string
	 */
	protected function getTheme()
	{
		return $this->getThemeConf()->getTheme();
	}
	

	/**
	 * Gets the Default theme
	 * @return string 
	 */
	protected function getDefaultTheme()
	{
		return $this->getThemeConf()->getDefaultTheme();
	}


	/**
	 * This method can be used to set theme uri rules, which binds a theme and layout to a uri pattern
	 * When theme is NULL, the current theme or default theme will be loaded 
	 * @param  string $pattern 
	 * @param  string $layout  
	 * @param  string $theme
	 */
	protected function addPatternRule($pattern, $layout, $theme = null)
	{
		if(is_null($theme))
		{
			$theme = $this->getDefaultTheme();
		}

		$this->getThemeConf()->setUriRule($pattern, $layout, $theme);
	}



	
}

