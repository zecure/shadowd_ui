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

class GeneratorSettingsType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('profile', 'entity', array('property' => 'getIdAndName', 'class' => 'SwdAnalyzerBundle:Profile',
				'query_builder' => function(EntityRepository $er) { return $er->createQueryBuilder('v')->orderBy('v.id', 'ASC'); }
			))
			->add('predefined', 'choice', array('choices' => array('1' => 'Low security', '2' => 'Moderate security', '3' => 'High security', '4' => 'Custom')))
			->add('status', 'choice', array('choices' => array('1' => 'Activated', '2' => 'Deactivated', '3' => 'Pending')))
			->add('enableWhitelist', 'checkbox', array('required' => false, 'label' => 'Generate whitelist rules'))
			->add('enableBlacklist', 'checkbox', array('required' => false, 'label' => 'Generate blacklist rules'))
			->add('enableIntegrity', 'checkbox', array('required' => false, 'label' => 'Generate integrity rules'))
			->add('minUniqueVisitors', 'integer', array('label' => 'Min. unique visitors'))
			->add('minFilterDominance', 'integer', array('label' => 'Min. filter dominance'))
			->add('maxLengthVariance', 'integer', array('label' => 'Max. length variance'))
			->add('minThresholdDominance', 'integer', array('label' => 'Min. threshold dominance'))
			->add('unifyWhitelistArrays', 'checkbox', array('required' => false, 'label' => 'Unify arrays'))
			->add('unifyWhitelistCallers', 'checkbox', array('required' => false, 'label' => 'Unify callers'))
			->add('unifyBlacklistArrays', 'checkbox', array('required' => false, 'label' => 'Unify arrays'))
			->add('unifyBlacklistCallers', 'checkbox', array('required' => false, 'label' => 'Unify callers'))
			->add('includeCallers', 'bootstrap_collection', array('allow_add' => true, 'allow_delete' => true, 'label' => 'Caller'))
			->add('includePaths', 'bootstrap_collection', array('allow_add' => true, 'allow_delete' => true, 'label' => 'Path'))
			->add('excludeCallers', 'bootstrap_collection', array('allow_add' => true, 'allow_delete' => true, 'label' => 'Caller'))
			->add('excludePaths', 'bootstrap_collection', array('allow_add' => true, 'allow_delete' => true, 'label' => 'Path'))
			->add('actions', 'form_actions', array('buttons' => array('generate' => array('type' => 'submit'), 'reset' => array('type' => 'reset'))));
	}

	public function getName()
	{
		return 'generator_settings';
	}
}
