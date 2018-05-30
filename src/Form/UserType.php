<?php
/**
 * Created by PhpStorm.
 * User: antoi
 * Date: 25/05/2018
 * Time: 21:38
 */

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("firstName", TextType::class)
            ->add("name", TextType::class)
            ->add("email", EmailType::class)
            ->add("changePassword", CheckboxType::class, array(
                "label" => "Change password",
                "mapped" => false,
                "required" => false,
                "attr" => array(
                    "data-toggle" => "collapse",
                    "data-target" => "#collapsePassword",
                    "aria-expanded" => "false",
                    "aria-controls" => "collapsePassword"
                ),
                "label_attr" => array(
                    "class" => "checkbox-custom"
                )
            ))->add("plainPassword", RepeatedType::class, array(
                "type" => PasswordType::class,
                "invalid_message" => "The password fields must match.",
                "required" => false,
                "first_options" => array(
                    "label" => "New password"),
                "second_options" => array(
                    "label" => "Repeat password")
            ))->add("updateData", SubmitType::class, array(
                "attr" => array(
                    "class" => "btn btn-success"
                )
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            "data_class" => User::class
        ));
    }
}