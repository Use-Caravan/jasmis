<?php

namespace App\Helpers;

use URL;
use Request;
use Illuminate\Support\Facades\Route;
use App\Helpers\AccessRules;

/**
 * SideBarMenus class is an widget used to construct the side bar
 * for the application
 *
 * @author A Vijay <vijay.a@technoduce.com>
 */
class SidebarMenu
{
    /**
     * @var array
     */
    private static $menus = [];
    /**
     *
     * @var array
     */
    private static $menuIndex = [];
    private static $_menuCache;
    private static $routes = [];

    /**
     *
     */
    private static function init()
    {
        self::$menuIndex = array_keys(self::$menus);
        natsort(self::$menuIndex);
    }

    /**
     * @param $requestPage
     * @return bool
     * @throws \ReflectionException
     * @throws \yii\base\Exception
     */
    public static function accessCheck($requestPage)
    {
        if (self::$routes === []) {
            $apps = [];
            foreach(APP_GUARDS as $route => $guard) {
                $apps[] = "/$route";
            }
            foreach(\Route::getRoutes() as $key => $route) {
                if (!in_array($route->getPrefix(), $apps)) {
                    continue;
                }
                self::$routes[$route->getName()] = $route->getActionName();
            }
        }
        if (substr($requestPage, 0, 1) !== '#') {
            if (isset(self::$routes[$requestPage])) {
                return AccessRules::check(self::$routes[$requestPage]);
            }
            
        }
        return true;
    }

    /**
     * @param $href
     * @return bool
     */
    public static function isActive($href)
    { 
        if ( $href == url()->current() ) {
            return true;
        }
        return false;
    }

    /**
     * @param $title
     * @param $href route name
     * @param null $icon
     * @param null $pos
     * @return float|int|string
     * @throws \ReflectionException
     * @throws \yii\base\Exception
     */
    public static function add($title, $href, $icon = null, $pos = null)
    {        
        if (!self::accessCheck($href)) {
            return -1;
        }        
        $item = array(
            'title' => $title,
            'slug' => strtolower(str_replace(array(' '), '-', $title)),
            'href' => $href,
            'icon' => $icon,
            'active' => self::isActive($href) ? 'active' : null
        );

        $id = count(self::$menus);
        $id = $pos === null ? ++$id : $id;

        while (true) {
            if (!array_key_exists("menu_$id", self::$menus)) {
                break;
            }
            if (is_float($id)) {
                $id += .1;
            } else {
                $id++;
            }
        }

        $id = "menu_$id";
        self::$menus[$id] = $item;

        return $id;
    }


    /**
     *
     * @param string $menuId
     * @param string $title
     * @param string $href route name
     * @return string
     * @throws \yii\base\InvalidParamException
     * @throws \yii\base\Exception
     */
    public static function addSub($menuId, $title, $href)
    {
        if (!self::accessCheck($href)) {

            return null;
        }
        $item = array(
            'title' => $title,
            'href' => ($href != '#') ? route($href) : '#',
            'active' => self::isActive($href) ? 'active' : null
        );

        if (in_array($menuId, self::$menus)) {
            if (!array_key_exists('subs', self::$menus[$menuId])) {
                self::$menus[$menuId]['subs'] = array();
            }

            $id = count(self::$menus[$menuId]['subs']);
            $id = 'menu_sub1_' . (++$id);
            self::$menus[$menuId]['subs'][$id] = $item;

            return $id;
        }
        if (!array_key_exists('subs', self::$menus[$menuId])) {
            self::$menus[$menuId]['subs'] = array();
        }

        $id = count(self::$menus[$menuId]['subs']);
        $id = 'menu_sub1_' . (++$id);
        self::$menus[$menuId]['subs'][$id] = $item;

        return $id;

    }

