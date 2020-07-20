<?php

namespace App\Service;

use App\DataObject\EarthObject;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class NasaNeoGrabber
 */
class NasaNeoGrabber
{
    private const NASA_API_LINK = 'https://api.nasa.gov';
    private const FEED_ENDPOINT = '/neo/rest/v1/feed';

    private HttpClientInterface $client;

    private string $apiKey;

    /**
     * NasaNeoGrabber constructor.
     *
     * @param HttpClientInterface $client
     * @param ParameterBagInterface $params
     */
    public function __construct(
        HttpClientInterface $client,
        ParameterBagInterface $params
    ) {
        $this->client = $client;
        $this->apiKey = $params->get('app.nasa_api_key');
    }

    /**
     * @return iterable
     *
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function getNeoFeed(): iterable
    {
        $response = $this->client->request(
            'GET',
            $this->getFeedEndpoint(
                date("Y-m-d", strtotime("-3 day")),
                date('Y-m-d')
            )
        );

        $neoFeedByDate = $response->toArray()['near_earth_objects'];

        $earthObjects = [];

        foreach ($neoFeedByDate as $items) {
            foreach ($items as $near_earth_object) {
                try {
                    $earthObject = new EarthObject(
                        $near_earth_object['neo_reference_id'],
                        $near_earth_object['name'],
                        floatval(
                            $near_earth_object['close_approach_data'][0]['relative_velocity']['kilometers_per_hour']
                        ),
                        \DateTime::createFromFormat(
                            'Y-m-d',
                            $near_earth_object['close_approach_data'][0]['close_approach_date']
                        ),
                        $near_earth_object['is_potentially_hazardous_asteroid']
                    );

                    $earthObjects[] = $earthObject;
                } catch (\Exception $exception) {
                    continue;
                }
            }
        }

        return $earthObjects;
    }

    /**
     * @param string $startDate
     * @param string $endDate
     *
     * @return string
     */
    private function getFeedEndpoint(string $startDate, string $endDate): string
    {
        return self::NASA_API_LINK
            . self::FEED_ENDPOINT
            . '?start_date=' . $startDate
            . '&end_date=' . $endDate
            . '&api_key=' . $this->apiKey;
    }
}
