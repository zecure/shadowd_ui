<?php

/**
 * Shadow Daemon -- Web Application Firewall
 *
 *   Copyright (C) 2014-2017 Hendrik Buchwald <hb@zecure.org>
 *
 * This file is part of Shadow Daemon. Shadow Daemon is free software: you can
 * redistribute it and/or modify it under the terms of the GNU General Public
 * License as published by the Free Software Foundation, version 2.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more
 * details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Swd\AnalyzerBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Braincrafted\Bundle\BootstrapBundle\Form\Type\FormActionsType;

class SettingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pageLimit', IntegerType::class, array('label' => 'Entries per page'))
            ->add('sortOrder', ChoiceType::class, array('choices' => array('0' => 'Descendent', '1' => 'Ascendent'), 'label' => 'Sort order'))
            ->add('openFilter', CheckboxType::class, array('required' => false, 'label' => 'Automatically open filter mask on new filter'))
            ->add('theme', ChoiceType::class, array('choices' => array(
                '0' => 'Plain',
                'cerulean' => 'Cerulean',
                'cosmo' => 'Cosmo',
                'cyborg' => 'Cyborg',
                'darkly' => 'Darkly',
                'flatly' => 'Flatly',
                'journal' => 'Journal',
                'lumen' => 'Lumen',
                'paper' => 'Paper',
                'readable' => 'Readable',
                'sandstone' => 'Sandstone',
                'simplex' => 'Simplex',
                'slate' => 'Slate',
                'spacelab' => 'Spacelab',
                'superhero' => 'Superhero',
                'united' => 'United',
                'yeti' => 'Yeti',
            )))
            ->add('locale', ChoiceType::class, array('choices' => array(
                'de' => 'Deutsch',
                'en' => 'English',
                'nl' => 'Nederlands',
                'zh_CN' => '中文（简体）',
            )))
            ->add('oldPassword', PasswordType::class, array('required' => false, 'label' => 'Old password'))
            ->add('newPassword', RepeatedType::class, array('required' => false, 'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'first_options'  => array('label' => 'New password'),
                'second_options' => array('label' => 'Repeat password'),
            ))
            ->add('actions', FormActionsType::class, array('buttons' => array('save' => array('type' => SubmitType::class), 'reset' => array('type' => ResetType::class))));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'validation_groups' =>
                function($form)
                {
                    $settings = $form->getData();

                    if (!$settings->getOldPassword())
                    {
                        return array('Default');
                    }
                    else
                    {
                        return array('Default', 'change_password');
                    }
                }
        ));
    }

    public function getName()
    {
        return 'setting_defaults';
    }
}
