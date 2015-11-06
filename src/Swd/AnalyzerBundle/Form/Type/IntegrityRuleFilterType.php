<?php

/**
 * Shadow Daemon -- Web Application Firewall
 *
 *   Copyright (C) 2014-2015 Hendrik Buchwald <hb@zecure.org>
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

class IntegrityRuleFilterType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->setMethod('GET')
			->setAction('#')
			->add('includeRuleIds', 'bootstrap_collection', array('allow_add' => true, 'allow_delete' => true, 'label' => 'Rule ID'))
			->add('includeProfileIds', 'bootstrap_collection', array('allow_add' => true, 'allow_delete' => true, 'label' => 'Profile ID'))
			->add('includeStatus', 'choice', array('required' => false, 'label' => 'Status', 'choices' => array('1' => 'Active', '2' => 'Inactive', '3' => 'Pending')))
			->add('includeCallers', 'bootstrap_collection', array('allow_add' => true, 'allow_delete' => true, 'label' => 'Caller'))
			->add('includeAlgorithms', 'bootstrap_collection', array('allow_add' => true, 'allow_delete' => true, 'label' => 'Algorithm'))
			->add('includeDigests', 'bootstrap_collection', array('allow_add' => true, 'allow_delete' => true, 'label' => 'Digest'))
			->add('includeDateStart', 'datetime', array('required' => false, 'label'  => 'From', 'empty_value' => array('year' => 'Year', 'month' => 'Month', 'day' => 'Day', 'hour' => 'Hour', 'minute' => 'Minute')))
			->add('includeDateEnd', 'datetime', array('required' => false, 'label'  => 'To', 'empty_value' => array('year' => 'Year', 'month' => 'Month', 'day' => 'Day', 'hour' => 'Hour', 'minute' => 'Minute')))
			->add('includeConflict', 'checkbox', array('required' => false, 'label' => 'Has conflict'))
			->add('excludeRuleIds', 'bootstrap_collection', array('allow_add' => true, 'allow_delete' => true, 'label' => 'Rule ID'))
			->add('excludeProfileIds', 'bootstrap_collection', array('allow_add' => true, 'allow_delete' => true, 'label' => 'Profile ID'))
			->add('excludeStatus', 'choice', array('required' => false, 'label' => 'Status', 'choices' => array('1' => 'Active', '2' => 'Inactive', '3' => 'Pending')))
			->add('excludeCallers', 'bootstrap_collection', array('allow_add' => true, 'allow_delete' => true, 'label' => 'Caller'))
			->add('excludeAlgorithms', 'bootstrap_collection', array('allow_add' => true, 'allow_delete' => true, 'label' => 'Algorithm'))
			->add('excludeDigests', 'bootstrap_collection', array('allow_add' => true, 'allow_delete' => true, 'label' => 'Digest'))
			->add('excludeDateStart', 'datetime', array('required' => false, 'label'  => 'From', 'empty_value' => array('year' => 'Year', 'month' => 'Month', 'day' => 'Day', 'hour' => 'Hour', 'minute' => 'Minute')))
			->add('excludeDateEnd', 'datetime', array('required' => false, 'label'  => 'To', 'empty_value' => array('year' => 'Year', 'month' => 'Month', 'day' => 'Day', 'hour' => 'Hour', 'minute' => 'Minute')))
			->add('excludeConflict', 'checkbox', array('required' => false, 'label' => 'Has conflict'))
			->add('actions', 'form_actions', array('buttons' => array('filter' => array('type' => 'submit'), 'reset' => array('type' => 'reset'))));
	}

	public function getName()
	{
		return 'integrity_rule_filter';
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
