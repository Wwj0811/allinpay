<?php

namespace allinpay\base;

class Log
{
    /**
     * 写入文件缓存
     * @param $path
     * @param $content
     * @param $description
     * @return false|int
     */
    public static function Write($path = '', $content = '', $description = '')
    {
        $dir = dirname($path);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        !is_string($content) && $content = json_encode($content, JSON_UNESCAPED_UNICODE);
        $content = $description . $content .  PHP_EOL . PHP_EOL;

        return file_put_contents($path, $content, FILE_APPEND);
    }
}