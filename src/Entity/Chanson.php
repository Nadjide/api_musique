<?php

namespace App\Entity;

use App\Entity\Album;
use App\Entity\Artiste;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\ChansonRepository;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Doctrine\Orm\Filter\RangeFilter;


#[ORM\Entity(repositoryClass: ChansonRepository::class)]

#[ApiResource(
    uriTemplate: 'artistes/{artiste_id}/albums/{album_id}/songs',
    uriVariables: [
        'artiste_id' => new Link(fromClass: Artiste::class, toProperty: 'albums'),
        'album_id' => new Link(fromClass: Album::class),
    ],
    operations: [new GetCollection(), new Post()]
)]
#[ApiResource(
    uriTemplate: 'artistes/{artiste_id}/albums/{album_id}/songs/{id}',
    uriVariables: [
        'artiste_id' => new Link(fromClass: Artiste::class, toProperty: 'albums'),
        'album_id' => new Link(fromClass: Album::class),
        'id' => new Link(fromClass: Chanson::class)
    ],
    operations: [new Get(), new Put(), new Delete(), new Patch()]
)]

#[ApiFilter(RangeFilter::class, properties: ['length'])]
class Chanson
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $length = null;

    #[ORM\ManyToOne(inversedBy: 'songs')]
    private ?Album $album = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getLength(): ?string
    {
        return $this->length;
    }

    public function setLength(string $length): static
    {
        $this->length = $length;

        return $this;
    }

    public function getAlbum(): ?Album
    {
        return $this->album;
    }

    public function setAlbum(?Album $album): static
    {
        $this->album = $album;

        return $this;
    }
}
