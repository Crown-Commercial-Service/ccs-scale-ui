<?php
    namespace App\GuideMatchApi;
    use App\GuideMatchApi\GuideMatchJourneyApi;
    use \Exception;


    class TestJourneyApi{

        public function testJourney($q){

        $api = new GuideMatchJourneyApi();
        $base_api_url =   getenv('GUIDE_MATCH_DECISION_TREE_API');
        $journey_data = $api->getJourneyUuid($base_api_url, $q);
        if(empty($journey_data)){
            echo "Don't exist a guide match journey for {$q}";
            die();
        }
        $uuid = $journey_data[0]['uuid'];
        $questionUuid = $journey_data[0]['questionUuid'];
        $definedAnswers = [];

        $questions= $api->getQuestions($base_api_url, $uuid, $questionUuid);
        $definedAnswers = $questions['definedAnswers'];
        $response_question_number = rand(0, count($definedAnswers)-1);
        $user_response = $definedAnswers[$response_question_number]['uuid'];
        echo $definedAnswers[$response_question_number]['text'];
        echo '<br>';
        $questions['outcomeType'] = false;

        while (true) {
            $questions =  $api->getDecisionTree($base_api_url, $uuid, $questionUuid, [$user_response]);

            if (empty($questions['outcomeType'])) {
                dump($questions);
                die('x');
            }

            if ($questions['outcomeType'] == 'lot') {

                echo '<br/><br/><br/>';
                echo '-----Finish ----- ';
                dump($questions);
                return;
            }

            $definedAnswers = !empty($questions['data']['definedAnswers']) ? $questions['data']['definedAnswers'] : $questions['data'] ;

            $count_q = count($definedAnswers);
            $response_question_number = 0;
            if ($count_q > 1) {
                $response_question_number =  rand(0, $count_q-1);
            }

            
            $user_response = $definedAnswers[$response_question_number]['uuid'];
            $questionUuid = $questions['data']['uuid'];

            echo '<br/>';
            echo '------------ RESPONSE -------' ;
            echo '<br/>';
           
            dump($questions);
            echo '<br/>';
            echo 'Set a response: ______ID:'.$user_response .' _____________ Text: '. $definedAnswers[$response_question_number]['text'];
        }


        }

    }
?>