    /**
     *
     */
    private static function processMenus()
    {
        $level2 = false;
        $level3 = false;

        foreach (self::$menus as $index => $menu) {
            # level2
            if (array_key_exists('subs', $menu)) {
                foreach ((array)$menu['subs'] as $index1 => $menu1) {
                    if ($menu1['active']) {
                        $menu['active'] = 'active';
                    }
                    # level3
                    if (array_key_exists('subs', $menu1)) {
                        foreach ((array)$menu1['subs'] as $menu2) {
                            if ($menu2['active']) {
                                $menu1['active'] = 'active';
                                $level3 = true;
                            }
                        }
                    }
                    $menu['subs'][$index1] = $menu1;
                    if ($level3) {
                        $menu['active'] = 'active';
                    }
                    $level3 = false;
                }

            }
            self::$menus[$index] = $menu;
        }
    }


    private static function subMenu($menuArray, &$html, $level3 = false)
    {                        
        if (array_key_exists('subs', $menuArray)) {
                        
            $param = Request::segment(3);

            $style = "";  
            $param = Request::segment(3);
            $routeC = ($menuArray['href'] != '#') ? route($menuArray['href']) : '#';

            $prefixIsset = false;
            foreach (APP_GUARDS as $route => $guard) {                
                if (request()->is("$route/*")) {
                    $prefixIsset = true;
                    break;
                }
            }
            if($prefixIsset) {
                $my_route = str_replace(URL::to('admin'),"",Request::url());
                $dynamicRoute = str_replace(URL::to('admin'),"", $routeC);   
                if($dynamicRoute == $my_route) {
                    $style .= 'style="display:block"';
                }                                   
                if(isset($menuArray['subs'])) {
                    foreach($menuArray['subs'] as $key => $value) {                           
                        $routeC = ($value['href'] != '#') ? $value['href'] : '#';
                        $my_route = str_replace(URL::to('admin'),"",Request::url());
                        $dynamicRoute = str_replace(URL::to('admin'),"", $routeC);
                        if($dynamicRoute == $my_route) {
                            $style .= 'style="display:block"';
                        }
                        if (strpos($my_route, $dynamicRoute) !== false) {
                            $style .= 'style="display:block"';
                        }   
                    }
                }                    
            }   

            
            $html .= '<ul class="treeview-menu" '.$style.' id="' . substr($menuArray['href'], 1) . '">';
            $endTag = '</ul>';

            $level2ActiveSet = false;
            foreach ((array)$menuArray['subs'] as $menu) {

                $active = $menu['active'];
                $liActive = '';
                $aActive = '';
                if ($active && $level3) {
                    $liActive = $active;
                } elseif ($active && !$level2ActiveSet) {
                    $level2ActiveSet = true;
                    $aActive = $active;
                    $liActive = $active;
                } else {
                    $aActive = $active;
                }              

                $routeC = ($menu['href'] != '#') ? $menu['href'] : '#';
                $my_route = str_replace(URL::to('admin'),"",Request::url());
                $dynamicRoute = str_replace(URL::to('admin'),"", $routeC);
                if($dynamicRoute == $my_route) {
                    $liActive = 'active';
                }                
                if (strpos($my_route, $dynamicRoute) !== false) {
                    $liActive = 'active';
                }                  

                $html .= <<<HTML
<li ><a  href="{$menu['href']}" class="$liActive"><i class="fa fa-circle-o"></i>{$menu['title']}</a>
HTML;
                if (array_key_exists('subs', $menu)) {
                    self::subMenu($menu, $html, true);
                }
                $html .= '</li>';
            }
            //$html .= '<li class="more"> <a class="more_link"><i class="fa fa-ellipsis-v"></i></a> <ul id="overflow"> </ul> </li>';
            $html .= $endTag;
        } else {
            
        }
    }

