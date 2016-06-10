<?php

namespace EnfantBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('EnfantBundle:Default:index.html.twig');
    }
}
