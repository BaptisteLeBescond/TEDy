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

        if(!$contrat){
            $contrats = $em->getRepository('SequenceBundle:Contrat')->findBy(array('enfant' => $user));
            for ($i=0; $i < sizeof($contrats); $i++) {
                if($contrats[$i]->getFini() == false && $contrats[$i]->getDate()->format('Y-m-d H:i') < date('Y-m-d H:i')){
                    $contrats[$i]->setEnCours(true);
                    $contrat = $contrats[$i];
                }
            }
        }

        $firstVisit = false;
        if(! $user->getAccueilVisited()){
            $user->setAccueilVisited(true);
            $em->persist($user);
            $em->flush();
            $firstVisit = true;
        }

        return $this->render('EnfantBundle:Default:index.html.twig', array('firstVisit' => $firstVisit,  'contrat' => $contrat, 'user' => $user));
    }

    public function calendrierAction()
    {
        if($this->container->get('security.authorization_checker')->isGranted('ROLE_EDUCATEUR'))
            return $this->render('EnfantBundle:Default:accessDenied.html.twig');

        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();
        $contrats = $em->getRepository('SequenceBundle:Contrat')->findBy(array('enfant' => $user));

        return $this->render('EnfantBundle:Default:calendrier.html.twig', array('contrats' => $contrats, 'user' => $user));
    }

    public function contratAction()
    {
        if($this->container->get('security.authorization_checker')->isGranted('ROLE_EDUCATEUR'))
            return $this->render('EnfantBundle:Default:accessDenied.html.twig');

        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();
        $contrat = $em->getRepository('SequenceBundle:Contrat')->findOneBy(array('enfant' => $user, 'enCours' => true));
        $offset = 0;

        if(is_null($contrat)) {
            $points = $user->getPoints();
            $contratsFinis = $em->getRepository('SequenceBundle:Contrat')->findOneBy(array('enfant' => $user, 'fini' => true));
            $recompenses = sizeof($contratsFinis);
            return $this->render('EnfantBundle:Default:contrat404.html.twig' , array('recompenses' => $recompenses, 'points' => $points));
        }
        else {
            $sequence = $contrat->getSequence();
            $etapes = $sequence->getEtapes();
            $nbreEtapes = sizeof($etapes);
            if($nbreEtapes > 6){
                $sizeCol = 1;
                $offset = round((12 - $nbreEtapes) / 2);
            }
            elseif ($nbreEtapes == 6)
                $sizeCol = 2;
            elseif ($nbreEtapes == 5){
                $sizeCol = 2;
                $offset = 1;
            }
            elseif ($nbreEtapes == 4)
                $sizeCol = 3;
            elseif ($nbreEtapes == 3)
                $sizeCol = 4;
            elseif ($nbreEtapes == 2)
                $sizeCol = 6;
            else{
                $sizeCol = 8;
                $offset = 2;
            }

            $firstVisit = false;
            if(! $user->getContratVisited()){
                $user->setContratVisited(true);
                $em->persist($user);
                $em->flush();
                $firstVisit = true;
            }

            return $this->render('EnfantBundle:Default:contrat.html.twig', array('offset' => $offset, 'firstVisit' => $firstVisit, 'sizeCol' => $sizeCol, 'nbreEtapes' => $nbreEtapes, 'etapes' => $etapes, 'contrat' => $contrat, 'user' => $user));
        }

    }

    public function planningAction()
    {
        if($this->container->get('security.authorization_checker')->isGranted('ROLE_EDUCATEUR'))
            return $this->render('EnfantBundle:Default:accessDenied.html.twig');

        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $plannings = $em->getRepository('SequenceBundle:Planning')->findBy(array('enfant' => $user));

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

        $firstVisit = false;
        if(! $user->getPlanningVisited()){
            $user->setPlanningVisited(true);
            $em->persist($user);
            $em->flush();
            $firstVisit = true;
        }

        $form = $this->get('form.factory')->createBuilder('form')
            ->add('duree',  'text', array('required' => false, 'attr' => array('id' => 'duree')))
            ->add('Enregistrer', 'submit', array('attr' => array('class' => 'btn')))
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $planning->setEnCours(false);
            $planning->setDuree($form['duree']->getData());

            $em->persist($planning);
            $em->flush();

            return $this->render('EnfantBundle:Default:planning.html.twig', array('user' => $user , 'plannings' => $plannings));
        }

        return $this->render('EnfantBundle:Default:planningencours.html.twig', array('firstVisit' => $firstVisit, 'planning' => $planning, 'form' => $form->createView()));
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

        if($form->isSubmitted() && $form->isValid()){
            for ($i=0; $i < sizeof($etapes) ; $i++) {
                $etape = new Etape();
                $libelle = $form['libelleEtape'.$i]->getData();
                $image = $form['imageEtape'.$i]->getData();
                $position = $form['positionEtape'.$i]->getData();
                if($libelle != '' && $image != '' && $position != ''){
                    $etape->setLibelle($libelle);
                    $etape->setImage($image);
                    $etape->setPosition($position);

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

            $message = "Le planning a été créé avec succès.";
            $plannings = $em->getRepository('SequenceBundle:Planning')->findBy(array('enfant' => $user));

            return $this->render('EnfantBundle:Default:planning.html.twig', array('message' => $message, 'user' => $user, 'plannings' => $plannings));
        }

        return $this->render('EnfantBundle:Default:creerPlanning.html.twig', array('form' => $form->createView(), 'user' => $user, 'etapes' => $etapes));
    }

    public function contratIndexAction($points,$id_contrat,$id_user) {
        if($this->container->get('security.authorization_checker')->isGranted('ROLE_EDUCATEUR'))
            return $this->render('EnfantBundle:Default:accessDenied.html.twig');

        $em = $this->getDoctrine()->getManager();

        $contrat = $em->getRepository('SequenceBundle:Contrat')->find($id_contrat);
        $contrat->setFini(true);
        $contrat->setEnCours(false);
        $em->persist($contrat);

        $user = $em->getRepository('UserBundle:User')->find($id_user);
        $user->setPoints($user->getPoints() + $points);
        $em->persist($user);

        $em->flush();

        return $this->forward('EnfantBundle:Default:index');
    }

}
