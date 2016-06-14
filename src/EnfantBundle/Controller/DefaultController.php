<?php

namespace EnfantBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $user = $this->getUser();

        return $this->render('EnfantBundle:Default:index.html.twig', array('user' => $user));
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
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $plannings = $em->getRepository('SequenceBundle:Planning')->findBy(array('enfant' => $user->getId()));

        return $this->render('EnfantBundle:Default:planning.html.twig', array('user' => $user , 'plannings' => $plannings));
    }

    public function planningencoursAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $planning = $em->getRepository('SequenceBundle:Planning')->find(array('id' => $id));

        return $this->render('EnfantBundle:Default:planningencours.html.twig', array('planning' => $planning));
    }
}
