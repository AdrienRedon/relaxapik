<?php 

namespace Core;

use \Core\Route;
use \Core\App;
use \Libs\Collection;

/**
 * Get the route
 */
require_once(ROOT . 'routes.php');

class Router 
{

    /**
     * List of routes
     * @var array
     */
    protected $routes = array();

    /**
     * List of named routes
     * @var array
     */
    protected $namedRoutes = array();

    /**
     * Register a new route
     * @param  string $method Name of the HTTP method
     * @param  array $args    List of the arguments for the route
     */
    public function __call($method, $args)
    {
        $method = strtoupper($method);
        if(in_array($method, ['GET', 'POST', 'PUT', 'DELETE']))
        {
            $route = new Route($args[0], $args[1]);
            $this->routes[$method][] = $route;
            if(isset($args[2]))
            {
                $this->namedRoutes[$args[2]] = $route;
            }
            return $route;
        }
    }

    /**
     * Register RESTful route associated to a resource
     * @param  string $name       Name of the resource
     * @param  string $controller Controller associated to the resource
     * @param  array  $options    Options for the routes
     */
    public function resource($name, $controller = null, $options = array())
    {
        if (!$controller)
        {
            $controller = ucfirst($name) . 'Controller';
        }

        if(!(array_key_exists('only', $options) && !in_array('index', $options['only'])) && 
            !(array_key_exists('except', $options) && in_array('index', $options['except'])))
        {
           $this->get($name, $controller . '@index', $name . '.index');        
        }

        if(!(array_key_exists('only', $options) && !in_array('create', $options['only'])) && 
            !(array_key_exists('except', $options) && in_array('create', $options['except'])))
        {
            $this->get($name . '/create', $controller . '@create', $name . '.create');
        }

        if(!(array_key_exists('only', $options) && !in_array('store', $options['only'])) && 
            !(array_key_exists('except', $options) && in_array('store', $options['except'])))
        {
            $this->post($name, $controller . '@store', $name . '.store');
        }

        if(!(array_key_exists('only', $options) && !in_array('show', $options['only'])) && 
            !(array_key_exists('except', $options) && in_array('show', $options['except']))) 
        {
            $this->get($name . '/:id', $controller . '@show', $name . '.show');
        }

        if(!(array_key_exists('only', $options) && !in_array('edit', $options['only'])) && 
            !(array_key_exists('except', $options) && in_array('edit', $options['except'])))
        {
            $this->get($name . '/:id/edit', $controller . '@edit', $name . '.edit');
        }

        if(!(array_key_exists('only', $options) && !in_array('update', $options['only'])) && 
            !(array_key_exists('except', $options) && in_array('update', $options['except'])))
        {
            $this->put($name . '/:id', $controller . '@update', $name . '.update');
        }

        if(!(array_key_exists('only', $options) && !in_array('delete', $options['only'])) && 
            !(array_key_exists('except', $options) && in_array('delete', $options['except'])))
        {
            $this->delete($name . '/:id', $controller . '@delete', $name . '.delete');
        }
    }

    /**
     * Call the action associated to the curent url
     */
    public function run()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if($method == 'POST')
        {
            if(array_key_exists('_method', $_POST) && in_array($_POST['_method'], ['GET', 'PUT', 'DELETE']))
            {
                $method = $_POST['_method'];
            }
        }

        $url = substr($_SERVER['REQUEST_URI'], strlen(WEBROOT));

        if(strpos($url, '?') !== false)
        {
            $url = strstr($url, '?', true);
        }

        foreach ($this->routes[$method] as $route) 
        {
            if($route->match($url))
            {
                return $route->call();
            }
        }
       
        $controller = App::get('Core\Controller');
        return $controller->notFound();
    }

    /**
     * Return the url from a named route
     * @param  string $name   Name of the routes
     * @param  array  $params List of the params for the route
     * @return string         Url of the route
     */
    public function url($name, $params = array())
    {
        if(!array_key_exists($name, $this->namedRoutes))
        {
            return '';
        }
        return $this->namedRoutes[$name]->getUrl($params);
    }
}