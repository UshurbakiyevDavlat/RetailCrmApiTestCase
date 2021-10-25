<?php

/**
 * PHP version 7.3
 *
 * @category AttachmentCustomer
 * @package  RetailCrm\Api\Model\Entity\Files
 */

namespace RetailCrm\Api\Model\Entity\Files;

use RetailCrm\Api\Component\Serializer\Annotation as JMS;

/**
 * Class AttachmentCustomer
 *
 * @category AttachmentCustomer
 * @package  RetailCrm\Api\Model\Entity\Files
 */
class AttachmentCustomer
{
    /**
     * @var int
     *
     * @JMS\Type("int")
     * @JMS\SerializedName("id")
     */
    public $id;

    /**
     * @var string
     *
     * @JMS\Type("string")
     * @JMS\SerializedName("externalId")
     */
    public $externalId;

    /**
     * @var string
     *
     * @JMS\Type("string")
     * @JMS\SerializedName("site")
     */
    public $site;
}
