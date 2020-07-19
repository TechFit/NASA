<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class NasaNeoGrabber
{
    private const NASA_API_LINK = 'https://api.nasa.gov';
    private const FEED_ENDPOINT = '/neo/rest/v1/feed';
    private const BROWSE_ENDPOINT = '/rest/v1/neo/browse';

    /**
     * @var HttpClientInterface
     */
    private $client;

    /** @var $apiKey string */
    private $apiKey;

    /**
     * NasaNeoGrabber constructor.
     *
     * @param HttpClientInterface $client
     * @param ContainerBuilder $container
     */
    public function __construct(
        HttpClientInterface $client,
        ContainerBuilder $container
    )
    {
        $this->client = $client;
        $this->apiKey = $container->getParameter('nasa_api_key');
    }
}
