<?php
/**
 * Global debug methods
 */

class Debug {

    private $__current_depth;
    private $__object_references;
    private $__options;

    public function dump($var, $options = array()) {
        $options += array(
            'trace' => null,
            'echo' => true,
            'depth' => 10
        );
        $this->__options = $options;
        $this->__current_depth = 1;
        $this->__object_references = array();
        
        if (!$options['trace']) $options['trace'] = debug_backtrace();
        extract($options);
        $location = $this->location($trace);

        $dump = $this->dump_it($var);
        
        $locString = $this->locationString($location);
        $result = '<style type="text/css">@import url("/css/debug.css");</style>';
        $result .= '<div class="debug-dump"><span class="location">' . $locString . '</span>'.
            '<br> ' . $dump . '</div>';
        if ($echo) echo $result;
    }
    private function dump_it($var) {
        if (is_array($var))
            return $this->dump_array($var);
        elseif (is_object($var)) 
            return $this->dump_object($var);
        else
            return $this->dump_other($var);
    }
    private function dump_array(array $array) {
        if ($this->__current_depth++ > $this->__options['depth']) return;
        $count = count($array);
        $ret = ' type[<span class="type"> Array </span>] ';
        $ret .= '[ <span class="count">' . $count . '</span> ] elements</li>';
        if ($count > 0) {
            $ret .= '<ul>';
            foreach ($array as $key => $value) {
                if (is_string($key)) $key = '<span class="key">\'' . $key . '\'</span>';
                $ret .= '<li>[ <span class="key">' . $key . '</span> ] => ';
                $ret .= $this->dump_it($value);
            }
            $ret .= '</ul>';
        }
        return $ret;    
    }

    private function dump_object($obj) {
        $hash = spl_object_hash($obj);
        $id = substr($hash, 9, 7);
        $class = get_class($obj);
        $ret = ' object[ <span class="class-id"> ' . $id . ' </span> ] ';
        $ret .= ' class[ <span class="class">' . $class . '</span> ] </li>';
        $ret .= '<ul class="properties">';
        if ($this->__object_references[$hash]) 
            return $ret . '<li><span class="empty"> -- Object recursion -- </span></li></ul>';
        
        $this->__object_references[$hash] = true;
        
        $reflection = new \ReflectionObject($obj);
        $props = '';
        foreach (array(
            'public' => \ReflectionProperty::IS_PUBLIC,
            'protected' => \ReflectionProperty::IS_PROTECTED,
            'private' => \ReflectionProperty::IS_PRIVATE
            ) as $type => $rule) {
            $props .= $this->dump_properties($reflection, $obj, $type, $rule);
        }
        if ($props == '') return $ret .= '<li><span class="empty"> -- No properties -- </span></li></ul>';
        else  $ret .=  $props;
        $ret .= '</ul>';
        return  $ret;
    }
    private function dump_properties($reflection, $obj, $type, $rule) {
        $vars = $reflection->getProperties($rule);
        $i = 0; $ret = '';
        foreach ($vars as $refProp) {
            $i++;
            $refProp->setAccessible(true);
            $value = $refProp->getValue($obj);
            $ret .= '<li>';
            $ret .= '[ <span class="property">' . $refProp->getName() . '</span> ][ <span class="access">' . $type . '</span>] => ';
            $ret .= $this->dump_it($value);
            $ret .= '</li>';
        }
        return $i ? $ret : '';
    }

    private function dump_other($var) {
        $type = gettype($var);
        switch ($type) {
            case 'boolean': $var = $var ? 'true' : 'false'; break;
            case 'string' : $var = '\'' . $var . '\''; break;
            case 'NULL' : return '[ <span class="empty">NULL</span> ]'; break;
        }
        return '[ <span class="type">' . $type . '</span> ][ <span class="value">' . $var . '</span> ]';
    }

    private function locationString($location) {
        extract($location);
        $ret = "line: <span>$line</span> &nbsp;".
               "file: <span>$file</span> &nbsp;";
        $ret .= isset($class) ? "class: <span>$class</strong> &nbsp;" :'';
        $ret .= isset($function) && $function != 'include' ? "function: <span>$function</span> &nbsp;" :'';
        return $ret;
    }

    private function location($trace) {
        $ret = array(
            'file' => $trace[0]['file'],
            'line' => $trace[0]['line']
        );
        if (isset($trace[1]['function'])) $ret['function'] = $trace[1]['function'];
        if (isset($trace[1]['class'])) $ret['class'] = $trace[1]['class'];
        return $ret;
    }
    
    public function defines() {
        $defines = get_defined_constants();
        $ret = array(); $offset = -1;
        while ($def = array_slice($defines, $offset--, 1)) {
            $key = key($def);
            $value = current($def);
            if ($key  == 'FIRST_APP_CONSTANT') break;
            $ret[$key ] = $value;
        }
        return $ret;
    }

}


function dd() {
    $debug = new Debug();
    $args = func_get_args();
    $trace = debug_backtrace();
    foreach ($args as $var) $debug->dump($var, compact('trace'));
    unset($debug);
};
function ddd() {
    $debug = new Debug();
    $args = func_get_args();
    $trace = debug_backtrace();
    foreach ($args as $var) $debug->dump($var, compact('trace'));
    unset($debug);
    die('<div style="margin-top: 25px;font-size: 10px;color: #500;">-Debug die-</div>');
}

