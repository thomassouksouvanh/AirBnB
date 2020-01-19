<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use DateTime;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ImageRepository")
 *@ORM\HasLifecycleCallbacks()
 *@Vich\Uploadable
 */
class Image
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * @Vich\UploadableField(mapping="images_gallery", fileNameProperty="photo")
     * @Assert\Image(mimeTypes={ "image/jpg", "image/jpeg" },mimeTypesMessage="Seul les images de type jpg ou jpeg sont acceptées")
     * @var File|null
     */
    private $photoFile;

    /**
     * @ORM\Column(type="string", length=255)
     *@var File|null
     */
    private $photo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(min=6, minMessage="Plus de 6 caractère minimum SVP")
     */
    private $caption;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Annonce", inversedBy="images")
     * @ORM\JoinColumn(nullable=false)
     */
    private $annonce;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var DateTime
     */
    private $updatedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getCaption(): ?string
    {
        return $this->caption;
    }

    public function setCaption(string $caption): self
    {
        $this->caption = $caption;

        return $this;
    }

    public function getAnnonce(): ?Annonce
    {
        return $this->annonce;
    }

    public function setAnnonce(?Annonce $annonce): self
    {
        $this->annonce = $annonce;

        return $this;
    }

    /**
     * @param File|null $photoFile
     * @throws Exception
     */
    public function setPhotoFile(?File $photoFile): void
    {
        $this->photoFile = $photoFile;
        if ($this->photoFile instanceof UploadedFile ) {
            $this->updatedAt = new \DateTime('now');
        }
    }
    public function getPhotoFile(): ?File
    {
        return $this->photoFile;
    }

}
