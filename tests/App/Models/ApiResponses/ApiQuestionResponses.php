<?php

namespace App\Tests\App\Models\ApiResponses;

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


    public function questionResponseMock(){
     
        $quesionResponse =    [
            "outcome" => [
                "outcomeType" => "question",
                "timestamp" => "2020-06-28T14:52:13.948492Z",
                "data" =>  [
                    0 =>  [
                        "question" =>  [
                            "id" => "ccb5a4f8-75b5-11ea-bc55-0242ac130003",
                            "text" => "Do you know your budget?",
                            "hint" => "This helps us find your best buying options. An estimate is fine.",
                            "type" => "boolean",
                            ],
                        "answerDefinitions" =>[
                            0 => [
                                "id" => "1144b698-c399-4b89-adb6-eb78bc3941ad",
                                "text" => "Yes",
                                "order" => 1,
                                "mutuallyExclusive" => false,
                                "conditionalInput" =>  [
                                    "text" => "How much is your budget?",
                                    "hint" => "An estimate is fine (£)",
                                    "type" => "number",
                                    ]
                                ],
                            1 => [
                                "id" => "ccb59b2a-75b5-11ea-bc55-0242ac130003",
                                "text" => "No",
                                "order" => 2,
                                "mutuallyExclusive" => false,
                            ]
                        ]
                    ]
                ]
                            ],
            "journeyHistory" => [
                0 => [
                "question" => [
                    "id" => "ccb5a43a-75b5-11ea-bc55-0242ac130003",
                    "text" => "Are you looking for a product or a service?",
                    "hint" => "Choose one option:",
                    "type" => "list",
                ],
                "answers" => [
                    0 =>  [
                    "answerText" => "Service",
                    "answer" => "b879fe0c-654e-11ea-bc55-0242ac130003",
                    ]
                ],
                "variableName" => ""
                ]
            ]
        ];
        
        return json_encode($quesionResponse);
    }


    public function lastJourneyResponse(){

        $response =  [
            "outcome" => [
                "outcomeType" => "agreement",
                "timestamp" => "2020-06-28T16:33:21.564179Z",
                "data" => [
                    0 => [
                        "number" => "RM6154",
                        "lots" =>[
                            0 => [
                                "number" => "3",
                                "type" => "cat",
                                "routeToMarket" => "fc",
                                "url" => "",
                                "scale" => true,
                            ]
                        ]
                    ]
                ]
            ],
            "journeyHistory" => [
                    0 => [
                        "question" => [
                        "id" => "ccb5a43a-75b5-11ea-bc55-0242ac130003",
                        "text" => "Are you looking for a product, service or both?",
                        "hint" => "Choose one option:",
                        "type" => "list",
                    ],
                    "answers" =>[
                        0 =>[
                            "answerText" => "Service",
                            "answer" => "b879fe0c-654e-11ea-bc55-0242ac130003",
                        ]
                    ],
                    "variableName" => ""
                ]
            ]
        ];

        return json_encode($response);

    }
 }
?>