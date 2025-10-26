<?php

namespace App\Form;

use App\Entity\Author; // CORRIGÉ : Doit être Author, pas AuthorTp
use App\Entity\Book;
use Symfony\Bridge\Doctrine\Form\Type\EntityType; // Import non utilisé, mais gardé
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('publicationDate')
            ->add('category', ChoiceType::class, [
                'choices' => [
                    'Science-Fiction' => 'Science-Fiction',
                    'Mystery' => 'Mystery',
                    'Autobiography' => 'Autobiography',
                    'Romance' => 'Romance', // Ajout de Romance (utilisé dans le contrôleur)
                ],
                'placeholder' => 'Choisir une catégorie',
            ])
            // CORRIGÉ : Doit être 'author' (minuscule) pour correspondre à la propriété de l'entité Book
            // Symfony va automatiquement utiliser EntityType ici grâce à la relation
            ->add('author'); 
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
