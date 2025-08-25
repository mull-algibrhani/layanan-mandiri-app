<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Normalisasi path supaya aman di semua OS
 */
if (!function_exists('normalize_path')) {
    function normalize_path(string $path): string
    {
        // Ganti semua backslash atau URL encoded %5C menjadi slash /
        $path = str_replace(['\\', '%5C'], '/', $path);

        // Hapus double slash
        $path = preg_replace('#/+#', '/', $path);

        return $path;
    }
}
