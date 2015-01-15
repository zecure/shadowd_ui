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

namespace Swd\AnalyzerBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ProfileRepository
 */
class ProfileRepository extends EntityRepository
{
	public function findAllFiltered(\Swd\AnalyzerBundle\Entity\ProfileFilter $filter)
	{
		$builder = $this->createQueryBuilder('v');

		/* Search. */
		if ($filter->getProfileId())
		{
			$builder->andWhere('v.id = :profileId')->setParameter('profileId', $filter->getProfileId());
		}

		if (!$filter->getSearchServerIPs()->isEmpty())
		{
			$orExpr = $builder->expr()->orX();

			foreach ($filter->getSearchServerIPs() as $key => $value)
			{
				$value = str_replace(array('_', '%', '*'), array('\\_', '\\%', '%'), $value);
				$orExpr->add($builder->expr()->like("v.serverIP", $builder->expr()->literal($value)));
			}

			$builder->andWhere($orExpr);
		}

		if (!$filter->getSearchNames()->isEmpty())
		{
			$orExpr = $builder->expr()->orX();

			foreach ($filter->getSearchNames() as $key => $value)
			{
				$value = str_replace(array('_', '%', '*'), array('\\_', '\\%', '%'), $value);
				$orExpr->add($builder->expr()->like("v.name", $builder->expr()->literal($value)));
			}

			$builder->andWhere($orExpr);
		}

		if ($filter->getDateStart())
		{
			$builder->andWhere('v.date >= :dateStart')->setParameter('dateStart', $filter->getDateStart());
		}

		if ($filter->getDateEnd())
		{
			$builder->andWhere('v.date <= :dateEnd')->setParameter('dateEnd', $filter->getDateEnd());
		}

		/* Ignore. */
		if (!$filter->getIgnoreServerIPs()->isEmpty())
		{
			$andExpr = $builder->expr()->andX();

			foreach ($filter->getIgnoreServerIPs() as $key => $value)
			{
				$value = str_replace(array('_', '%', '*'), array('\\_', '\\%', '%'), $value);
				$andExpr->add($builder->expr()->not($builder->expr()->like("v.serverIP", $builder->expr()->literal($value))));
			}

			$builder->andWhere($andExpr);
		}

		if (!$filter->getIgnoreNames()->isEmpty())
		{
			$andExpr = $builder->expr()->andX();

			foreach ($filter->getIgnoreNames() as $key => $value)
			{
				$value = str_replace(array('_', '%', '*'), array('\\_', '\\%', '%'), $value);
				$andExpr->add($builder->expr()->not($builder->expr()->like("v.name", $builder->expr()->literal($value))));
			}

			$builder->andWhere($andExpr);
		}

		return $builder->getQuery();
	}

	public function findAll()
	{
		return $this->findBy(array(), array('id' => 'ASC'));
	}
}
