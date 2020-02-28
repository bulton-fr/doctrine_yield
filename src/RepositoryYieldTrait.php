<?php

namespace BultonFr\DoctrineYield;

use Doctrine\ORM\QueryBuilder;
use Generator;

trait RepositoryYieldTrait
{
    /**
     * Yield a query from the QueryBuilder
     *
     * @param QueryBuilder $queryBuilder The Doctrine QueryBuilder object
     * @param boolean $detachRows (default true) To detach (or not) each row
     * from the entity manager after being yielded.
     *
     * @return Generator
     */
    protected function yieldQuery(
        QueryBuilder $queryBuilder,
        $detachRows = true
    ): Generator {
        $iterator = $queryBuilder->getQuery()->iterate();
        foreach ($iterator as $row) {
            yield $row[0];

            if ($detachRows === true) {
                $this->_em->detach($row[0]);
            }
        }
    }

    /**
     * Obtain all data and yield it
     *
     * @param boolean $detachRows (default true) To detach (or not) each row
     * from the entity manager after being yielded.
     *
     * @return \Generator
     */
    public function yieldAll($detachRows = true): Generator
    {
        $qb = $this->createQueryBuilder('t');
        yield from $this->yieldQuery($qb, $detachRows);
    }
}
