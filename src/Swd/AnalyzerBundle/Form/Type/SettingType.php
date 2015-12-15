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

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SettingType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('pageLimit', 'integer', array('label' => 'Entries per page'))
			->add('sortOrder', 'choice', array('choices' => array('0' => 'Descendent', '1' => 'Ascendent'), 'label' => 'Sort order'))
			->add('openFilter', 'checkbox', array('required' => false, 'label' => 'Automatically open filter mask on new filter'))
			->add('theme', 'choice', array('choices' => array(
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
			->add('locale', 'choice', array('choices' => array(
				'de' => 'Deutsch',
				'en' => 'English',
				'zh_CN' => '中文（简体）',
			)))
			->add('oldPassword', 'password', array('required' => false, 'label' => 'Old password'))
			->add('newPassword', 'repeated', array('required' => false, 'type' => 'password',
				'invalid_message' => 'The password fields must match.',
				'first_options'  => array('label' => 'New password'),
				'second_options' => array('label' => 'Repeat password'),
			))
			->add('actions', 'form_actions', array('buttons' => array('save' => array('type' => 'submit'), 'reset' => array('type' => 'reset'))));
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver)
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
