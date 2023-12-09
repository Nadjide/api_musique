<?php

namespace App\Entity;

use App\Entity\Chanson;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\AlbumRepository;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: AlbumRepository::class)]
#[ApiResource(
    uriTemplate: 'artistes/{artiste_id}/albums',
    uriVariables: [
        'artiste_id' => new Link(fromClass: Artiste::class, toProperty: 'artiste')
    ],
    operations: [new GetCollection(), new Post()]

)]

#[ApiResource(
    uriTemplate: 'artistes/{artiste_id}/albums/{album_id}',
    uriVariables: [
        'artiste_id' => new Link(fromClass: Artiste::class, toProperty: 'artiste'),
        'album_id' => new Link(fromClass: Album::class)
    ],
    operations: [new GetCollection(), new Get(), new Put(), new Delete(), new Patch()]
)]
class Album
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 50)]
    private ?string $date = null;

    #[ORM\ManyToOne(inversedBy: 'albums')]
    private ?Artiste $artiste = null;

    #[ORM\OneToMany(mappedBy: 'album', targetEntity: Chanson::class)]
    private Collection $songs;

    public function __construct()
    {
        $this->songs = new ArrayCollection();
    }

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

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(string $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getArtiste(): ?Artiste
    {
        return $this->artiste;
    }

    public function setArtiste(?Artiste $artiste): static
    {
        $this->artiste = $artiste;

        return $this;
    }

    /**
     * @return Collection<int, Chanson>
     */
    public function getSongs(): Collection
    {
        return $this->songs;
    }

    public function addSong(Chanson $song): static
    {
        if (!$this->songs->contains($song)) {
            $this->songs->add($song);
            $song->setAlbum($this);
        }

        return $this;
    }

    public function removeSong(Chanson $song): static
    {
        if ($this->songs->removeElement($song)) {
            // set the owning side to null (unless already changed)
            if ($song->getAlbum() === $this) {
                $song->setAlbum(null);
            }
        }

        return $this;
    }
}
