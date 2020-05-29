<?php

namespace App\Models;

class JourneyTrackModel{

    private $userJourneyTrack;

    public function __construct($userJourneyTrack)
    {
        $this->userJourneyTrack = $userJourneyTrack;
    }

    /**
     * Undocumented function
     *
     * @param [type] $userResponse an array with question id and response.
     * @return void 
     */
    public function add(array $userResponse){

        array_push( $this->userJourneyTrack,$userResponse);
    }

    /**
     * remove the last element from history
     *
     * @return void
     */
    public function remove(){

    }

    public function get(){

    }
}