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
 * BlacklistRuleRepository
 */
class BlacklistRuleRepository extends EntityRepository
{
	public function findAllFiltered(\Swd\AnalyzerBundle\Entity\BlacklistRuleFilter $filter)
	{
		$builder = $this->createQueryBuilder('br')->leftJoin('br.profile', 'v');

		/* Search. */
		if ($filter->getRuleId())
		{
			$builder->andWhere('br.id = :ruleId')->setParameter('ruleId', $filter->getRuleId());
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
				$orExpr->add($builder->expr()->like("br.caller", $builder->expr()->literal($value)));
			}

			$builder->andWhere($orExpr);
		}

		if ($filter->getDateStart())
		{
			$builder->andWhere('br.date >= :dateStart')->setParameter('dateStart', $filter->getDateStart());
		}

		if ($filter->getDateEnd())
		{
			$builder->andWhere('br.date <= :dateEnd')->setParameter('dateEnd', $filter->getDateEnd());
		}

		if (!$filter->getSearchPaths()->isEmpty())
		{
			$orExpr = $builder->expr()->orX();

			foreach ($filter->getSearchPaths() as $key => $value)
			{
				$value = str_replace(array('_', '%', '*'), array('\\_', '\\%', '%'), $value);
				$orExpr->add($builder->expr()->like("br.path", $builder->expr()->literal($value)));
			}

			$builder->andWhere($orExpr);
		}

		/* Status. */
		if ($filter->getStatus() !== null)
		{
			$builder->andWhere('br.status = :status')->setParameter('status', $filter->getStatus());
		}

		/* Ignore. */
		if (!$filter->getIgnoreCallers()->isEmpty())
		{
			$andExpr = $builder->expr()->andX();

			foreach ($filter->getIgnoreCallers() as $key => $value)
			{
				$value = str_replace(array('_', '%', '*'), array('\\_', '\\%', '%'), $value);
				$andExpr->add($builder->expr()->not($builder->expr()->like("br.caller", $builder->expr()->literal($value))));
			}

			$builder->andWhere($andExpr);
		}

		if (!$filter->getIgnorePaths()->isEmpty())
		{
			$andExpr = $builder->expr()->andX();

			foreach ($filter->getIgnorePaths() as $key => $value)
			{
				$value = str_replace(array('_', '%', '*'), array('\\_', '\\%', '%'), $value);
				$andExpr->add($builder->expr()->not($builder->expr()->like("br.path", $builder->expr()->literal($value))));
			}

			$builder->andWhere($andExpr);
		}

		return $builder->getQuery();
	}

	public function findAllByRule(\Swd\AnalyzerBundle\Entity\BlacklistRule $rule)
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

	public function findAllByExport(\Swd\AnalyzerBundle\Entity\BlacklistExport $filter)
	{
		$builder = $this->createQueryBuilder('br')
			->orderBy('br.caller', 'ASC')
			->addOrderBy('br.path', 'ASC')
			->where('br.status = 1')
			->andWhere('br.profile = :profile')->setParameter('profile', $filter->getProfile());

		if (!$filter->getCallers()->isEmpty())
		{
			$orExpr = $builder->expr()->orX();

			foreach ($filter->getCallers() as $key => $value)
			{
				$value = str_replace(array('_', '%', '*'), array('\\_', '\\%', '%'), $value);
				$orExpr->add($builder->expr()->like("br.caller", $builder->expr()->literal($value)));
			}

			$builder->andWhere($orExpr);
		}

		if (!$filter->getPaths()->isEmpty())
		{
			$orExpr = $builder->expr()->orX();

			foreach ($filter->getPaths() as $key => $value)
			{
				$value = str_replace(array('_', '%', '*'), array('\\_', '\\%', '%'), $value);
				$orExpr->add($builder->expr()->like("br.path", $builder->expr()->literal($value)));
			}

			$builder->andWhere($orExpr);
		}

		return $builder->getQuery();
	}
}
