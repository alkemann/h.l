<?php 

namespace hl\core\exceptions;

class InvalidUrlException extends BaseException {

    public function __toString() {
        return __CLASS__ . " <br>\n<br>".
        "View file not found! <br>\n<br>".
        'Create it at: <strong style="color:blue">' . $this->message . '</strong><br><br>';
    }
}
