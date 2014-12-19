<?php
namespace system;

/**
 * Class which is able to use the Base registry
 * @author Vincent Quatrevieux
 * @see \system\Base
 * @property \system\Loader $loader The loader
 * @property \system\Config $config Configuration
 * @property \system\output\Output $output Output manager
 * @property \system\input\Input $input Input manager
 * @property \system\helper\HelpersManager $helpers The helpers manager
 * @property \system\Session $session Session handler
 */
abstract class Registry {
    private $base;
    
    public function __construct(Base $base) {
        $this->base = $base;
    }
    
    public function __get($name) {
        return $this->base->$name;
    }
    
    public function __set($name, $value) {
        $this->base->$name = $value;
    }
    
    public function __isset($name) {
        return isset($this->base->$name);
    }
    
    /**
     * Get the Base instance
     * @return \system\Base
     */
    public function getInstance(){
        return $this->base;
    }
}

