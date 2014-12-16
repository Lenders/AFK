<?php

namespace system;

/**
 * Base class, used as registry
 * @author Vincent Quatrevieux
 * @property \system\Loader $loader The loader
 * @property \system\Config $config Configuration
 * @property \system\output\Output $output Output manager
 * @property \system\input\Input $input Input manager
 * @property \system\helper\HelpersManager $helpers The helpers manager
 * @warning This class is a critical class because this class is loaded before having an error handler.
 */
class Base{
    /**
     * Registry
     * @var array
     */
    private $vars = array();
    
    public function __construct(Loader $loader, Config $config) {
        $this->loader = $loader;
        $this->config = $config;
        $this->loader->setBase($this);
        $this->autoload();
    }
    
    private function autoload(){
        foreach($this->config->autoload as $name => $class){
            $obj = $this->loader->load($class);
            
            if(is_string($name))
                $this->$name = $obj;
        }
    }

    public function __get($name) {
        return isset($this->vars[$name]) ? $this->vars[$name] : null;
    }
    
    public function __set($name, $value) {
        $this->vars[$name] = $value;
    }
    
    public function __isset($name) {
        return isset($this->vars[$name]);
    }

    /**
     * Load the router and display the web page
     */
    public function run(){
        $this->output->start();
        
        $this->loader->load('\system\Router')->loadPage($this->config->router);
        
        $this->output->flush();
    }
}

