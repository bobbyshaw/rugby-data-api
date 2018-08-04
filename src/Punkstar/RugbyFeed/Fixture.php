<?php

namespace Punkstar\RugbyFeed;

class Fixture
{

    const REGEX_RESULT = '/^(.*?) (\d+) - (\d+) (.*?)$/';
    const REGEX_FIXTURE = '/^(.*?) v (.*?)$/';

    /**
     * @var Team
     */
    public $home_team;

    /**
     * @var Team
     */
    public $away_team;

    public $home_score;
    public $away_score;
    public $location;
    public $kickoff;

    /**
     * @param array $array
     * @param League $league
     *
     * @return Fixture
     * @throws \Exception
     */
    public static function buildFromArray($array, League $league)
    {

        $obj = new self();

        if (isset($array['LOCATION'])) {
            $obj->location = $array['LOCATION'];
        }

        if (isset($array['DTSTART'])) {
            $obj->kickoff = strtotime($array['DTSTART']);
        }

        if (isset($array['SUMMARY'])) {
            $summary = $array['SUMMARY'];
            $summary = str_replace('BBC', '', $summary);
            $summary = str_replace('BT Sport', '', $summary);
            $summary = str_replace('Sky', '', $summary);
            $summary = preg_replace('![/A-Z0-9]{1,}$!', '', $summary);
            $summary = str_replace('TBC: ', '', $summary);

            $is_fixture = preg_match(self::REGEX_FIXTURE, $summary, $fixture_match);

            if ($is_fixture) {
                list($full_match, $home_team, $away_team) = $fixture_match;

                $obj->home_team = $league->getTeam(trim($home_team));
                $obj->away_team = $league->getTeam(trim($away_team));
            } else {
                $is_result = preg_match(self::REGEX_RESULT, $summary, $result_match);

                if ($is_result) {
                    list($full_match, $home_team, $home_score, $away_score, $away_team) = $result_match;

                    $obj->home_team = $league->getTeam(trim($home_team));
                    $obj->away_team = $league->getTeam(trim($away_team));
                    $obj->home_score = trim($home_score);
                    $obj->away_score = trim($away_score);
                }
            }
        }

        return $obj;
    }

    /**
     * @param \ICal\Event $event
     * @param League $league
     *
     * @return Fixture
     * @throws \Exception
     */
    public static function buildFromICalEvent(\ICal\Event $event , League $league)
    {
        $array = [
            'LOCATION' => $event->location,
            'SUMMARY'  => $event->summary,
            'DTSTART'  => $event->dtstart,
        ];

        return self::buildFromArray($array, $league);
    }

    /**
     * Fixture constructor.
     *
     * @param string           $home_team
     * @param string           $away_team
     * @param int              $home_score
     * @param int              $away_score
     * @param string           $location
     * @param int              $kickoff
     * @param League           $league
     *
     * @throws \Exception
     */
    public function __construct(
        $home_team = null,
        $away_team = null,
        $home_score = null,
        $away_score = null,
        $location = null,
        $kickoff = null,
        $league = null
    ) {

        $this->home_team = $home_team ? $league->getTeam($home_team) : null;
        $this->away_team = $away_team ? $league->getTeam($away_team) : null;
        $this->home_score = $home_score;
        $this->away_score = $away_score;
        $this->kickoff = $kickoff;

        if ($location) {
            $this->location = trim($location);
        } elseif ($this->home_team) {
            $this->location = $this->home_team->getStadium();
        }
    }

    /**
     * @return Team
     */
    public function getHomeTeam()
    {
        return $this->home_team;
    }

    /**
     * @return Team
     */
    public function getAwayTeam()
    {
        return $this->away_team;
    }

    public function getHomeScore()
    {
        return $this->home_score;
    }

    public function getAwayScore()
    {
        return $this->away_score;
    }

    public function isGameFinished()
    {
        return is_numeric($this->home_score) && is_numeric($this->away_score);
    }

    public function getLocation()
    {
        return $this->location;
    }

    public function getKickoffDateTime()
    {
        return \DateTime::createFromFormat('U', $this->kickoff);
    }
}
