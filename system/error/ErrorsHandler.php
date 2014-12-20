<?php
namespace system\error;

/**
 * Handler errors
 * @author Vincent Quatrevieux
 * @warning This class is a critical class because this class is loaded before having an error handler.
 */
class ErrorsHandler{
    /**
     * The ErrosHandler's config
     * @var \system\Config
     */
    private $config;
    
    /**
     * The output handler
     * @var \system\output\Output
     */
    private $output;
    
    /**
     * The loader
     * @var \system\Loader
     */
    private $loader;
    
    public function __construct(\system\output\Output $output, \system\Config $config, \system\Loader $loader) {
        
        //fatal error !
        if($config->isEmpty())
            throw new InexistantConfigItemException($config);
        
        $this->config = $config;
        $this->output = $output;
        $this->loader = $loader;


        set_exception_handler(array($this, 'exceptionHandler'));
        set_error_handler(function($errno, $errstr, $errfile, $errline ) {
            if($errno & error_reporting())
                throw new \ErrorException($errstr, 20001, $errno, $errfile, $errline);
        });
        
        //fatal errors
        register_shutdown_function(function(){
            $error = error_get_last();
            
            if(!$error)
                return;
            
            $this->exceptionHandler(new \ErrorException($error['message'], 20002, $error['type'], $error['file'], $error['line']));
        });
    }
    
    public function exceptionHandler(\Exception $ex){
        $this->output->clear(); //clear the output before display error
        
        $bestClass = null;
        $bestHandler = '';
        
        foreach ($this->config->handlers as $className => $handler){
            $class = new \ReflectionClass($className);
            
            if(!$class->isInstance($ex)) //invalid class
                continue;
            
            if($bestClass != null && !$class->isSubclassOf($bestClass)){ //if $bestClass is more precise than $class
                continue; //keep them
            }
            
            //congratulation, you're the best class !
            $bestClass = $class;
            $bestHandler = $handler;
        }
        
        $bestHandlerArray = explode('::', $bestHandler); //separe class and method
        
        if(count($bestHandlerArray) != 2) //Oh no, the configuration is not valid...
            throw new BadConfigurationException('Invalid error handler "' . $bestHandler . '"', 10002, $ex);
        
        $handler = $this->loader->load($bestHandlerArray[0]);
        $method = $bestHandlerArray[1];
        echo $handler->$method($ex);
        
        $this->output->flush(); //flush and exit
        exit;
    }
}