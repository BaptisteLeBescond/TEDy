<?php

namespace SequenceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('SequenceBundle:Default:index.html.twig');
    }
}
