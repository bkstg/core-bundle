<?php

namespace Bkstg\CoreBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration as Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    /**
     * @Route\Route("/", name="bkstg_home")
     */
    public function indexAction()
    {
        return $this->redirectToRoute('bkstg_board_home');
    }
}
