<?php

namespace App\Helpers;

use Addendum\Annotation;
use Addendum\ReflectionAnnotatedClass;
use Addendum\ReflectionAnnotatedMethod;
use Auth;
use Session;
use Route;

/**
 * This Summary block
 *
 * Class AccessRules
 * @package common\helpers
 *
 * @author A Vijay <vijay.a@technoduce.com>
 */
class AccessRules
{

    const ADMIN_MODULE = 'AdminModule';

    /**
     * @var array list of Route name to exclude while performing [[AccessRules::check()]]
     */
    private static $whiteList = [
        'admin-logout',
        'admin-dashboard'
    ];

    /**
     * Returns the array of access rule's.
     *
     * Method will return array of access rule's available for the given $app and filter the
     * module based on the $moduleFilter. by default working applications rules will be returned without
     * filtering
     *
     * @param null|string $app
     * @param array $moduleFilter
     * @return array
     * @throws \ReflectionException
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidParamException
     */
    public static function getRulesIndex($app = null, array $moduleFilter = [])
    {
        if ($app === null) {
            $app = 'admin';
        }
        $app = ucfirst($app);
        
        $controllers = $controllerMethods = [];

        
        $directories[] = app_path("Http/Controllers/$app/*Controller.php");

        $namespace = self::getNamespace($app);
        
        foreach (self::listAllFiles($directories) as $controller) {
            $class = basename($controller, '.php');
            
            
            $controllerSlug = str_replace('Controller', '', lcfirst($class));

            $controllers[] = $controllerSlug;
            $class = "$namespace\\$class";

            $reflection = new ReflectionAnnotatedClass($class);
            
            /**
             * Skip Admin Modules from Access Rules!
             */
            if ($reflection->hasAnnotation('AdminModule')) {
                continue;
            }

            $classTitle = $controllerSlug;
            if ($reflection->hasAnnotation('Title')) {
                $classTitle = $reflection->getAnnotation('Title')->value;
            }
            
            $reflection = new \ReflectionClass($class);

            $methods = $reflection->getMethods();

            
            foreach ($methods as $method) {
                if ($method->class !== $class) {
                    # Skip if the method/function is from extended class
                    continue;
                }
                $parameters = [];
                
                foreach($method->getParameters() AS $arg)
                {
                    if ($arg->hasType()) { 
                        continue;
                    }
                    $parameters[] = $arg->name;
                }
                if (($ruouteName = self::getRouteName($class, $method->name)) === false ) {
                    continue;
                }

                $reflection = new ReflectionAnnotatedMethod($class, $method->name);

                if ($moduleFilter !== [] && !$reflection->hasAnnotation('backendModule')) {
                    $excludeAnnotation = true;

                    foreach ($moduleFilter as $filter) {
                        if ($reflection->hasAnnotation($filter)) {
                            $excludeAnnotation = false;
                            break;
                        }
                    }
                    if ($excludeAnnotation) {
                        continue;
                    }
                }

                if ($reflection->hasAnnotation('Title') === false) {
                    # Skip the action if annotation is not present
                    continue;
                }

                $methodTitle = $reflection->getAnnotation('Title')->value;
                // $methodSlug = str_replace('action', '', $method->name);

                // $controllerSlug = Inflector::camel2id($controllerSlug);
                // $methodSlug = Inflector::camel2id($methodSlug);

                if (!array_key_exists($controllerSlug, $controllerMethods)) {
                    $controllerMethods[$controllerSlug] = [
                        'title' => $classTitle,
                        'slug' => $controllerSlug,
                        'methods' => []
                    ];
                }
                $controllerMethods[$controllerSlug]['methods'][] = [
                    'title' => $methodTitle,
                    'slug' => $ruouteName
                ];
            }
        }

        return [
            'controllers' => $controllers,
            'methods' => $controllerMethods
        ];
    }

    /**
     * Returns array of files path for given $directories.
     *
     * @param array $directories
     * @return array
     */
    public static function listAllFiles(array $directories)
    {
        $files = [];
        foreach ($directories as $dir) {
            foreach (glob($dir) as $file) {
                $files[] = $file;
            }
        }
        return $files;
    }

