<?php

namespace TicketManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

use TicketManagerBundle\Entity\Message;
use TicketManagerBundle\Entity\Ticket;
use TicketManagerBundle\Form\MessageType;

/**
 * Message controller.
 *
 * @Route("message")
 */
class MessageController extends Controller
{
    /**
     * Creates a new message entity.
     *
     * @Route("/new/{id}", name="message_new")
     * @Method({"POST"})
     */
    public function newAction(Request $request, Ticket $ticket)
    {
        $message = new Message();
        $messageForm = $this->createForm(MessageType::class, $message);
        $messageForm->handleRequest($request);

        if ($messageForm->isValid()) {

            $message->setTicket($ticket);
            $message->setCreated(new \DateTime());
            $message->setAuthor($this->getUser());

            $em = $this->getDoctrine()->getManager();
            $em->persist($message);
            $em->flush();

            return $this->redirectToRoute('ticket_show', array('id' => $ticket->getId()));
        } else {
            // TODO: USE referer to reshow the form
            return $this->redirectToRoute('ticket_show', array('id' => $ticket->getId()));
        }
    }
}
