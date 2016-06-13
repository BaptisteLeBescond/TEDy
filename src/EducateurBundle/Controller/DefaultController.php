<?php

namespace EducateurBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use UserBundle\Entity\User;
use SequenceBundle\Entity\Sequence;
// use EducateurBundle\Form\EnfantType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

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
    	$etapes = $em->getRepository('SequenceBundle:Etape')->findAll();

    	$form = $this->get('form.factory')->createBuilder('form', $sequence)
		  ->add('libelle',  'text', array('label' => 'Titre','required' => true, 'attr' => array('class' => 'form-required')))
	      ->add('description',     'textarea', array('label' => 'Description','required' => true, 'attr' => array('class' => 'form-required')))
	      // ->add('musique',   'integer', array('label' => 'Âge','required' => false, 'attr' => array('class' => 'form-required')))
	      ->add('save',      'submit')
	      ->getForm()
	      ;

        $form->handleRequest($request);

        return $this->render('EducateurBundle:Default:creerSequence.html.twig', array('form' => $form->createView(), 'user' => $user, 'etapes' => $etapes));
    }
}
