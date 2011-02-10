<?php 

namespace core\exceptions;

class InvalidUrlException extends BaseException {

    public function __toString() {
        return __CLASS__ . '<br><br>'.
        'View file not found! <br><br>'.
        'Create it at: <strong style="color:blue">' . $this->message . '</strong><br><br>';
    }
}
