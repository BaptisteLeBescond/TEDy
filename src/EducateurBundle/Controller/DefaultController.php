<?php

namespace EducateurBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	$user = $this->getUser();

        return $this->render('EducateurBundle:Default:index.html.twig', array('user' => $user));
    }
}
