<?php

namespace App\Repository;

use App\Entity\Currency;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Currency>
 *
 * @method Currency|null find($id, $lockMode = null, $lockVersion = null)
 * @method Currency|null findOneBy(array $criteria, array $orderBy = null)
 * @method Currency[]    findAll()
 * @method Currency[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CurrencyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Currency::class);
    }

    public function save(Currency $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Currency $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
    * @param array<string, mixed> $values
    * @return Currency[] Returns an array of Currency objects
    */
    public function findByMultiple(array $values): array
    {
        $qb = $this->createQueryBuilder('c');

        foreach ($values as $attribute => $value) {
            // $qb->andWhere('c.'.$attribute, $value instanceof \DateTimeInterface ? $value->format('Y-m-d') : $value);
            $qb->andWhere("c.$attribute = :$attribute");
            $qb->setParameter($attribute, $value);
        }

        return $qb
            ->getQuery()
            ->getResult()
        ;
    }
}
