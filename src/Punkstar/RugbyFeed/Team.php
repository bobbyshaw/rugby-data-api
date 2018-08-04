<?php

namespace Punkstar\RugbyFeed;

class Team
{
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function getUrlKey()
    {
        return $this->data['url'] ?? str_replace(' ', '_', strtolower($this->getName()));
    }

    /**
     * @param $searchString
     * @return bool
     */
    public function isAliasedTo($searchString)
    {
        $aliases = array_map('strtolower', $this->data['alias'] ?? []);
        $aliases[] = strtolower($this->getName());
        $aliases[] = strtolower($this->getUrlKey());

        return in_array(strtolower($searchString), $aliases);
    }

    public function getName()
    {
        return $this->data['name'] ?? 'Unknown Name';
    }

    /**
     * @return string
     */
    public function getStadium()
    {
        return $this->data['stadium'] ?? '';
    }

    /**
     * @return string
     */
    public function getConference()
    {
        return $this->data['conference'] ?? '';
    }
}
