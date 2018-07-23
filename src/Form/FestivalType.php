<?php
/**
 * Created by PhpStorm.
 * User: antoi
 * Date: 23/07/2018
 * Time: 21:50
 */

namespace App\Form;


use App\Entity\Festival;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FestivalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("registrationStartDate", DateType::class, array(
                "years" => range(date("Y"), date("Y") + 5),
                "format" => "ddMMMMyyyy"
            ))
            ->add("registrationEndDate", DateType::class, array(
                "years" => range(date("Y"), date("Y") + 5),
                "format" => "ddMMMMyyyy"
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            "data_class" => Festival::class
        ));
    }
}