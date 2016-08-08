<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

 // Add these import statements:
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as DbTableAuthAdapter;

 // Add these import statements:
 use Application\Model\User;
 use Application\Model\UserTable;
 use Zend\Db\ResultSet\ResultSet;
 use Zend\Db\TableGateway\TableGateway;



class Module implements AutoloaderProviderInterface
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }
/*
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }


    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    */

    // Add this method:
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
            // if we're in a namespace deeper than one level we need to fix the \ in the path
                    __NAMESPACE__ => __DIR__ . '/src/' . str_replace('\\', '/' , __NAMESPACE__),
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getServiceConfig()
    {
        return array(

    // setting db config immediately if necessary, ignore if already defined in global.php
    //   'db' => array(
    //  'username' => 'YOUR USERNAME HERE',
    //  'password' => 'YOUR PASSWORD HERE',
    //  'driver'         => 'Pdo',
    //  'dsn'            => 'mysql:dbname=zf2tutorial;host=localhost',
    //  'driver_options' => array(
    //      PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
    //  ),
    //    ),

            'factories'=>array(
        'Application\Model\UserTable' =>  function($sm) {
                     $tableGateway = $sm->get('UserTableGateway');
                     $table = new UserTable($tableGateway);
                     return $table;
                 },
                 'UserTableGateway' => function ($sm) {
                     $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                     $resultSetPrototype = new ResultSet();
                     $resultSetPrototype->setArrayObjectPrototype(new User());
                     return new TableGateway('user', $dbAdapter, null, $resultSetPrototype);
                 },

//       'Zend\Db\Adapter\Adapter'
  //                  => 'Zend\Db\Adapter\AdapterServiceFactory',

        'Application\Model\MyAuthStorage' => function ($sm) {
            return new \Application\Model\MyAuthStorage('zf_tutorial');
        },

        'AuthService' => function ($sm) {
            $dbAdapter      = $sm->get('Zend\Db\Adapter\Adapter');
                    $dbTableAuthAdapter  = new DbTableAuthAdapter($dbAdapter, 'user','username','password'/*, 'MD5(?)'*/);

            $authService = new AuthenticationService();
            $authService->setAdapter($dbTableAuthAdapter);
            $authService->setStorage($sm->get('Application\Model\MyAuthStorage'));

            return $authService;
        },
            ),
        );
    }

}
