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

    function getPostsSortedByUpdated()
    {
        $query = $this->getEntityManager()->createQuery(
            'SELECT mp.id as postId, u.id as userId, u.name, mp.content, mp.created_at, mp.updated_at, mp.likes 
        FROM App:Users u JOIN App:Microposts mp
        WHERE mp.user_id = u.id
        ORDER BY mp.updated_at DESC');
        return $query->getResult();
    }

    function getContentById($postId): string
    {
        $query = $this->getEntityManager()->createQuery('SELECT m.content FROM App:Microposts m WHERE m.id = ?1');
        $query->setParameter(1, $postId);
        $result = $query->getResult();
        if ($result) {
            return $query->getResult()[0]['content'];
        } else {
            return '';
        }
    }

    function insertPost($content, $user)
    {
        $entityManager = $this->getEntityManager();
        $post = new Microposts();
        $likes = 0;
        $post->setContent($content);
        $post->setUser($user);
        $post->setCreated_at(date("Y-m-d H:i:s"));
        $post->setUpdated_at(date("Y-m-d H:i:s"));
        $post->setLikes($likes);
        $entityManager->persist($post);
        $entityManager->flush();
    }

    function updatePost($content, $postId)
    {
        $query = $this->getEntityManager()->createQuery('UPDATE App:Microposts m SET m.content = ?1, m.updated_at = ?2 WHERE m.id = ?3');
        $query->setParameter(1, $content);
        $query->setParameter(2, new \DateTime());
        $query->setParameter(3, $postId);
        $query->getResult();
    }

    function getPostUid($postId)
    {
        $query = $this->getEntityManager()->createQuery('SELECT IDENTITY(m.user_id) FROM App:Microposts m WHERE m.id = ?1');
        $query->setParameter(1, $postId);
        $result = $query->getResult();
        if ($result) {
            return $query->getResult()[0][1];
        } else {
            return '';
        }
    }

}
