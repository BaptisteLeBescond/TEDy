<?php

namespace EducateurBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('EducateurBundle:Default:index.html.twig');
    }
}
