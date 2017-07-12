<?php

namespace amoCRM\Repository;

use amoCRM\Entity\Contact;
use amoCRM\Interfaces\Requester;

class ContactsRepository extends BaseEntityRepository
{
    public function __construct(Requester $_requester)
    {
        $names = [
            'many' => Contact::TYPE_MANY,
        ];

        $paths = [
            'set' => Contact::TYPE_MANY . '/set',
            'list' => Contact::TYPE_MANY . '/list',
        ];

        parent::__construct($_requester, $names, $paths);
    }
}