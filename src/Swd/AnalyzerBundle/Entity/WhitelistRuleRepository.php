<?php

/*
 * Shadow Daemon -- High-Interaction Web Honeypot
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
 * WhitelistRuleRepository
 */
class WhitelistRuleRepository extends EntityRepository
{
	public function findAllFiltered(\Swd\AnalyzerBundle\Entity\WhitelistRuleFilter $filter)
	{
		$builder = $this->createQueryBuilder('wr')->leftJoin('wr.filter', 'wf')->leftJoin('wr.profile', 'v');

		/* Search. */
		if ($filter->getRuleId())
		{
			$builder->andWhere('wr.id = :ruleId')->setParameter('ruleId', $filter->getRuleId());
		}

		if ($filter->getProfileId())
		{
			$builder->andWhere('v.id = :profileId')->setParameter('profileId', $filter->getProfileId());
		}

		if (!$filter->getSearchCallers()->isEmpty())
		{
			$orExpr = $builder->expr()->orX();

			foreach ($filter->getSearchCallers() as $key => $value)
			{
				$value = str_replace(array('_', '%', '*'), array('\\_', '\\%', '%'), $value);
				$orExpr->add($builder->expr()->like("wr.caller", $builder->expr()->literal($value)));
			}

			$builder->andWhere($orExpr);
		}

		if ($filter->getDateStart())
		{
			$builder->andWhere('wr.date >= :dateStart')->setParameter('dateStart', $filter->getDateStart());
		}

		if ($filter->getDateEnd())
		{
			$builder->andWhere('wr.date <= :dateEnd')->setParameter('dateEnd', $filter->getDateEnd());
		}

		if (!$filter->getSearchPaths()->isEmpty())
		{
			$orExpr = $builder->expr()->orX();

			foreach ($filter->getSearchPaths() as $key => $value)
			{
				$value = str_replace(array('_', '%', '*'), array('\\_', '\\%', '%'), $value);
				$orExpr->add($builder->expr()->like("wr.path", $builder->expr()->literal($value)));
			}

			$builder->andWhere($orExpr);
		}

		if ($filter->hasConflict())
		{
			$builder->andWhere('(SELECT COUNT(x.id) FROM Swd\AnalyzerBundle\Entity\WhitelistRule x WHERE wr.profile = x.profile AND wr.caller = x.caller AND wr.path = x.path AND (wr.minLength != x.minLength OR wr.maxLength != x.maxLength OR wr.filter != x.filter)) > 0');
		}

		/* Status. */
		if ($filter->getStatus() !== null)
		{
			$builder->andWhere('wr.status = :status')->setParameter('status', $filter->getStatus());
		}

		/* Ignore. */
		if (!$filter->getIgnoreCallers()->isEmpty())
		{
			$andExpr = $builder->expr()->andX();

			foreach ($filter->getIgnoreCallers() as $key => $value)
			{
				$value = str_replace(array('_', '%', '*'), array('\\_', '\\%', '%'), $value);
				$andExpr->add($builder->expr()->not($builder->expr()->like("wr.caller", $builder->expr()->literal($value))));
			}

			$builder->andWhere($andExpr);
		}

		if (!$filter->getIgnorePaths()->isEmpty())
		{
			$andExpr = $builder->expr()->andX();

			foreach ($filter->getIgnorePaths() as $key => $value)
			{
				$value = str_replace(array('_', '%', '*'), array('\\_', '\\%', '%'), $value);
				$andExpr->add($builder->expr()->not($builder->expr()->like("wr.path", $builder->expr()->literal($value))));
			}

			$builder->andWhere($andExpr);
		}

		return $builder->getQuery();
	}

	public function findAllByRule(\Swd\AnalyzerBundle\Entity\WhitelistRule $rule)
	{
		$builder = $this->createQueryBuilder('wr')
			->andWhere('wr.profile = :profile')->setParameter('profile', $rule->getProfile())
			->andWhere('wr.caller = :caller')->setParameter('caller', $rule->getCaller())
			->andWhere('wr.path = :path')->setParameter('path', $rule->getPath())
			->andWhere('wr.minLength = :minLength')->setParameter('minLength', $rule->getMinLength())
			->andWhere('wr.maxLength = :maxLength')->setParameter('maxLength', $rule->getMaxLength())
			->andWhere('wr.filter = :filter')->setParameter('filter', $rule->getFilter());

		return $builder->getQuery();
	}

	public function findMinLengthConflict($rule)
	{
		$builder = $this->createQueryBuilder('wr')
			->select('count(wr.id)')
			->andWhere('wr.profile = :profile')->setParameter('profile', $rule->getProfile())
			->andWhere('wr.caller = :caller')->setParameter('caller', $rule->getCaller())
			->andWhere('wr.path = :path')->setParameter('path', $rule->getPath())
			->andWhere('wr.minLength != :minLength')->setParameter('minLength', $rule->getMinLength());

		return $builder->getQuery();
	}

	public function findMaxLengthConflict($rule)
	{
		$builder = $this->createQueryBuilder('wr')
			->select('count(wr.id)')
			->andWhere('wr.profile = :profile')->setParameter('profile', $rule->getProfile())
			->andWhere('wr.caller = :caller')->setParameter('caller', $rule->getCaller())
			->andWhere('wr.path = :path')->setParameter('path', $rule->getPath())
			->andWhere('wr.maxLength != :maxLength')->setParameter('maxLength', $rule->getMaxLength());

		return $builder->getQuery();
	}

	public function findFilterConflict($rule)
	{
		$builder = $this->createQueryBuilder('wr')
			->select('count(wr.id)')
			->andWhere('wr.profile = :profile')->setParameter('profile', $rule->getProfile())
			->andWhere('wr.caller = :caller')->setParameter('caller', $rule->getCaller())
			->andWhere('wr.path = :path')->setParameter('path', $rule->getPath())
			->andWhere('wr.filter != :filter')->setParameter('filter', $rule->getFilter());

		return $builder->getQuery();
	}

	public function findAllByExport(\Swd\AnalyzerBundle\Entity\WhitelistExport $filter)
	{
		$builder = $this->createQueryBuilder('wr')
			->where('wr.profile = :profile')->setParameter('profile', $filter->getProfile());

		if (!$filter->getCallers()->isEmpty())
		{
			$orExpr = $builder->expr()->orX();

			foreach ($filter->getCallers() as $key => $value)
			{
				$value = str_replace(array('_', '%', '*'), array('\\_', '\\%', '%'), $value);
				$orExpr->add($builder->expr()->like("wr.caller", $builder->expr()->literal($value)));
			}

			$builder->andWhere($orExpr);
		}

		if (!$filter->getPaths()->isEmpty())
		{
			$orExpr = $builder->expr()->orX();

			foreach ($filter->getPaths() as $key => $value)
			{
				$value = str_replace(array('_', '%', '*'), array('\\_', '\\%', '%'), $value);
				$orExpr->add($builder->expr()->like("wr.path", $builder->expr()->literal($value)));
			}

			$builder->andWhere($orExpr);
		}

		return $builder->getQuery();
	}
}
