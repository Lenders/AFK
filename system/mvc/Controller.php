<?php
namespace system\mvc;

/**
 * Base Controller class for MVC2 structure
 * @author Vincent Quatrevieux
 * {@inheritdoc}
 */
abstract class Controller extends \system\Registry{
    public function __construct(\system\Base $base) {
        parent::__construct($base);
    }
}

