<?php

namespace Laravel\VaporCli;

use Illuminate\Support\Str;

class RewriteAssetUrls
{
    /**
     * Rewrite relative asset URLs in the given CSS string.
     *
     * @param  string  $css
     * @param  string  $baseUrl
     * @return string
     */
    public static function inCssString($css, $baseUrl)
    {
        return preg_replace_callback('/url\([\'"]?(?<url>[^)]+?)[\'"]?\)/', function ($matches) use ($baseUrl) {
            return Str::startsWith($url = $matches[1], ['/'])
                        ? Str::replaceFirst('/', $baseUrl.'/', $matches[0])
                        : $matches[0];
        }, $css);
    }

    /**
     * Rewrite relative asset URLs in the given JS string.
     *
     * @param  string  $js
     * @param  string  $baseUrl
     * @return string
     */
    public static function inJsString($js, $baseUrl)
    {
        return preg_replace_callback('/url\([\'"]?(?<url>[^)]+?)[\'"]?\)/', function ($matches) use ($baseUrl) {
            return Str::startsWith($url = $matches[1], ['/'])
                        ? Str::replaceFirst('/', $baseUrl.'/', $matches[0])
                        : $matches[0];
        }, $js);
    }
}
