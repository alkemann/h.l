<?php
/**
 * Global debug methods
 */

class Debug {

    public static function dump($var) {
        $location = Debug::location();
        extract($location);
        echo '<div>';
        echo "line: <strong style='color:red'>$line</strong> &nbsp;";
        echo "file: <strong style='color:red'>$file</strong> &nbsp;";
        echo $class ? "class: <strong style='color:red'>$class</strong> &nbsp;" :'';
        echo $function ? "function: <strong style='color:red'>$function</strong> &nbsp;" :'';
        var_dump($var);
    }

    private static function location() {
        $stacktrace = debug_backtrace();
        $key = 1;
        if ($stacktrace[1]['function'] == 'dd') $key++;
        return $stacktrace[$key];
    }
}

function dd() {
    $args = func_get_args();
    foreach ($args as $var) 
        Debug::dump($var);
};