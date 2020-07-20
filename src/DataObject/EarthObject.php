<?php

namespace App\DataObject;

/**
 * Class NearEarthObject
 */
class EarthObject
{
    private int $neo_reference_id;
    private string $name;
    private float $kilometers_per_hour;
    private \DateTime $date;
    private bool $is_hazardous;

    /**
     * NearEarthObject constructor.
     *
     * @param int $neo_reference_id
     * @param string $name
     * @param float $kilometers_per_hour
     * @param \DateTime $date
     * @param bool $is_hazardous
     */
    public function __construct(
        int $neo_reference_id,
        string $name,
        float $kilometers_per_hour,
        \DateTime $date,
        bool $is_hazardous
    )
    {
        $this->neo_reference_id = $neo_reference_id;
        $this->name = $name;
        $this->kilometers_per_hour = $kilometers_per_hour;
        $this->date = $date;
        $this->is_hazardous = $is_hazardous;
    }

    /**
     * @return int
     */
    public function getNeoReferenceId(): int
    {
        return $this->neo_reference_id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return float
     */
    public function getKilometersPerHour(): float
    {
        return $this->kilometers_per_hour;
    }

    /**
     * @return \DateTime
     */
    public function getDate(): \DateTime
    {
        return $this->date;
    }

    /**
     * @return bool
     */
    public function isHazardous(): bool
    {
        return $this->is_hazardous;
    }
}
