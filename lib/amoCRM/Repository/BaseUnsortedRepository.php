<?php

namespace amoCRM\Repository;

use amoCRM\Entity\BaseUnsorted;
use amoCRM\Exception;
use amoCRM\Service\Interfaces\UnsortedRequesterService;

/**
 * Class BaseUnsortedRepository
 * Common methods for unsorted requests
 * @package amoCRM\Unsorted
 */
abstract class BaseUnsortedRepository implements Interfaces\BaseUnsortedRepository
{
    const BASE_PATH = 'api/unsorted/';

    /** @var UnsortedRequesterService */
    private $requester;

    /** @var string */
    private $category;

    /**
     * BaseUnsortedRepository constructor.
     * @param UnsortedRequesterService $requester
     * @param string $category
     */
    public function __construct(UnsortedRequesterService $requester, $category)
    {
        $this->requester = $requester;
        $this->setCategory($category);
    }

    /**
     * @param string $category
     */
    private function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Send request to add unsorted
     * @link https://developers.amocrm.ru/rest_api/unsorted/add.php
     *
     * @param array $elements
     * @return bool
     * @throws \amoCRM\Exception\ValidateException
     * @throws \amoCRM\Exception\RuntimeException
     * @throws \amoCRM\Exception\InvalidArgumentException
     * @throws Exception\InvalidResponseException
     */
    public function add(array $elements)
    {
        $elements = $this->ensureIsArrayOfElements($elements);

        $data = [
            'request' => [
                'unsorted' => [
                    'category' => $this->category,
                    'add' => $elements,
                ],
            ],
        ];

        /**
         * На данный момент $response выглядит так:
         * 'response' => [
         *   'unsorted' => [
         *     'add' => [
         *       'status' => 'success',
         *     ],
         *   ],
         * ],
         */
        $response = $this->requester->post(self::BASE_PATH . 'add/', $data);

        if (!isset($response['unsorted']['add']['status'])) {
            throw new Exception\InvalidResponseException(json_encode($response));
        }

        return $response['unsorted']['add']['status'] === 'success';
    }

    /**
     * Check for possible wrong nesting level
     *
     * @param array $elements
     * @return array
     * @throws \amoCRM\Exception\ValidateException
     * @throws \amoCRM\Exception\RuntimeException
     * @throws Exception\InvalidArgumentException
     */
    private function ensureIsArrayOfElements(array $elements)
    {
        $result = [];

        foreach ($elements as $element) {
            if (!is_array($element) && !($element instanceof BaseUnsorted)) {
                $message = sprintf('Element "%s" is not an array and not unsorted', var_export($element, true));
                throw new Exception\InvalidArgumentException($message);
            }

            if ($element instanceof BaseUnsorted) {
                $result[] = $element->toAmo();
            } else {
                $result[] = $element;
            }
        }

        return $result;
    }
}
