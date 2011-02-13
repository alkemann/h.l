<?php

namespace util;

class Sitemap {

    public static function generate($whitelist = array(), $blacklist = array()) {
        $whitelist += array('pages');
        $blacklist += array();
        return static::recursive('pages', CONTENT_PATH);
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
                        $ret[] = $file;
                    }
                }
                closedir($dirHandler);
                foreach ($subfolders as $folder) {
                    $ret[$folder] = static::recursive($folder, $path.$dir.DS);
                }
            }
        }
        return $ret;
    }
}