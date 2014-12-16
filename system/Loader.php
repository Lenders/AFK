<?php
namespace system;

/**
 * Loader class
 * @author Vincent Quatrevieux
 * @warning This class is a critical class because this class is loaded before having an error handler.
 */
class Loader{
    /**
     * List of instancied classes
     * @var array
     */
    private $instances = array();
    
    /**
     * The base instance
     * @var \system\Base
     */
    private $base;

    public function __construct() {
        spl_autoload_register(function($class_name){
            $file = $this->getClassFileByName($class_name);
            
            if(!file_exists($file))
                throw new error\ClassNotFoundException('For class name ' . $class_name);
            
            require $file;
            
            if(!class_exists($class_name) && !interface_exists($class_name))
                throw new error\ClassNotFoundException('For class file ' . $file);
        });
        
        $this->addInstance($this);
    }
    
    public function addInstance($obj){
        if($obj == null)
            throw new error\NullPointerException('Null object in \system\Loader::addInstance()');
        
        $this->instances['\\' . get_class($obj)] = $obj;
    }
    
    /**
     * Add to loader the instance of the base application
     * @param \system\Base $base the base application object
     */
    public function setBase(Base $base){
        $this->addInstance($base);
        $this->base = $base;
    }

    private function getClassFileByName($class_name){
        $file = str_replace('\\', DS, $class_name);
        return ROOT . $file . EXT;
    }
    
    /**
     * Load or get an instance of a class
     * @param string $class_name the class name to get or load
     * @return object
     */
    public function load($class_name){
        if(substr($class_name, 0, 1) != '\\')
            $class_name = '\\' . $class_name; //add backslash at the start of string
        
        
        if(isset($this->instances[$class_name]))
            return $this->instances[$class_name];
        
        $instance = $this->newInstance(new \ReflectionClass($class_name));
        $this->instances[$class_name] = $instance;
        
        return $instance;
    }
    
    private function newInstance(\ReflectionClass $class){
        $constructor = $class->getConstructor();
        
        if($constructor === null) //no contructor, use default contructor
            return $class->newInstanceWithoutConstructor();
        
        $args = array();
        
        foreach ($constructor->getParameters() as $param){
            $param_class = $param->getClass();
            
            if($param_class === null)
                throw new error\InvalidClassConstructorException($class->getName(), $param->getName());
            
            $class_name = $param_class->getName();
            
            if($class_name === 'system\Config')
                $obj = $this->getConfigInstance ($class->getName());
            else
                $obj = $this->load ($class_name);

            array_push($args, $obj);
        }
        
        return $class->newInstanceArgs($args);
    }
    
    private function getConfigInstance($class_name){
        $array = explode('\\', $class_name);
        $config = $this->base->config;
        
        foreach($array as $item){
            if(empty($item))
                continue;
            
            $config = $config->$item;
        }
        
        return $config;
    }
}