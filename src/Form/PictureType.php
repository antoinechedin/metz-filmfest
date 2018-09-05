<?php
/**
 * Created by PhpStorm.
 * User: antoi
 * Date: 19/07/2018
 * Time: 15:59
 */

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class PictureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("picture", FileType::class, array(
                "label" => "Choose a Picture",
                "required" => false,
                "constraints" => array(
                    new Image()
                )
            ))
            ->add("save", SubmitType::class, array(
                "label" => " ",
                "attr" => array(
                    "class" => "btn btn-success"
                )
            ))
            ->add("removePicture", SubmitType::class, array(
                "label" => " ",
                "attr" => array(
                    "class" => "btn btn-danger"
                )
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            "data_class" => null
        ));
    }
}