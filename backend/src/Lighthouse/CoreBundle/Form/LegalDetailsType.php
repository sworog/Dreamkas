<?php

namespace Lighthouse\CoreBundle\Form;

use Lighthouse\CoreBundle\Document\LegalDetails\EntrepreneurLegalDetails;
use Lighthouse\CoreBundle\Document\LegalDetails\LegalEntityLegalDetails;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\NotBlank;

class LegalDetailsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fullName', 'text')
            ->add('legalAddress', 'text')
            ->add('okpo', 'text')
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

        $builder->addEventListener(FormEvents::PRE_SUBMIT, array($this, 'setTypeForm'));
    }

    /**
     * @param FormEvent $event
     */
    public function setTypeForm(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();
        $type = (isset($data['type'])) ? $data['type'] : null;

        switch ($type) {
            case LegalEntityLegalDetails::TYPE:
                $this->buildLegalEntityForm($form);
                break;
            case EntrepreneurLegalDetails::TYPE:
                $this->buildEntrepreneurForm($form);
                break;

        }
    }

    /**
     * @param FormInterface $form
     */
    public function buildLegalEntityForm(FormInterface $form)
    {
        $form
            ->add('inn', 'text')
            ->add('kpp', 'text')
            ->add('ogrn', 'text')
        ;
    }

    /**
     * @param FormInterface $form
     */
    public function buildEntrepreneurForm(FormInterface $form)
    {
        $form
            ->add('inn', 'text')
            ->add('orgnip', 'text')
            ->add('certificateNumber', 'text')
            ->add('certificateDate', 'text')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'csrf_protection' => false,
                'cascade_validation' => true
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
