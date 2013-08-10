## Plutonex Theme

Theme library for Laravel 4.

### How to install
Add dependency to the Laravel composer.json file
```
"require": {
        "plutonex/themes": "1.0.*"
    }
```
and add Service Provider to the app/config/app.php file under array key 'providers'
```
'Plutonex\Themes\ThemesServiceProvider',
```
You are required to create a 'themes' folder under the public directory of laravel app.
For example all your themes should be developed under /public/themes/

Your themes should be basic laravel views inside the themes directory within a directory with theme name.
For example, if you want to create a theme called 'myTheme', the layouts of the theme should be within the path
/public/themes/myTheme/layouts

#### Tip
You can create sub directories under your theme folder, like 'partials' to keep parts of the theme file in common and 'assets' folder to keep all your assets.

### How to use inside Routes
There are many ways to use this library. The easiest way is to set the theme and layout inside a app/routes.php
```php
	
	Route::get('/', function()
	{
		pxTheme::setTheme('myTheme');
		pxTheme::setLayout('default');

		return View::make('hello');
	});
	
	Route::group(array('prefix' => 'admin'),function()
	{
		pxTheme::setTheme('adminTheme');
		pxTheme::setLayout('default');

		Route::get('dashboard', function()
		{
			return View::make('admin.dashboard');
		}

		Route::get('users', function()
		{
			pxTheme::setLayout('list');

			return View::make('admin.dashboard');
		}

	});
```
You can either choose to set theme and layout as shown above or simply bind your URI pattern with theme and layout.
```php
 // app/routes.php 

 // Theme::when({pattern},{layout},{theme});

 Theme::when('/','homePage','myTheme');
 Theme::when('/*','default','myTheme');

 Theme::when('admin','default','adminTheme');
 Theme::when('admin/*','appLayout','adminTheme');
```

### Blade Helpers

#### @px.theme('themeName')
This helps you set the theme within a blade view template

#### @px.layout('layoutName')
This helps you set the theme layout within a blade view 

#### @px.include('path.to.view')
This helps include a view file within the current theme directory


