<?php

namespace Punkstar\RugbyFeed;

class Team
{
    private $data;

    /**
     * @var string
     */
    private $url;

    public function __construct($data)
    {
        $this->data = $data;

        $this->url = $this->data['url'] ?? str_replace(' ', '_', strtolower($this->getName()));
    }

    /**
     * @return string
     */
    public function getUrlKey()
    {
        return $this->url;
    }

    /**
     * @param $searchString
     * @return bool
     */
    public function isAliasedTo($searchString)
    {
        $aliases = array_map('strtolower', $this->data['alias'] ?? []);
        $aliases[] = strtolower($this->getName());
        $aliases[] = $this->getUrlKey();

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
