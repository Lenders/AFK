<?php
namespace system\error;

/**
 * For loader error
 * @author Vincent Quatrevieux
 */
class ClassNotFoundException extends \Exception{
    public function __construct($message, $code = 1000, $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}