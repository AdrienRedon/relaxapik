<?php 

namespace App\Core\Controller;

use App\Core\DependencyInjection\ContainerInterface;
use App\Core\DependencyInjection\ContainerAwareInterface;
use App\Core\DependencyInjection\Exception\ServiceNotFoundException;
use App\Core\Controller\Exception\MethodNotFoundException;

class ControllerResolver
{
    /**
     * Container
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Create the controller and return the action
     * 
     * @return array Controller & method
     */
    public function getAction($name)
    {
        $controllerParams = explode('@', $name);

        try {  
            $controller = $this->container->resolve('App\Controller\\' . $controllerParams[0]);
        } catch (ServiceNotFoundException $e) {
            if (file_exists(ROOT . '/app/Controller/' . $controllerParams[0] . '.php')) {
                include_once(ROOT . '/app/Controller/' . $controllerParams[0] . '.php');
                $controllerName = 'App\Controller\\' . $controllerParams[0];
                $controller = new $controllerName();
                $this->container->register($controllerName, $controller);
            } else {
                die(ROOT . '/app/Controller/' . $controllerParams[0] . '.php');
                throw $e; 
            }
        }

        if (!method_exists($controller, $controllerParams[1])) {
            throw new MethodNotFoundException($controllerParams[1]);
        }

        if ($controller instanceof ContainerAwareInterface) {
            $controller->setContainer($this->container);
        }

        return array($controller, $controllerParams[1]);
    }
}