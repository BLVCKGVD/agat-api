<?php


namespace App\Form;
use App\Entity\Aircraft;
use App\Entity\EmailSubsription;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

class EmailForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('subject', TextType::class,[
                'required' => false,
                'mapped'=>false,
                'label'=>'Введите тему письма',
                'attr' => [
                    'class'=>'form-control'
                ],
            ])
            ->add('text', CKEditorType::class,[
                'required' => false,
                'mapped'=>false,
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

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EmailSubsription::class,
            'allow_extra_fields' => true
        ]);
    }

}