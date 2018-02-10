<?php

namespace App\Workflow;

class BillDefinitionWorflow
{
    const PLACE_IN_PROGRESS = 'in-progress';
    const PLACE_ACQUITTED = 'acquitted';
    const PLACE_CANCELED = 'canceled';

    const TRANS_EDIT = 'edit';
    const TRANS_ACQUIT = 'acquit';
    const TRANS_CANCEL = 'cancel';

    const TITLE_PLACE_IN_PROGRESS = 'En-cours';
    const TITLE_PLACE_ACQUITTED = 'Acquittée';
    const TITLE_PLACE_CANCELED = 'Annulée';

    const TITLES_PLACES = [
        self::PLACE_IN_PROGRESS => self::TITLE_PLACE_IN_PROGRESS,
        self::PLACE_ACQUITTED => self::TITLE_PLACE_ACQUITTED,
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
