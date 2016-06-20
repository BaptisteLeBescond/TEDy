<?php

namespace EducateurBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EnfantType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('username',  'text')
      ->add('name',     'text')
      ->add('password',    'password')
      ->add('age',   'integer')
      ->add('telephone', 'integer')
      ->add('adresse_postale', 'text')
      ->add('code_postale', 'integer')
      ->add('ville', 'text')
      ->add('photo', 'file')
      ->add('save',      'submit')
    ;
  }

  public function setDefaultOptions(OptionsResolverInterface $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => 'UserBundle\Entity\User'
    ));
  }

  public function getName()
  {
    return 'userbundle_user';
  }
}