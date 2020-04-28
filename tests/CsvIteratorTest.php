<?php
/**
 * PHP Unit Test
 *
 * @author    Jeroen Zwart <mailme@jeroenzwart.nl>
 * @link      https://github.com/jeroenzwart/php-csv-iterator
 */

declare(strict_types=1);

use JeroenZwart\CsvIterator\CsvIterator;
use JeroenZwart\CsvIterator\CsvIteratorException;

/**
 * Class CsvReaderTest.
 */
final class CsvIteratorTest extends BaseTest
{
    /** @var CsvIterator $csvIterator */
    private $csvIterator;

    /** @var SplFileObject $file */
    private $file;

    protected function setUp(): void
    {
        $this->file = new SplFileObject(self::FILEPATH);

        $this->csvIterator = new CsvIterator($this->file, 0, -1, ',', '"', '\\', true, true);
    }

    public function testRewind(): void
    {
        // Test to rewind with headers
        $this->csvIterator->rewind();
        $this->assertEquals(1, $this->csvIterator->getPosition());

        // Test to rewind without headers
        $csvIterator = new CsvIterator($this->file, 0, -1, ',', '"', '\\', false, true);
        $csvIterator->rewind();
        $this->assertEquals(0, $csvIterator->getPosition());
    }

    public function testCurrent(): void
    {
        // Test to get headers
        $current = $this->csvIterator->current();
        $this->assertEquals([
            'name' => 'name',
            'year_release' => 'year_release',
            'order' => 'order',
            'imdb_rating' => 'imdb_rating',
        ], $current);

        // Test to get with no headers
        $csvIterator = new CsvIterator($this->file, 0, -1, ',', '"', '\\', false, true);
        $current = $csvIterator->current();
        $this->assertEquals([], $current);

        // Test to get with mismatch headers
        $this->expectException(CsvIteratorException::class);
        $this->expectExceptionMessage('The amount of headers mismatch with line [1].');
        $file = new SplFileObject('./csv/invalid.csv');
        $csvIterator = new CsvIterator($file, 0, -1, ',', '"', '\\', true, false);
        $csvIterator->rewind();
        $csvIterator->current();
    }

    public function testGetFile(): void
    {
        // Test to get the file
        $file = $this->invokeMethod($this->csvIterator, 'getFile');
        $this->assertEquals($file, $this->file);
    }

    public function testSetFile(): void
    {
        $file = new SplFileObject('./csv/actors.csv');

        // Test to set the file
        $this->invokeMethod($this->csvIterator, 'setFile', [$file]);
        $expected = $this->invokeMethod($this->csvIterator, 'getFile');
        $this->assertEquals($file, $expected);
    }

    public function testGetDelimiter(): void
    {
        // Test to get a delimiter
        $delimiter = $this->invokeMethod($this->csvIterator, 'getDelimiter');
        $this->assertEquals(',', $delimiter);
    }

    public function testSetDelimiter(): void
    {
        // Test to set a delimiter
        $this->invokeMethod($this->csvIterator, 'setDelimiter', ['#']);
        $delimiter = $this->invokeMethod($this->csvIterator, 'getDelimiter');
        $this->assertEquals('#', $delimiter);
    }

    public function testGetEnclosure(): void
    {
        // Test to get a enclosure
        $enclosure = $this->invokeMethod($this->csvIterator, 'getEnclosure');
        $this->assertEquals('"', $enclosure);
    }

    public function testSetEnclosure(): void
    {
        // Test to set a enclosure
        $this->invokeMethod($this->csvIterator, 'setEnclosure', ['`']);
        $enclosure = $this->invokeMethod($this->csvIterator, 'getEnclosure');
        $this->assertEquals('`', $enclosure);
    }

    public function testGetEscape(): void
    {
        // Test to get a escape
        $escape = $this->invokeMethod($this->csvIterator, 'getEscape');
        $this->assertEquals('\\', $escape);
    }

    public function testSetEscape(): void
    {
        // Test to set a escape
        $this->invokeMethod($this->csvIterator, 'setEscape', ['$']);
        $escape = $this->invokeMethod($this->csvIterator, 'getEscape');
        $this->assertEquals('$', $escape);
    }

    public function testGetHeaders(): void
    {
        // Test to get headers
        $headers = $this->invokeMethod($this->csvIterator, 'getHeaders');
        $this->assertEquals([
            'name',
            'year_release',
            'order',
            'imdb_rating',
        ], $headers);
    }

    public function testSetHeaders(): void
    {
        // Test to set without headers
        $this->invokeMethod($this->csvIterator, 'setHeaders', [false]);
        $headers = $this->invokeMethod($this->csvIterator, 'getHeaders');
        $this->assertNull($headers);
    }

    public function testGetSkipEmpty(): void
    {
        // Test to get skipEmpty
        $skipEmpty = $this->invokeMethod($this->csvIterator, 'getSkipEmpty');
        $this->assertTrue($skipEmpty);
    }

    public function testSetSkipEmpty(): void
    {
        // Test to set skipEmpty
        $this->invokeMethod($this->csvIterator, 'setSkipEmpty', [false]);
        $skipEmpty = $this->invokeMethod($this->csvIterator, 'getSkipEmpty');
        $this->assertFalse($skipEmpty);
    }
}
