<?php

namespace Fonasa\MonitorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('MonitorBundle:Default:index.html.twig');
    }
}
