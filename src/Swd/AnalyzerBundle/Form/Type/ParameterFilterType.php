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

namespace Swd\AnalyzerBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Braincrafted\Bundle\BootstrapBundle\Form\Type\BootstrapCollectionType;
use Braincrafted\Bundle\BootstrapBundle\Form\Type\FormActionsType;

class ParameterFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setMethod('GET')
            ->setAction('#')
            ->add('includeParameterIds', BootstrapCollectionType::class, array('allow_add' => true, 'allow_delete' => true, 'label' => 'Parameter ID'))
            ->add('includeProfileIds', BootstrapCollectionType::class, array('allow_add' => true, 'allow_delete' => true, 'label' => 'Profile ID'))
            ->add('includeRequestIds', BootstrapCollectionType::class, array('allow_add' => true, 'allow_delete' => true, 'label' => 'Request ID'))
            ->add('includeCallers', BootstrapCollectionType::class, array('allow_add' => true, 'allow_delete' => true, 'label' => 'Caller'))
            ->add('includeClientIPs', BootstrapCollectionType::class, array('allow_add' => true, 'allow_delete' => true, 'label' => 'Client IP'))
            ->add('includeDateStart', DateTimeType::class, array('required' => false, 'label' => 'From', 'placeholder' => array('year' => 'Year', 'month' => 'Month', 'day' => 'Day', 'hour' => 'Hour', 'minute' => 'Minute')))
            ->add('includeDateEnd', DateTimeType::class, array('required' => false, 'label' => 'To', 'placeholder' => array('year' => 'Year', 'month' => 'Month', 'day' => 'Day', 'hour' => 'Hour', 'minute' => 'Minute')))
            ->add('includePaths', BootstrapCollectionType::class, array('allow_add' => true, 'allow_delete' => true, 'label' => 'Path'))
            ->add('includeValues', BootstrapCollectionType::class, array('allow_add' => true, 'allow_delete' => true, 'label' => 'Value'))
            ->add('includeThreat', CheckboxType::class, array('required' => false, 'label' => 'Is threat'))
            ->add('includeNoWhitelistRule', CheckboxType::class, array('required' => false, 'label' => 'Has no whitelist rule'))
            ->add('includeBrokenWhitelistRule', CheckboxType::class, array('required' => false, 'label' => 'Has broken whitelist rule'))
            ->add('includeNoIntegrityRule', CheckboxType::class, array('required' => false, 'label' => 'Has no integrity rule'))
            ->add('includeBrokenIntegrityRule', CheckboxType::class, array('required' => false, 'label' => 'Has broken integrity rule'))
            ->add('includeCriticalImpact', CheckboxType::class, array('required' => false, 'label' => 'Has critical impact'))
            ->add('excludeParameterIds', BootstrapCollectionType::class, array('allow_add' => true, 'allow_delete' => true, 'label' => 'Parameter ID'))
            ->add('excludeProfileIds', BootstrapCollectionType::class, array('allow_add' => true, 'allow_delete' => true, 'label' => 'Profile ID'))
            ->add('excludeRequestIds', BootstrapCollectionType::class, array('allow_add' => true, 'allow_delete' => true, 'label' => 'Request ID'))
            ->add('excludeCallers', BootstrapCollectionType::class, array('allow_add' => true, 'allow_delete' => true, 'label' => 'Caller'))
            ->add('excludeClientIPs', BootstrapCollectionType::class, array('allow_add' => true, 'allow_delete' => true, 'label' => 'Client IP'))
            ->add('excludeDateStart', DateTimeType::class, array('required' => false, 'label' => 'From', 'placeholder' => array('year' => 'Year', 'month' => 'Month', 'day' => 'Day', 'hour' => 'Hour', 'minute' => 'Minute')))
            ->add('excludeDateEnd', DateTimeType::class, array('required' => false, 'label' => 'To', 'placeholder' => array('year' => 'Year', 'month' => 'Month', 'day' => 'Day', 'hour' => 'Hour', 'minute' => 'Minute')))
            ->add('excludePaths', BootstrapCollectionType::class, array('allow_add' => true, 'allow_delete' => true, 'label' => 'Path'))
            ->add('excludeValues', BootstrapCollectionType::class, array('allow_add' => true, 'allow_delete' => true, 'label' => 'Value'))
            ->add('excludeThreat', CheckboxType::class, array('required' => false, 'label' => 'Is threat'))
            ->add('excludeNoWhitelistRule', CheckboxType::class, array('required' => false, 'label' => 'Has no whitelist rule'))
            ->add('excludeBrokenWhitelistRule', CheckboxType::class, array('required' => false, 'label' => 'Has broken whitelist rule'))
            ->add('excludeNoIntegrityRule', CheckboxType::class, array('required' => false, 'label' => 'Has no integrity rule'))
            ->add('excludeBrokenIntegrityRule', CheckboxType::class, array('required' => false, 'label' => 'Has broken integrity rule'))
            ->add('excludeCriticalImpact', CheckboxType::class, array('required' => false, 'label' => 'Has critical impact'))
            ->add('actions', FormActionsType::class, array('buttons' => array('filter' => array('type' => SubmitType::class), 'reset' => array('type' => ResetType::class))));
    }

    public function getName()
    {
        return 'parameter_filter';
    }

    /**
     * CSRF protection is useless for searching and makes it impossible
     * to send urls with filters to other persons, so better disable it.
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'csrf_protection' => false
            )
        );
    }
}
