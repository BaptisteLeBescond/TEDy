<?php

namespace EducateurBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use UserBundle\Entity\User;
use SequenceBundle\Entity\Sequence;
use SequenceBundle\Entity\Etape;
use SequenceBundle\Entity\Contrat;
// use EducateurBundle\Form\EnfantType;
use EducateurBundle\Form\SequenceType;
use EducateurBundle\Form\EtapeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Validator\Constraints\DateTime;

class DefaultController extends Controller
{
    public function menuAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $enfants = $user->getEnfant();

        return $this->render(
            'menu/menu.html.twig',
            array('enfants' => $enfants)
        );
    }

    public function indexAction()
    {
    	if($this->container->get('security.authorization_checker')->isGranted('ROLE_ENFANT'))
            return $this->render('EducateurBundle:Default:accessDenied.html.twig');

    	$em = $this->getDoctrine()->getManager();
    	$user = $this->getUser();
      	$enfants = $user->getEnfant();
      	for ($i=0; $i < sizeof($enfants); $i++) {
            $contrats[$i] = $em->getRepository('SequenceBundle:Contrat')->findBy(array('enfant' => $enfants[$i]));
            for ($j=0; $j < sizeof($contrats[$i]); $j++) {
                if($contrats[$i][$j]->getEnCours() == false && $contrats[$i][$j]->getFini() == false && $contrats[$i][$j]->getDate()->format('Y-m-d H:i') < date('Y-m-d H:i')){
                    $contrats[$i][$j]->setEnCours(true);
                    $em->persist($contrats[$i][$j]);
                    $em->flush();
                }
            }
        }
      	$i--;

        return $this->render('EducateurBundle:Default:index.html.twig', array('compteur' => $i, 'contrats' => $contrats, 'user' => $user, 'enfants' => $enfants));
    }

    public function compteAction(Request $request){
        if($this->container->get('security.authorization_checker')->isGranted('ROLE_ENFANT'))
            return $this->render('EducateurBundle:Default:accessDenied.html.twig');

        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $form = $this->get('form.factory')->createBuilder('form', $user)
          ->add('username',  'text', array('label' => 'Identifiant','required' => true, 'attr' => array('class' => 'form-required')))
          ->add('email',     'text', array('label' => 'Email','required' => true, 'attr' => array('class' => 'form-required')))
          ->add('password',    RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options'  => array('label' => 'Mot de passe'),
                'second_options' => array('label' => 'Vérification du mot de passe')))
          ->add('save',      'submit')
          ->getForm()
          ;

        $form->handleRequest($request);

        if($form->isValid()){
          var_dump('Compte modifié avec succès');

          $factory = $this->get('security.encoder_factory');
          $encoder = $factory->getEncoder($user);
          $password = $encoder->encodePassword($form->get('password')->getData(), $user->getSalt());
          $user->setPassword($password);

          $em->persist($user);
          $em->flush();

        }
        else
            var_dump('ERREUR !');

        return $this->render('EducateurBundle:Default:monCompte.html.twig', array('user' => $user, 'form' => $form->createView()));
    }

    public function ajoutEnfantAction(Request $request)
    {
    	if($this->container->get('security.authorization_checker')->isGranted('ROLE_ENFANT'))
            return $this->render('EducateurBundle:Default:accessDenied.html.twig');

    	$em = $this->getDoctrine()->getManager();
    	$user = $this->getUser();

		$enfant = new User;
		$enfant->setRoles(array('role' => 'ROLE_ENFANT'));
		$enfant->setEnabled(1);

		$form = $this->get('form.factory')->createBuilder('form', $enfant)
		  ->add('username',  'text', array('label' => 'Prénom','required' => true, 'attr' => array('class' => 'form-required')))
	      ->add('name',     'text', array('label' => 'Nom','required' => true, 'attr' => array('class' => 'form-required')))
	      ->add('password',    RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options'  => array('label' => 'Mot de passe'),
                'second_options' => array('label' => 'Vérification du mot de passe')))
	      ->add('age',   'integer', array('label' => 'Âge','required' => false, 'attr' => array()))
	      ->add('telephone', 'integer', array('label' => 'Téléphone','required' => false, 'attr' => array()))
	      ->add('adresse_postale', 'text', array('label' => 'Adresse','required' => false, 'attr' => array()))
	      ->add('code_postale', 'integer', array('label' => 'Code postal','required' => false, 'attr' => array()))
	      ->add('ville', 'text', array('label' => 'Ville','required' => false, 'attr' => array()))
	      ->add('photo', 'file', array('label' => 'Photo','required' => false, 'attr' => array()))
	      ->add('save',      'submit')
	      ->getForm()
	      ;

	      $form->handleRequest($request);

		if($form->isValid()){
			var_dump('Enfant créé avec succès');

			$user->addEnfant($enfant);

			$factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($enfant);
            $password = $encoder->encodePassword($form->get('password')->getData(), $enfant->getSalt());
            $enfant->setPassword($password);

			$em->persist($user);
			$em->persist($enfant);
			$em->flush();

		}
		else
			var_dump('ERREUR !');

        return $this->render('EducateurBundle:Default:ajoutEnfant.html.twig', array('user' => $user, 'form' => $form->createView()));
    }

    public function modifEnfantAction($username, $name, Request $request)
    {
      if($this->container->get('security.authorization_checker')->isGranted('ROLE_ENFANT'))
            return $this->render('EducateurBundle:Default:accessDenied.html.twig');

      $em = $this->getDoctrine()->getManager();
    	$user = $this->getUser();

    	$enfant = $em->getRepository('UserBundle:User')->findOneBy(array('username' => $username, 'name' => $name));

      $form = $this->get('form.factory')->createBuilder('form', $enfant)
      ->add('username',  'text', array('label' => 'Prénom','required' => true, 'attr' => array('class' => 'form-required')))
        ->add('name',     'text', array('label' => 'Nom','required' => true, 'attr' => array('class' => 'form-required')))
        ->add('age',   'integer', array('label' => 'Âge','required' => false, 'attr' => array()))
        ->add('telephone', 'integer', array('label' => 'Téléphone','required' => false, 'attr' => array()))
        ->add('adresse_postale', 'text', array('label' => 'Adresse','required' => false, 'attr' => array()))
        ->add('code_postale', 'integer', array('label' => 'Code postal','required' => false, 'attr' => array()))
        ->add('ville', 'text', array('label' => 'Ville','required' => false, 'attr' => array()))
        ->add('photo', FileType::class, array('label' => 'Photo','required' => false, 'attr' => array()))
        ->add('save',      'submit')
        ->getForm()
	      ;

        $form->handleRequest($request);

        if($form->isValid()){
        	$file = $form['photo']->getData();
        	$fileName = md5(uniqid()).'.'.$file->getClientOriginalName();
        	$file->move(
                $this->container->getParameter('photosEnfant_directory'),
                $fileName
            );
        	$enfant->setPhoto($fileName	);

        	$em->persist($enfant);
  			$em->flush();
        }
        else
			var_dump('ERREUR !');

            return $this->render('EducateurBundle:Default:modifEnfant.html.twig', array('user' => $user, 'enfant' => $enfant, 'form' => $form->createView()));

    }

    public function creerSequenceAction(Request $request)
    {
    	if($this->container->get('security.authorization_checker')->isGranted('ROLE_ENFANT'))
            return $this->render('EducateurBundle:Default:accessDenied.html.twig');

    	$em = $this->getDoctrine()->getManager();
    	$user = $this->getUser();
    	$sequence = new Sequence;
    	// $etapes = $em->getRepository('SequenceBundle:Etape')->findByPosition(0);
        $repository = $this->getDoctrine()->getRepository('SequenceBundle:Etape');
        $etapes = $repository->createQueryBuilder('e')
            ->where('e.position = :position')
            ->orWhere('e.createur = :user')
            ->setParameter('position', 0)
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();

    	$form = $this->get('form.factory')->createBuilder('form')
    	  ->add('libelle',  'text', array('label' => 'Titre','required' => true, 'attr' => array('class' => 'form-required')))
	      ->add('description',     'textarea', array('label' => 'Description','required' => true, 'attr' => array('class' => 'form-required')))
	      ->add('musique',   FileType::class, array('label' => 'Musique','required' => false, 'attr' => array('class' => 'form-required')))
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

        	$file = $form['musique']->getData();
        	var_dump($file->getClientOriginalName());
        	$fileName = md5(uniqid()).'.'.$file->getClientOriginalName();
        	var_dump($fileName);
        	$file->move(
                $this->container->getParameter('musiques_directory'),
                $fileName
            );

        	$sequence->setMusique($file);
        	$sequence->setCreateur($user);
        	$sequence->setLibelle($form['libelle']->getData());
        	$sequence->setDescription($form['description']->getData());
        	$em->persist($sequence);

        	$em->flush();
		}
		else
			var_dump('ERREUR !');

        return $this->render('EducateurBundle:Default:creerSequence.html.twig', array('form' => $form->createView(), 'user' => $user, 'etapes' => $etapes));
    }

    public function ficheEnfantAction($username, $name)
    {
    	if($this->container->get('security.authorization_checker')->isGranted('ROLE_ENFANT'))
            return $this->render('EducateurBundle:Default:accessDenied.html.twig');

    	$em = $this->getDoctrine()->getManager();
    	$user = $this->getUser();

        $contrat = array();
        $planning = array();

    	$enfant = $em->getRepository('UserBundle:User')->findOneBy(array('username' => $username, 'name' => $name));
    	$contrats = $em->getRepository('SequenceBundle:Contrat')->findByEnfant($enfant);
        $plannings = $em->getRepository('SequenceBundle:Planning')->findByEnfant($enfant);

        for ($i = 0 ; $i < count($contrats) ; $i++) {
            if($contrats[$i]->getEnCours() == true) {
                array_push($contrat, $contrats[$i]);
            }
        }

        for ($i = 0 ; $i < count($plannings) ; $i++) {
            if($plannings[$i]->getEnCours() == true) {
                array_push($planning, $plannings[$i]);
            }
        }

        return $this->render('EducateurBundle:Default:ficheEnfant.html.twig', array('contrats' => $contrats, 'user' => $user, 'enfant' => $enfant, 'contratEnCours' => $contrat, 'planningEnCours' => $planning));
    }

    public function creerContratAction(Request $request, $username, $name)
    {
    	if($this->container->get('security.authorization_checker')->isGranted('ROLE_ENFANT'))
            return $this->render('EducateurBundle:Default:accessDenied.html.twig');

    	$em = $this->getDoctrine()->getManager();
    	$user = $this->getUser();

    	$sequences = $em->getRepository('SequenceBundle:Sequence')->findBy(array('createur' => $user));
    	$enfant = $em->getRepository('UserBundle:User')->findOneBy(array('username' => $username, 'name' => $name));

    	$contrat = new Contrat;

    	$form = $this->get('form.factory')->createBuilder('form', $contrat)
    	  ->add('libelle',  'text', array('label' => 'Titre','required' => true, 'attr' => array('class' => 'form-required')))
	      ->add('description',  'textarea', array('label' => 'Description','required' => true, 'attr' => array('class' => 'form-required')))
	      ->add('date',  'datetime', array('date_format' => 'yyyy-MM-dd', 'widget' => 'single_text', 'label' => 'Date','required' => true, 'attr' => array('id' => 'champ_date',  'class' => 'form-required')))
	      ->add('recompense',     EntityType::class, array('class' => 'SequenceBundle:Recompense', 'label' => 'Sélectionnez une récompense','required' => true, 'attr' => array('class' => 'form-required')))
	      ->add('sequence',     EntityType::class, array('class' => 'SequenceBundle:Sequence', 'choices' => $sequences, 'label' => 'Sélectionnez une séquence','required' => true, 'attr' => array('class' => 'form-required')))
        ->add('code',  'integer', array('label' => 'Code de validation','required' => true, 'attr' => array('class' => 'form-required')))
	      ->add('save', 'submit')
	      ->getForm()
	      ;

	    $form->handleRequest($request);

        if($form->isValid()){
        	if($contrat->getDate()->format('Y-m-d H:i') < date('Y-m-d H:i')){
        		$contrat->setEnCours(true);
        	}
        	else
        		$contrat->setEnCours(false);

        	$contrat->setFini(false);
        	$contrat->setEducateur($user);
        	$contrat->setEnfant($enfant);

        	$em->persist($contrat);
        	$em->flush();

        }

        return $this->render('EducateurBundle:Default:creerContrat.html.twig', array('form' => $form->createView(), 'user' => $user, 'enfant' => $enfant, 'sequences' => $sequences));
    }

    public function supprimerContratAction(Request $request, $id)
    {
    	if($this->container->get('security.authorization_checker')->isGranted('ROLE_ENFANT'))
            return $this->render('EducateurBundle:Default:accessDenied.html.twig');

    	$em = $this->getDoctrine()->getManager();
    	$user = $this->getUser();

    	$contrat = $em->getRepository('SequenceBundle:Contrat')->find($id);

    	if(!$contrat)
    		return $this->forward('EducateurBundle:Default:index');
    	if($contrat->getEducateur() != $user)
    		return $this->render('EducateurBundle:Default:accessDenied.html.twig');

    	$em->remove($contrat);
    	$em->flush();

    	return $this->forward('EducateurBundle:Default:index');

    }

    public function creerEtapeAction(Request $request){
        if($this->container->get('security.authorization_checker')->isGranted('ROLE_ENFANT'))
            return $this->render('EducateurBundle:Default:accessDenied.html.twig');

        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $form = $this->get('form.factory')->createBuilder('form')
          ->add('libelle',  'text', array('label' => 'Titre','required' => true, 'attr' => array('class' => 'form-required')))
          ->add('image',  FileType::class, array('label' => 'Image','required' => true, 'attr' => array('class' => 'form-required')))
          ->add('save', 'submit')
          ->getForm()
          ;

        $form->handleRequest($request);

        if($form->isValid()){
            $etape = new Etape;
            $etape->setLibelle($form['libelle']->getData());
            $file = $form['image']->getData();
            $fileName = md5(uniqid()).'.'.$file->getClientOriginalName();
            $file->move(
                $this->container->getParameter('imageEtapes_directory'),
                $fileName
            );
            $etape->setImage($fileName);
            $etape->setPosition(99);
            $etape->setCreateur($user);

            $em->persist($etape);
            $em->flush();
        }

        return $this->render('EducateurBundle:Default:creerEtape.html.twig', array('form' => $form->createView(), 'user' => $user));
    }

}
