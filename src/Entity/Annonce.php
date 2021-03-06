<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AnnonceRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(
 *     fields={"title"},
 *     message="Une annonce possède déja ce titre,merci de le modifier"
 * )
 *@Vich\Uploadable
 */
class Annonce implements FormTypeInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=10, minMessage="Le titre doit contenir plus de 10 caractères")
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(min=10, minMessage="Votre introduction doit contenit plus de 10 caractères")
     */
    private $introduction;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * @Vich\UploadableField(mapping="images_photocover", fileNameProperty="photocover")
     * @Assert\Image(mimeTypes={ "image/jpg", "image/jpeg" },mimeTypesMessage="Seul les images de type jpg ou jpeg sont acceptées")
     * @var File|null
     */
    private $photoCoverFile;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     *@var File|null
     */
    private $photoCover;

    /**
     * @ORM\Column(type="integer")
     * @Assert\PositiveOrZero()
     */
    private $rooms;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Image", mappedBy="annonce", orphanRemoval=true)
     * @Assert\Valid()
     */
    private $images;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="annonces")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Reservation", mappedBy="annonce")
     */
    private $reservations;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="annonce", orphanRemoval=true)
     */
    private $comments;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var DateTime
     */
    private $updatedAt;

    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->reservations = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    /**
     * permet d'initialiser le slug
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function initializeSlug()
    {
        if(empty($this->slug)){
            $slugify = new Slugify();
            $this->slug = $slugify->Slugify($this->title);
        }
    }

    /**
     * Permet d'obtenir un tableau des jours qui ne sont pas disponibles pour cette annonce
     * @return array un tableau d'objets datetime représentant les jours d'occupation
     */
    public function getNotAvailableDays()
    {
        $notAvailableDays = [];

        foreach ($this->reservations as $reservation)
        {
            // Calculer les jours qui se trouvent entre la date d'arrivée et de départ
            $resultat = range(
                $reservation->getStartDate()->getTimeStamp(),
                $reservation->getEndDate()->getTimeStamp(),
                24 * 60 * 60
            );
                $days = array_map(function ($dayTimeStamp)
                {
                    return new DateTime(date('Y-m-d',$dayTimeStamp));
                },$resultat);
                $notAvailableDays = array_merge($notAvailableDays,$days);
        }
        return $notAvailableDays;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getIntroduction(): ?string
    {
        return $this->introduction;
    }

    public function setIntroduction(string $introduction): self
    {
        $this->introduction = $introduction;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return File|null
     */
    public function getPhotoCover()
    {
        return $this->photoCover;
    }

    public function setPhotoCover($photoCover)
    {
        $this->photoCover = $photoCover;

        return $this;
    }

    public function getRooms(): ?int
    {
        return $this->rooms;
    }

    public function setRooms(int $rooms): self
    {
        $this->rooms = $rooms;

        return $this;
    }

    /**
     * @return Collection|Image[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setAnnonce($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
            // set the owning side to null (unless already changed)
            if ($image->getAnnonce() === $this) {
                $image->setAnnonce(null);
            }
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // TODO: Implement buildForm() method.
    }

    /**
     * @inheritDoc
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        // TODO: Implement buildView() method.
    }

    /**
     * @inheritDoc
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        // TODO: Implement finishView() method.
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        // TODO: Implement configureOptions() method.
    }

    /**
     * @inheritDoc
     */
    public function getBlockPrefix()
    {
        // TODO: Implement getBlockPrefix() method.
    }

    /**
     * @inheritDoc
     */
    public function getParent()
    {
        // TODO: Implement getParent() method.
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection|Reservation[]
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations[] = $reservation;
            $reservation->setAnnonce($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->contains($reservation)) {
            $this->reservations->removeElement($reservation);
            // set the owning side to null (unless already changed)
            if ($reservation->getAnnonce() === $this) {
                $reservation->setAnnonce(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setAnnonce($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getAnnonce() === $this) {
                $comment->setAnnonce(null);
            }
        }

        return $this;
    }

    /**
     * @return float|int
     * Permet de récupérer une note moyenne d'une annonce
     */
    public function getAvgRatings()
    {   // calculer la somme des notes
            $sum = array_reduce($this->comments->toArray(),function ($total,$comment){
                return $total + $comment->getRating();
            },0);
        // faire la division pour avoir la moyenne
            if(count($this->comments) > 0) return $sum / count($this->comments);
            return 0;
    }

    /**
     * Permet de récupérer un user par rapport a une annonce
     * @param User $author
     * @return Comment | null
     */
    public function getCommentFromAuthor(User $author)
    {
        foreach ($this->comments as $comment)
        {
            if($comment->getAuthor()===$author) return $comment;
        }
        return null;
    }

    /**
     * @param File $photoCoverFile
     * @throws Exception
     */
    public function setPhotoCoverFile(?File $photoCoverFile = null)
    {
        $this->photoCoverFile = $photoCoverFile ;

        if ($this->photoCoverFile instanceof UploadedFile ) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getPhotoCoverFile(): ?File
    {
        return $this->photoCoverFile;
    }

}
