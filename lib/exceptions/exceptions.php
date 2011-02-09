<?php
/**
 * Contains all the Exceptions
 */
 
namespace core\exceptions;

class BaseException extends \Exception {

}

class InvalidUrlException extends BaseException {

    public function __toString() {
        return $this->message;
    }
}