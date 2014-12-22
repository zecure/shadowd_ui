<?php

/**
 * Shadow Daemon -- Web Application Firewall
 *
 *   Copyright (C) 2014 Hendrik Buchwald <hb@zecure.org>
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
 * RequestRepository
 */
class RequestRepository extends EntityRepository
{
	public function findAllFiltered(\Swd\AnalyzerBundle\Entity\RequestFilter $filter)
	{
		$builder = $this->createQueryBuilder('r')
			->leftJoin('r.profile', 'v');

		/* Search. */
		if ($filter->getRequestId())
		{
			$builder
				->andWhere('r.id = :requestId')
				->setParameter('requestId', $filter->getRequestId());
		}

		if ($filter->getProfileId())
		{
			$builder
				->andWhere('v.id = :profileId')
				->setParameter('profileId', $filter->getProfileId());
		}

		if (!$filter->getSearchCallers()->isEmpty())
		{
			$orExpr = $builder->expr()->orX();

			foreach ($filter->getSearchCallers() as $key => $value)
			{
				$value = str_replace(array('_', '%', '*'), array('\\_', '\\%', '%'), $value);
				$orExpr->add($builder->expr()->like("r.caller", $builder->expr()->literal($value)));
			}

			$builder->andWhere($orExpr);
		}

		if (!$filter->getSearchClientIPs()->isEmpty())
		{
			$orExpr = $builder->expr()->orX();

			foreach ($filter->getSearchClientIPs() as $key => $value)
			{
				$value = str_replace(array('_', '%', '*'), array('\\_', '\\%', '%'), $value);
				$orExpr->add($builder->expr()->like("r.clientIP", $builder->expr()->literal($value)));
			}

			$builder->andWhere($orExpr);
		}

		if ($filter->getDateStart())
		{
			$builder
				->andWhere('r.date >= :dateStart')
				->setParameter('dateStart', $filter->getDateStart());
		}

		if ($filter->getDateEnd())
		{
			$builder
				->andWhere('r.date <= :dateEnd')
				->setParameter('dateEnd', $filter->getDateEnd());
		}

		/* Learning data. */
		if ($filter->getLearning() !== null)
		{
			$builder->andWhere('r.learning = :learning')->setParameter('learning', $filter->getLearning());
		}

		/* Ignore. */
		if (!$filter->getIgnoreCallers()->isEmpty())
		{
			$andExpr = $builder->expr()->andX();

			foreach ($filter->getIgnoreCallers() as $key => $value)
			{
				$value = str_replace(array('_', '%', '*'), array('\\_', '\\%', '%'), $value);
				$andExpr->add($builder->expr()->not($builder->expr()->like("r.caller", $builder->expr()->literal($value))));
			}

			$builder->andWhere($andExpr);
		}

		if (!$filter->getIgnoreClientIPs()->isEmpty())
		{
			$andExpr = $builder->expr()->andX();

			foreach ($filter->getIgnoreClientIPs() as $key => $value)
			{
				$value = str_replace(array('_', '%', '*'), array('\\_', '\\%', '%'), $value);
				$andExpr->add($builder->expr()->not($builder->expr()->like("r.clientIP", $builder->expr()->literal($value))));
			}

			$builder->andWhere($andExpr);
		}
			
		return $builder->getQuery();
	}

	public function findByDate($date)
	{
		$builder = $this->createQueryBuilder('r')
			->where('r.learning = 0')
			->andWhere('r.date > :date')->setParameter(':date', $date);

		return $builder->getQuery();
	}

	public function deleteByProfileAndLearning($profile, $learning)
	{
		$builder = $this->createQueryBuilder('r')
			->where('r.learning = :learning')->setParameter(':learning', $learning)
			->andWhere('r.profile = :profile')->setParameter(':profile', $profile)
			->delete();

		return $builder->getQuery();
	}

	public function countByProfileAndLearning($profile, $learning)
	{
		$builder = $this->createQueryBuilder('r')
			->select('count(r.id)')
			->where('r.learning = :learning')->setParameter(':learning', $learning)
			->andWhere('r.profile = :profile')->setParameter(':profile', $profile);

		return $builder->getQuery();
	}
}
