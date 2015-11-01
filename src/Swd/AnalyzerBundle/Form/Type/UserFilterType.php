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

class UserFilterType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->setMethod('GET')
			->setAction('#')
			->add('includeUserIds', 'bootstrap_collection', array('allow_add' => true, 'allow_delete' => true, 'label' => 'User ID'))
			->add('includeUsernames', 'bootstrap_collection', array('allow_add' => true, 'allow_delete' => true, 'label' => 'Username'))
			->add('includeEmails', 'bootstrap_collection', array('allow_add' => true, 'allow_delete' => true, 'label' => 'Email'))
			->add('includeDateStart', 'datetime', array('required' => false, 'label'  => 'From', 'empty_value' => array('year' => 'Year', 'month' => 'Month', 'day' => 'Day', 'hour' => 'Hour', 'minute' => 'Minute')))
			->add('includeDateEnd', 'datetime', array('required' => false, 'label'  => 'To', 'empty_value' => array('year' => 'Year', 'month' => 'Month', 'day' => 'Day', 'hour' => 'Hour', 'minute' => 'Minute')))
			->add('excludeUserIds', 'bootstrap_collection', array('allow_add' => true, 'allow_delete' => true, 'label' => 'User ID'))
			->add('excludeUsernames', 'bootstrap_collection', array('allow_add' => true, 'allow_delete' => true, 'label' => 'Username'))
			->add('excludeEmails', 'bootstrap_collection', array('allow_add' => true, 'allow_delete' => true, 'label' => 'Email'))
			->add('excludeDateStart', 'datetime', array('required' => false, 'label'  => 'From', 'empty_value' => array('year' => 'Year', 'month' => 'Month', 'day' => 'Day', 'hour' => 'Hour', 'minute' => 'Minute')))
			->add('excludeDateEnd', 'datetime', array('required' => false, 'label'  => 'To', 'empty_value' => array('year' => 'Year', 'month' => 'Month', 'day' => 'Day', 'hour' => 'Hour', 'minute' => 'Minute')))
			->add('actions', 'form_actions', array('buttons' => array('filter' => array('type' => 'submit'), 'reset' => array('type' => 'reset'))));
	}

	public function getName()
	{
		return 'user_filter';
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
