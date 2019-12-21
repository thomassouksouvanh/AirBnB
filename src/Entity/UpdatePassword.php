<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class UpdatePassword
{

    private $oldPassword;

    /**
     * @Assert\Length(min="6", minMessage="Votre mot de passe doit contenir au moins 6 caractères !")
     */
    private $newPassword;

    /**
     * @Assert\EqualTo(propertyPath="newPassword", message="Le nouveau mot de passe n'est pas correctement confirmé")
     */
    private $confirmPassword;

    public function getConfirmPassword()
    {
        return $this->confirmPassword;
    }

    public function setConfirmPassword($confirmPassword): void
    {
        $this->confirmPassword = $confirmPassword;
    }

    public function getOldPassword(): ?string
    {
        return $this->oldPassword;
    }

    public function setOldPassword(string $oldPassword): self
    {
        $this->oldPassword = $oldPassword;

        return $this;
    }

    public function getNewPassword(): ?string
    {
        return $this->newPassword;
    }

    public function setNewPassword(string $newPassword): self
    {
        $this->newPassword = $newPassword;

        return $this;
    }
}
