<?php

/**
 * Shadow Daemon -- Web Application Firewall
 *
 *   Copyright (C) 2014-2017 Hendrik Buchwald <hb@zecure.org>
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
 * IntegrityRuleRepository
 */
class IntegrityRuleRepository extends EntityRepositoryTransformer
{
    public function findAllFiltered(\Swd\AnalyzerBundle\Entity\IntegrityRuleFilter $filter)
    {
        $builder = $this->createQueryBuilder('ir')->leftJoin('ir.profile', 'v');

        if (!$filter->getIncludeRuleIds()->isEmpty())
        {
            $orExpr = $builder->expr()->orX();

            foreach ($filter->getIncludeRuleIds() as $key => $value)
            {
                $orExpr->add($builder->expr()->eq('ir.id', $builder->expr()->literal($value)));
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
                $orExpr->add($builder->expr()->like('ir.caller', $builder->expr()->literal($this->prepareWildcard($value))));
            }

            $builder->andWhere($orExpr);
        }

        if (!$filter->getIncludeAlgorithms()->isEmpty())
        {
            $orExpr = $builder->expr()->orX();

            foreach ($filter->getIncludeAlgorithms() as $key => $value)
            {
                $orExpr->add($builder->expr()->like('ir.algorithm', $builder->expr()->literal($this->prepareWildcard($value))));
            }

            $builder->andWhere($orExpr);
        }

        if (!$filter->getIncludeDigests()->isEmpty())
        {
            $orExpr = $builder->expr()->orX();

            foreach ($filter->getIncludeDigests() as $key => $value)
            {
                $orExpr->add($builder->expr()->like('ir.digest', $builder->expr()->literal($this->prepareWildcard($value))));
            }

            $builder->andWhere($orExpr);
        }

        if ($filter->getIncludeDateStart())
        {
            $builder->andWhere('ir.date >= :includeDateStart')->setParameter('includeDateStart', $filter->getIncludeDateStart());
        }

        if ($filter->getIncludeDateEnd())
        {
            $builder->andWhere('ir.date <= :includeDateEnd')->setParameter('includeDateEnd', $filter->getIncludeDateEnd());
        }

        if ($filter->getIncludeStatus())
        {
            $builder->andWhere('ir.status = :includeStatus')->setParameter('includeStatus', $filter->getIncludeStatus());
        }

        if ($filter->hasIncludeConflict())
        {
            $builder->andWhere('(SELECT COUNT(i_ir.id) FROM Swd\AnalyzerBundle\Entity\IntegrityRule i_ir WHERE ir.profile = i_ir.profile AND ir.caller = i_ir.caller AND ir.algorithm = i_ir.algorithm AND ir.digest != i_ir.digest) > 0');
        }

        if (!$filter->getExcludeRuleIds()->isEmpty())
        {
            $andExpr = $builder->expr()->andX();

            foreach ($filter->getExcludeRuleIds() as $key => $value)
            {
                $andExpr->add($builder->expr()->not($builder->expr()->eq('ir.id', $builder->expr()->literal($value))));
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

        if (!$filter->getExcludeCallers()->isEmpty())
        {
            $andExpr = $builder->expr()->andX();

            foreach ($filter->getExcludeCallers() as $key => $value)
            {
                $andExpr->add($builder->expr()->not($builder->expr()->like('ir.caller', $builder->expr()->literal($this->prepareWildcard($value)))));
            }

            $builder->andWhere($andExpr);
        }

        if (!$filter->getExcludeAlgorithms()->isEmpty())
        {
            $andExpr = $builder->expr()->andX();

            foreach ($filter->getExcludeAlgorithms() as $key => $value)
            {
                $andExpr->add($builder->expr()->not($builder->expr()->like('ir.algorithm', $builder->expr()->literal($this->prepareWildcard($value)))));
            }

            $builder->andWhere($andExpr);
        }

        if (!$filter->getExcludeDigests()->isEmpty())
        {
            $andExpr = $builder->expr()->andX();

            foreach ($filter->getExcludeDigests() as $key => $value)
            {
                $andExpr->add($builder->expr()->not($builder->expr()->like('ir.digest', $builder->expr()->literal($this->prepareWildcard($value)))));
            }

            $builder->andWhere($andExpr);
        }

        if ($filter->getExcludeDateStart())
        {
            $builder->andWhere('ir.date < :excludeDateStart')->setParameter('excludeDateStart', $filter->getExcludeDateStart());
        }

        if ($filter->getExcludeDateEnd())
        {
            $builder->andWhere('ir.date > :excludeDateEnd')->setParameter('excludeDateEnd', $filter->getExcludeDateEnd());
        }

        if ($filter->getExcludeStatus())
        {
            $builder->andWhere('ir.status != :excludeStatus')->setParameter('excludeStatus', $filter->getExcludeStatus());
        }

        if ($filter->hasExcludeConflict())
        {
            $builder->andWhere('(SELECT COUNT(e_ir.id) FROM Swd\AnalyzerBundle\Entity\IntegrityRule e_ir WHERE ir.profile = e_ir.profile AND ir.caller = e_ir.caller AND ir.algorithm = e_ir.algorithm AND ir.digest != e_ir.digest) = 0');
        }

        return $builder->getQuery();
    }

    public function findAllByRule(\Swd\AnalyzerBundle\Entity\IntegrityRule $rule)
    {
        $builder = $this->createQueryBuilder('ir')
            ->andWhere('ir.profile = :profile')->setParameter('profile', $rule->getProfile())
            ->andWhere('ir.caller = :caller')->setParameter('caller', $rule->getCaller())
            ->andWhere('ir.algorithm = :algorithm')->setParameter('algorithm', $rule->getAlgorithm())
            ->andWhere('ir.digest = :digest')->setParameter('digest', $rule->getDigest());

        return $builder->getQuery();
    }

    public function findConflict($rule)
    {
        $builder = $this->createQueryBuilder('ir')
            ->select('count(ir.id)')
            ->andWhere('ir.profile = :profile')->setParameter('profile', $rule->getProfile())
            ->andWhere('ir.caller = :caller')->setParameter('caller', $rule->getCaller())
            ->andWhere('ir.algorithm = :algorithm')->setParameter('algorithm', $rule->getAlgorithm())
            ->andWhere('ir.digest != :digest')->setParameter('digest', $rule->getDigest());

        return $builder->getQuery();
    }

    public function findAllByExport(\Swd\AnalyzerBundle\Entity\IntegrityExport $filter)
    {
        $builder = $this->createQueryBuilder('ir')
            ->orderBy('ir.caller', 'ASC')
            ->where('ir.status = 1')
            ->andWhere('ir.profile = :profile')->setParameter('profile', $filter->getProfile());

        if (!$filter->getIncludeCallers()->isEmpty())
        {
            $orExpr = $builder->expr()->orX();

            foreach ($filter->getIncludeCallers() as $key => $value)
            {
                $orExpr->add($builder->expr()->like('ir.caller', $builder->expr()->literal($this->prepareWildcard($value))));
            }

            $builder->andWhere($orExpr);
        }

        if (!$filter->getExcludeCallers()->isEmpty())
        {
            $andExpr = $builder->expr()->andX();

            foreach ($filter->getExcludeCallers() as $key => $value)
            {
                $andExpr->add($builder->expr()->not($builder->expr()->like('ir.caller', $builder->expr()->literal($this->prepareWildcard($value)))));
            }

            $builder->andWhere($andExpr);
        }

        return $builder->getQuery();
    }
}
