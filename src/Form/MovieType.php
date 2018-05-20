<?php
/**
 * Created by PhpStorm.
 * User: antoi
 * Date: 02/05/2018
 * Time: 11:53
 */

namespace App\Form;


use App\Entity\Category;
use App\Entity\Movie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;

class MovieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("director", DirectorType::class)
            ->add("title", TextType::class, array(
                "attr" => array(
                    "placeholder" => "Title"
                )
            ))
            ->add("completionDate", DateType::class, array(
                "years" => range(date("Y") - 2, date("Y")),
                "format" => "ddMMMMyyyy",
            ))
            ->add("duration", IntegerType::class, array(
                "label" => "Duration (in minutes)",
                "attr" => array(
                    "placeholder" => "Duration"
                )
            ))
            ->add("categories", EntityType::class, array(
                "class" => Category::class,
                "choice_label" => "name",
                "multiple" => true,
                "expanded" => true,
                "required" => true
            ))
            ->add("synopsis", TextareaType::class, array(
                "required" => false
            ))
            ->add("nominateActor", CheckboxType::class, array(
                "label" => "I would like to nominate an actor for the acting award",
                "mapped" => false,
                "attr" => array(
                    "data-toggle" => "collapse",
                    "data-target" => "#collapseActor",
                    "aria-expanded" => "false",
                    "aria-controls" => "collapseActor"
                )
            ))
            ->add("actor", ActorType::class, array(
                "required" => false
            ))
            ->add("link", TextType::class, array(
                "label" => "Link to your short",
                "attr" => array(
                    "placeholder" => "Link to your short"
                )
            ))
            ->add("additionalInformation", TextareaType::class, array(
                "required" => false,
                "attr" => array(
                    "placeholder" => "Optional"
                )
            ))
            ->add("agreeTerms", CheckboxType::class, array(
                "label" => "I have read, understand and agree to the terms and conditions of the festival's rules",
                "required" => true,
                "constraints" => new isTrue(),
                "mapped" => false
            ))
            ->add("submit", SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            "data_class" => Movie::class
        ));
    }

}