<?php

namespace Lighthouse\CoreBundle\Form;

use Lighthouse\CoreBundle\Document\LegalDetails\EntrepreneurLegalDetails;
use Lighthouse\CoreBundle\Document\LegalDetails\LegalEntityLegalDetails;
use Lighthouse\CoreBundle\Document\Organization\Organizationable;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\NotBlank;

class LegalDetailsType extends DocumentType
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
     * @return string
     */
    protected function getDataClass()
    {
        return null;
    }

    /**
     * @param FormEvent $event
     */
    public static function setTypeForm(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();

        if (!isset($data['legalDetails']['type'])) {
            return;
        }

        /* @var Organizationable $organization */
        $organization = $form->getData();

        switch ($data['legalDetails']['type']) {
            case EntrepreneurLegalDetails::TYPE:
                if (!$organization->getLegalDetails() instanceof EntrepreneurLegalDetails) {
                    $organization->setLegalDetails(new EntrepreneurLegalDetails());
                }
                $form->add(
                    'legalDetails',
                    new LegalDetailsType(),
                    array(
                        'data_class' => EntrepreneurLegalDetails::getClassName()
                    )
                );
                break;

            case LegalEntityLegalDetails::TYPE:
            default:
                if (!$organization->getLegalDetails() instanceof LegalEntityLegalDetails) {
                    $organization->setLegalDetails(new LegalEntityLegalDetails());
                }
                $form->add(
                    'legalDetails',
                    new LegalDetailsType(),
                    array(
                        'data_class' => LegalEntityLegalDetails::getClassName()
                    )
                );
                break;
        }
    }
}
