<?php


namespace App\Form;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

class ContentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('text', CKEditorType::class,[
                'required' => false,
                'label'=>'Введите текст',
                'attr' => [
                ],
            ])
            ->add('submit', SubmitType::class,
                [
                    'label' => 'Сохранить',
                    'attr'=>[
                        'class' =>'form-control btn-success',
                    ]
                ]
            );
    }

}