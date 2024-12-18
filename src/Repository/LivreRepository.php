<?php

namespace App\Repository;

use App\Entity\Livre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class LivreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Livre::class);
    }

    /**
     * Cette méthode retourne les livres empruntés et non restitués à temps.
     *
     * @return Livre[] Retourne la liste des livres en retard
     */
    public function findLivresEnRetard(): array
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.estDisponible = false') // Filtre : livre emprunté
            ->andWhere('l.dateRetourPrevue < :aujourdhui') // Filtre : date de retour dépassée
            ->setParameter('aujourdhui', new \DateTime()) // Paramètre : date actuelle
            ->orderBy('l.dateRetourPrevue', 'ASC') // Tri : les retards les plus anciens en premier
            ->getQuery()
            ->getResult();
    }
}
