<?php

namespace Bkstg\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Bkstg\CoreBundle\Entity\Position;

class PositionType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('designation', 'choice', array(
                'choices' => array(
                    Position::DESIGNATION_CAST => Position::DESIGNATION_CAST,
                    Position::DESIGNATION_CREW => Position::DESIGNATION_CREW,
                ),
            ))
            ->add('role');
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Bkstg\CoreBundle\Entity\Position'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'bkstgcorebundle_position';
    }
}
