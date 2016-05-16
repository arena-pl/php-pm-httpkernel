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

        $app = require "./src/app.php";
        require './config/prod.php';
        require './src/controllers.php';

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