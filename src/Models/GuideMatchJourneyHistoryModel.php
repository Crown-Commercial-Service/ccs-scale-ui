<?php
declare(strict_types=1);

namespace App\Models;

use App\GuideMatchApi\GuideMatchJourneyApi;

class GuideMatchJourneyHistoryModel
{
    private $journeyApi;
    private $journeyInstanceId;
   
    private $journeyHistoryAnswers;
    private $searchTerm;
    private $outcomeData;
    private $outcomeType;

    public function __construct(GuideMatchJourneyApi $journeyApi, string $journeyInstanceId)
    {
        $this->journeyApi = $journeyApi;
        $this->journeyInstanceId = $journeyInstanceId;
        $this->handleJourneyHistory();
    }


    /**
     * Set Journey History details
     *
     * @return void
     */
    private function handleJourneyHistory()
    {
        $journeyHistory = $this->journeyApi->getJourneyHistory($this->journeyInstanceId);

        if (!empty($journeyHistory['searchTerm'])) {
            $this->setSearchTerm($journeyHistory['searchTerm']);
        }

        if (!empty($journeyHistory['journeyHistory'])) {
            $this->setJourneyHistoryAnswers($journeyHistory['journeyHistory']);
        }

        if (!empty($journeyHistory['outcome']['data'])) {
            $this->setOutcomeData($journeyHistory['outcome']['data']);
        }

        if (!empty($journeyHistory['outcome']['outcomeType'])) {
            $this->setOutcomeType($journeyHistory['outcome']['outcomeType']);
        }
    }
    


    /**
     * Setter function for search term of journey
     *
     * @param string $searchTerm
     * @return void
     */
    private function setSearchTerm(string $searchTerm)
    {
        $this->searchTerm = $searchTerm;
    }

    /**
     * Getter function for search term of journey
     *
     * @return string
     */
    public function getSearchTerm()
    {
        return $this->searchTerm;
    }

    /**
     * Setter function for journey user answers
     *
     * @param array $journeyHistoryAnswers
     * @return void
     */
    private function setJourneyHistoryAnswers(array $journeyHistoryAnswers)
    {
        $this->journeyHistoryAnswers = $journeyHistoryAnswers;
    }

    /**
     * Getter function for journey user answers
     *
     * @return array
     */
    public function getJourneyHistoryAnswers()
    {
        return $this->journeyHistoryAnswers;
    }

    /**
     * Setter function for journey outcome data
     *
     * @param array $outcomeData
     * @return void
     */
    private function setOutcomeData(array $outcomeData)
    {
        $this->outcomeData = $outcomeData;
    }

    /**
     * Getter function for journey outcome data
     *
     * @return array
     */
    public function getOutcomeData()
    {
        return  $this->outcomeData;
    }

    /**
     * Setter function for journey outcome type
     *
     * @param string $outcomeType
     * @return void
     */
    private function setOutcomeType(string $outcomeType)
    {
        $this->outcomeType = $outcomeType;
    }

    /**
     * Getter function for journey outcome type
     *
     * @return void
     */
    public function getOutcomeType()
    {
        return $this->outcomeType;
    }
}
