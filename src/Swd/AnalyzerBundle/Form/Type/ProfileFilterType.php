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

class ProfileFilterType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->setMethod('GET')
			->setAction('#')
			->add('profileId', 'integer', array('required' => false, 'label'  => 'Profile ID'))
			->add('searchServerIPs', 'bootstrap_collection', array('allow_add' => true, 'allow_delete' => true, 'label' => 'Server IP'))
			->add('searchNames', 'bootstrap_collection', array('allow_add' => true, 'allow_delete' => true, 'label' => 'Name'))
			->add('dateStart', 'datetime', array('required' => false, 'label'  => 'From', 'empty_value' => array('year' => 'Year', 'month' => 'Month', 'day' => 'Day', 'hour' => 'Hour', 'minute' => 'Minute')))
			->add('dateEnd', 'datetime', array('required' => false, 'label'  => 'To', 'empty_value' => array('year' => 'Year', 'month' => 'Month', 'day' => 'Day', 'hour' => 'Hour', 'minute' => 'Minute')))
			->add('ignoreServerIPs', 'bootstrap_collection', array('allow_add' => true, 'allow_delete' => true, 'label' => 'Server IP'))
			->add('ignoreNames', 'bootstrap_collection', array('allow_add' => true, 'allow_delete' => true, 'label' => 'Name'))
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
