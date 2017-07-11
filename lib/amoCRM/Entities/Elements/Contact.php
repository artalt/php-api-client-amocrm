<?php

namespace amoCRM\Entities\Elements;

/**
 * Class Lead
 * @package amoCRM\Entities\Elements
 * Класс для удобной работы с полями контакта
 */
final class Contact extends BaseElement
{
    const TYPE_NUMERIC = 1;
    const TYPE_SINGLE = 'contact';
    const TYPE_MANY = 'contacts';

    /** @var integer */
    private $company_id;

    /** @var array<integer> */
    private $leads_id = [];

    /**
     * @param integer $company_id
     * @throws \amoCRM\Exceptions\InvalidArgumentException
     */
    public function setCompanyId($company_id)
    {
        $this->company_id = $this->parseInteger($company_id);
    }

    /**
     * @param integer $lead_id
     * @throws \amoCRM\Exceptions\InvalidArgumentException
     */
    public function addLeadId($lead_id)
    {
        $this->leads_id[] = $this->parseInteger($lead_id);
    }

    /**
     * Prepare lead for sending to amoCRM
     *
     * @return array
     */
    public function toAmo()
    {
        $contact = parent::toAmo();

        if ($this->company_id !== null) {
            $contact['linked_company_id'] = $this->company_id;
        }

        foreach ($this->leads_id as $lead_id) {
            if (!isset($contact['linked_leads_id'])) {
                $contact['linked_leads_id'] = [];
            }

            $contact['linked_leads_id'][] = $lead_id;
        }

        return $contact;
    }
}
