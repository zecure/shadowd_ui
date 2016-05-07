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

class ProfileFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setMethod('GET')
            ->setAction('#')
            ->add('includeProfileIds', 'bootstrap_collection', array('allow_add' => true, 'allow_delete' => true, 'label' => 'Profile ID'))
            ->add('includeServerIPs', 'bootstrap_collection', array('allow_add' => true, 'allow_delete' => true, 'label' => 'Server IP'))
            ->add('includeNames', 'bootstrap_collection', array('allow_add' => true, 'allow_delete' => true, 'label' => 'Name'))
            ->add('includeMode', 'choice', array('required' => false, 'label' => 'Mode', 'choices' => array('1' => 'Active', '2' => 'Passive', '3' => 'Learning')))
            ->add('includeDateStart', 'datetime', array('required' => false, 'label'  => 'From', 'empty_value' => array('year' => 'Year', 'month' => 'Month', 'day' => 'Day', 'hour' => 'Hour', 'minute' => 'Minute')))
            ->add('includeDateEnd', 'datetime', array('required' => false, 'label'  => 'To', 'empty_value' => array('year' => 'Year', 'month' => 'Month', 'day' => 'Day', 'hour' => 'Hour', 'minute' => 'Minute')))
            ->add('excludeProfileIds', 'bootstrap_collection', array('allow_add' => true, 'allow_delete' => true, 'label' => 'Profile ID'))
            ->add('excludeServerIPs', 'bootstrap_collection', array('allow_add' => true, 'allow_delete' => true, 'label' => 'Server IP'))
            ->add('excludeNames', 'bootstrap_collection', array('allow_add' => true, 'allow_delete' => true, 'label' => 'Name'))
            ->add('excludeMode', 'choice', array('required' => false, 'label' => 'Mode', 'choices' => array('1' => 'Active', '2' => 'Passive', '3' => 'Learning')))
            ->add('excludeDateStart', 'datetime', array('required' => false, 'label'  => 'From', 'empty_value' => array('year' => 'Year', 'month' => 'Month', 'day' => 'Day', 'hour' => 'Hour', 'minute' => 'Minute')))
            ->add('excludeDateEnd', 'datetime', array('required' => false, 'label'  => 'To', 'empty_value' => array('year' => 'Year', 'month' => 'Month', 'day' => 'Day', 'hour' => 'Hour', 'minute' => 'Minute')))
            ->add('actions', 'form_actions', array('buttons' => array('filter' => array('type' => 'submit'), 'reset' => array('type' => 'reset'))));
    }

    public function getName()
    {
        return 'profile_filter';
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
