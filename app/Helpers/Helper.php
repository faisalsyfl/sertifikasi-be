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

function statusConvert($status = null)
{
    $code = 0;
    switch ($status) {
        case 'MISSION_IN_REVIEW':
            $code = 1;
            break;
        case 'MISSION_APPROVED':
            $code = 2;
            break;
        case 'MISSION_REJECTED':
            $code = 3;
            break;
    }

    return $code;
}