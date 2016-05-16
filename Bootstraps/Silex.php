<?php
/**
 * Created by PhpStorm.
 * User: emgiezet
 * Date: 15/05/2016
 * Time: 00:27
 */

namespace PHPPM\Bootstraps;

use PHPPM\Symfony\StrongerNativeSessionStorage;
use PHPPM\Utils;

class Silex  extends AbstractBootstrap implements HooksInterface
{
    /**
     * @var string|null The application environment
     */
    protected $appenv;

    /**
     * @var boolean
     */
    protected $debug;

    /**
     * Instantiate the bootstrap, storing the $appenv
     */
    public function __construct($appenv, $debug)
    {
        $this->appenv = $appenv;
        $this->debug = $debug;
    }


    /**
     * Create a Symfony application
     *
     * @return \AppKernel
     */
    public function getApplication()
    {
        // include applications autoload
        $appAutoLoader = './app/autoload.php';
        if (file_exists($appAutoLoader)) {
            require $appAutoLoader;
        } elseif (file_exists('./vendor/autoload.php')) {
            require './vendor/autoload.php';
        }
        if(class_exists('\AppKernel')) {
            $app = new \AppKernel($this->appenv, $this->debug);
        } else {
            if(file_exists('./app/bootstrap.php.cache') && file_exists('./app/AppKernel.php')) {
                require_once './app/bootstrap.php.cache';
                require_once './app/AppKernel.php';
                $app = new \AppKernel($this->appenv, $this->debug);
            }


        }
        //since we need to change some services, we need to manually change some services
        //we need to change some services, before the boot, because they would otherwise
        //be instantiated and passed to other classes which makes it impossible to replace them.
        Utils::bindAndCall(function () use ($app) {
            // init bundles
            $app->initializeBundles();

            // init container
            $app->initializeContainer();
        }, $app);

        Utils::bindAndCall(function () use ($app) {
            foreach ($app->getBundles() as $bundle) {
                $bundle->setContainer($app->container);
                $bundle->boot();
            }

            $app->booted = true;
        }, $app);

        //warm up
        $request = new Request();
        $request->setMethod(Request::METHOD_HEAD);
        $app->handle($request);
        $this->postHandle($app);

        return $app;
    }


    public function getStaticDirectory()
    {
        // TODO: Implement getStaticDirectory() method.
    }

    public function preHandle($app)
    {
        // TODO: Implement preHandle() method.
    }

    public function postHandle($app)
    {
        // TODO: Implement postHandle() method.
    }

}