    /**
     *
     * @param integer $echo
     * @return string
     */
    public static function render($echo = 1)
    {
        $html = '';
        self::init();
        self::processMenus();        
        foreach (self::$menuIndex as $index) {
            $menu = self::$menus[$index];           

            $active = $menu['active'] === null ? '' : 'active';

            $hasSub = '';
            $aTagClass = '';
            $attributes = '';
            $subIcon = "<i class='fa fa-{$menu['icon']}'></i>";
            $subTag = '';
            if (array_key_exists('subs', $menu)) {

                $hasSub = 'treeview';                                                                                

                $aTagClass = '';
                $attributes = '';# 'data-toggle="collapse"';
                # $subIcon = '<i class="fa fa-angle-down"></i>';
                $subTag = '<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>';
            }
            
            $menu['title'] = ucfirst($menu['title']);
            
            $href = ($menu['href'] == '#') ? '#' : route($menu['href']);
            
            /*if ($href === '#') {
                $href = 'javascript:void(0)';
            }*/      

            $param = Request::segment(3);
            $routeC = ($menu['href'] != '#') ? route($menu['href']) : '#';
            $my_route = str_replace(URL::to('admin'),"",Request::url());
            $dynamicRoute = str_replace(URL::to('admin'),"", $routeC);                                                
            $notSubActive = '';
            if (strpos($my_route, $dynamicRoute) !== false) {
                $notSubActive = "active";
            }
            $prefixIsset = false;
            foreach (APP_GUARDS as $route => $guard) {                
                if (request()->is("$route/*")) {
                    $prefixIsset = true;
                    break;
                }
            }
            if($prefixIsset) {            
                $my_route = str_replace(URL::to('admin'),"",Request::url());
                $dynamicRoute = str_replace(URL::to('admin'),"", $routeC);   
                if($dynamicRoute == $my_route) {
                    $hasSub .= " menu-open";
                }
                if(isset($menu['subs'])) {
                    foreach($menu['subs'] as $key => $value) {                           
                        $routeC = ($value['href'] != '#') ? $value['href'] : '#';
                        $my_route = str_replace(URL::to('admin'),"",Request::url());
                        $dynamicRoute = str_replace(URL::to('admin'),"", $routeC);                                                
                        if($dynamicRoute == $my_route) {
                            $hasSub .= " menu-open";
                        }
                        if (strpos($my_route, $dynamicRoute) !== false) {
                            $hasSub .= " menu-open";
                        }
                    }
                }                    
            }                            
            $html .= <<<HTML
__MENU__
<li class="$hasSub" >
    <a href="{$href}" class="$aTagClass $notSubActive" $attributes>
        {$subIcon}
        {$menu['title']}
        {$subTag}
    </a>
HTML;

            self::subMenu($menu, $html);

            /**
             * Removing dropdown menu that doesn't have child
             * Mainly used for AccessRules based menu building
             */
            if (
                ($href === 'javascript:void(0)' || substr($href, 0, 1) === '#')
               && !array_key_exists('subs', $menu)
            ) {
                $tempHtml = explode('__MENU__', $html);
                array_pop($tempHtml);
                $html = implode('__MENU__', $tempHtml);
                continue;
            }
            $html .= '</li>';
        }

        $html = str_replace('__MENU__', '', $html);

        self::setMenuCache(self::$menus);
        self::$menus = null;
        if ($echo) {
            echo $html;
        }
        return $html;
    }


    /**
     * @param $href
     * @return bool
     * @deprecated since version number
     */
    public static function addSub2($menuId, $menuId2, $title, $href)
    {
        $item = array(
            'title' => $title,
            'href' => $href,
            'active' => self::isActive($href) ? 'active' : null
        );

        if (!array_key_exists('subs', self::$menus[$menuId]['subs'][$menuId2])) {
            self::$menus[$menuId]['subs'][$menuId2]['subs'] = array();
        }

        $id = count(self::$menus[$menuId]['subs'][$menuId2]['subs']);
        $id = 'menu_sub2_' . (++$id);

        self::$menus[$menuId]['subs'][$menuId2]['subs'][$id] = $item;

        return $id;
    }

    /**
     *
     * @param string $menuTitle
     * @return mixed
     */
    public static function search($menuTitle)
    {
        $menuId = false;
        foreach (self::$menus as $key => $menu) {
            if ($menu['title'] === $menuTitle) {
                $menuId = $key;
                break;
            }
        }
        return $menuId;
    }

    /**
     * @return null
     */
    public static function getMenuCache()
    {
        return self::$_menuCache;
    }

    /**
     * @param null $menuCache
     */
    public static function setMenuCache($menuCache)
    {
        self::$_menuCache = $menuCache;
    }
}