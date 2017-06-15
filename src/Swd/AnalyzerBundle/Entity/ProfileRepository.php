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
 * ProfileRepository
 */
class ProfileRepository extends EntityRepositoryTransformer
{
    public function findAllFiltered(\Swd\AnalyzerBundle\Entity\ProfileFilter $filter)
    {
        $builder = $this->createQueryBuilder('v');

        if (!$filter->getIncludeProfileIds()->isEmpty())
        {
            $orExpr = $builder->expr()->orX();

            foreach ($filter->getIncludeProfileIds() as $key => $value)
            {
                $orExpr->add($builder->expr()->eq('v.id', $builder->expr()->literal($value)));
            }

            $builder->andWhere($orExpr);
        }

        if (!$filter->getIncludeServerIPs()->isEmpty())
        {
            $orExpr = $builder->expr()->orX();

            foreach ($filter->getIncludeServerIPs() as $key => $value)
            {
                $orExpr->add($builder->expr()->like('v.serverIP', $builder->expr()->literal($this->prepareWildcard($value))));
            }

            $builder->andWhere($orExpr);
        }

        if (!$filter->getIncludeNames()->isEmpty())
        {
            $orExpr = $builder->expr()->orX();

            foreach ($filter->getIncludeNames() as $key => $value)
            {
                $orExpr->add($builder->expr()->like('v.name', $builder->expr()->literal($this->prepareWildcard($value))));
            }

            $builder->andWhere($orExpr);
        }

        if ($filter->getIncludeMode())
        {
            $builder->andWhere('v.mode = :includeMode')->setParameter('includeMode', $filter->getIncludeMode());
        }

        if ($filter->getIncludeDateStart())
        {
            $builder->andWhere('v.date >= :includeDateStart')->setParameter('includeDateStart', $filter->getIncludeDateStart());
        }

        if ($filter->getIncludeDateEnd())
        {
            $builder->andWhere('v.date <= :includeDateEnd')->setParameter('includeDateEnd', $filter->getIncludeDateEnd());
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

        if (!$filter->getExcludeServerIPs()->isEmpty())
        {
            $andExpr = $builder->expr()->andX();

            foreach ($filter->getExcludeServerIPs() as $key => $value)
            {
                $andExpr->add($builder->expr()->not($builder->expr()->like('v.serverIP', $builder->expr()->literal($this->prepareWildcard($value)))));
            }

            $builder->andWhere($andExpr);
        }

        if (!$filter->getExcludeNames()->isEmpty())
        {
            $andExpr = $builder->expr()->andX();

            foreach ($filter->getExcludeNames() as $key => $value)
            {
                $andExpr->add($builder->expr()->not($builder->expr()->like('v.name', $builder->expr()->literal($this->prepareWildcard($value)))));
            }

            $builder->andWhere($andExpr);
        }

        if ($filter->getExcludeMode())
        {
            $builder->andWhere('v.mode != :excludeMode')->setParameter('excludeMode', $filter->getExcludeMode());
        }

        if ($filter->getExcludeDateStart())
        {
            $builder->andWhere('v.date < :excludeDateStart')->setParameter('excludeDateStart', $filter->getExcludeDateStart());
        }

        if ($filter->getExcludeDateEnd())
        {
            $builder->andWhere('v.date > :excludeDateEnd')->setParameter('excludeDateEnd', $filter->getExcludeDateEnd());
        }

        return $builder->getQuery();
    }

    public function findAll()
    {
        return $this->findBy(array(), array('id' => 'ASC'));
    }
}
