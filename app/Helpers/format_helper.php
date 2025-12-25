<?php

if (!function_exists('rupiah_short')) {
    function rupiah_short($angka)
    {
        if ($angka >= 1000000000000) {
            return number_format($angka / 1000000000000, 1, ',', '.') . 'T';
        } elseif ($angka >= 1000000000) {
            return number_format($angka / 1000000000, 1, ',', '.') . 'M';
        } elseif ($angka >= 1000000) {
            return number_format($angka / 1000000, 1, ',', '.') . 'jt';
        } elseif ($angka >= 1000) {
            return number_format($angka / 1000, 0, ',', '.') . 'k';
        }

        return number_format($angka, 0, ',', '.');
    }
}
