<?php 

namespace hl\core\exceptions;

class InvalidUrlException extends BaseException {

    public function __toString() {

        return <<<MESSAGE
<h1>Invalid URL or no such view found</h1>
<h4>InvalidUrlException</h4>
<p>View file not found!</p>
<p>Create it at: <strong style="color:blue"> {$this->message} </strong></p>
MESSAGE;
	}
}
