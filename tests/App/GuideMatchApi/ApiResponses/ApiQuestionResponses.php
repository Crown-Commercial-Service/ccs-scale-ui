<?php

namespace App\Tests\App\GuideMatchyApi\ApiResponses;

 class ApiQuestionResponses{

    public function startJourneyApiResponse(){
        $apiResponse = [
            "journeyInstanceId" => "b31e1dae-14da-4a17-9a85-9c8cf64822bb",
            "questions" => [
                0 => [
                    "question" => [
                        "id" => "ccb5a43a-75b5-11ea-bc55-0242ac130003",
                        "text" => "Are you looking for a product or a service?",
                        "hint" => "Choose one option:",
                        "type" => "list",
                    ],
                    "answerDefinitions" => [
                        0 =>[
                            "id" => "b879fcf4-654e-11ea-bc55-0242ac130003",
                            "text" => "Product",
                            "hint" => "A product is an item you can buy, such as a kettle.  It requires no ongoing contract.",
                            "order" => 1,
                            "mutuallyExclusive" => false,
                        ],
                        1 =>[
                            "id" => "b879fe0c-654e-11ea-bc55-0242ac130003",
                            "text" => "Service",
                            "hint" => "A service is a contract for something to happen, one time or regularly, such as window cleaning. Some contracts may involve hiring a product as part of the serv ",
                            "order" => 2,
                            "mutuallyExclusive" => false,
                        ]
                    ]
                ]
            ]
        ];

        return json_encode($apiResponse);

    }
 }
?>