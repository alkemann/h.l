<?php

namespace alkemann\hl\util;

class Sitemap {

    public static function generate($whitelist = array(), $blacklist = array()) {
        $whitelist += array('pages');
        $blacklist += array();
        $ret = static::recursive('pages', CONTENT_PATH);
        return $ret;
    }

    private static function recursive($dir, $path) {
        $subfolders = array();
        $ret = array();
        if (is_dir($path.$dir)) {
            if ($dirHandler = opendir($path.$dir)) {
                while (($file = readdir($dirHandler)) !== false) {
                    if ($file == '.' || $file == '..') continue;
                    if (filetype($path.$dir.DS.$file) == 'dir') {
                        $subfolders[] = $file;
                    } else {
                        $url = '/' . substr($file, 0, -4);
                        $arr = explode('.', $file);
                        $name = $arr[0];
                        $ret[$name] = array($url);
                    }
                }
                closedir($dirHandler);
                foreach ($subfolders as $folder) {
                    if (!isset($ret[$folder])) $ret[$folder] = array();
                    $ret[$folder][] = static::recursive($folder, $path.$dir.DS);
                }
            }
        }
        return $ret;
    }
}
