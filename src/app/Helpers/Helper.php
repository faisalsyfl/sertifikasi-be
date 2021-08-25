<?php

use Illuminate\Support\Facades\Schema;

/**
 * Get Fields Name
 * @param  string $table   table name
 * @param  array  $ignores list of ingnored field
 * @return array list column name
 */
function getField($table, $ignores = array())
{
    $columns = Schema::getColumnListing($table);

    if (!empty($ignores))
        return array_except($columns, $ignores);

    return $columns;
}

/**
 * Function for checking field is valid in table
 * @param  string  $table table name
 * @param  string  $field field name
 * @return boolean
 */
function isValidField($table, $field)
{
    $columns = Schema::getColumnListing($table);

    return in_array($field, $columns);
}

/**
 * FUnction to create money format
 * @param  integer $value
 * @return string
 */
function money($value = 0)
{
    return 'Rp. ' . number_format($value, 0, '', '.') . ',-';
}

/**
 * Function to generate random string
 * @param  integer $length
 * @return string
 */
function generateRandomString($length = 10)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';

    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }

    return $randomString;
}

function penyebut($nilai)
{
    $nilai = abs($nilai);
    $huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
    $temp = "";
    if ($nilai < 12) {
        $temp = " " . $huruf[$nilai];
    } else if ($nilai < 20) {
        $temp = penyebut($nilai - 10) . " belas";
    } else if ($nilai < 100) {
        $temp = penyebut($nilai / 10) . " puluh" . penyebut($nilai % 10);
    } else if ($nilai < 200) {
        $temp = " seratus" . penyebut($nilai - 100);
    } else if ($nilai < 1000) {
        $temp = penyebut($nilai / 100) . " ratus" . penyebut($nilai % 100);
    } else if ($nilai < 2000) {
        $temp = " seribu" . penyebut($nilai - 1000);
    } else if ($nilai < 1000000) {
        $temp = penyebut($nilai / 1000) . " ribu" . penyebut($nilai % 1000);
    } else if ($nilai < 1000000000) {
        $temp = penyebut($nilai / 1000000) . " juta" . penyebut($nilai % 1000000);
    } else if ($nilai < 1000000000000) {
        $temp = penyebut($nilai / 1000000000) . " milyar" . penyebut(fmod($nilai, 1000000000));
    } else if ($nilai < 1000000000000000) {
        $temp = penyebut($nilai / 1000000000000) . " trilyun" . penyebut(fmod($nilai, 1000000000000));
    }
    return $temp;
}

function terbilang($nilai)
{
    if ($nilai < 0) {
        $hasil = "minus " . trim(penyebut($nilai));
    } else {
        $hasil = trim(penyebut($nilai));
    }
    return str_replace("  "," ", ucwords($hasil)) . ' Rupiah';
}
