<?php

/**
 * Shadow Daemon -- Web Application Firewall
 *
 *   Copyright (C) 2014-2021 Hendrik Buchwald <hb@zecure.org>
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

namespace App\Form\Type;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Braincrafted\Bundle\BootstrapBundle\Form\Type\BootstrapCollectionType;
use Braincrafted\Bundle\BootstrapBundle\Form\Type\FormActionsType;

class GeneratorSettingsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('profile', EntityType::class, ['property' => 'getIdAndName', 'class' => 'SwdAnalyzerBundle:Profile',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('v')->orderBy('v.id', 'ASC');
                }
            ])
            ->add('predefined', ChoiceType::class, ['choices' => ['1' => 'Low security', '2' => 'Moderate security', '3' => 'High security', '4' => 'Custom']])
            ->add('status', ChoiceType::class, ['choices' => ['1' => 'Activated', '2' => 'Deactivated', '3' => 'Pending']])
            ->add('enableWhitelist', CheckboxType::class, ['required' => false, 'label' => 'Generate whitelist rules'])
            ->add('enableBlacklist', CheckboxType::class, ['required' => false, 'label' => 'Generate blacklist rules'])
            ->add('enableIntegrity', CheckboxType::class, ['required' => false, 'label' => 'Generate integrity rules'])
            ->add('minUniqueVisitors', IntegerType::class, ['label' => 'Min. unique visitors'])
            ->add('minFilterDominance', IntegerType::class, ['label' => 'Min. filter dominance'])
            ->add('maxLengthVariance', IntegerType::class, ['label' => 'Max. length variance'])
            ->add('minThresholdDominance', IntegerType::class, ['label' => 'Min. threshold dominance'])
            ->add('unifyWhitelistArrays', CheckboxType::class, ['required' => false, 'label' => 'Unify arrays'])
            ->add('unifyWhitelistCallers', CheckboxType::class, ['required' => false, 'label' => 'Unify callers'])
            ->add('unifyBlacklistArrays', CheckboxType::class, ['required' => false, 'label' => 'Unify arrays'])
            ->add('unifyBlacklistCallers', CheckboxType::class, ['required' => false, 'label' => 'Unify callers'])
            ->add('includeCallers', BootstrapCollectionType::class, ['allow_add' => true, 'allow_delete' => true, 'label' => 'Caller'])
            ->add('includePaths', BootstrapCollectionType::class, ['allow_add' => true, 'allow_delete' => true, 'label' => 'Path'])
            ->add('excludeCallers', BootstrapCollectionType::class, ['allow_add' => true, 'allow_delete' => true, 'label' => 'Caller'])
            ->add('excludePaths', BootstrapCollectionType::class, ['allow_add' => true, 'allow_delete' => true, 'label' => 'Path'])
            ->add('actions', FormActionsType::class, ['buttons' => ['generate' => ['type' => SubmitType::class], 'reset' => ['type' => ResetType::class]]]);
    }

    public function getName()
    {
        return 'generator_settings';
    }
}
