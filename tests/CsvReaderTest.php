<?php
/**
 * PHP Unit Test
 *
 * @author    Jeroen Zwart <mailme@jeroenzwart.nl>
 * @link      https://github.com/jeroenzwart/php-csv-iterator
 */

declare(strict_types=1);

use JeroenZwart\CsvIterator\CsvIteratorException;
use JeroenZwart\CsvIterator\CsvReader;

/**
 * Class CsvReaderTest.
 */
class CsvReaderTest extends BaseTest
{
    /** @var CsvReader $csvReader */
    private $csvReader;

    protected function setUp(): void
    {
        $this->csvReader = new CsvReader(self::FILEPATH);
    }

    public function testDelimiter(): void
    {
        // Test to get default delimiter
        $delimiter = $this->csvReader->delimiter();
        $this->assertEquals(',', $delimiter);

        // Test to set another delimiter
        $that = $this->csvReader->delimiter(';');
        $delimiter = $this->csvReader->delimiter();
        $this->assertIsObject($that);
        $this->assertEquals(';', $delimiter);

        // Test to set an invalid delimiter
        $this->expectException(CsvIteratorException::class);
        $this->expectExceptionMessage('The given delimiter [invalid] is not a single character.');
        $this->csvReader->delimiter('invalid');
    }

    public function testEnclosure(): void
    {
        // Test to get default enclosure
        $enclosure = $this->csvReader->enclosure();
        $this->assertEquals('"', $enclosure);

        // Test to set another enclosure
        $that = $this->csvReader->enclosure('|');
        $enclosure = $this->csvReader->enclosure();
        $this->assertIsObject($that);
        $this->assertEquals('|', $enclosure);

        // Test to set an invalid enclosure
        $this->expectException(CsvIteratorException::class);
        $this->expectExceptionMessage('The given enclosure [invalid] is not a single character.');
        $this->csvReader->enclosure('invalid');
    }

    public function testEscape(): void
    {
        // Test to get default escape
        $escape = $this->csvReader->escape();
        $this->assertEquals('\\', $escape);

        // Test to set another escape
        $that = $this->csvReader->escape('#');
        $escape = $this->csvReader->escape();
        $this->assertIsObject($that);
        $this->assertEquals('#', $escape);

        // Test to set an invalid escape
        $this->expectException(CsvIteratorException::class);
        $this->expectExceptionMessage('The given escape [invalid] is not a single character.');
        $this->csvReader->escape('invalid');
    }

    public function testHeaders(): void
    {
        // Test to get default with headers
        $headers = $this->csvReader->headers();
        $this->assertEquals([
            'name',
            'year_release',
            'order',
            'imdb_rating',
        ], $headers);

        // Test to set with headers
        $that = $this->csvReader->headers(true);
        $this->assertIsObject($that);

        // Test to set without headers
        $that = $this->csvReader->headers(false);
        $headers = $this->csvReader->headers();
        $this->assertIsObject($that);
        $this->assertNull($headers);
    }

    public function testAsObject(): void
    {
        // Test to get default asObject
        $asObject = $this->csvReader->asObject();
        $this->assertTrue($asObject);

        // Test to set asObject
        $that = $this->csvReader->asObject(true);
        $asObject = $this->csvReader->asObject();
        $this->assertIsObject($that);
        $this->assertTrue($asObject);

        // Test to set not asObject
        $that = $this->csvReader->asObject(false);
        $asObject = $this->csvReader->asObject();
        $this->assertIsObject($that);
        $this->assertFalse($asObject);
    }

    public function testEmpty(): void
    {
        // Test to get default empty
        $empty = $this->csvReader->empty();
        $this->assertFalse($empty);

        // Test to set with empty
        $that = $this->csvReader->empty(true);
        $empty = $this->csvReader->empty();
        $this->assertIsObject($that);
        $this->assertTrue($empty);

        // Test to set without empty
        $that = $this->csvReader->empty(false);
        $empty = $this->csvReader->empty();
        $this->assertIsObject($that);
        $this->assertFalse($empty);
    }

    public function testPosition(): void
    {
        // Test to get position
        $position = $this->csvReader->position();
        $this->assertEquals(0, $position);

        // Test to set position
        $that = $this->csvReader->position(5);
        $position = $this->csvReader->position();
        $this->assertIsObject($that);
        $this->assertEquals(5, $position);
    }

    public function testCount(): void
    {
        // Test to get count
        $count = count($this->csvReader);
        $this->assertEquals(11, $count);
    }

    public function testTakeFile(): void
    {
        // Test to convert path to file
        /** @var SplFileObject $file */
        $file = $this->invokeMethod($this->csvReader, 'takeFile', [self::FILEPATH]);
        $this->assertInstanceOf(SplFileObject::class, $file);
        $this->assertEquals(realpath(self::FILEPATH), $file->getRealPath());

        // Test to set file with invalid path
        try {
            $this->invokeMethod($this->csvReader, 'takeFile', ['a/path/to/nowhere']);
        } catch (CsvIteratorException $exception) {
            $this->assertInstanceOf(CsvIteratorException::class, $exception);
            $this->assertEquals('The file [a/path/to/nowhere] does not exist.', $exception->getMessage());
        }

        // Test to set file that is not a file
        try {
            $this->invokeMethod($this->csvReader, 'takeFile', ['./csv']);
        } catch (CsvIteratorException $exception) {
            $this->assertInstanceOf(CsvIteratorException::class, $exception);
            $this->assertEquals('The given path [./csv] is not a file.', $exception->getMessage());
        }
    }

    public function testOnlyCharacter(): void
    {
        // Test to get true
        $bool = $this->invokeMethod($this->csvReader, 'onlyCharacter', ['-']);
        $this->assertTrue($bool);

        // Test to get false
        $bool = $this->invokeMethod($this->csvReader, 'onlyCharacter', ['--']);
        $this->assertFalse($bool);
    }
}
