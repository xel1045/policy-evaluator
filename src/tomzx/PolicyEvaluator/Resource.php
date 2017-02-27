<?php

namespace tomzx\PolicyEvaluator;

class Resource
{
    /**
     * @var array
     */
    private $resources;

    /**
     * @param array|string $resources
     */
    public function __construct($resources)
    {
        if ( ! is_array($resources)) {
            $resources = (array)$resources;
        }

        foreach ($resources as $resource) {
            if ($resource === '*') {
                continue;
            }

            // TODO(tom@tomrochette.com): Change resource prefix and partition
            if (strpos($resource, 'arn:aws') !== 0) {
                throw new \InvalidArgumentException('Invalid resource "' . $resource . '".');
            }
        }

        $this->resources = $resources;
    }

    /**
     * @return array
     */
    public function getResources()
    {
        return $this->resources;
    }

    /**
     * @param string $requestedResource
     * @return bool
     */
    public function matches($requestedResource)
    {
        foreach ($this->resources as $resource) {
            $resourceRegex = '/^'.str_replace('\*', '.*', preg_quote($resource, '/')).'$/';
            if (preg_match($resourceRegex, $requestedResource)) {
                return true;
            }
        }

        return false;
    }
}
