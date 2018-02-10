<?php

namespace App\Workflow;

class EstimateDefinitionWorflow
{
    const PLACE_IN_PROGRESS = 'in-progress';
    const PLACE_ACCEPTED = 'accepted';
    const PLACE_CANCELED = 'canceled';

    const TRANS_EDIT = 'edit';
    const TRANS_ACCEPT = 'accept';
    const TRANS_CANCEL = 'cancel';

    const TITLE_PLACE_IN_PROGRESS = 'En-cours';
    const TITLE_PLACE_ACCEPTED = 'Accepté';
    const TITLE_PLACE_CANCELED = 'Annulé';

    const TITLES_PLACES = [
        self::PLACE_IN_PROGRESS => self::TITLE_PLACE_IN_PROGRESS,
        self::PLACE_ACCEPTED => self::TITLE_PLACE_ACCEPTED,
        self::PLACE_CANCELED => self::TITLE_PLACE_CANCELED,
    ];

    public static function getTitleByPlace(string $place): string
    {
        if (!isset(self::TITLES_PLACES[$place])) {
            return $place;
        }

        return self::TITLES_PLACES[$place];
    }
}
