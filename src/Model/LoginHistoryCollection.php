<?php
/**
 * Created by PhpStorm.
 * User: cleyer
 * Date: 22/12/2017
 * Time: 16:57
 */

namespace App\Model;

class LoginHistoryCollection implements \JsonSerializable
{
    private $history = [];

    /**
     * VinculatedDevices constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return array
     */
    public function getHistories(): array
    {
        return $this->history;
    }

    /**
     * @param array $history
     *
     * @return LoginHistoryCollection
     */
    public function setHistories(array $history): LoginHistoryCollection
    {
        $this->history = $history;
        return $this;
    }

    /**
     * @param LoginHistory $history
     *
     * @return LoginHistoryCollection
     */
    public function addHistory(LoginHistory $history)
    {
        $this->history[] = $history;
        return $this;
    }

    public function hydrate(array $histories)
    {
        /* var App\Entity\UserDevice $link */
        foreach ($histories as $history) {
            $loginHistory = new LoginHistory();
            $loginHistory->hydrate($history);
            $this->addHistory($loginHistory);
        }
    }

    public function jsonSerialize()
    {
        $history = array(
            'history' => $this->history,

        );
        return $history;
    }
}
