<?php namespace Plutonex\Themes;

use Illuminate\Support\ServiceProvider;


class ThemesServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->package('plutonex/themes', 'pxTheme');

        //set theme layout directory
        $paths     = $this->app['config']->get('view.paths');
        $themePath = $this->app->config->get('pxTheme::config.themes_path');
        array_push($paths, $themePath);
        $this->app['config']->set('view.paths', $paths);

        //bind themeConfig object
        $this->app['px.themeConfig'] = $this->app->share(
           function ($app) {
               return new ThemeConfig();
           }
        );


        $this->app->bind(
           'px.theme',
           function ($app) {
               return new ThemeManager($app['px.themeConfig']);
           }
        );

        //extend blade engine by adding @px.theme and @px.layout compile function
        $this->app['view.engine.resolver']->resolve('blade')->getCompiler()->extend(
           function ($view) {
               $themePattern         = '/(?<!\w)(\s*)@px.theme(\s*\(.*\))/';
               $layoutPattern        = '/(?<!\w)(\s*)@px.layout(\s*\(.*\))/';
               $layoutIncludePattern = '/(?<!\w)(\s*)@px.include(\s*\(.*)\)/';

               $view = preg_replace($themePattern, '$1<?php pxTheme::setTheme$2;?>', $view);
               $view = preg_replace($layoutPattern, '$1<?php pxTheme::setLayout$2;?>', $view);


               $view = preg_replace(
                  $layoutIncludePattern,
                  '$1<?php echo $__env->make(pxTheme::path$2), array_except(get_defined_vars(), array(\'__data\', \'__path\')))->render(); ?>',
                  $view
               );


               $view = preg_replace($layoutPattern, '$1<?php pxTheme::setLayout$2;?>', $view);

               return $view;
           }
        );


    }


    public function boot()
    {
        $this->setFilter();
    }


    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array();
    }


    /**
     * Register Theme Filter
     *
     * @return [type] [description]
     */
    protected function setFilter()
    {
        $ThemeLib = $this->app['px.theme'];
        $app      = $this->app;

        $this->app['router']->after(
           function ($request, $response) use ($ThemeLib, $app) {

               // Redirects have no content and errors should handle their own layout.
               if ($response->getStatusCode() > 300) return;

               //JsonResponse
               if (is_a($response, 'Illuminate\Http\JsonResponse')) return;

               $themeRules = $this->app['px.themeConfig']->getThemeUriRules();

               if (!empty($themeRules)):
                   foreach ($themeRules as $pattern => $option):
                       if ($request->is($pattern)):
                           $ThemeLib->setTheme($option['theme']);
                           $ThemeLib->setLayout($option['layout']);
                       endif;
                   endforeach;
               else:
                   //if no rules are set, sets default theme
                   $app['px.themeConfig']->rulesApplied(false);

                   //Support AJAX and pJax
                   $HTTP_X_PJAX  = $request->server->get('HTTP_X_PJAX');
                   $AJAX         = ($request->ajax()) ? true : $HTTP_X_PJAX;
                   $ajax_support = $app->config->get('pxTheme::config.ajax_support');

                   if ($AJAX && $ajax_support):
                       $_layout = $app->config->get('pxTheme::config.ajax_layout');

                       $app['px.themeConfig']->setLayout($_layout);
                       $app['px.themeConfig']->setTheme();
                   else:
                       $app['px.themeConfig']->setTheme();
                   endif;
               endif;

               $layout = $ThemeLib->getLayoutPath();

               //get original view object
               $view = $response->getOriginalContent();

               if (is_object($view)):
                   //we will render the view nested to the layout
                   $content = $app['view']->make($layout)->nest('_content', $view->getName(), $view->getData())->render();

               else:
                   //when response is returned without a view, we set no themes
                   $content = $view;
               endif;

               $response->setContent($content);

           }
        );

    }

}