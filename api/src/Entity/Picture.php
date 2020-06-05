<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Type;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PictureRepository")
 */
class Picture
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $filename;

    /**
     * @var string
     * @Type("string")
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $code;

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     * @return Picture
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="pictures")
     */
    private $user;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(?string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }
    /**
     * @return string|null
     */
    public function getImageFile(): ?string
    {
        return $this->imageFile;
    }

    /**
     * @param string|null $imageFile
     * @return Picture
     */
    public function setImageFile(?string $imageFile): self
    {
        $this->imageFile = $imageFile;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

}
