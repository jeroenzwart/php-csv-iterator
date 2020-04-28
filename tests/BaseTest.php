<?php
/**
 * PHP Unit Test
 *
 * @author    Jeroen Zwart <mailme@jeroenzwart.nl>
 * @link      https://github.com/jeroenzwart/php-csv-iterator
 */

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

/**
 * Class BaseTest.
 */
abstract class BaseTest extends TestCase
{
    /** @var string FILEPATH */
    protected const FILEPATH = './csv/movies.csv';

    /**
     * Call protected/private method of a class.
     *
     * @param object &$object Instantiated object that we will run method on.
     * @param string $methodName Method name to call
     * @param array $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     */
    public function invokeMethod(&$object, string $methodName, array $parameters = [])
    {
        $reflection = new ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

}

