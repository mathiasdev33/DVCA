<?php


namespace App\Form;


use App\Entity\Article;
use App\Entity\Categorie;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ArticleType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
       $builder
           ->add('title')
           ->add('content')
           ->add('date', DateType::class,[
               'widget' => 'single_text',
               "placeholder"=>"jj/mm/aaaa"
           ])
           ->add('published')

           ->add('categorie',EntityType::class,[
               'class'=>Categorie::class,
               'choice_label'=>'title'
           ])

           ->add('image',FileType::class,[
               'mapped'=>false,
               'constraints' => [
                   new File([
                       'mimeTypes' => [
                           'image/jpeg', 'image/jpg', 'image/png'
                       ],
                       'mimeTypesMessage' => 'format autorisés (JPG, JPEG, PNG)'
                   ])
               ]
           ])
           ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}