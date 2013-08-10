<?php namespace Plutonex\Themes;


class ThemeConfig implements ThemeConfigInterface
{
	protected $theme = null;

	protected $layout = null;

	protected $default_layout = 'default';

	protected $default_theme = 'default';

	protected $rulesApplied = false;

	protected $skip_uri_rules = false;


	protected $themeUriRules = array(
				
	);


	/**
	 * Sets config status rules applied to true/false, 
	 * This is useful to mark the point when theme uri rules get processed.
	 * When a new theme or layout is set before uri rules are processed, then the uri rules will be skipped
	 * @param  boolean $bool 
	 * @return void       
	 */
	public function rulesApplied($bool)
	{
		$this->rulesApplied = $bool;
	}


	/**
	 * Returns the array of uri rules, if skip uri rules is set to true, it returns an empty array
	 * @return array 
	 */
	public function getThemeUriRules()
	{
		if($this->skip_uri_rules)
		{
			return array();
		}

		return $this->themeUriRules;
	}


	/**
	 * Bind a uri pattern with a layout and theme
	 * @param string $pattern 
	 * @param string $layout  
	 * @param string $theme   
	 */
	public function setUriRule($pattern, $layout, $theme = null)
	{
		
		$this->themeUriRules[$pattern] = array('theme' => $theme, 'layout' => $layout);

		$this->rulesApplied(true);
	}


	/**
	 * Set a theme
	 * @param string $theme 
	 */
	public function setTheme($theme = null)
	{
		if(! $this->hasRulesApplied())
		{
			$this->skip_uri_rules = true;
		}

		if(is_null($theme))
		{
			$theme = $this->getDefaultTheme();
		}

		$this->theme = $theme;
	}


	/**
	 * get the current theme set
	 * @return string 
	 */
	public function getTheme()
	{
		return $this->theme;
	}


	/**
	 * set a layout 
	 * @param string $layout 
	 */
	public function setLayout($layout)
	{
		$this->layout = $layout;
	}


	/**
	 * get current layout, if nothing set then it returns default layout
	 * @return string 
	 */
	public function getLayout()
	{
		if(is_null($this->layout))
		{
			$this->setLayout($this->getDefaultLayout());
		}

		return $this->layout;
	}


	/**
	 * Fetch default theme
	 * @return string 
	 */
	public function getDefaultTheme()
	{
		if(! empty($this->theme))
		{
			return $this->theme;
		}
		return $this->default_theme;
	}

	
	/**
	 * Fetch default layout
	 * @return string 
	 */
	public function getDefaultLayout()
	{
		return $this->default_layout;
	}


	/**
	 * Check if uri theme rules have been applied
	 * @return boolean 
	 */
	public function hasRulesApplied()
	{
		return $this->rulesApplied;
	}

	
}