<?php

namespace App\Repository;

use App\Entity\Microposts;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Microposts|null find($id, $lockMode = null, $lockVersion = null)
 * @method Microposts|null findOneBy(array $criteria, array $orderBy = null)
 * @method Microposts[]    findAll()
 * @method Microposts[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MicropostsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Microposts::class);
    }

    function getPosts()
    {
        $query = $this->getEntityManager()->createQuery(
            'SELECT mp.id as postId, u.id as userId, u.name, mp.content, mp.created_at, mp.updated_at, mp.likes 
        FROM App:Users u JOIN App:Microposts mp
        WHERE mp.user_id = u.id
        ORDER BY mp.updated_at DESC');
        return $query->getResult();
    }
}
