<?php 

namespace core\exceptions;

class InvalidUrlException extends BaseException {

    public function __toString() {
        return $this->message;
    }
}
