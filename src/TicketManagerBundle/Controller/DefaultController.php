<?php

namespace TicketManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function indexAction()
    {
        var_dump($this->isGranted('ROLE_USER'));
        return $this->render('TicketManagerBundle:Default:index.html.twig');
    }
}
