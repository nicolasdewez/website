<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Post;
use AppBundle\Entity\Tag;
use Doctrine\ORM\EntityRepository;

/**
 * Class PostRepository.
 */
class PostRepository extends EntityRepository
{
    /**
     * @param int|null $offset
     * @param int|null $limit
     * @param Tag|null $tag
     *
     * @return Post[]
     */
    public function findVisiblePost(int $offset = null, int $limit = null, Tag $tag = null): array
    {
        $queryBuilder = $this->createQueryBuilder('p');

        $queryBuilder
            ->where('p.published = TRUE')
            ->orderBy('p.writingDate', 'DESC')
            ->addOrderBy('p.id', 'DESC')
        ;

        if (null !== $tag) {
            $queryBuilder
                ->innerJoin('p.tags', 't')
                ->andWhere('t.title = :tag')
                ->setParameter('tag', $tag->getTitle())
            ;
        }

        if (null !== $limit) {
            $queryBuilder->setMaxResults($limit);
        }

        if (null !== $offset) {
            $queryBuilder->setFirstResult($offset);
        }

        return $queryBuilder->getQuery()->execute();
    }

    /**
     * @param Tag|null $tag
     * @return int
     */
    public function countVisiblePost(Tag $tag = null): int
    {
        $queryBuilder = $this->createQueryBuilder('p');

        $queryBuilder
            ->select('COUNT(p)')
            ->where('p.published = TRUE')
        ;

        if (null !== $tag) {
            $queryBuilder
                ->innerJoin('p.tags', 't')
                ->andWhere('t.title = :tag')
                ->setParameter('tag', $tag->getTitle())
            ;
        }

        return $queryBuilder->getQuery()->getSingleScalarResult();
    }
}
