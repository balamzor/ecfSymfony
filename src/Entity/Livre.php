<?php

// src/Entity/Livre.php
namespace App\Entity;

use App\Repository\LivreRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LivreRepository::class)]
class Livre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $titre;

    #[ORM\Column(type: 'string', length: 255)]
    private $auteur;

    #[ORM\Column(type: 'integer')]
    private $anneePublication;

    #[ORM\Column(type: 'text')]
    private $resume;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $image;

    #[ORM\Column(type: 'boolean')]
    private $disponibilite;

    #[ORM\Column(type: 'string', length: 50)]
    private $etat; // Excellent, Bon, Moyen, Mauvais

    #[ORM\Column(type: 'float', nullable: true)]
    private $note;

    #[ORM\Column(type: 'text', nullable: true)]
    private $commentaires;


}
