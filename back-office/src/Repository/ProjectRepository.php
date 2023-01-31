<?php

namespace App\Repository;

use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;

/**
 * @extends ServiceEntityRepository<Project>
 *
 * @method Project|null find($id, $lockMode = null, $lockVersion = null)
 * @method Project|null findOneBy(array $criteria, array $orderBy = null)
 * @method Project[]    findAll()
 * @method Project[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private Security $security)
    {
        parent::__construct($registry, Project::class);
    }
    
    /**
     * save
     *
     * @param  mixed $entity
     * @param  mixed $flush
     * @return void
     */
    public function save(Project $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    
    /**
     * remove
     *
     * @param  mixed $entity
     * @param  mixed $flush
     * @return void
     */
    public function remove(Project $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function latest():mixed 
    {
        $params = [];
        $qb = $this->createQueryBuilder('p')
                ->andWhere('p.state = :state')
            ;
        $params['state'] = 'published';

        if (!$this->security->isGranted(['ROLE_ADMIN'])) {
            $qb->andWhere('p.visibility = :visibility');
            $params['visibility'] = 'public';
        }
        
        return $qb
            ->setParameters($params)
            ->orderBy('p.publishedAt', 'desc')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult()
        ;
    }
    
    /**
     * search
     *
     * @param  mixed $search
     * @return mixed
     */
    public function search(?string $search = null):mixed
    {
        $qb = $this->createQueryBuilder('p');

        if ($search !== null && $search !== '') {
            $qb
                ->where('MATCH_AGAINST(p.name, p.description, p.slug) AGAINST(:search boolean)>0')
                ->setParameters(['search' => $search])
            ;
        }
        
        return $qb->orderBy('p.updatedAt', 'desc')->getQuery()->getResult();
    }

//    /**
//     * @return Project[] Returns an array of Project objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Project
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
