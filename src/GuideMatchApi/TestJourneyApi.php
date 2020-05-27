<?php
    namespace App\GuideMatchApi;
    use App\GuideMatchApi\GuideMatchJourneyApi;
    use \Exception;
    use Symfony\Component\HttpClient\HttpClient;

    class TestJourneyApi{

        public function testJourney($q){

            $httpClient = HttpClient::create();
            $api =  new GuideMatchJourneyApi($httpClient, getenv('GUIDE_MATCH_DECISION_TREE_API'));

            $base_api_url =   getenv('GUIDE_MATCH_DECISION_TREE_API');
            $journey_data = $api->getJourneyUuid($q);
            if(empty($journey_data)){
                echo "Don't exist a guide match journey for {$q}";
                die();
            }
            $uuid = $journey_data[0]['uuid'];
            $questionUuid = $journey_data[0]['questionUuid'];
            $definedAnswers = [];

            $questions= $api->getQuestions($uuid, $questionUuid);
            $definedAnswers = $questions['definedAnswers'];
            $response_question_number = rand(0, count($definedAnswers)-1);
            $user_response = $definedAnswers[$response_question_number]['uuid'];
            echo $definedAnswers[$response_question_number]['text'];
            echo '<br>';
            $questions['outcomeType'] = false;

            while (true) {
                $questions =  $api->getDecisionTree($uuid, $questionUuid, [$user_response]);

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
                echo '<pre>';
                echo '<br/>';
                echo '------------ RESPONSE -------' ;
                echo '<br/>';
                echo '<div>';
                    dump($questions);
                echo '</div>';
                echo '<br/>';
                echo '<div>Set a response: ______ID:'.$user_response .' _____________ Text: '. $definedAnswers[$response_question_number]['text'] .'</div>';
                
            }

        }

    }
?>