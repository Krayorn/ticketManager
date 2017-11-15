<?php

namespace UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use TicketManagerBundle\Entity\Message;
use TicketManagerBundle\Entity\Ticket;

/**
 * @ORM\Entity
 * @ORM\Table(name="`users`")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="TicketManagerBundle\Entity\Message", mappedBy="author")
     */
    protected $messages;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="TicketManagerBundle\Entity\Ticket", mappedBy="author")
     */
    protected $ticketsWritten;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="TicketManagerBundle\Entity\Ticket", mappedBy="assignedAt")
     */
    protected $ticketsAssigned;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

    /**
     * Add message
     *
     * @param Message $message
     *
     * @return User
     */
    public function addMessage(Message $message)
    {
        $this->messages[] = $message;

        return $this;
    }

    /**
     * Remove message
     *
     * @param Message $message
     */
    public function removeMessage(Message $message)
    {
        $this->messages->removeElement($message);
    }

    /**
     * Get messages
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * Add ticketsWritten
     *
     * @param Ticket $ticketsWritten
     *
     * @return User
     */
    public function addTicketsWritten(Ticket $ticketsWritten)
    {
        $this->ticketsWritten[] = $ticketsWritten;

        return $this;
    }

    /**
     * Remove ticketsWritten
     *
     * @param Ticket $ticketsWritten
     */
    public function removeTicketsWritten(Ticket $ticketsWritten)
    {
        $this->ticketsWritten->removeElement($ticketsWritten);
    }

    /**
     * Get ticketsWritten
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTicketsWritten()
    {
        return $this->ticketsWritten;
    }

    /**
     * Add ticketsAssigned
     *
     * @param Ticket $ticketsAssigned
     *
     * @return User
     */
    public function addTicketsAssigned(Ticket $ticketsAssigned)
    {
        $this->ticketsAssigned[] = $ticketsAssigned;

        return $this;
    }

    /**
     * Remove ticketsAssigned
     *
     * @param Ticket $ticketsAssigned
     */
    public function removeTicketsAssigned(Ticket $ticketsAssigned)
    {
        $this->ticketsAssigned->removeElement($ticketsAssigned);
    }

    /**
     * Get ticketsAssigned
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTicketsAssigned()
    {
        return $this->ticketsAssigned;
    }
}
