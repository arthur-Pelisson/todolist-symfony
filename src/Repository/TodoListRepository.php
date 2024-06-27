<?php

namespace App\Repository;

use App\Entity\TodoList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TodoList>
 */
class TodoListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TodoList::class);
    }

    /**
     * Fetches all TodoLists with their associated User entities.
     *
     * @return TodoList[] Returns an array of TodoList objects
     */
    public function findAllWithUser(): array
    {
        return $this->createQueryBuilder('t')
            ->leftJoin('t.user', 'u')
            ->addSelect('u')
            ->getQuery()
            ->getResult();
    }
}
