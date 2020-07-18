<?php

namespace App\Repository;

use App\Entity\Item;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Item|null find($id, $lockMode = null, $lockVersion = null)
 * @method Item|null findOneBy(array $criteria, array $orderBy = null)
 * @method Item[]    findAll()
 * @method Item[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Item::class);
    }


    /**
     * @return Item[]
     */
    public function findAllSuccess(): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT i
            FROM App\Entity\Item i
            WHERE i.checked = 1');

        // returns an array of Product objects
        return $query->getResult();
    }

    /**
     * @return Item[]
     */
    public function findAllPending(): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT i
            FROM App\Entity\Item i
            WHERE i.checked = 0');

        // returns an array of Product objects
        return $query->getResult();
    }
    
    /**
     * @return Item[]
     */
    public function findAllDeleted(): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT i
            FROM App\Entity\Item i
            WHERE i.checked = 2');

        // returns an array of Product objects
        return $query->getResult();
    }

    /**
     * @return Item[]
     */
    public function findCountSuccess(): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT COUNT(i.id) as cant
            FROM App\Entity\Item i
            WHERE i.checked = 1');

        // returns an array of Product objects
        return $query->getResult();
    }

    /**
     * @return Item[]
     */
    public function findCountPending(): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT COUNT(i.id) as cant
            FROM App\Entity\Item i
            WHERE i.checked = 0');

        // returns an array of Product objects
        return $query->getResult();
    }

    /**
    * @return Item[]
    */
    public function findCountDeleted(): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT COUNT(i.id) as cant
            FROM App\Entity\Item i
            WHERE i.checked = 2');

        // returns an array of Product objects
        return $query->getResult();
    }

}
