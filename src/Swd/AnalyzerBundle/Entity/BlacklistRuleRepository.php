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

use Swd\AnalyzerBundle\Entity\EntityRepositoryTransformer;

/**
 * BlacklistRuleRepository
 */
class BlacklistRuleRepository extends EntityRepositoryTransformer
{
	public function findAllFiltered(\Swd\AnalyzerBundle\Entity\BlacklistRuleFilter $filter)
	{
		$builder = $this->createQueryBuilder('br')->leftJoin('br.profile', 'v');

		if (!$filter->getIncludeRuleIds()->isEmpty())
		{
			$orExpr = $builder->expr()->orX();

			foreach ($filter->getIncludeRuleIds() as $key => $value)
			{
				$orExpr->add($builder->expr()->eq('br.id', $builder->expr()->literal($value)));
			}

			$builder->andWhere($orExpr);
		}

		if (!$filter->getIncludeProfileIds()->isEmpty())
		{
			$orExpr = $builder->expr()->orX();

			foreach ($filter->getIncludeProfileIds() as $key => $value)
			{
				$orExpr->add($builder->expr()->eq('v.id', $builder->expr()->literal($value)));
			}

			$builder->andWhere($orExpr);
		}

		if (!$filter->getIncludeCallers()->isEmpty())
		{
			$orExpr = $builder->expr()->orX();

			foreach ($filter->getIncludeCallers() as $key => $value)
			{
				$orExpr->add($builder->expr()->like('br.caller', $builder->expr()->literal($this->prepareWildcard($value))));
			}

			$builder->andWhere($orExpr);
		}

		if ($filter->getIncludeDateStart())
		{
			$builder->andWhere('br.date >= :includeDateStart')->setParameter('includeDateStart', $filter->getIncludeDateStart());
		}

		if ($filter->getIncludeDateEnd())
		{
			$builder->andWhere('br.date <= :includeDateEnd')->setParameter('includeDateEnd', $filter->getIncludeDateEnd());
		}

		if (!$filter->getIncludePaths()->isEmpty())
		{
			$orExpr = $builder->expr()->orX();

			foreach ($filter->getIncludePaths() as $key => $value)
			{
				$orExpr->add($builder->expr()->like('br.path', $builder->expr()->literal($this->prepareWildcard($value))));
			}

			$builder->andWhere($orExpr);
		}

		if ($filter->getIncludeStatus())
		{
			$builder->andWhere('br.status = :includeStatus')->setParameter('includeStatus', $filter->getIncludeStatus());
		}

		if (!$filter->getExcludeRuleIds()->isEmpty())
		{
			$andExpr = $builder->expr()->andX();

			foreach ($filter->getExcludeRuleIds() as $key => $value)
			{
				$andExpr->add($builder->expr()->not($builder->expr()->eq('br.id', $builder->expr()->literal($value))));
			}

			$builder->andWhere($andExpr);
		}

		if (!$filter->getExcludeProfileIds()->isEmpty())
		{
			$andExpr = $builder->expr()->andX();

			foreach ($filter->getExcludeProfileIds() as $key => $value)
			{
				$andExpr->add($builder->expr()->not($builder->expr()->eq('v.id', $builder->expr()->literal($value))));
			}

			$builder->andWhere($andExpr);
		}

		if ($filter->getExcludeDateStart())
		{
			$builder->andWhere('br.date < :excludeDateStart')->setParameter('excludeDateStart', $filter->getExcludeDateStart());
		}

		if ($filter->getExcludeDateEnd())
		{
			$builder->andWhere('br.date > :excludeDateEnd')->setParameter('excludeDateEnd', $filter->getExcludeDateEnd());
		}

		if (!$filter->getExcludePaths()->isEmpty())
		{
			$andExpr = $builder->expr()->andX();

			foreach ($filter->getExcludePaths() as $key => $value)
			{
				$andExpr->add($builder->expr()->not($builder->expr()->like('br.path', $builder->expr()->literal($this->prepareWildcard($value)))));
			}

			$builder->andWhere($andExpr);
		}

		if ($filter->getExcludeStatus())
		{
			$builder->andWhere('br.status != :excludeStatus')->setParameter('excludeStatus', $filter->getExcludeStatus());
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

		if (!$filter->getIncludeCallers()->isEmpty())
		{
			$orExpr = $builder->expr()->orX();

			foreach ($filter->getIncludeCallers() as $key => $value)
			{
				$orExpr->add($builder->expr()->like('br.caller', $builder->expr()->literal($this->prepareWildcard($value))));
			}

			$builder->andWhere($orExpr);
		}

		if (!$filter->getIncludePaths()->isEmpty())
		{
			$orExpr = $builder->expr()->orX();

			foreach ($filter->getPaths() as $key => $value)
			{
				$orExpr->add($builder->expr()->like('br.path', $builder->expr()->literal($this->prepareWildcard($value))));
			}

			$builder->andWhere($orExpr);
		}

		if (!$filter->getExcludeCallers()->isEmpty())
		{
			$andExpr = $builder->expr()->andX();

			foreach ($filter->getExcludeCallers() as $key => $value)
			{
				$andExpr->add($builder->expr()->not($builder->expr()->like('br.caller', $builder->expr()->literal($this->prepareWildcard($value)))));
			}

			$builder->andWhere($andExpr);
		}

		if (!$filter->getExcludePaths()->isEmpty())
		{
			$andExpr = $builder->expr()->andX();

			foreach ($filter->getExcludePaths() as $key => $value)
			{
				$andExpr->add($builder->expr()->not($builder->expr()->like('br.path', $builder->expr()->literal($this->prepareWildcard($value)))));
			}

			$builder->andWhere($andExpr);
		}

		return $builder->getQuery();
	}
}
