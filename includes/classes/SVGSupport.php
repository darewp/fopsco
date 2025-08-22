<?php
namespace Fopsco\Classes;

use Fopsco\Traits\Singleton;

class SVGSupport {
    use Singleton;

    public function __construct() {
        add_filter('upload_mimes', [$this, 'allow_svg_upload']);
        add_filter('wp_check_filetype_and_ext', [$this, 'fix_svg_mime_type'], 10, 5);
    }

    public function allow_svg_upload($mimes) {
        $mimes['svg'] = 'image/svg+xml';
        return $mimes;
    }

    public function fix_svg_mime_type($data, $file, $filename, $mimes, $real_mime) {
        if (substr($filename, -4) === '.svg') {
            $data['ext']  = 'svg';
            $data['type'] = 'image/svg+xml';
        }
        return $data;
    }
}