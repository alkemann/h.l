<?php
/**
 * Global debug methods
 */

class Debug {

    private static $__current_depth;
    private static $__object_references;
    private static $__options;

    public static function dump($var, $options = array()) {
        $options += array(
            'trace' => null,
            'echo' => true,
            'depth' => 6
        );
        $this->__options = $options;
        if (!$options['trace']) $options['trace'] = debug_backtrace();
        extract($options);
        $location = Debug::location($trace);

        static::$__current_depth = 1;
        static::$__object_references = array();
        $dump = static::dump_it($var);
        
        $locString = Debug::locationString($location);
        $result = '<style type="text/css">@import url("/css/debug.css");</style>';
        $result .= '<div class="debug-dump"><span class="location">' . $locString . '</span>'.
            '<br> ' . $dump . '</div>';
        if ($echo) echo $result;
    }
    private static function dump_it($var) {
        if (static::$__current_depth++ > static::$__options['depth']) return;
        if (is_array($var))
            return static::dump_array($var);
        elseif (is_object($var)) 
            return static::dump_object($var);
        else
            return static::dump_other($var);
    }
    private static function dump_array(array $array) {
        $count = count($array);
        $ret = ' type[<span class="type"> Array </span>] ';
        $ret .= '[ <span class="count">' . $count . '</span> ] elements</li>';
        if ($count > 0) {
            $ret .= '<ul>';
            foreach ($array as $key => $value) {
                if (is_string($key)) $key = '<span>\'' . $key . '\'</span>';
                $ret .= '<li>[ <span class="key">' . $key . '</span> ] => ';
                $ret .= static::dump_it($value);
            }
            $ret .= '</ul>';
        }
        return $ret;    
    }

    private static function dump_object($obj) {
        $ret = ' type[<span class="type"> Object </span>] ';
        $class = get_class($obj);
        $ret .= ' class[ <span class="class">' . $class . '</span> ]</li>';
        $ret .= '<ul class="properties"><li><span class="class">' . $class . '</span></li>';
        $reflection = new \ReflectionObject($obj);
        $props = '';
        foreach (array(
            'public' => \ReflectionProperty::IS_PUBLIC,
            'protected' => \ReflectionProperty::IS_PROTECTED,
            'private' => \ReflectionProperty::IS_PRIVATE
            ) as $type => $rule) {
            $props .= static::dump_properties($reflection, $obj, $type, $rule);
        }
        if ($props == '') $ret .= '<span class="empty"> -- No properties -- </span>';
        else  $ret .= $props;
        $ret .= '</ul>';
        return  $ret;
    }
    private static function dump_properties($reflection, $obj, $type, $rule) {
        $vars = $reflection->getProperties($rule);
        $i = 0; $ret = '';
        foreach ($vars as $refProp) {
            $i++;
            $refProp->setAccessible(true);
            $value = $refProp->getValue($obj);
            $ret .= '<li>';
            $ret .= '[ <span>' . $refProp->getName() . '</span> ][ <span class="type">' . $type . '</span>] => ';
            $ret .= static::dump_it($value);
            $ret .= '</li>';
        }
        return $i ? $ret : '';
    }

    private static function dump_other($var) {
        $type = gettype($var);
        switch ($type) {
            case 'boolean': $var = $var ? 'true' : 'false'; break;
            case 'string' : $var = '\'' . $var . '\''; break;
            case 'NULL' : return '[ <span>NULL</span> ]'; break;
        }
        return '[ <span class="type">' . $type . '</span> ][ <span>' . $var . '</span> ]';
    }

    private static function locationString($location) {
        extract($location);
        $ret = "line: <span>$line</span> &nbsp;".
               "file: <span>$file</span> &nbsp;";
        $ret .= isset($class) ? "class: <span>$class</strong> &nbsp;" :'';
        $ret .= isset($function) && $function != 'include' ? "function: <span>$function</span> &nbsp;" :'';
        return $ret;
    }

    private static function location($trace) {
        $ret = array(
            'file' => $trace[0]['file'],
            'line' => $trace[0]['line']
        );
        if (isset($trace[1]['function'])) $ret['function'] = $trace[1]['function'];
        if (isset($trace[1]['class'])) $ret['class'] = $trace[1]['class'];
        return $ret;
    }
}

function dd() {
    $args = func_get_args();
    $trace = debug_backtrace();
    foreach ($args as $var) 
        Debug::dump($var, compact('trace'));
};
function ddd() {
    $args = func_get_args();
    $trace = debug_backtrace();
    foreach ($args as $var) 
        Debug::dump($var, compact('trace'));
    die('<div style="margin-top: 25px;font-size: 10px;color: #500;">-Debug die-</div>');
}
