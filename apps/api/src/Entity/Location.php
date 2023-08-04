<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\GetCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * L'entité représentant une localisation.
 */
#[
    GetCollection(),
    ORM\Entity()
]
class Location
{
    // Propriétés :

    /**
     * @var int|null l'identifiant.
     */
    #[
        Groups(['intervention:get']),
        ORM\Id(),
        ORM\GeneratedValue(),
        ORM\Column(type: Types::BIGINT)
    ]
    private ?int $id;

    /**
     * @var string|null la rue.
     */
    #[
        Assert\Length(
            max: 100,
            maxMessage: 'location.street.maxLength',
        ),
        Groups([
            'intervention:get',
            'intervention:get-collection',
            'intervention:patch',
            'intervention:post'
        ]),
        ORM\Column(type: Types::TEXT, nullable: true)
    ]
    private ?string $street;

    /**
     * @var string|null le complément d'adresse.
     */
    #[
        Assert\Length(
            max: 500,
            maxMessage: 'location.rest.maxLength',
        ),
        Groups([
            'intervention:get',
            'intervention:get-collection',
            'intervention:patch',
            'intervention:post'
        ]),
        ORM\Column(type: Types::TEXT, nullable: true)
    ]
    private ?string $rest;

    /**
     * @var string|null le code postal.
     */
    #[
//        Assert\Regex(
//            '/^\d{5}$/',
//            message: 'location.postcode'
//        ),
        Groups([
            'intervention:get',
            'intervention:get-collection',
            'intervention:patch',
            'intervention:post'
        ]),
        ORM\Column(type: Types::TEXT, nullable: true)
    ]
    private ?string $postcode;

    /**
     * @var string|null la ville.
     */
    #[
        Assert\Length(
            max: 163,
            maxMessage: 'location.city.maxLength',
        ),
        Groups([
            'intervention:get',
            'intervention:get-collection',
            'intervention:patch',
            'intervention:post'
        ]),
        ORM\Column(type: Types::TEXT, nullable: true)
    ]
    private ?string $city;

    /**
     * @var float la longitude.
     */
    #[
        Assert\Range(
            min: -180,
            max: 180,
            notInRangeMessage: 'location.longitude.notInRange'
        ),
        Groups([
            'intervention:get',
            'intervention:get-collection',
            'intervention:patch',
            'intervention:post'
        ]),
        ORM\Column(type: Types::DECIMAL, precision: 9, scale: 6)
    ]
    private float $longitude;

    /**
     * @var float la latitude.
     */
    #[
        Assert\Range(
            min: -90,
            max: 90,
            notInRangeMessage: 'location.latitude.notInRange'
        ),
        Groups([
            'intervention:get',
            'intervention:get-collection',
            'intervention:patch',
            'intervention:post'
        ]),
        ORM\Column(type: Types::DECIMAL, precision: 8, scale: 6)
    ]
    private float $latitude;


    // Méthodes magiques :

    /**
     * Le constructeur.
     * @param string|null $street la rue.
     * @param string|null $rest le complément d'adresse.
     * @param string|null $postcode le code postal.
     * @param string|null $city la ville.
     * @param float $longitude la longitude.
     * @param float $latitude la latitude.
     */
    public function __construct(
        ?string $street,
        ?string $rest,
        ?string $postcode,
        ?string $city,
        float $longitude,
        float $latitude
    ) {
        $this->id = null;
        $this->street = $street;
        $this->rest = $rest;
        $this->postcode = $postcode;
        $this->city = $city;
        $this->longitude = $longitude;
        $this->latitude = $latitude;
    }


    // Accesseurs :

    /**
     * Renvoie l'identifiant.
     * @return int|null l'identifiant.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Renvoie la rue.
     * @return string|null la rue.
     */
    public function getStreet(): ?string
    {
        return $this->street;
    }

    /**
     * Renvoie le complément d'adresse.
     * @return string|null le complément d'adresse.
     */
    public function getRest(): ?string
    {
        return $this->rest;
    }

    /**
     * Renvoie le code postal.
     * @return string|null le code postal.
     */
    public function getPostcode(): ?string
    {
        return $this->postcode;
    }

    /**
     * Renvoie la ville.
     * @return string|null la ville.
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * Renvoie la longitude.
     * @return float la longitude.
     */
    public function getLongitude(): float
    {
        return $this->longitude;
    }

    /**
     * Renvoie la latitude.
     * @return float la latitude.
     */
    public function getLatitude(): float
    {
        return $this->latitude;
    }


    // Mutateurs ;

    /**
     * Change la rue.
     * @param string|null $street la rue.
     */
    public function setStreet(?string $street): void
    {
        $this->street = $street;
    }

    /**
     * Change le complément d'adresse.
     * @param string|null $rest le complément d'adresse.
     */
    public function setRest(?string $rest): void
    {
        $this->rest = $rest;
    }

    /**
     * Change le code postal.
     * @param string|null $postcode le code postal.
     */
    public function setPostcode(?string $postcode): void
    {
        $this->postcode = $postcode;
    }

    /**
     * Change la ville.
     * @param string|null $city la ville.
     */
    public function setCity(?string $city): void
    {
        $this->city = $city;
    }

    /**
     * Change la longitude.
     * @param float $longitude la longitude.
     */
    public function setLongitude(float $longitude): void
    {
        $this->longitude = $longitude;
    }

    /**
     * Change la latitude.
     * @param float $latitude la latitude.
     */
    public function setLatitude(float $latitude): void
    {
        $this->latitude = $latitude;
    }
}
