<?php

namespace App\Entity;

use App\Repository\UserRepository;//kol entité tetsna3 andaha repository
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id] // dans la base de donnée :id cle primaire (auto incr )
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;//kol entite tetsna andha id yetsnaa maha 

    public function getId(): ?int //pour acceder a un element private on utilise les gets
    {
        return $this->id;
    }
}
