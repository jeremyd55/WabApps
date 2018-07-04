<?php namespace Framework\Core\Http;


interface UriInterface
{
    /**
     * UriInterface constructor.
     * @param array $server
     */
    public function __construct(array $server = array());

    /**
     * @return mixed
     */
    public function getScheme();

    /**
     * @return mixed
     */
    public function getPath();

    /**
     * @return mixed
     */
    public function getHost();

    /**
     * @return mixed
     */
    public function getPort();

    /**
     * @return mixed
     */
    public function getQuery();

}