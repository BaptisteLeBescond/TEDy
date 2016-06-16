<?php

namespace EnfantBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use SequenceBundle\Entity\Sequence;
use SequenceBundle\Entity\Etape;
use SequenceBundle\Entity\Contrat;
use SequenceBundle\Entity\Planning;

class DefaultController extends Controller
{
    public function indexAction()
    {
        if($this->container->get('security.authorization_checker')->isGranted('ROLE_EDUCATEUR'))
            return $this->render('EnfantBundle:Default:accessDenied.html.twig');

        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();
        $contrat = $em->getRepository('SequenceBundle:Contrat')->findOneBy(array('enfant' => $user, 'enCours' => true));

        return $this->render('EnfantBundle:Default:index.html.twig', array('contrat' => $contrat, 'user' => $user));
    }

    public function calendrierAction()
    {
        if($this->container->get('security.authorization_checker')->isGranted('ROLE_EDUCATEUR'))
            return $this->render('EnfantBundle:Default:accessDenied.html.twig');

        return $this->render('EnfantBundle:Default:calendrier.html.twig');
    }

    public function contratAction()
    {
        if($this->container->get('security.authorization_checker')->isGranted('ROLE_EDUCATEUR'))
            return $this->render('EnfantBundle:Default:accessDenied.html.twig');

        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();
        $contrat = $em->getRepository('SequenceBundle:Contrat')->findOneBy(array('enfant' => $user, 'enCours' => true));

        if(is_null($contrat)) {
            return $this->render('EnfantBundle:Default:contrat404.html.twig');
        }
        else {
            $sequence = $contrat->getSequence();
            $etapes = $sequence->getEtapes();
            $nbreEtapes = sizeof($etapes);
            if($nbreEtapes > 6)
                $sizeCol = 1;
            elseif ($nbreEtapes > 4)
                $sizeCol = 2;
            elseif ($nbreEtapes == 4)
                $sizeCol = 3;
            elseif ($nbreEtapes == 3)
                $sizeCol = 4;
            elseif ($nbreEtapes == 2)
                $sizeCol = 6;
            else
                $sizeCol = 12;

            return $this->render('EnfantBundle:Default:contrat.html.twig', array('sizeCol' => $sizeCol, 'nbreEtapes' => $nbreEtapes, 'etapes' => $etapes, 'contrat' => $contrat, 'user' => $user));
        }
        
    }

    public function planningAction()
    {
        if($this->container->get('security.authorization_checker')->isGranted('ROLE_EDUCATEUR'))
            return $this->render('EnfantBundle:Default:accessDenied.html.twig');

        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $plannings = $em->getRepository('SequenceBundle:Planning')->findBy(array('enfant' => $user->getId()));

        return $this->render('EnfantBundle:Default:planning.html.twig', array('user' => $user , 'plannings' => $plannings));
    }

    public function planningencoursAction(Request $request, $id)
    {
        if($this->container->get('security.authorization_checker')->isGranted('ROLE_EDUCATEUR'))
            return $this->render('EnfantBundle:Default:accessDenied.html.twig');

        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $plannings = $em->getRepository('SequenceBundle:Planning')->findBy(array('enfant' => $user->getId()));
        $planning = $em->getRepository('SequenceBundle:Planning')->find(array('id' => $id));

        $planning->setEnCours(true);
        $em->persist($planning);
        $em->flush();

        $form = $this->get('form.factory')->createBuilder('form')
            ->add('duree',  'text', array('required' => false, 'attr' => array('id' => 'duree')))
            ->add('Enregistrer', 'submit', array('attr' => array('class' => 'btn')))
            ->getForm();

        $form->handleRequest($request);

        if($form->isValid()){
            $planning->setEnCours(false);
            $planning->setDuree($form['duree']->getData());

            $em->persist($planning);
            $em->flush();

            return $this->render('EnfantBundle:Default:planning.html.twig', array('user' => $user , 'plannings' => $plannings));
        }

        return $this->render('EnfantBundle:Default:planningencours.html.twig', array('planning' => $planning, 'form' => $form->createView()));
    }

    public function creerPlanningAction(Request $request)
    {
        if($this->container->get('security.authorization_checker')->isGranted('ROLE_EDUCATEUR'))
            return $this->render('EnfantBundle:Default:accessDenied.html.twig');

        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $sequence = new Sequence;
        $etapes = $em->getRepository('SequenceBundle:Etape')->findByPosition(0);

        $form = $this->get('form.factory')->createBuilder('form')
          ->add('libelle',  'text', array('label' => 'Titre','required' => true, 'attr' => array('class' => 'form-required')))
          ->add('description',     'textarea', array('label' => 'Description','required' => true, 'attr' => array('class' => 'form-required')))
          // ->add('musique',   'integer', array('label' => 'Âge','required' => false, 'attr' => array('class' => 'form-required')))
          ->add('save', 'submit')
          ;

          for ($i=0; $i < sizeof($etapes) ; $i++) { 
            $form->add('libelleEtape'.$i, 'text', array('required' => false, 'attr' => array('id' => 'libelleEtape'.$i, 'class' => 'inputLibelle hidden'), 'label_attr' => array('class' => 'hidden')));
            $form->add('imageEtape'.$i, 'text', array('required' => false, 'attr' => array('id' => 'imageEtape'.$i, 'class' => 'inputImage hidden'), 'label_attr' => array('class' => 'hidden')));
            $form->add('positionEtape'.$i, 'integer', array('required' => false, 'attr' => array('id' => 'positionEtape'.$i, 'class' => 'inputPosition hidden'), 'label_attr' => array('class' => 'hidden')));
          }

        $form = $form->getForm();

        $form->handleRequest($request);

        if($form->isValid()){
            for ($i=0; $i < sizeof($etapes) ; $i++) { 
                $etape = new Etape();
                $libelle = $form['libelleEtape'.$i]->getData();
                $image = $form['imageEtape'.$i]->getData();
                $position = $form['positionEtape'.$i]->getData();
                if($libelle != '' && $image != '' && $position != ''){
                    $etape->setLibelle($libelle);
                    $etape->setImage($image);
                    $etape->setPosition($position);
                    var_dump($etape);

                    $sequence->addEtape($etape);

                    $em->persist($etape);
                }
            }

            $sequence->setCreateur($user);
            $sequence->setLibelle($form['libelle']->getData());
            $sequence->setDescription($form['description']->getData());

            $planning = new Planning;

            $planning->setLibelle($form['libelle']->getData());
            $planning->setEnCours(false);
            $planning->setEnfant($user);
            $planning->setSequence($sequence);

            $em->persist($sequence);
            $em->persist($planning);

            $em->flush();
        }
        else
            var_dump('ERREUR !');

        return $this->render('EnfantBundle:Default:creerPlanning.html.twig', array('form' => $form->createView(), 'user' => $user, 'etapes' => $etapes));
    }

}
