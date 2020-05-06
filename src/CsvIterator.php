<?php
/**
 * Simple CSV Iterator
 *
 * @author    Jeroen Zwart <mailme@jeroenzwart.nl>
 * @link      https://github.com/jeroenzwart/php-csv-iterator
 */

declare(strict_types=1);

namespace JeroenZwart\CsvIterator;

use LimitIterator;
use SplFileObject;

/**
 * Class CsvIterator.
 *
 * @package JeroenZwart\CsvIterator
 */
class CsvIterator extends LimitIterator
{
    /** @var int FLAGS */
    private const FLAGS = SplFileObject::READ_CSV | SplFileObject::READ_AHEAD | SplFileObject::DROP_NEW_LINE;

    /** @var SplFileObject $file */
    private $file;

    /** @var array|null $headers */
    private $headers;

    /** @var bool $resultAsObject */
    private $resultAsObject;

    /**
     * CsvIterator constructor.
     *
     * @param SplFileObject $file The SplFileObject instance.
     */
    public function __construct(
        SplFileObject $file,
        int $offset,
        int $limit,
        string $delimiter,
        string $enclosure,
        string $escape,
        bool $hasHeaders,
        bool $resultAsObject,
        bool $ignoreEmpty)
    {
        parent::__construct($file, $offset, $limit);

        $this->setFile($file);

        $this->setDelimiter($delimiter);

        $this->setEnclosure($enclosure);

        $this->setEscape($escape);

        $this->setHeaders($hasHeaders);

        $this->setResultAsObject($resultAsObject);

        $this->setSkipEmpty($ignoreEmpty);
    }

    /**
     * Rewind to the first element without being the headers.
     */
    public function rewind(): void
    {
        parent::rewind();

        if ($this->headers !== null) parent::next();
    }

    /**
     * Get the current value with/without headers as keys.
     *
     * @return object|array The current values.
     */
    public function current()
    {
        $current = (array) parent::current();

        if (empty($this->headers) === true) return ($this->resultAsObject ? (object) $current : $current);

        try {
            $line = array_combine($this->headers, $current);
        } catch (\Exception $exception) {
            throw new CsvIteratorException(
                sprintf(
                    'The amount of headers mismatch with line [%s].',
                    $this->getPosition()
                )
            );
        }

        return ($this->resultAsObject ? (object) $line : $line);
    }

    /**
     * Get the file.
     *
     * @return SplFileObject The file as SplFileObject instance.
     */
    protected function getFile(): SplFileObject
    {
        return $this->file;
    }

    /**
     * Set the file.
     *
     * @param SplFileObject $file
     */
    protected function setFile(SplFileObject $file): void
    {
        $file->setFlags(self::FLAGS);

        $this->file = $file;
    }

    /**
     * Get the delimiter.
     *
     * @return string The delimiter character.
     */
    protected function getDelimiter(): string
    {
        return $this->file->getCsvControl()[0];
    }

    /**
     * Set the delimiter.
     *
     * @param string $delimiter The only one character for the delimiter.
     */
    protected function setDelimiter(string $delimiter): void
    {
        $this->file->setCsvControl($delimiter);
    }

    /**
     * Get the enclosure.
     *
     * @return string The enclosure character.
     */
    protected function getEnclosure(): string
    {
        return $this->file->getCsvControl()[1];
    }

    /**
     * Set the enclosure.
     *
     * @param string $enclosure The only one character for the enclosure.
     *
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    protected function setEnclosure(string $enclosure): void
    {
        [$currentDelimiter, $currentEnclosure, $currentEscape] = $this->file->getCsvControl();

        $this->file->setCsvControl($currentDelimiter, $enclosure, $currentEscape);
    }

    /**
     * Get the escape.
     *
     * @return string The escape character.
     */
    protected function getEscape(): string
    {
        return $this->file->getCsvControl()[2];
    }

    /**
     * Set the escape.
     *
     * @param string $escape The only one character for the escape.
     *
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    protected function setEscape(string $escape): void
    {
        [$currentDelimiter, $currentEnclosure, $currentEscape] = $this->file->getCsvControl();

        $this->file->setCsvControl($currentDelimiter, $currentEnclosure, $escape);
    }

    /**
     * Get the headers.
     *
     * @return array|null The headers or nothing.
     */
    protected function getHeaders(): ?array
    {
        return $this->headers;
    }

    /**
     * Set the headers.
     *
     * @param bool $hasHeaders The boolean if CSV has headers.
     */
    protected function setHeaders(bool $hasHeaders): void
    {
        $this->headers = null;

        if ($hasHeaders === false) return;

        parent::rewind();

        $this->headers = (array) parent::current();
    }

    /**
     * Get the mode to get values as object/array.
     *
     * @return bool The mode for result as object/array.
     */
    protected function isResultAsObject(): bool
    {
        return $this->resultAsObject;
    }

    /**
     * Set the mode to get values as object/array.
     *
     * @param bool $resultAsObject The boolean to get result as object/array.
     */
    protected function setResultAsObject(bool $resultAsObject): void
    {
        $this->resultAsObject = $resultAsObject;
    }

    /**
     * Get the mode to ignore empty lines.
     *
     * @return bool The mode to ignore empty lines.
     */
    protected function isSkipEmpty(): bool
    {
        return ($this->file->getFlags() & SplFileObject::SKIP_EMPTY) === SplFileObject::SKIP_EMPTY;
    }

    /**
     * Set the mode to ignore empty lines.
     *
     * @param bool $skipEmpty The boolean to ignore empty lines in the CSV file.
     */
    protected function setSkipEmpty(bool $skipEmpty): void
    {
        if ($skipEmpty === false) {
            $this->file->setFlags(self::FLAGS);

            return;
        }

        $this->file->setFlags(self::FLAGS | SplFileObject::SKIP_EMPTY);
    }

}
