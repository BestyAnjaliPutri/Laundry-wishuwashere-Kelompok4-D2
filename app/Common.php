<?php

/**
 * Master Helper Global
 */

if (!function_exists('terbilang')) {
    function terbilang($angka) {
        $angka = abs((float)$angka);
        $baca = ['', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan', 'Sepuluh', 'Sebelas'];
        $hasil = '';

        if ($angka < 12) {
            $hasil = ' ' . $baca[$angka];
        } else if ($angka < 20) {
            $hasil = terbilang($angka - 10) . ' Belas';
        } else if ($angka < 100) {
            $hasil = terbilang(floor($angka / 10)) . ' Puluh' . terbilang($angka % 10);
        } else if ($angka < 200) {
            $hasil = ' Seratus' . terbilang($angka - 100);
        } else if ($angka < 1000) {
            $hasil = terbilang(floor($angka / 100)) . ' Ratus' . terbilang($angka % 100);
        } else if ($angka < 2000) {
            $hasil = ' Seribu' . terbilang($angka - 1000);
        } else if ($angka < 1000000) {
            $hasil = terbilang(floor($angka / 1000)) . ' Ribu' . terbilang($angka % 1000);
        } else if ($angka < 1000000000) {
            $hasil = terbilang(floor($angka / 1000000)) . ' Juta' . terbilang($angka % 1000000);
        } else if ($angka < 1000000000000) {
            $hasil = terbilang(floor($angka / 1000000000)) . ' Miliar' . terbilang(fmod($angka, 1000000000));
        }

        return trim($hasil);
    }
}
