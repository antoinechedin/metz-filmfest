<?php
/**
 * Created by PhpStorm.
 * User: antoi
 * Date: 30/04/2018
 * Time: 16:57
 */

namespace App\Form;


use App\Entity\Actor;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("firstName", TextType::class, array(
                "attr" => array(
                    "placeholder" => "First name"
                )
            ))
            ->add("name", TextType::class, array(
                "attr" => array(
                    "placeholder" => "Name"
                )
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            "data_class" => Actor::class
        ));
    }
}