    /**
     * Checks the accessibility
     *
     * @param null $actionId
     * @param bool $noRecursive
     * @return bool
     * @throws Exception
     * @throws \ReflectionException
     * @throws \Throwable
     */
    public static function check($actionId = null, $noRecursive = false)
    {
        if ($actionId === null) {
            $actionId = Route::current()->getActionName();
        }

        /**
         * Allowing white listed modules
         */
        if (in_array($actionId, self::$whiteList, true)) {
            return true;
        }
        
        if (!Auth::guard(APP_GUARD)->check()) {
            return false;
        }
        /* @var $userIdentity AdminUser */
        $userIdentity = auth()->guard(APP_GUARD)->user();
        
        /**
         * Allow access to owners
         */
        if (
            APP_GUARD === GUARD_ADMIN && 
            (int)$userIdentity->user_type === ADMIN
        ) {
            return true;
        }
        
        if (Session::has(SES_ROLE_JSON)) {
            $rules = Session::get(SES_ROLE_JSON);            
            $rules = json_decode($rules, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $rules = [];
            }
        } else {
            return false;
        }
        
        list($controller, $action) = explode('@', $actionId);
        // if (($routeName = self::getRouteName($controller, $action)) === false) {
        //     return false;
        // }
        $routeNames = [];
        $routeNames[] = self::getRouteName($controller, $action);

        $class = last(explode('\\', $controller));
        $namespace = str_replace("\\$class", '', $controller); #Route::current()->action['namespace'];
        // $controller = lcfirst($class);
        $method = $action;

        /**
         * Block Access to "Admin Modules"
         * if current login user type is not Super Admin
         */
        try {
            $reflection = new ReflectionAnnotatedClass("$namespace\\$class");
        } catch (\Exception $exception) {
            echo "$namespace\\$class";
            echo "$namespace\\$class";
            die('Please contact your administrator');
        }
        
        $partialModule = false;
        if (
            (int)$userIdentity->user_type !== ADMIN &&
            $reflection->hasAnnotation('AdminModule')
        ) {
            if ( $reflection->getAnnotation('AdminModule')->value !== 'partial') {
                return false;
            }
            $partialModule = true;
        }        

        /**
         * Now checking cached rules
         */
        switch ($action) {
            case 'store':
                $routeNames[] = self::getRouteName($controller, 'create');
                break;
            case 'update':
                $routeNames[] = self::getRouteName($controller, 'edit');
                break;
        }

        foreach ($routeNames as $routeName) {
            if (($index = array_search($routeName, array_column($rules, 'id'), true)) !== false) {
                $rules = $rules[$index];
                return (int)$rules['state'] === 1;
            }                
        }
        

        if (!$reflection->hasMethod($method)) {
            # Allow errors in Yii style!
            return true;
        }        

        $reflection = new ReflectionAnnotatedMethod("$namespace\\$class", $method);        

        if ($partialModule) {
            return $reflection->hasAnnotation('DefaultActionModule');
        }        

        if ($reflection->hasAnnotation('AdminModuleAction')) {
            return false;
        }

        if ($reflection->hasAnnotation('Assoc')) {
            $assocAction = $reflection->getAnnotation('Assoc')->value;
            return self::check("$controller@$assocAction", true);
        } else {
            if (!$reflection->hasAnnotation('Title')) {
                /**
                 * ALLOW method without @Assoc and @Title value...
                 * Since it does not required to apply rules! is not secured!
                 */
                return true;
            }
        }
        return false;
    }

    /**
     * @param $id
     * @return mixed
     */
    private static function format($id)
    {
        return str_replace(' ', '', ucwords(implode(' ', explode('-', $id))));
    }

    /**
     * @param $app
     * @return mixed|string
     * @throws \yii\base\InvalidParamException
     * @throws Exception
     */
    private static function getNamespace($app)
    {
        return "App\Http\Controllers\\$app";

        /**
         * Getting the namespace of annotation performing app, because \ReflectionClass require
         * namespace'd class
         */
        if (($namespace = array_search(Yii::getAlias($app), Yii::$aliases, true)) === false) {
            throw new Exception('Unable to find the request app annotations');
        }

        if (0 === strpos($namespace, '@')) {
            $namespace = substr($namespace, 1);
        }
        return $namespace;
    }

    /**
     * 
     */ 
    private static function getRouteName($controller, $action, $app = null) 
    {
        // print_r(func_get_args());
        if ($app === null) {
            $app = 'admin';
        }

        if ($app === 'admin') {
            foreach(APP_GUARDS as $path => $guard) {
                if (APP_GUARD === $guard) {
                    $app = $path;
                    break;
                }
            }
        }
        
        $routeId = "$controller@$action";
        $route = false;

        foreach(\Route::getRoutes() as $key => $appRoute ) {
            if ($appRoute->getPrefix() !== "/$app" ) {
                continue;
            }
            if ($appRoute->getActionName() === $routeId) {
                $route = $appRoute->getName();
                break;
            }
        }
        // var_dump($controller, $action, $route);exit;
        return $route;
    }

    // private function 
}

/**
 * Class AdminModule
 * @package backend\helpers
 * @ignore
 */
class AdminModule extends Annotation
{
}

/**
 * Class DefaultActionModule
 * @package common\helpers
 * @ignore
 */
class DefaultActionModule extends Annotation
{
}

/**
 * Class AdminModuleAction
 * @package backend\helpers
 * @ignore
 */
class AdminModuleAction extends Annotation
{

}


/**
 * Class Title
 * @package backend\helpers
 * @ignore
 */
class Title extends Annotation
{

}

/**
 * Class Assoc
 * @package backend\helpers
 * @ignore
 */
class Assoc extends Annotation
{

}