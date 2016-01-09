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

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class WhitelistRuleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('profile', null, array('property' => 'getIdAndName'))
            ->add('caller')
            ->add('path')
            ->add('minLength', null, array('required' => false, 'empty_data' => '-1', 'label' => 'Min. length'))
            ->add('maxLength', null, array('required' => false, 'empty_data' => '-1', 'label' => 'Max. length'))
            ->add('filter', 'entity', array('property' => 'getDescription', 'class' => 'SwdAnalyzerBundle:WhitelistFilter',
                'query_builder' => function(EntityRepository $er) { return $er->createQueryBuilder('wf')->orderBy('wf.impact', 'ASC'); }
            ))
            ->add('status', 'choice', array('choices' => array('1' => 'Activated', '2' => 'Deactivated', '3' => 'Pending')))
            ->add('actions', 'form_actions', array('buttons' => array('save' => array('type' => 'submit'), 'reset' => array('type' => 'reset'))));
    }

    public function getName()
    {
        return 'whitelist_rule';
    }
}
