<?php

namespace Punkstar\RugbyFeed;

class League
{
    protected $teams;
    protected $data;
    protected $fixtures;
    protected $table;

    public function __construct($data, array $teams, FixtureSet $fixtures, Table $table)
    {
        $this->data = $data;
        $this->teams = $teams;
        $this->fixtures = $fixtures;
        $this->table = $table;
    }

    public function getName()
    {
        return $this->data['name'] ?? 'unknown';
    }

    /**
     * @return string
     */
    public function getUrlKey()
    {
        return $this->data['url'] ?? 'unknown';
    }

    /**
     * @return Team[]
     */
    public function getTeams()
    {
        return $this->teams;
    }

    /**
     * @param $teamSearchString
     * @return Team
     */
    public function getTeam($teamSearchString)
    {
        foreach ($this->getTeams() as $team) {
            if ($team->isAliasedTo($teamSearchString)) {
                return $team;
            }
        }

        return null;
    }

    /**
     * @param $searchString
     * @return bool
     */
    public function isAliasedTo($searchString)
    {
        $aliases = array_map('strtolower', [$this->getName(), $this->getUrlKey()]);

        return in_array($searchString, $aliases);
    }

    /**
     * @return Fixture[]
     */
    public function getFixtures()
    {
        return $this->fixtures->getFixtures();
    }

    /**
     * @return Fixture[]
     */
    public function getFixturesForTeam($teamSearchString)
    {
        return $this->fixtures->getEventsFromTeam($teamSearchString);
    }

    /**
     * @return Table
     */
    public function getTable()
    {
        return $this->table;
    }
}
