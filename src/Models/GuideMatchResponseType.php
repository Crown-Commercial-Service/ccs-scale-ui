<?php


declare(strict_types=1);

namespace App\Models;

/**
 * Response from Guide Match JOurney Api can be different :
 * question if the journey is not finished or outcome on the final
*/
class GuideMatchResponseType
{
    const GuideMatchResponseQuestion = 'question' ;
    const GuideMatchResponseAgreement = 'agreement';
    const GuideMatchResponseSupport = 'support';
}
