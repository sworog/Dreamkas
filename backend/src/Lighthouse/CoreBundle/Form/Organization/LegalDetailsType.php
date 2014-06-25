<?php

namespace Lighthouse\CoreBundle\Form\Organization;

use Lighthouse\CoreBundle\Document\LegalDetails\EntrepreneurLegalDetails;
use Lighthouse\CoreBundle\Document\LegalDetails\LegalDetails;
use Lighthouse\CoreBundle\Document\LegalDetails\LegalEntityLegalDetails;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\NotBlank;

class LegalDetailsType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fullName', 'text')
            ->add('legalAddress', 'text')
            ->add(
                'type',
                'text',
                array(
                    'mapped' => false,
                    'constraints' => array(
                        new NotBlank(),
                        new Choice(
                            array(
                                'choices' => array(
                                    EntrepreneurLegalDetails::TYPE,
                                    LegalEntityLegalDetails::TYPE,
                                )
                            )
                        )
                    ),
                )
            );
        ;

        switch ($options['data_class']) {
            case LegalEntityLegalDetails::getClassName():
                $builder
                    ->add('inn', 'text')
                    ->add('kpp', 'text')
                    ->add('ogrn', 'text')
                    ->add('okpo', 'text')
                ;
                break;

            case EntrepreneurLegalDetails::getClassName():
                $builder
                    ->add('inn', 'text')
                    ->add('ogrnip', 'text')
                    ->add('okpo', 'text')
                    ->add('certificateNumber', 'text')
                    ->add(
                        'certificateDate',
                        'date',
                        array(
                            'format' => DateType::HTML5_FORMAT,
                            'widget' => 'single_text'
                        )
                    )
                ;
        }
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'csrf_protection' => false
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return '';
    }
}
