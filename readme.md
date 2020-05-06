# PHP CSV Iterator

> #### A simple CSV reader with PHP Iterator

![PHP from Packagist](https://img.shields.io/packagist/php-v/jeroenzwart/php-csv-iterator?style=flat-square)
![GitHub code size in bytes](https://img.shields.io/github/languages/code-size/jeroenzwart/php-csv-iterator?style=flat-square)
![GitHub release (latest SemVer)](https://img.shields.io/github/v/release/jeroenzwart/php-csv-iterator?style=flat-square)
![Scrutinizer build (GitHub/Bitbucket)](https://img.shields.io/scrutinizer/build/g/jeroenzwart/php-csv-iterator?style=flat-square)
![Scrutinizer coverage (GitHub/BitBucket)](https://img.shields.io/scrutinizer/coverage/g/jeroenzwart/php-csv-iterator?style=flat-square)
![Scrutinizer code quality (GitHub/Bitbucket)](https://img.shields.io/scrutinizer/quality/g/jeroenzwart/php-csv-iterator?style=flat-square)

This package is an easy way to read CSV files. The CSV Reader will iterate over a CSV file with low memory usage. 


### Features

- Returns an array with keys from the headers.
- Reads a specific line in the CSV file.
- Skip the empty lines.
- Reading a CSV file with an offset and/or limit.


## Installation

Via Composer
``` bash
$ composer require jeroenzwart/php-csv-iterator
```


## Usage

This example is a part of the CSV file *./csv/movies.csv*;
````text
    name,year_release,order,imdb_rating
    Star Wars Episode I – The Phantom Menace,1999,1,6.5
    Star Wars Episode II – Attack of the Clones,2002,2,6.5 
````

To loop over each item in the CSV file, you will use the reader like this;
``` php
$csv = new \JeroenZwart\CsvIterator\CsvReader('../csv/movies.csv');
$csv->delimiter('"')
foreach ($csv as $line) {
    var_dump($line)
    // Or do something with $line...
}
// The first dump will look like this
// class stdClass(4) {
//     public 'name' => string(42) "Star Wars Episode I – The Phantom Menace"
//     public 'year_release' => string(4) "1999"
//     public 'order' => string(1) "1"
//     public 'imdb_rating' => string(3) "6.5"
// }
```

Or use one of the default iterator methods;
``` php
$csv = new \JeroenZwart\CsvIterator\CsvReader('../csv/movies.csv');
$line = $csv->next()->current();
```


## Options
- `filePath` *(string*) - Path to the CSV file.
- `offset` *(integer 0)* - The offset from start reading the CSV file.
- `limit` *(integer -1)* - The limit to end reading the CSV file.
- `delimiter` *(string ,)* - The delimiter character in the CSV file.
- `enclosure` *(string ")* - The enclosure character in the CSV file.
- `escape` *(string \\)* - The escape character in the CSV file.
- `hasHeaders` *(boolean TRUE)* - To set if the CSV file has a header, set FALSE if not.
- `asObject` *(boolean TRUE)* - Return the lines of the CSV file as object, set FALSE as array.
- `keepEmptyLines` *(boolean FALSE)* - Set TRUE for keeping an empty lines in the CSV file.


## Examples

#### Offset and limit

Start reading with another position with offset and limit;
``` php
$csv = new CsvReader('../csv/actors.csv', 3, 5);
foreach ($csv as $line) {
    // Do something with $line...
}
```


#### Delimiter, enclose and escape

Read with another delimiter, enclose and escape;
``` php
$csv = new CsvReader('../csv/actors.csv');
$csv->delimiter(';')->enclose('`')->escape('');
foreach ($csv as $line) {
    // Do something with $line...
}
```
To get the current delimiter, enclosure or escape, you use `$csv->delimiter()`


#### Headers, asObject and empty

Ignore the headers in the CSV file and return array with regular keys, but keep empty lines;
``` php
$csv = new CsvReader('../csv/actors.csv');
$csv->headers(false)->asObject(false)->empty(true);
foreach ($csv as $line) {
    // Do something with $line...
}
```
To get the headers of a CSV file as array, you use `$csv->headers()`.
For getting the modes for settings asObject or empty, you can use  `$csv->asObject()` or `$csv->empty()`.


#### Position

Load a line at a given position;
``` php
$csv = new CsvReader('../csv/actors.csv');
var_dump($csv->position(3));
```
To get the current position of the iterator, you use `$csv->position()`


## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.


## License

Please see the [license file](LICENSE) for more information.
