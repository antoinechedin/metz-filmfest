<?php
/**
 * Created by PhpStorm.
 * User: antoi
 * Date: 30/04/2018
 * Time: 16:43
 */

namespace App\Form;


use App\Entity\Director;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DirectorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add("firstName", TextType::class, array(
            "attr" => array(
                "placeholder" => "First name"
            )
        ));
        $builder->add("name", TextType::class, array(
            "attr" => array(
                "placeholder" => "Name"
            )
        ));
        $builder->add("personalInformation", PersonalInformationType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            "data_class" => Director::class
        ));
    }
}