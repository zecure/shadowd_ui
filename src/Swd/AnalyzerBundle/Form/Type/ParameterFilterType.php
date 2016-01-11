<?php

/**
 * Shadow Daemon -- Web Application Firewall
 *
 *   Copyright (C) 2014-2016 Hendrik Buchwald <hb@zecure.org>
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
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ParameterFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setMethod('GET')
            ->setAction('#')
            ->add('includeParameterIds', 'bootstrap_collection', array('allow_add' => true, 'allow_delete' => true, 'label' => 'Parameter ID'))
            ->add('includeProfileIds', 'bootstrap_collection', array('allow_add' => true, 'allow_delete' => true, 'label' => 'Profile ID'))
            ->add('includeRequestIds', 'bootstrap_collection', array('allow_add' => true, 'allow_delete' => true, 'label' => 'Request ID'))
            ->add('includeCallers', 'bootstrap_collection', array('allow_add' => true, 'allow_delete' => true, 'label' => 'Caller'))
            ->add('includeClientIPs', 'bootstrap_collection', array('allow_add' => true, 'allow_delete' => true, 'label' => 'Client IP'))
            ->add('includeDateStart', 'datetime', array('required' => false, 'label' => 'From', 'empty_value' => array('year' => 'Year', 'month' => 'Month', 'day' => 'Day', 'hour' => 'Hour', 'minute' => 'Minute')))
            ->add('includeDateEnd', 'datetime', array('required' => false, 'label' => 'To', 'empty_value' => array('year' => 'Year', 'month' => 'Month', 'day' => 'Day', 'hour' => 'Hour', 'minute' => 'Minute')))
            ->add('includePaths', 'bootstrap_collection', array('allow_add' => true, 'allow_delete' => true, 'label' => 'Path'))
            ->add('includeValues', 'bootstrap_collection', array('allow_add' => true, 'allow_delete' => true, 'label' => 'Value'))
            ->add('includeThreat', 'checkbox', array('required' => false, 'label' => 'Is threat'))
            ->add('includeNoWhitelistRule', 'checkbox', array('required' => false, 'label' => 'Has no whitelist rule'))
            ->add('includeBrokenWhitelistRule', 'checkbox', array('required' => false, 'label' => 'Has broken whitelist rule'))
            ->add('includeNoIntegrityRule', 'checkbox', array('required' => false, 'label' => 'Has no integrity rule'))
            ->add('includeBrokenIntegrityRule', 'checkbox', array('required' => false, 'label' => 'Has broken integrity rule'))
            ->add('includeCriticalImpact', 'checkbox', array('required' => false, 'label' => 'Has critical impact'))
            ->add('excludeParameterIds', 'bootstrap_collection', array('allow_add' => true, 'allow_delete' => true, 'label' => 'Parameter ID'))
            ->add('excludeProfileIds', 'bootstrap_collection', array('allow_add' => true, 'allow_delete' => true, 'label' => 'Profile ID'))
            ->add('excludeRequestIds', 'bootstrap_collection', array('allow_add' => true, 'allow_delete' => true, 'label' => 'Request ID'))
            ->add('excludeCallers', 'bootstrap_collection', array('allow_add' => true, 'allow_delete' => true, 'label' => 'Caller'))
            ->add('excludeClientIPs', 'bootstrap_collection', array('allow_add' => true, 'allow_delete' => true, 'label' => 'Client IP'))
            ->add('excludeDateStart', 'datetime', array('required' => false, 'label' => 'From', 'empty_value' => array('year' => 'Year', 'month' => 'Month', 'day' => 'Day', 'hour' => 'Hour', 'minute' => 'Minute')))
            ->add('excludeDateEnd', 'datetime', array('required' => false, 'label' => 'To', 'empty_value' => array('year' => 'Year', 'month' => 'Month', 'day' => 'Day', 'hour' => 'Hour', 'minute' => 'Minute')))
            ->add('excludePaths', 'bootstrap_collection', array('allow_add' => true, 'allow_delete' => true, 'label' => 'Path'))
            ->add('excludeValues', 'bootstrap_collection', array('allow_add' => true, 'allow_delete' => true, 'label' => 'Value'))
            ->add('excludeThreat', 'checkbox', array('required' => false, 'label' => 'Is threat'))
            ->add('excludeNoWhitelistRule', 'checkbox', array('required' => false, 'label' => 'Has no whitelist rule'))
            ->add('excludeBrokenWhitelistRule', 'checkbox', array('required' => false, 'label' => 'Has broken whitelist rule'))
            ->add('excludeNoIntegrityRule', 'checkbox', array('required' => false, 'label' => 'Has no integrity rule'))
            ->add('excludeBrokenIntegrityRule', 'checkbox', array('required' => false, 'label' => 'Has broken integrity rule'))
            ->add('excludeCriticalImpact', 'checkbox', array('required' => false, 'label' => 'Has critical impact'))
            ->add('actions', 'form_actions', array('buttons' => array('filter' => array('type' => 'submit'), 'reset' => array('type' => 'reset'))));
    }

    public function getName()
    {
        return 'parameter_filter';
    }

    /**
     * CSRF protection is useless for searching and makes it impossible
     * to send urls with filters to other persons, so better disable it.
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'csrf_protection' => false
            )
        );
    }
}
