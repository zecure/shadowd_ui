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

namespace Swd\AnalyzerBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;

class MergePathExtension extends \Twig_Extension
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getFunctions()
    {
        return array('mergePath' => new \Twig_Function_Method($this, 'mergePath'));
    }

    private function getUser()
    {
        return $this->container->get('security.context')->getToken()->getUser();
    }

    private function array_merge_recursive(array &$array1, array &$array2)
    {
        $merged = $array1;

        foreach ($array2 as $key => &$value)
        {
            if (is_array($value) && isset($merged[$key]) && is_array($merged[$key]))
            {
                $merged[$key] = $this->array_merge_recursive($merged[$key], $value);
            }
            else
            {
                if (is_int($key))
                {
                    if (!in_array($value, $merged))
                    {
                        $merged[] = $value;
                    }
                }
                else
                {
                    $merged[$key] = $value;
                }
            }
        }

        return $merged;
    }

    function array_filter_recursive($input)
    {
        foreach ($input as &$value)
        {
            if (is_array($value))
            {
                $value = $this->array_filter_recursive($value);
            }
        }

        // Remove empty elements.
        return array_filter($input);
    }

    public function mergePath($input, $disableFilter = false)
    {
        $router = $this->container->get('router');
        $request = $this->container->get('request');
        $routeName = $request->attributes->get('_route');
        $routeParams = $request->query->all();

        /* Remove page, because it makes no sense after a new filter. */
        if (isset($routeParams['page']))
        {
            unset($routeParams['page']);
        }

        /* Merge and replace arrays. */
        $result = $this->array_merge_recursive($routeParams, $input);

        /* Remove empty elements. */
        $result = $this->array_filter_recursive($result);

        /* Generate the url. */
        $url = $router->generate($routeName, $result);

        /* Check if the filter hashtag should be appended for the js. */
        if (!$disableFilter && $this->getUser()->getSetting()->getOpenFilter())
        {
            $url .= '#filters';
        }

        return $url;
    }

    public function getName()
    {
        return 'merge_path_extension';
    }
}
