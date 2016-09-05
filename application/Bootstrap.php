<?php

/**
 * Created by PhpStorm.
 * User: ellipsis
 * Date: 2015/4/3
 * Time: 11:13
 * 该类的所有方法，方法名都以_init为开头，都可以接受一个参数：Yaf\Dispatcher $dispatcher
 * 调用的顺序与声明的顺序一致
 */
class Bootstrap extends Yaf\Bootstrap_Abstract
{

    private $_config;
    public $_db;

    //将配置文件中的配置注册到config
    public function _initConfig()
    {
        //$this->_config = Yaf\Application::app()->getConfig();
        //Yaf\Registry::set('config', $this->_config);
    }

    //session
    public function _initSession(Yaf\Dispatcher $dispatcher)
    {
        Yaf\Session::getInstance()->start();
    }

    public function _initPlugin(Yaf\Dispatcher $dispatcher)
    {
        $user = new UserPlugin();
        $dispatcher->registerPlugin($user);
    }

    public function _initRoute(Yaf\Dispatcher $dispatcher)
    {
        $router = Yaf\Dispatcher::getInstance()->getRouter();

        //rewrite
        $route = new Yaf\Route\Rewrite(
            '/admin/:id', array(
                'module' => 'admin',
                'controller' => 'index',
                'action' => 'test',
            )
        );
        //$router->addRoute('rewrite', $route);

        //admin/login
        $route = new Yaf\Route\Rewrite(
            '/admin', array(
                'module' => 'admin',
                'controller' => 'index',
                'action' => 'index',
            )
        );
        //$router->addRoute('rewrite', $route);

        //regex
        $route = new Yaf\Route\Regex(
            '#admin/([0-9]+).html#', array(
            'module' => 'admin',
            'controller' => 'index',
            'action' => 'test'
        ), array(1 => 'id')
        );
        //$router->addRoute('regex', $route);
    }

}
