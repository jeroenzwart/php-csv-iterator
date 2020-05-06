<?php
/**
 * A simple example how to use.
 *
 * @author     Jeroen Zwart <mailme@jeroenzwart.nl>
 * @link       https://github.com/jeroenzwart/php-csv-iterator
 */

declare(strict_types=1);

use JeroenZwart\CsvIterator\CsvReader;

/**
 * Autoloader
 */
require_once '../vendor/autoload.php';


// Read the CSV file with headers
$csv = new CsvReader('../csv/movies.csv');

echo PHP_EOL . 'Output the headers of the `movies` file:' . PHP_EOL;
var_dump($csv->headers());

echo PHP_EOL . 'Output the amount lines of the `movies` file:' . PHP_EOL;
var_dump($csv->count());

echo PHP_EOL . 'Output the single requested line of the `movies` file:' . PHP_EOL;
var_dump($csv->position(2)->current());

echo PHP_EOL . 'Output each line of the `movies` file:' . PHP_EOL;
foreach ($csv as $line) {
    var_dump($line);
}


// Read the CSV file without headers through offset
$csv = new CsvReader('../csv/actors.csv', 5);
$csv->delimiter(';')
    ->headers(false)
    ->asObject(false)
    ->empty(true);

//echo PHP_EOL . 'Output the headers of the `actors` file:' . PHP_EOL;
var_dump($csv->headers());

echo PHP_EOL . 'Output each line of the other `actors` file:' . PHP_EOL;
foreach ($csv as $line) {
    var_dump($line);
}
