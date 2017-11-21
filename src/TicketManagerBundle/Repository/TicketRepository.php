<?php

namespace TicketManagerBundle\Repository;

/**
 * TicketRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TicketRepository extends \Doctrine\ORM\EntityRepository
{
    public function findUserTickets($user)
    {
        $qb = $this->createQueryBuilder('t');

        return $qb->where('t.assignedAt = :user')
            ->orWhere('t.author = :user')
            ->setParameter('user', $user)
            ->getQuery()->getResult();
    }
}
