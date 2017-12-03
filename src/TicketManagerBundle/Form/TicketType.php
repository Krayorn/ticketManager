<?php

namespace TicketManagerBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TicketType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('content', null, array('attr'=> array(
                'class'=>'materialize-textarea'
            )))
            ;
        if ($options['isAdmin']){
            $builder->add('assignedAt', EntityType::class, array(
                'class' => 'UserBundle:User',
                'label' => false,
                'placeholder' => 'select a user',
                'required' => false,
            ));
        }

        //more logical behavior in case user may acquire permission to edit ticket.

        if ($options['isEdition']){
            $builder->add('author', TextType::class, array(
                'disabled'  => true,
                'attr'=> array('class'=>'input-field')
            ));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TicketManagerBundle\Entity\Ticket',
            'isAdmin'    => false,
            'isEdition'  => false,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ticketmanagerbundle_ticket';
    }


}
