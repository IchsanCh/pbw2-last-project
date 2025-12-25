<?php

if (!function_exists('initial_nama')) {
    function initial_nama(?string $nama, int $max = 2): string
    {
        if (!$nama) {
            return '?';
        }

        $nama = trim($nama);

        if ($nama === '') {
            return '?';
        }

        $kata = preg_split('/\s+/', $nama);
        $initial = '';

        foreach ($kata as $k) {
            if ($k === '') {
                continue;
            }

            $initial .= mb_substr($k, 0, 1);
            if (mb_strlen($initial) >= $max) {
                break;
            }
        }

        return strtoupper($initial);
    }
}
