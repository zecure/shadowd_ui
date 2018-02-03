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
 * RequestRepository
 */
class RequestRepository extends EntityRepositoryTransformer
{
    public function findAllFiltered(\Swd\AnalyzerBundle\Entity\RequestFilter $filter)
    {
        $builder = $this->createQueryBuilder('r')
            ->leftJoin('r.profile', 'v');

        if (!$filter->getIncludeRequestIds()->isEmpty()) {
            $orExpr = $builder->expr()->orX();

            foreach ($filter->getIncludeRequestIds() as $key => $value) {
                $orExpr->add($builder->expr()->eq('r.id', $builder->expr()->literal($value)));
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
                $orExpr->add($builder->expr()->like('r.caller', $builder->expr()->literal($this->prepareWildcard($value))));
            }

            $builder->andWhere($orExpr);
        }

        if (!$filter->getIncludeResources()->isEmpty()) {
            $orExpr = $builder->expr()->orX();

            foreach ($filter->getIncludeResources() as $key => $value) {
                $orExpr->add($builder->expr()->like('r.resource', $builder->expr()->literal($this->prepareWildcard($value))));
            }

            $builder->andWhere($orExpr);
        }

        if (!$filter->getIncludeClientIPs()->isEmpty()) {
            $orExpr = $builder->expr()->orX();

            foreach ($filter->getIncludeClientIPs() as $key => $value) {
                $orExpr->add($builder->expr()->like('r.clientIP', $builder->expr()->literal($this->prepareWildcard($value))));
            }

            $builder->andWhere($orExpr);
        }

        if ($filter->getIncludeDateStart()) {
            $builder->andWhere('r.date >= :includeDateStart')->setParameter('includeDateStart', $filter->getIncludeDateStart());
        }

        if ($filter->getIncludeDateEnd()) {
            $builder->andWhere('r.date <= :includeDateEnd')->setParameter('includeDateEnd', $filter->getIncludeDateEnd());
        }

        if (!$filter->getExcludeRequestIds()->isEmpty()) {
            $andExpr = $builder->expr()->andX();

            foreach ($filter->getExcludeRequestIds() as $key => $value) {
                $andExpr->add($builder->expr()->not($builder->expr()->eq('r.id', $builder->expr()->literal($value))));
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

        if (!$filter->getExcludeCallers()->isEmpty()) {
            $andExpr = $builder->expr()->andX();

            foreach ($filter->getExcludeCallers() as $key => $value) {
                $andExpr->add($builder->expr()->not($builder->expr()->like('r.caller', $builder->expr()->literal($this->prepareWildcard($value)))));
            }

            $builder->andWhere($andExpr);
        }

        if (!$filter->getExcludeResources()->isEmpty()) {
            $andExpr = $builder->expr()->andX();

            foreach ($filter->getExcludeResources() as $key => $value) {
                $andExpr->add($builder->expr()->not($builder->expr()->like('r.resource', $builder->expr()->literal($this->prepareWildcard($value)))));
            }

            $builder->andWhere($andExpr);
        }

        if (!$filter->getExcludeClientIPs()->isEmpty()) {
            $andExpr = $builder->expr()->andX();

            foreach ($filter->getExcludeClientIPs() as $key => $value) {
                $andExpr->add($builder->expr()->not($builder->expr()->like('r.clientIP', $builder->expr()->literal($this->prepareWildcard($value)))));
            }

            $builder->andWhere($andExpr);
        }

        if ($filter->getExcludeDateStart()) {
            $builder->andWhere('r.date < :excludeDateStart')->setParameter('excludeDateStart', $filter->getExcludeDateStart());
        }

        if ($filter->getExcludeDateEnd()) {
            $builder->andWhere('r.date > :excludeDateEnd')->setParameter('excludeDateEnd', $filter->getExcludeDateEnd());
        }

        return $builder->getQuery();
    }

    public function findByDate($date)
    {
        $builder = $this->createQueryBuilder('r')
            ->where('r.mode != 3')
            ->andWhere('r.date > :date')->setParameter(':date', $date);

        return $builder->getQuery();
    }

    public function deleteByDate($date)
    {
        $builder = $this->createQueryBuilder('r')
            ->where('r.date < :date')->setParameter(':date', $date)
            ->delete();

        return $builder->getQuery();
    }

    public function deleteByProfileAndMode($profile, $mode)
    {
        $builder = $this->createQueryBuilder('r')
            ->where('r.mode = :mode')->setParameter(':mode', $mode)
            ->andWhere('r.profile = :profile')->setParameter(':profile', $profile)
            ->delete();

        return $builder->getQuery();
    }

    public function countByProfileAndMode($profile, $mode)
    {
        $builder = $this->createQueryBuilder('r')
            ->select('count(r.id)')
            ->where('r.mode = :mode')->setParameter(':mode', $mode)
            ->andWhere('r.profile = :profile')->setParameter(':profile', $profile);

        return $builder->getQuery();
    }

    public function findAllLearningBySettings(\Swd\AnalyzerBundle\Entity\GeneratorSettings $settings)
    {
        $builder = $this->createQueryBuilder('r')
            ->where('r.mode = 3')
            ->andWhere('r.profile = :profile')->setParameter('profile', $settings->getProfile());

        if (!$settings->getIncludeCallers()->isEmpty()) {
            $orExpr = $builder->expr()->orX();

            foreach ($settings->getIncludeCallers() as $key => $value) {
                $orExpr->add($builder->expr()->like('r.caller', $builder->expr()->literal($this->prepareWildcard($value))));
            }

            $builder->andWhere($orExpr);
        }

        if (!$settings->getExcludeCallers()->isEmpty()) {
            $andExpr = $builder->expr()->andX();

            foreach ($settings->getExcludeCallers() as $key => $value) {
                $andExpr->add($builder->expr()->not($builder->expr()->like('r.caller', $builder->expr()->literal($this->prepareWildcard($value)))));
            }

            $builder->andWhere($andExpr);
        }

        return $builder->getQuery();
    }
}
