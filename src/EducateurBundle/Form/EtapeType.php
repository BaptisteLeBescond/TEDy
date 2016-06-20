<?php

namespace EducateurBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EtapeType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('libelle',  'text')
      ->add('position',  'integer')
    ;
  }

  public function setDefaultOptions(OptionsResolverInterface $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => 'SequenceBundle\Entity\Etape'
    ));
  }
}