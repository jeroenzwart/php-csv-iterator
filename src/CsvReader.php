<?php
/**
 * Simple CSV Iterator
 *
 * @author    Jeroen Zwart <mailme@jeroenzwart.nl>
 * @link      https://github.com/jeroenzwart/php-csv-iterator
 */

declare(strict_types=1);

namespace JeroenZwart\CsvIterator;

use Countable;
use SplFileObject;

/**
 * Class CsvReader.
 *
 * @package JeroenZwart\CsvIterator
 */
class CsvReader extends CsvIterator implements Countable
{
    /** @var int OFFSET */
    private const OFFSET = 0;

    /** @var int LIMIT */
    private const LIMIT = -1;

    /** @var string DELIMITER */
    private const DELIMITER = ',';

    /** @var string ENCLOSURE */
    private const ENCLOSURE = '"';

    /** @var string ESCAPE */
    private const ESCAPE = '\\';

    /** @var bool HAS_HEADERS */
    private const HAS_HEADERS = true;

    /** @var bool RESULT_AS_OBJECT */
    private const RESULT_AS_OBJECT = true;

    /** @var bool SKIP_EMPTY */
    private const SKIP_EMPTY = true;

    /**
     * CsvReader constructor.
     *
     * @param string $filePath The path of the CSV file.
     * @param int $offset The offset to start at.
     * @param int $limit The number of items to iterate.
     */
    public function __construct(string $filePath, int $offset = self::OFFSET, int $limit = self::LIMIT)
    {
        $file = $this->takeFile($filePath);

        parent::__construct(
            $file,
            $offset,
            $limit,
            self::DELIMITER,
            self::ENCLOSURE,
            self::ESCAPE,
            self::HAS_HEADERS,
            self::RESULT_AS_OBJECT,
            self::SKIP_EMPTY
        );
    }

    /**
     * Set or get the delimiter character of the CSV.
     *
     * @param string|null $delimiter The single character.
     *
     * @throws CsvIteratorException Throws a CsvIteratorException when given delimiter is incorrect.
     *
     * @return string|self Returns the escape delimiter or this instance.
     */
    public function delimiter(?string $delimiter = null)
    {
        if ($delimiter === null) {
            return $this->getDelimiter();
        }

        if ($this->onlyCharacter($delimiter) === false) {
            throw new CsvIteratorException(
                sprintf(
                    'The given delimiter [%s] is not a single character.',
                    $delimiter
                )
            );
        }

        $this->setDelimiter($delimiter);

        return $this;
    }

    /**
     * Set or get the enclosure character of the CSV.
     *
     * @param string|null $enclosure The single character.
     *
     * @throws CsvIteratorException Throws a CsvIteratorException when given enclosure is incorrect.
     *
     * @return string|self Returns the escape enclosure or this instance.
     */
    public function enclosure(?string $enclosure = null)
    {
        if ($enclosure === null) {
            return $this->getEnclosure();
        }

        if ($this->onlyCharacter($enclosure) === false) {
            throw new CsvIteratorException(
                sprintf(
                    'The given enclosure [%s] is not a single character.',
                    $enclosure
                )
            );
        }

        $this->setEnclosure($enclosure);

        return $this;
    }

    /**
     * Set or get the escape character of the CSV.
     *
     * @param string|null $escape The single character.
     *
     * @throws CsvIteratorException Throws a CsvIteratorException when given escape is incorrect.
     *
     * @return string|self Returns the escape character or this instance.
     */
    public function escape(?string $escape = null)
    {
        if ($escape === null) {
            return $this->getEscape();
        }

        if ($this->onlyCharacter($escape) === false) {
            throw new CsvIteratorException(
                sprintf(
                    'The given escape [%s] is not a single character.',
                    $escape
                )
            );
        }

        $this->setEscape($escape);

        return $this;
    }

    /**
     * Set or get the headers of the CSV.
     *
     * @param bool|null $hasHeaders The boolean if the CSV has headers.
     *
     * @return array|self Returns the headers or this instance.
     */
    public function headers(?bool $hasHeaders = null)
    {
        if ($hasHeaders === null) {
            return $this->getHeaders();
        }

        $this->setHeaders($hasHeaders);

        return $this;
    }

    /**
     * Set or get to return the result as object/array.
     *
     * @param bool|null $resultAsObject The boolean for the result mode.
     *
     * @return bool|self Returns the mode or this instance.
     */
    public function asObject(?bool $resultAsObject = null)
    {
        if ($resultAsObject === null) {
            return $this->isResultAsObject();
        }

        $this->setResultAsObject($resultAsObject);

        return $this;
    }

    /**
     * Set or get to keep the empty lines of the CSV.
     *
     * @param bool|null $keepEmptyLines The boolean to keep empty lines.
     *
     * @return bool|self Returns the mode or this instance.
     */
    public function empty(?bool $keepEmptyLines = null)
    {
        if ($keepEmptyLines === null) {
            return $this->isSkipEmpty() === false;
        }

        $this->setSkipEmpty(($keepEmptyLines === false));

        return $this;
    }

    /**
     * Set or get the position in the CSV file.
     *
     * @param int|null $position The position of to seek.
     *
     * @return int|self Returns the requested line or this instance.
     */
    public function position(int $position = null)
    {
        if ($position === null) {
            return $this->getPosition();
        }

        $this->seek($position);

        return $this;
    }

    /**
     * Return the amount of lines in the CSV file.
     *
     * @return int The amount of the lines.
     */
    public function count(): int
    {
        return iterator_count($this);
    }

    /**
     * Check and set the CSV file.
     *
     * @param string $filePath The path of the file.
     *
     * @return SplFileObject The SplFileObject instance.
     */
    private function takeFile(string $filePath): SplFileObject
    {
        $path = realpath($filePath);

        if ($path === false) {
            throw new CsvIteratorException(
                sprintf(
                    'The file [%s] does not exist.',
                    $filePath
                )
            );
        }

        if (is_file($path) === false) {
            throw new CsvIteratorException(
                sprintf(
                    'The given path [%s] is not a file.',
                    $filePath
                )
            );
        }

        return new SplFileObject($path);
    }

    /**
     * Check if given value has a single character.
     *
     * @param string $value The string to check.
     *
     * @return bool The boolean.
     */
    private function onlyCharacter(string $value): bool
    {
        return strlen($value) === 1;
    }

}
