<?php

namespace EducateurBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use UserBundle\Entity\User;
use SequenceBundle\Entity\Sequence;
use SequenceBundle\Entity\Etape;
// use EducateurBundle\Form\EnfantType;
use EducateurBundle\Form\SequenceType;
use EducateurBundle\Form\EtapeType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class DefaultController extends Controller
{
    public function indexAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	$user = $this->getUser();
      $enfants = $user->getEnfant();

        return $this->render('EducateurBundle:Default:index.html.twig', array('user' => $user, 'enfants' => $enfants));
    }

    public function ajoutEnfantAction(Request $request)
    {
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

    public function creerSequenceAction(Request $request)
    {
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
        	$em->persist($sequence);

        	$em->flush();
		}
		else
			var_dump('ERREUR !');

        return $this->render('EducateurBundle:Default:creerSequence.html.twig', array('form' => $form->createView(), 'user' => $user, 'etapes' => $etapes));
    }

}
