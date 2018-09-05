<?php
/**
 * Created by PhpStorm.
 * User: antoi
 * Date: 21/05/2018
 * Time: 19:49
 */

namespace App\Form;


use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("message", TextType::class, array(
                "attr" => array(
                    "placeholder" => "Message"
                )
            ))
            ->add("submit", SubmitType::class, array(
                "attr" => array(
                    "class" => "btn btn-primary"
                )
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            "data_class" => Comment::class
        ));
    }
}