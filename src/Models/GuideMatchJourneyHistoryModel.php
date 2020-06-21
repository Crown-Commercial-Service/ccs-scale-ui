<?php 
declare(strict_types=1);

namespace App\Models;

use App\GuideMatchApi\GuideMatchJourneyApi;


class GuideMatchJourneyHistoryModel{

    private $journeyApi;
    private $journeyInstanceId;
    private $journeyHistory;

    public function __construct(GuideMatchJourneyApi $journeyApi, string $journeyInstanceId)
    {
        $this->journeyApi = $journeyApi;
        $this->journeyInstanceId = $journeyInstanceId;
        $this->setJourneyHistory();
    }


    /**
     * set Journey History details
     *
     * @return void
     */
    private function setJourneyHistory(){
        $this->journeyHistory = $this->journeyApi->getJourneyHistory($this->journeyInstanceId);

    }

    /**
     * Get History of a Guided Match Journey.
     * It's available only at the end of a journey
     *
     * @return void
     */
    public function getJourneyHistory(){

        return $this->journeyHistory;

    }
}