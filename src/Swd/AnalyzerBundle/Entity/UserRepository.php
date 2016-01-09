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

namespace Swd\AnalyzerBundle\Entity;

use Swd\AnalyzerBundle\Entity\EntityRepositoryTransformer;

/**
 * UserRepository
 */
class UserRepository extends EntityRepositoryTransformer
{
    public function findAllFiltered(\Swd\AnalyzerBundle\Entity\UserFilter $filter)
    {
        $builder = $this->createQueryBuilder('u');

        if (!$filter->getIncludeUserIds()->isEmpty())
        {
            $orExpr = $builder->expr()->orX();

            foreach ($filter->getIncludeUserIds() as $key => $value)
            {
                $orExpr->add($builder->expr()->eq('u.id', $builder->expr()->literal($value)));
            }

            $builder->andWhere($orExpr);
        }

        if (!$filter->getIncludeUsernames()->isEmpty())
        {
            $orExpr = $builder->expr()->orX();

            foreach ($filter->getIncludeUsernames() as $key => $value)
            {
                $orExpr->add($builder->expr()->like('u.username', $builder->expr()->literal($this->prepareWildcard($value))));
            }

            $builder->andWhere($orExpr);
        }

        if (!$filter->getIncludeEmails()->isEmpty())
        {
            $orExpr = $builder->expr()->orX();

            foreach ($filter->getIncludeEmails() as $key => $value)
            {
                $orExpr->add($builder->expr()->like('u.email', $builder->expr()->literal($this->prepareWildcard($value))));
            }

            $builder->andWhere($orExpr);
        }

        if ($filter->getIncludeDateStart())
        {
            $builder->andWhere('u.date >= :includeDateStart')->setParameter('includeDateStart', $filter->getIncludeDateStart());
        }

        if ($filter->getIncludeDateEnd())
        {
            $builder->andWhere('u.date <= :includeDateEnd')->setParameter('includeDateEnd', $filter->getIncludeDateEnd());
        }

        if (!$filter->getExcludeUserIds()->isEmpty())
        {
            $andExpr = $builder->expr()->andX();

            foreach ($filter->getExcludeUserIds() as $key => $value)
            {
                $andExpr->add($builder->expr()->not($builder->expr()->eq('u.id', $builder->expr()->literal($value))));
            }

            $builder->andWhere($andExpr);
        }

        if (!$filter->getExcludeUsernames()->isEmpty())
        {
            $andExpr = $builder->expr()->andX();

            foreach ($filter->getExcludeUsernames() as $key => $value)
            {
                $andExpr->add($builder->expr()->not($builder->expr()->like('u.username', $builder->expr()->literal($this->prepareWildcard($value)))));
            }

            $builder->andWhere($andExpr);
        }

        if (!$filter->getExcludeEmails()->isEmpty())
        {
            $andExpr = $builder->expr()->andX();

            foreach ($filter->getExcludeEmails() as $key => $value)
            {
                $andExpr->add($builder->expr()->not($builder->expr()->like('u.email', $builder->expr()->literal($this->prepareWildcard($value)))));
            }

            $builder->andWhere($andExpr);
        }

        if ($filter->getExcludeDateStart())
        {
            $builder->andWhere('u.date < :excludeDateStart')->setParameter('excludeDateStart', $filter->getExcludeDateStart());
        }

        if ($filter->getExcludeDateEnd())
        {
            $builder->andWhere('u.date > :excludeDateEnd')->setParameter('excludeDateEnd', $filter->getExcludeDateEnd());
        }

        return $builder->getQuery();
    }

    public function findByEmail()
    {
        $builder = $this->createQueryBuilder('u')
            ->where('u.email IS NOT NULL');

        return $builder->getQuery();
    }
}
