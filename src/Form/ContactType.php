<?php

namespace App\Form;

use App\Contact\ContactRequest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @extends AbstractType<ContactRequest>
 */
final class ContactType extends AbstractType
{
    /**
     * Champ piège : invisible pour les visiteurs, rempli par les robots.
     */
    public const HONEYPOT = 'website';

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'attr' => ['autocomplete' => 'name'],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => ['autocomplete' => 'email'],
            ])
            ->add('service', ChoiceType::class, [
                'label' => 'Prestation',
                'choices' => ContactRequest::SERVICES,
                'placeholder' => 'Choisissez…',
            ])
            ->add('date', DateType::class, [
                'label' => 'Date souhaitée',
                'required' => false,
                'input' => 'datetime_immutable',
                'widget' => 'single_text',
                'help' => 'Facultatif',
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Votre projet',
                'attr' => ['rows' => 6],
            ])
            ->add(self::HONEYPOT, TextType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'Ne pas remplir ce champ',
                'row_attr' => ['class' => 'form-trap', 'aria-hidden' => 'true'],
                'attr' => ['tabindex' => '-1', 'autocomplete' => 'off'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ContactRequest::class,
        ]);
    }
}
