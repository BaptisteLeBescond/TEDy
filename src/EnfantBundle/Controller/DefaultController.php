<?php

namespace EnfantBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('EnfantBundle:Default:index.html.twig');
    }

    public function calendrierAction()
    {
        return $this->render('EnfantBundle:Default:calendrier.html.twig');
    }

    public function contratAction()
    {
        return $this->render('EnfantBundle:Default:contrat.html.twig');
    }

    public function planningAction()
    {
        return $this->render('EnfantBundle:Default:planning.html.twig');
    }
}
