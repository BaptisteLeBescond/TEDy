<?php

namespace EducateurBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class SequenceType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('libelle',  'text', array('label' => 'Titre','required' => true, 'attr' => array('class' => 'form-required')))
      ->add('description',     'textarea', array('label' => 'Description','required' => true, 'attr' => array('class' => 'form-required')))
      // ->add('musique',   'integer', array('label' => 'Âge','required' => false, 'attr' => array('class' => 'form-required')))
      // ->add('etapes',  EntityType::class, array('class' => 'SequenceBundle:Etape', 'expanded' => true, 'multiple' => true, 'required' => false, 'attr' => array('class' => 'form-required')))
      ->add('etapes',  CollectionType::class, array('entry_type' => EtapeType::class))
      ->add('save', 'submit')
    ;
  }

  public function setDefaultOptions(OptionsResolverInterface $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => 'SequenceBundle\Entity\Sequence'
    ));
  }
}