<?php

namespace TicketManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;

use TicketManagerBundle\Entity\Ticket;
use TicketManagerBundle\Entity\Message;
use TicketManagerBundle\Form\MessageType;

/**
 * Ticket controller.
 *
 * @Route("ticket")
 */
class TicketController extends Controller
{
    /**
     * Lists all ticket entities.
     *
     * @Route("/", name="ticket_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        if ($this->isGranted('ROLE_ADMIN')){
            $tickets = $em->getRepository('TicketManagerBundle:Ticket')->findAll();
        }else{
            $currentUser = $this->getUser();
            $tickets = $em->getRepository('TicketManagerBundle:Ticket')->findUserTickets($currentUser);
        }

        $arrayDeleteForm = [];

        foreach ($tickets as $ticket) {
            $deleteForm = $this->createDeleteForm($ticket);

            $arrayDeleteForm[$ticket->getId()] = $deleteForm;
        }

        return $this->render('TicketManagerBundle::ticket/index.html.twig', array(
            'tickets' => $tickets,
            'delete_tickets_forms' => $arrayDeleteForm
        ));
    }

    /**
     * Creates a new ticket entity.
     *
     * @Route("/new", name="ticket_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $ticket = new Ticket();
        $ticket->setCreated(new \DateTime());
        $ticket->setAuthor($this->getUser());

        $options = array('isAdmin' => $this->isGranted('ROLE_ADMIN'));

        $form = $this->createForm('TicketManagerBundle\Form\TicketType', $ticket, $options);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ticket);
            $em->flush();

            return $this->redirectToRoute('ticket_show', array('id' => $ticket->getId()));
        }

        return $this->render('TicketManagerBundle::ticket/new.html.twig', array(
            'ticket' => $ticket,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a ticket entity.
     *
     * @Route("/{id}", name="ticket_show")
     * @Method("GET")
     */
    public function showAction(Ticket $ticket)
    {
        if (!$ticket->canBeSeenBy($this->getUser())){
            throw new AccessDeniedException('Access denied');
        }

        $deleteForm = $this->createDeleteForm($ticket);

        $message = new Message();

        $messageForm = $this->createForm(MessageType::class, $message, array(
            'action' => $this->generateUrl('message_new', ['id' => $ticket->getId()])
        ));

        $ticketMessages = $ticket->getMessages();
        $arrayDeleteMessageForm = [];

        foreach ($ticketMessages as $ticketMessage) {
            $deleteMessageForm = $this->createMessageDeleteForm($ticketMessage);

            $arrayDeleteMessageForm[$ticketMessage->getId()] = $deleteMessageForm;
        }


        return $this->render('TicketManagerBundle::ticket/show.html.twig', array(
            'ticket' => $ticket,
            'delete_form' => $deleteForm->createView(),
            'new_message_form' => $messageForm->createView(),
            'delete_message_forms' => $arrayDeleteMessageForm
        ));
    }

    /**
     * Displays a form to edit an existing ticket entity.
     *
     * @Route("/admin/edit/{id}", name="ticket_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Ticket $ticket)
    {
        $deleteForm = $this->createDeleteForm($ticket);

        $options = array(
            'isAdmin'   => $this->isGranted('ROLE_ADMIN'),
            'isEdition' => true,
        );

        $editForm = $this->createForm('TicketManagerBundle\Form\TicketType', $ticket, $options);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('ticket_edit', array('id' => $ticket->getId()));
        }

        return $this->render('TicketManagerBundle::ticket/edit.html.twig', array(
            'ticket' => $ticket,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a ticket entity.
     *
     * @Route("/admin/delete/{id}", name="ticket_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Ticket $ticket)
    {
        $form = $this->createDeleteForm($ticket);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($ticket);
            $em->flush();
        }

        return $this->redirectToRoute('ticket_index');
    }

    /**
     * Creates a form to delete a ticket entity.
     *
     * @param Ticket $ticket The ticket entity
     *
     * @return \Symfony\Component\Form\FormInterface The form
     */
    private function createDeleteForm(Ticket $ticket)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('ticket_delete', array('id' => $ticket->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * Creates a form to delete a message entity.
     *
     * @param Message $message The message entity
     *
     * @return \Symfony\Component\Form\FormInterface The form
     */
    private function createMessageDeleteForm(Message $message)
    {
        return $this->createFormBuilder()
            ->setMethod('DELETE')
            ->setAction($this->generateUrl('message_delete', array('id' => $message->getId())))
            ->getForm()
        ;
    }
}
