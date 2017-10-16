<?php

const FIELD_OPERATION = 'a';
const OPERATION_CACHE_SET = 'cache_set';
const OPERATION_ADD_MODEL = 'add_model';
const OPERATION_CACHE_INC = 'cache_inc';
const OPERATION_CACHE_DELETE = 'cache_del';

const TEST_BUCKET = '';

require_once '../vendor/autoload.php';

\CacheDemo\CacheDemoConfig::init();

echo '<hr/>';

echo '<div><a href="/">reload</a></div>';

//
// CACHE TEST
//

$test_cache_key = 'test_key';

if (\OLOG\GET::optional('a') == OPERATION_CACHE_SET) {
    \OLOG\Cache\Cache::set(TEST_BUCKET, $test_cache_key, 100, 20);
}

/*
if (\OLOG\GET::optional('a') == OPERATION_CACHE_INC) {
    \OLOG\Cache\Cache::increment(TEST_BUCKET, $test_cache_key);
}
*/

if (\OLOG\GET::optional('a') == OPERATION_CACHE_DELETE) {
    \OLOG\Cache\Cache::delete(TEST_BUCKET, $test_cache_key);
}

$test_value_from_cache = OLOG\Cache\Cache::get(TEST_BUCKET, $test_cache_key);

echo '<div>Value from cache: <b>' . json_encode($test_value_from_cache) . '</b></div>';

echo '<div><a href="?' . FIELD_OPERATION . '=' . OPERATION_CACHE_SET . '">set value</a></div>';
//echo '<div><a href="?' . FIELD_OPERATION . '=' . OPERATION_CACHE_INC . '">increment value</a></div>';
echo '<div><a href="?' . FIELD_OPERATION . '=' . OPERATION_CACHE_DELETE . '">delete value</a></div>';
