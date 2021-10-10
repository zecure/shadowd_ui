<?php

/**
 * Shadow Daemon -- Web Application Firewall
 *
 *   Copyright (C) 2014-2021 Hendrik Buchwald <hb@zecure.org>
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

namespace App\Repository;

use App\Entity\EntityRepositoryTransformer;
use App\Entity\WhitelistExport;
use App\Entity\WhitelistRule;
use App\Entity\WhitelistRuleFilter;

/**
 * WhitelistRuleRepository
 */
class WhitelistRuleRepository extends EntityRepositoryTransformer
{
    public function findAllFiltered(WhitelistRuleFilter $filter)
    {
        $builder = $this->createQueryBuilder('wr')->leftJoin('wr.filter', 'wf')->leftJoin('wr.profile', 'v');

        if (!$filter->getIncludeRuleIds()->isEmpty()) {
            $orExpr = $builder->expr()->orX();

            foreach ($filter->getIncludeRuleIds() as $key => $value) {
                $orExpr->add($builder->expr()->eq('wr.id', $builder->expr()->literal($value)));
            }

            $builder->andWhere($orExpr);
        }

        if (!$filter->getIncludeProfileIds()->isEmpty()) {
            $orExpr = $builder->expr()->orX();

            foreach ($filter->getIncludeProfileIds() as $key => $value) {
                $orExpr->add($builder->expr()->eq('v.id', $builder->expr()->literal($value)));
            }

            $builder->andWhere($orExpr);
        }

        if (!$filter->getIncludeCallers()->isEmpty()) {
            $orExpr = $builder->expr()->orX();

            foreach ($filter->getIncludeCallers() as $key => $value) {
                $orExpr->add($builder->expr()->like('wr.caller', $builder->expr()->literal($this->prepareWildcard($value))));
            }

            $builder->andWhere($orExpr);
        }

        if ($filter->getIncludeDateStart()) {
            $builder->andWhere('wr.date >= :includeDateStart')->setParameter('includeDateStart', $filter->getIncludeDateStart());
        }

        if ($filter->getIncludeDateEnd()) {
            $builder->andWhere('wr.date <= :includeDateEnd')->setParameter('includeDateEnd', $filter->getIncludeDateEnd());
        }

        if (!$filter->getIncludeCallers()->isEmpty()) {
            $orExpr = $builder->expr()->orX();

            foreach ($filter->getIncludeCallers() as $key => $value) {
                $orExpr->add($builder->expr()->like('wr.caller', $builder->expr()->literal($this->prepareWildcard($value))));
            }

            $builder->andWhere($orExpr);
        }

        if (!$filter->getIncludePaths()->isEmpty()) {
            $orExpr = $builder->expr()->orX();

            foreach ($filter->getIncludePaths() as $key => $value) {
                $orExpr->add($builder->expr()->like('wr.path', $builder->expr()->literal($this->prepareWildcard($value))));
            }

            $builder->andWhere($orExpr);
        }

        if ($filter->getIncludeStatus()) {
            $builder->andWhere('wr.status = :includeStatus')->setParameter('includeStatus', $filter->getIncludeStatus());
        }

        if ($filter->hasIncludeConflict()) {
            $builder->andWhere('(SELECT COUNT(i_wr.id) FROM Swd\AnalyzerBundle\Entity\WhitelistRule i_wr WHERE wr.profile = i_wr.profile AND wr.caller = i_wr.caller AND wr.path = i_wr.path AND (wr.minLength != i_wr.minLength OR wr.maxLength != i_wr.maxLength OR wr.filter != i_wr.filter)) > 0');
        }

        if (!$filter->getExcludeRuleIds()->isEmpty()) {
            $andExpr = $builder->expr()->andX();

            foreach ($filter->getExcludeRuleIds() as $key => $value) {
                $andExpr->add($builder->expr()->not($builder->expr()->eq('wr.id', $builder->expr()->literal($value))));
            }

            $builder->andWhere($andExpr);
        }

        if (!$filter->getExcludeProfileIds()->isEmpty()) {
            $andExpr = $builder->expr()->andX();

            foreach ($filter->getExcludeProfileIds() as $key => $value) {
                $andExpr->add($builder->expr()->not($builder->expr()->eq('v.id', $builder->expr()->literal($value))));
            }

            $builder->andWhere($andExpr);
        }

        if ($filter->getExcludeDateStart()) {
            $builder->andWhere('wr.date < :excludeDateStart')->setParameter('excludeDateStart', $filter->getExcludeDateStart());
        }

        if ($filter->getExcludeDateEnd()) {
            $builder->andWhere('wr.date > :excludeDateEnd')->setParameter('excludeDateEnd', $filter->getExcludeDateEnd());
        }

        if (!$filter->getExcludeCallers()->isEmpty()) {
            $andExpr = $builder->expr()->andX();

            foreach ($filter->getExcludeCallers() as $key => $value) {
                $andExpr->add($builder->expr()->not($builder->expr()->like('wr.caller', $builder->expr()->literal($this->prepareWildcard($value)))));
            }

            $builder->andWhere($andExpr);
        }

        if (!$filter->getExcludePaths()->isEmpty()) {
            $andExpr = $builder->expr()->andX();

            foreach ($filter->getExcludePaths() as $key => $value) {
                $andExpr->add($builder->expr()->not($builder->expr()->like('wr.path', $builder->expr()->literal($this->prepareWildcard($value)))));
            }

            $builder->andWhere($andExpr);
        }

        if ($filter->getExcludeStatus()) {
            $builder->andWhere('wr.status != :excludeStatus')->setParameter('excludeStatus', $filter->getExcludeStatus());
        }

        if ($filter->hasExcludeConflict()) {
            $builder->andWhere('(SELECT COUNT(e_wr.id) FROM Swd\AnalyzerBundle\Entity\WhitelistRule e_wr WHERE wr.profile = e_wr.profile AND wr.caller = e_wr.caller AND wr.path = e_wr.path AND (wr.minLength != e_wr.minLength OR wr.maxLength != e_wr.maxLength OR wr.filter != e_wr.filter)) = 0');
        }

        return $builder->getQuery();
    }

