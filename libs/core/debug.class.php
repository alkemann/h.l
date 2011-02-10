<?php
/**
 * Global debug methods
 */

class Debug {

    public static function dump($var, $options = array()) {
        $options += array(
            'trace' => null,
            'echo' => true,
            'depth' => 6 
        );
        
        if (!$options['trace']) $options['trace'] = debug_backtrace();
        extract($options);
        ini_set('xdebug.var_display_max_depth', $depth);
        $location = Debug::location($trace);
        ob_start();
        var_dump($var);
        $dump = ob_get_contents();
        ob_end_clean();
        
        $locString = Debug::locationString($location);
        $result = '<div style="font-size:11px;">' . $locString . $dump . '</div>';
        if ($echo) echo $result;
   }

    private static function locationString($location) {
        extract($location);
        $ret = "line: <strong style='color:#C00'>$line</strong> &nbsp;".
               "file: <strong style='color:#C00'>$file</strong> &nbsp;";
        $ret .= $class ? "class: <strong style='color:#C00'>$class</strong> &nbsp;" :'';
        $ret .= $function ? "function: <strong style='color:#C00'>$function</strong> &nbsp;" :'';
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
