<?php

class MongoWriteConcernException extends MongoCursorException
{
    /**
     * Get the error document
     * @return array
     */
    public function getDocument()
{
    }

    /**
     * The hostname of the server that encountered the error
     * @return string
     */
    public function getHost()
    {
    }
}

class MongoDuplicateKeyException extends MongoWriteConcernException
{
}

interface MongoCursorInterface extends Iterator, Traversable
{
    /**
     * @param int $number
     * @return MongoCursorInterface
     */
    public function batchSize($number);

    /**
     * @return array
     */
    public function info();

    /**
     * @return bool
     */
    public function dead();
}

class MongoCommandCursor implements MongoCursorInterface
{
    /**
     * @param MongoClient $connection
     * @param string $ns
     * @param array $command
     */
    public function __construct(MongoClient $connection, $ns, array $command = array())
    {
    }

    /**
     * @param MongoClient $connection
     * @param string $hash
     * @param array $document
     * @return MongoCommandCursor
     */
    public static function createFromDocument(MongoClient $connection, $hash, array $document)
    {
    }

    /**
     * @param int $number
     * @return MongoCursorInterface
     */
    public function batchSize($number)
    {
    }

    /**
     * @return array
     */
    public function info()
    {
    }

    /**
     * @return bool
     */
    public function dead()
    {
    }

    /**
     * @return array
     */
    public function current()
    {
    }

    /**
     * @return void
     */
    public function next()
    {
    }

    /**
     * @return string
     */
    public function key()
    {
    }

    /**
     * @return bool
     */
    public function valid()
    {
    }

    /**
     * @return void
     */
    public function rewind()
    {
    }
}