    public function findAllByRule(WhitelistRule $rule)
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

    public function findAllByExport(WhitelistExport $filter)
    {
        $builder = $this->createQueryBuilder('wr')
            ->orderBy('wr.caller', 'ASC')
            ->addOrderBy('wr.path', 'ASC')
            ->where('wr.status = 1')
            ->andWhere('wr.profile = :profile')->setParameter('profile', $filter->getProfile());

        if (!$filter->getIncludeCallers()->isEmpty()) {
            $orExpr = $builder->expr()->orX();

            foreach ($filter->getIncludeCallers() as $key => $value) {
                $orExpr->add($builder->expr()->like('wr.caller', $builder->expr()->literal($this->prepareWildcard($value))));
            }

            $builder->andWhere($orExpr);
        }

        if (!$filter->getIncludePaths()->isEmpty()) {
            $orExpr = $builder->expr()->orX();

            foreach ($filter->getIncludePaths() as $key => $value) {
                $orExpr->add($builder->expr()->like('wr.path', $builder->expr()->literal($this->prepareWildcard($value))));
            }

            $builder->andWhere($orExpr);
        }

        if (!$filter->getExcludeCallers()->isEmpty()) {
            $andExpr = $builder->expr()->andX();

            foreach ($filter->getExcludeCallers() as $key => $value) {
                $andExpr->add($builder->expr()->not($builder->expr()->like('wr.caller', $builder->expr()->literal($this->prepareWildcard($value)))));
            }

            $builder->andWhere($andExpr);
        }

        if (!$filter->getExcludePaths()->isEmpty()) {
            $andExpr = $builder->expr()->andX();

            foreach ($filter->getExcludePaths() as $key => $value) {
                $andExpr->add($builder->expr()->not($builder->expr()->like('wr.path', $builder->expr()->literal($this->prepareWildcard($value)))));
            }

            $builder->andWhere($andExpr);
        }

        return $builder->getQuery();
    }
}
