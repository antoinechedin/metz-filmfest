<?php
/**
 * Created by PhpStorm.
 * User: antoi
 * Date: 30/04/2018
 * Time: 17:02
 */

namespace App\Form;


use App\Entity\PersonalInformation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PersonalInformationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add("birthDate", DateType::class, array(
            "years" => range(date("Y") - 31, date("Y")),
            "format" => "ddMMMMyyyy"
        ));
        $builder->add("email", EmailType::class, array(
            "attr" => array(
                "placeholder" => "Email"
            )
        ));
        $builder->add("studyYear", TextType::class, array(
            "attr" => array(
                "placeholder" => "Study year"
            )
        ));
        $builder->add("studyPlace", TextType::class, array(
            "attr" => array(
                "placeholder" => "Study place"
            )
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            "data_class" => PersonalInformation::class
        ));
    }

}