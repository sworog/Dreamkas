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
