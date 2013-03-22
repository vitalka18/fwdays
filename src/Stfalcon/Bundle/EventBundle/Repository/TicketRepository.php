<?php

namespace Stfalcon\Bundle\EventBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Application\Bundle\UserBundle\Entity\User;
use Doctrine\Tests\Models\DDC869\DDC869ChequePayment;
use Proxies\__CG__\Stfalcon\Bundle\PaymentBundle\Entity\Payment;
use Stfalcon\Bundle\EventBundle\Entity\Event;

/**
 * EventRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TicketRepository extends EntityRepository
{

    /**
     * Find tickets of active events for some user
     *
     * @param User $user
     *
     * @return array
     */
    public function findTicketsOfActiveEventsForUser(User $user)
    {
        return $this->getEntityManager()
            ->createQuery('
                SELECT t
                FROM StfalconEventBundle:Ticket t
                JOIN t.event e
                WHERE e.active = TRUE
                    AND t.user = :user
            ')
            ->setParameter('user', $user)
            ->getResult();
    }


    /**
     * @param Event $event
     * @param null  $status
     *
     * @return array
     */
    public function findUsersByEventAndStatus (Event $event = null, $status = null)
    {

        $query = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('u', 't', 'p')
            ->from('StfalconEventBundle:Ticket', 't')
            ->join('t.user', 'u')
            ->join('t.event', 'e')
            ->join('t.payment', 'p')
            ->andWhere('e.active = 1');

        if ($event != null) {
            $query->andWhere('t.event = :event')
                ->setParameter(':event', $event);
        }
        if ($status != null) {
            $query->andWhere('p.status = :status')
                ->setParameter(':status', $status);
        }

        $query = $query->getQuery();


        $users = array();
        foreach ($query->execute() as $result) {
            $users[] = $result->getUser();
        }


        return $users;
    }

    /**
     * Find tickets by event
     *
     * @param Event $event
     *
     * @return array
     */
    public function findTicketsByEvent(Event $event)
    {
        return $this->getEntityManager()
            ->createQuery('
                SELECT t
                FROM StfalconEventBundle:Ticket t
                JOIN t.event e
                WHERE e.active = TRUE
                    AND t.event = :event
                GROUP BY t.user
            ')
            ->setParameter('event', $event)
            ->getResult();
    }

    /**
     * Find tickets by event group by user
     *
     * @param Event $event
     *
     * @return array
     */
    public function findTicketsByEventGroupByUser(Event $event)
    {
        return $this->getEntityManager()
            ->createQuery('
                SELECT t
                FROM StfalconEventBundle:Ticket t
                JOIN t.event e
                WHERE e.active = TRUE
                    AND t.event = :event
                GROUP BY t.user
            ')
            ->setParameter('event', $event)
            ->getResult();
    }
}
