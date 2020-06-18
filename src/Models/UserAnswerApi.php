<?php 

namespace App\Models;
//TBD temporary until we get the other type of answers

class UserAnswerApi{

    public function formatAnswer($userAnswer){

        $answers = [];
        foreach($userAnswer as  $value) {
            $answers = [
                'id' => $value,
                'value' => null
            ];
        }
        return $answers;

    }
}

?>