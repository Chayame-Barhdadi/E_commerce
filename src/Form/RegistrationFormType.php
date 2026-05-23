<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

// RegistrationFormType : formulaire d'inscription utilisé à la fois pour l'inscription et la connexion
// Il hérite de AbstractType qui fournit les méthodes de base pour construire un formulaire Symfony
class RegistrationFormType extends AbstractType
{
    // Construit les champs du formulaire d'inscription
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class)    // Champ texte pour le prénom
            ->add('lastName', TextType::class)     // Champ texte pour le nom de famille
            ->add('email', EmailType::class)       // Champ email avec validation de format automatique
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false, // Non mappé à l'entité : il sera géré manuellement dans le contrôleur
                'attr' => ['autocomplete' => 'new-password'], // Désactive l'autocomplétion du navigateur
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password', // Validation : champ obligatoire
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters', // Min 6 caractères
                        'max' => 4096, // Limite max pour éviter les attaques par mots de passe très longs
                    ]),
                ],
            ])
        ;
    }

    // Configure les options du formulaire, notamment la classe d'entité associée
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class, // Lie le formulaire à l'entité User pour le mappage automatique
        ]);
    }
}
