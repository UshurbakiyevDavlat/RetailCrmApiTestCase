<?php

/**
 * PHP version 7.3
 *
 * @category ReferencesTest
 * @package  RetailCrm\Tests\ResourceGroup
 */

namespace RetailCrm\Tests\ResourceGroup;

use RetailCrm\Api\Enum\CountryCodeIso3166;
use RetailCrm\Api\Enum\Reference\StoreInventoryType;
use RetailCrm\Api\Enum\Reference\StoreType;
use RetailCrm\Api\Enum\RequestMethod;
use RetailCrm\Api\Model\Callback\Entity\Delivery\SerializedStoreWeekOpeningHours;
use RetailCrm\Api\Model\Callback\Entity\Delivery\StoreWorkTime;
use RetailCrm\Api\Model\Entity\Orders\Delivery\CourierPhone;
use RetailCrm\Api\Model\Entity\References\CostGroup;
use RetailCrm\Api\Model\Entity\References\CostItem;
use RetailCrm\Api\Model\Entity\References\Courier;
use RetailCrm\Api\Model\Entity\References\DeliveryService;
use RetailCrm\Api\Model\Entity\References\DeliveryType;
use RetailCrm\Api\Model\Entity\References\LegalEntity;
use RetailCrm\Api\Model\Entity\References\OrderMethod;
use RetailCrm\Api\Model\Entity\References\OrderProductStatus;
use RetailCrm\Api\Model\Entity\References\OrderType;
use RetailCrm\Api\Model\Entity\References\PaymentStatus;
use RetailCrm\Api\Model\Entity\References\PaymentType;
use RetailCrm\Api\Model\Entity\References\PriceType;
use RetailCrm\Api\Model\Entity\References\SerializedUnit;
use RetailCrm\Api\Model\Entity\References\Site;
use RetailCrm\Api\Model\Entity\References\Status;
use RetailCrm\Api\Model\Entity\References\Store;
use RetailCrm\Api\Model\Entity\References\StoreAddress;
use RetailCrm\Api\Model\Entity\References\StorePhone;
use RetailCrm\Api\Model\Request\References\CostGroupsEditRequest;
use RetailCrm\Api\Model\Request\References\CostItemsEditRequest;
use RetailCrm\Api\Model\Request\References\CouriersCreateRequest;
use RetailCrm\Api\Model\Request\References\DeliveryServicesEditRequest;
use RetailCrm\Api\Model\Request\References\DeliveryTypesEditRequest;
use RetailCrm\Api\Model\Request\References\LegalEntityEditRequest;
use RetailCrm\Api\Model\Request\References\OrderMethodsEditRequest;
use RetailCrm\Api\Model\Request\References\OrderTypesEditRequest;
use RetailCrm\Api\Model\Request\References\PaymentStatusesEditRequest;
use RetailCrm\Api\Model\Request\References\PaymentTypesEditRequest;
use RetailCrm\Api\Model\Request\References\PriceTypesEditRequest;
use RetailCrm\Api\Model\Request\References\ProductStatusesEditRequest;
use RetailCrm\Api\Model\Request\References\SitesEditRequest;
use RetailCrm\Api\Model\Request\References\StatusesEditRequest;
use RetailCrm\Api\Model\Request\References\StoresEditRequest;
use RetailCrm\Api\Model\Request\References\UnitsEditRequest;
use RetailCrm\TestUtils\Factory\TestClientFactory;
use RetailCrm\TestUtils\TestCase\AbstractApiResourceGroupTestCase;

/**
 * Class ReferencesTest
 *
 * @category ReferencesTest
 * @package  RetailCrm\Tests\ResourceGroup
 */
class ReferencesTest extends AbstractApiResourceGroupTestCase
{
    public function testCostGroups(): void
    {
        $json = <<<'EOF'
{
  "success": true,
  "costGroups": [
    {
      "code": "commission",
      "name": "????????????????",
      "ordering": 60,
      "active": true,
      "color": "#ffd298"
    },
    {
      "code": "assembling",
      "name": "???????????????????????? ????????????",
      "ordering": 80,
      "active": true,
      "color": "#ef8e06"
    },
    {
      "code": "administrative",
      "name": "???????????????????????????????? ??????????????",
      "ordering": 70,
      "active": true,
      "color": "#bc6b01"
    },
    {
      "code": "delivery",
      "name": "????????????????",
      "ordering": 991,
      "active": true,
      "color": "#dd4040"
    },
    {
      "code": "attraction",
      "name": "?????????????? ???? ?????????????????????? ????????????????",
      "ordering": 50,
      "active": true,
      "color": "#0044b7"
    },
    {
      "code": "product-cost",
      "name": "?????????????????? ????????????",
      "ordering": 990,
      "active": true,
      "color": "#22C993"
    }
  ]
}
EOF;

        $mock = static::createApiMockBuilder('reference/cost-groups');
        $mock->matchMethod(RequestMethod::GET)
            ->reply(200)
            ->withBody($json);

        $client   = TestClientFactory::createClient($mock->getClient());
        $response = $client->references->costGroups();

        self::assertModelEqualsToResponse($json, $response);
    }
    public function testCostGroupsEdit(): void
    {
        $json = <<<'EOF'
{
  "success": true
}
EOF;

        $entity           = new CostGroup();
        $entity->name     = '????????????????';
        $entity->ordering = 60;
        $entity->active   = true;

        $request = new CostGroupsEditRequest($entity);

        $mock = static::createApiMockBuilder('reference/cost-groups/commission/edit');
        $mock->matchMethod(RequestMethod::POST)
            ->matchBody(self::encodeForm($request))
            ->reply(200)
            ->withBody($json);

        $client   = TestClientFactory::createClient($mock->getClient());
        $response = $client->references->costGroupsEdit('commission', $request);

        self::assertModelEqualsToResponse($json, $response);
    }

    public function testCostItems(): void
    {
        $json = <<<'EOF'
{
  "success": true,
  "costItems": [
    {
      "code": "products-purchase-price",
      "name": "???????????????????? ?????????????????? ??????????????",
      "group": "product-cost",
      "ordering": 990,
      "active": true,
      "appliesToOrders": true,
      "type": "var",
      "appliesToUsers": false
    },
    {
      "code": "delivery-cost",
      "name": "?????????????????? ????????????????",
      "group": "delivery",
      "ordering": 991,
      "active": true,
      "appliesToOrders": true,
      "type": "var",
      "appliesToUsers": false
    },
    {
      "code": "office-expenses",
      "name": "?????????????? ??????????????",
      "group": "administrative",
      "ordering": 10,
      "active": true,
      "appliesToOrders": false,
      "type": "const",
      "appliesToUsers": false
    },
    {
      "code": "payroll-fund",
      "name": "??????",
      "group": "administrative",
      "ordering": 20,
      "active": true,
      "appliesToOrders": false,
      "type": "const",
      "appliesToUsers": true
    },
    {
      "code": "employee-bonus",
      "name": "???????????? ??????????????????????",
      "group": "administrative",
      "ordering": 30,
      "active": true,
      "appliesToOrders": true,
      "type": "var",
      "appliesToUsers": true
    },
    {
      "code": "payment-systems-commission",
      "name": "???????????????? ?????????????????? ????????????",
      "group": "commission",
      "ordering": 40,
      "active": true,
      "appliesToOrders": true,
      "type": "var",
      "appliesToUsers": false
    },
    {
      "code": "commission-for-warehouse-services",
      "name": "???????????????? ???? ???????????? ????????????",
      "group": "assembling",
      "ordering": 50,
      "active": true,
      "appliesToOrders": true,
      "type": "var",
      "appliesToUsers": false
    },
    {
      "code": "product-moving",
      "name": "?????????? ???????????? ???? ????????????????????",
      "group": "assembling",
      "ordering": 60,
      "active": true,
      "appliesToOrders": true,
      "type": "var",
      "appliesToUsers": false
    },
    {
      "code": "packaging",
      "name": "????????????????",
      "group": "assembling",
      "ordering": 70,
      "active": true,
      "appliesToOrders": true,
      "type": "var",
      "appliesToUsers": false
    },
    {
      "code": "warehousing-services",
      "name": "?????????????????? ????????????????????????",
      "group": "assembling",
      "ordering": 80,
      "active": true,
      "appliesToOrders": true,
      "type": "const",
      "appliesToUsers": false
    },
    {
      "code": "seo",
      "name": "SEO",
      "group": "attraction",
      "ordering": 90,
      "active": true,
      "appliesToOrders": false,
      "type": "const",
      "appliesToUsers": false
    },
    {
      "code": "context-ad",
      "name": "?????????????????????? ??????????????",
      "group": "attraction",
      "ordering": 100,
      "active": true,
      "appliesToOrders": false,
      "type": "const",
      "appliesToUsers": false
    },
    {
      "code": "banner-ad",
      "name": "?????????????????? ??????????????",
      "group": "attraction",
      "ordering": 110,
      "active": true,
      "appliesToOrders": false,
      "type": "const",
      "appliesToUsers": false
    },
    {
      "code": "emailing",
      "name": "Email-????????????????",
      "group": "attraction",
      "ordering": 120,
      "active": true,
      "appliesToOrders": false,
      "type": "const",
      "appliesToUsers": false
    },
    {
      "code": "partnership",
      "name": "?????????????????????? ??????????????????",
      "group": "attraction",
      "ordering": 130,
      "active": true,
      "appliesToOrders": false,
      "type": "const",
      "appliesToUsers": false
    },
    {
      "code": "referrals",
      "name": "????????????????",
      "group": "attraction",
      "ordering": 140,
      "active": true,
      "appliesToOrders": false,
      "type": "const",
      "appliesToUsers": false
    },
    {
      "code": "social-media-ad",
      "name": "?????????????? ?? ????????????????",
      "group": "attraction",
      "ordering": 150,
      "active": true,
      "appliesToOrders": false,
      "type": "const",
      "appliesToUsers": false
    }
  ]
}
EOF;

        $mock = static::createApiMockBuilder('reference/cost-items');
        $mock->matchMethod(RequestMethod::GET)
            ->reply(200)
            ->withBody($json);

        $client   = TestClientFactory::createClient($mock->getClient());
        $response = $client->references->costItems();

        self::assertModelEqualsToResponse($json, $response);
    }

    public function testCostItemsEdit(): void
    {
        $json = <<<'EOF'
{
  "success": true
}
EOF;

        $entity                  = new CostItem();
        $entity->name            = "Test item";
        $entity->group           = "product-cost";
        $entity->ordering        = 990;
        $entity->active          = true;
        $entity->appliesToOrders = true;
        $entity->type            = "var";
        $entity->appliesToUsers  = false;

        $request = new CostItemsEditRequest($entity);

        $mock = static::createApiMockBuilder('reference/cost-items/test-item/edit');
        $mock->matchMethod(RequestMethod::POST)
            ->matchBody(self::encodeForm($request))
            ->reply(200)
            ->withBody($json);

        $client   = TestClientFactory::createClient($mock->getClient());
        $response = $client->references->costItemsEdit('test-item', $request);

        self::assertModelEqualsToResponse($json, $response);
    }

    public function testCountries(): void
    {
        $json = <<<'EOF'
{
  "success": true,
  "countriesIso": [
    "RU",
    "UA",
    "BY",
    "KZ",
    "AZ",
    "HU",
    "PL",
    "GB",
    "US",
    "FR",
    "ES"
  ]
}
EOF;

        $mock = static::createApiMockBuilder('reference/countries');
        $mock->matchMethod(RequestMethod::GET)
            ->reply(200)
            ->withBody($json);

        $client   = TestClientFactory::createClient($mock->getClient());
        $response = $client->references->countries();

        self::assertModelEqualsToResponse($json, $response);
    }

    public function testCouriers(): void
    {
        $json = <<<'EOF'
{
  "success": true,
  "couriers": [
    {
      "id": 3,
      "firstName": "??????????",
      "lastName": "????????????????",
      "active": true,
      "phone": {
        "number": "88005553535"
      }
    }
  ]
}
EOF;

        $mock = static::createApiMockBuilder('reference/couriers');
        $mock->matchMethod(RequestMethod::GET)
            ->reply(200)
            ->withBody($json);

        $client   = TestClientFactory::createClient($mock->getClient());
        $response = $client->references->couriers();

        self::assertModelEqualsToResponse($json, $response);
    }

    public function testCouriersCreate(): void
    {
        $json = <<<'EOF'
{
  "success": true,
  "id": 1
}
EOF;

        $entity            = new Courier();
        $entity->firstName = 'Tester';
        $entity->lastName  = 'Tester';
        $entity->phone     = new CourierPhone('88005553125');

        $request = new CouriersCreateRequest($entity);

        $mock = static::createApiMockBuilder('reference/couriers/create');
        $mock->matchMethod(RequestMethod::POST)
            ->matchBody(self::encodeForm($request))
            ->reply(200)
            ->withBody($json);

        $client   = TestClientFactory::createClient($mock->getClient());
        $response = $client->references->couriersCreate($request);

        self::assertModelEqualsToResponse($json, $response);
    }

    public function testCouriersEdit(): void
    {
        $json = <<<'EOF'
{
  "success": true
}
EOF;

        $entity            = new Courier();
        $entity->firstName = 'Tester';
        $entity->lastName  = 'Courier';
        $entity->phone     = new CourierPhone('88005553126');

        $request = new CouriersCreateRequest($entity);

        $mock = static::createApiMockBuilder('reference/couriers/4/edit');
        $mock->matchMethod(RequestMethod::POST)
            ->matchBody(self::encodeForm($request))
            ->reply(200)
            ->withBody($json);

        $client   = TestClientFactory::createClient($mock->getClient());
        $response = $client->references->couriersEdit(4, $request);

        self::assertModelEqualsToResponse($json, $response);
    }

    public function testDeliveryServices(): void
    {
        $json = <<<'EOF'
{
  "success": true,
  "deliveryServices": {
    "1": {
      "name": "???????????? ????????????",
      "code": "1",
      "active": true
    },
    "2": {
      "name": "Flat rate",
      "code": "2",
      "active": true
    },
    "3": {
      "name": "Free shipping",
      "code": "3",
      "active": true
    },
    "avia": {
      "name": "?????????????????? ??????????????????",
      "code": "avia",
      "active": true
    },
    "land": {
      "name": "???????????????? ??????????????????",
      "code": "land",
      "active": true
    },
    "pvz-out2": {
      "name": "pvpHern",
      "code": "pvz-out2",
      "active": true
    },
    "pvz-out3": {
      "name": "pvpHern",
      "code": "pvz-out3",
      "active": true
    },
    "dict-deliveryservices-1571123786": {
      "name": "Bbbdict-deliveryservices-1571123786",
      "code": "dict-deliveryservices-1571123786",
      "active": false
    },
    "dict-deliveryservices-1571123849": {
      "name": "Bbbdict-deliveryservices-1571123849",
      "code": "dict-deliveryservices-1571123849",
      "active": false
    },
    "dict-deliveryservices-1571124851": {
      "name": "Bbbdict-deliveryservices-1571124851",
      "code": "dict-deliveryservices-1571124851",
      "active": false
    },
    "dict-deliveryservices-1571124915": {
      "name": "Bbbdict-deliveryservices-1571124915",
      "code": "dict-deliveryservices-1571124915",
      "active": false
    },
    "dict-deliveryservices-1571134088": {
      "name": "Bbbdict-deliveryservices-1571134088",
      "code": "dict-deliveryservices-1571134088",
      "active": false
    },
    "dict-deliveryservices-1571134205": {
      "name": "Bbbdict-deliveryservices-1571134205",
      "code": "dict-deliveryservices-1571134205",
      "active": false
    },
    "dict-deliveryservices-1575878847": {
      "name": "Bbbdict-deliveryservices-1575878847",
      "code": "dict-deliveryservices-1575878847",
      "active": false
    },
    "dict-deliveryservices-1575878957": {
      "name": "Bbbdict-deliveryservices-1575878957",
      "code": "dict-deliveryservices-1575878957",
      "active": false
    },
    "dict-deliveryservices-1581413621": {
      "name": "Bbbdict-deliveryservices-1581413621",
      "code": "dict-deliveryservices-1581413621",
      "active": false
    },
    "dict-deliveryservices-1581413624": {
      "name": "Bbbdict-deliveryservices-1581413624",
      "code": "dict-deliveryservices-1581413624",
      "active": false
    },
    "dict-deliveryservices-1581413629": {
      "name": "Bbbdict-deliveryservices-1581413629",
      "code": "dict-deliveryservices-1581413629",
      "active": false
    },
    "dict-deliveryservices-1581413633": {
      "name": "Bbbdict-deliveryservices-1581413633",
      "code": "dict-deliveryservices-1581413633",
      "active": false
    },
    "dict-deliveryservices-1581413756": {
      "name": "Bbbdict-deliveryservices-1581413756",
      "code": "dict-deliveryservices-1581413756",
      "active": false
    },
    "dict-deliveryservices-1581413762": {
      "name": "Bbbdict-deliveryservices-1581413762",
      "code": "dict-deliveryservices-1581413762",
      "active": false
    },
    "dict-deliveryservices-1581413765": {
      "name": "Bbbdict-deliveryservices-1581413765",
      "code": "dict-deliveryservices-1581413765",
      "active": false
    },
    "dict-deliveryservices-1581413773": {
      "name": "Bbbdict-deliveryservices-1581413773",
      "code": "dict-deliveryservices-1581413773",
      "active": false
    }
  }
}
EOF;

        $mock = static::createApiMockBuilder('reference/delivery-services');
        $mock->matchMethod(RequestMethod::GET)
            ->reply(200)
            ->withBody($json);

        $client   = TestClientFactory::createClient($mock->getClient());
        $response = $client->references->deliveryServices();

        self::assertModelEqualsToResponse($json, $response);
    }

    public function testDeliveryServicesEdit(): void
    {
        $json = <<<'EOF'
{
  "success": true
}
EOF;

        $entity         = new DeliveryService();
        $entity->name   = 'dict-deliveryservices-1571123786';
        $entity->active = false;

        $request = new DeliveryServicesEditRequest($entity);

        $mock = static::createApiMockBuilder('reference/delivery-services/dict-deliveryservices-1571123786/edit');
        $mock->matchMethod(RequestMethod::POST)
            ->matchBody(self::encodeForm($request))
            ->reply(200)
            ->withBody($json);

        $client   = TestClientFactory::createClient($mock->getClient());
        $response = $client->references->deliveryServicesEdit('dict-deliveryservices-1571123786', $request);

        self::assertModelEqualsToResponse($json, $response);
    }

    public function testDeliveryTypes(): void
    {
        $json = <<<'EOF'
{
  "success": true,
  "deliveryTypes": {
    "2": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": false,
      "isAutoNetCostCalculation": false,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? ????????????????",
      "code": "2",
      "active": true,
      "defaultCost": 500,
      "defaultNetCost": 0,
      "description": "???????????????? ???????????????????????????? ?? ?????????????? ?????? ?? ?????????????? ?????? ?????? ??????????.",
      "paymentTypes": [
        "7",
        "bonuses-sl",
        "bank-card",
        "bank-transfer",
        "credit",
        "cash",
        "e-money"
      ],
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "3": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": false,
      "isAutoNetCostCalculation": false,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "??????????????????",
      "code": "3",
      "active": true,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "description": "???? ???????????? ???????????????????????????? ?????????????? ?????????? ???? ???????????? ????????????????.",
      "paymentTypes": [
        "12",
        "13",
        "14",
        "15",
        "16",
        "test-payment-integration"
      ],
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "8": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": false,
      "isAutoNetCostCalculation": false,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "??????????????????",
      "code": "8",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [
        "13",
        "14",
        "16",
        "test-payment-integration"
      ],
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "9": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": false,
      "isAutoNetCostCalculation": false,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "??????????????????",
      "code": "9",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [
        "13",
        "14",
        "16"
      ],
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "10": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": false,
      "isAutoNetCostCalculation": false,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "?????????????? ???????????????? ???? ??????????????????",
      "code": "10",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "11": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": false,
      "isAutoNetCostCalculation": false,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? ???? ??????????",
      "code": "11",
      "active": true,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [
        "13",
        "15"
      ],
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "12": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": false,
      "isAutoNetCostCalculation": false,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "????????????????",
      "code": "12",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "13": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": false,
      "isAutoNetCostCalculation": false,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "????????????",
      "code": "13",
      "active": true,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "15": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": false,
      "isAutoNetCostCalculation": false,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "????????????????",
      "code": "15",
      "active": true,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "16": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": false,
      "isAutoNetCostCalculation": false,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "?????????? ????????????",
      "code": "16",
      "active": true,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "description": "???????????????? ????????????  (???????????? ???? ?????????????????? ?????????????????? ????????????)",
      "paymentTypes": [
        "bank-card",
        "bank-transfer",
        "credit",
        "e-money"
      ],
      "deliveryServices": [
        "avia",
        "land"
      ],
      "defaultForCrm": false
    },
    "583103": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": false,
      "isAutoNetCostCalculation": false,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "?????????????????? InSales ????????",
      "code": "583103",
      "active": true,
      "defaultCost": 100,
      "defaultNetCost": 0,
      "description": "",
      "paymentTypes": [
        "442099",
        "442100",
        "555159",
        "555161",
        "776106",
        "818241",
        "897285",
        "423361"
      ],
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "651157": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": false,
      "isAutoNetCostCalculation": false,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "EMS ?????????? ???????????? InSales",
      "code": "651157",
      "active": true,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "description": "",
      "paymentTypes": [
        "423361",
        "442099",
        "442100",
        "555159",
        "555161",
        "818241",
        "897285"
      ],
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "651158": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": false,
      "isAutoNetCostCalculation": false,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????? ???????????????? InSales",
      "code": "651158",
      "active": true,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "description": "",
      "paymentTypes": [
        "423361",
        "442099",
        "442100",
        "555159",
        "555161",
        "818241",
        "897285"
      ],
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "1344511": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": false,
      "isAutoNetCostCalculation": false,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "????????????????",
      "code": "1344511",
      "active": true,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "description": "",
      "paymentTypes": [
        "818241",
        "897285"
      ],
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "1832810": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": false,
      "isAutoNetCostCalculation": false,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? ?? ?????????? ????????????????????",
      "code": "1832810",
      "active": true,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "description": "",
      "paymentTypes": [
        "423361",
        "442099",
        "442100",
        "555159",
        "555161",
        "776106",
        "818241",
        "897285"
      ],
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "1939924": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": false,
      "isAutoNetCostCalculation": false,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "Test",
      "code": "1939924",
      "active": true,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "description": "",
      "paymentTypes": [
        "897285"
      ],
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "1960337": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": false,
      "isAutoNetCostCalculation": false,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????? ?????????????????????????? ??????????????????",
      "code": "1960337",
      "active": true,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "description": "",
      "paymentTypes": [
        "423361",
        "442099",
        "442100",
        "555159",
        "555161",
        "776106",
        "818241",
        "897285"
      ],
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "1993015": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": false,
      "isAutoNetCostCalculation": false,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "????????1",
      "code": "1993015",
      "active": true,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "description": "",
      "paymentTypes": [
        "423361",
        "442099",
        "442100",
        "555159",
        "555161",
        "776106",
        "818241",
        "897285"
      ],
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "1994098": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": false,
      "isAutoNetCostCalculation": false,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????? ???????????????????????????? ????????????????",
      "code": "1994098",
      "active": true,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "description": "",
      "paymentTypes": [
        "423361",
        "442099",
        "442100",
        "555159",
        "555161",
        "776106",
        "818241",
        "897285"
      ],
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "2014666": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": false,
      "isAutoNetCostCalculation": false,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "????????????????",
      "code": "2014666",
      "active": true,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "description": "",
      "paymentTypes": [
        "423361",
        "442099",
        "442100",
        "555159",
        "555161",
        "776106",
        "818241",
        "897285"
      ],
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "2058866": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": false,
      "isAutoNetCostCalculation": false,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "?????????????????? ???? ?????????? \"?????????? ????????????\"",
      "code": "2058866",
      "active": true,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "description": "",
      "paymentTypes": [
        "555161"
      ],
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "2503367": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": false,
      "isAutoNetCostCalculation": false,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "67883",
      "code": "2503367",
      "active": true,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "description": "<p>?????????????? ?????????? ?? ???????? ?????????????????? ???? ??????????????????</p>",
      "paymentTypes": [
        "442099",
        "555159",
        "555161",
        "776106",
        "818241",
        "897285",
        "423361",
        "442100"
      ],
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "2508998": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": false,
      "isAutoNetCostCalculation": false,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "New: ???????????????? ?? ?????????? ????????????????????",
      "code": "2508998",
      "active": true,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "description": "",
      "paymentTypes": [
        "442099",
        "555159",
        "555161",
        "776106",
        "818241",
        "897285",
        "423361",
        "442100"
      ],
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "iml-test": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "IML-test",
      "code": "iml-test",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [
        "bank-card",
        "bank-transfer",
        "credit",
        "cash",
        "e-money"
      ],
      "integrationCode": "iml-1-5bbdbf91e3004",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "iml-1": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "iml-1",
      "code": "iml-1",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [
        "bank-card",
        "bank-transfer",
        "credit",
        "cash",
        "e-money"
      ],
      "integrationCode": "iml-7",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "boxberry-old": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "Boxberry old",
      "code": "boxberry-old",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [
        "bank-card",
        "bank-transfer",
        "credit",
        "cash",
        "e-money"
      ],
      "integrationCode": "boxberry-1-5bbdc1cb90c32",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "hernya": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "hernya",
      "code": "hernya",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "description": "hernya",
      "paymentTypes": [
        "bank-card",
        "bank-transfer",
        "credit",
        "cash",
        "e-money"
      ],
      "integrationCode": "courierist-1",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "russian-post-online-analitika": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "?????????? ???????????? (?????????????????? ????????????)",
      "code": "russian-post-online-analitika",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [
        "cash",
        "bank-card",
        "bank-transfer",
        "credit",
        "e-money"
      ],
      "integrationCode": "rs_russian_post",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "boxberry-old2": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "Boxberry old2",
      "code": "boxberry-old2",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [
        "bank-card",
        "bank-transfer",
        "credit",
        "cash",
        "e-money"
      ],
      "integrationCode": "boxberry-1-5bbdc1cb90c32",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "glavpunkt-test": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "??????????????????",
      "code": "glavpunkt-test",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "description": "???????????????? ???????????????? ??????????????????",
      "paymentTypes": [
        "bank-card",
        "bank-transfer",
        "credit",
        "e-money",
        "cash"
      ],
      "integrationCode": "ts-delivery-integration-glavpunkt",
      "deliveryServices": [

      ],
      "defaultForCrm": false,
      "vatRate": "20.00"
    },
    "boxberry-symf4-local-test1-id6": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "boxberry-symf4-local-test1-id6",
      "code": "boxberry-symf4-local-test1-id6",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "description": "boxberry-symf4-local-test1-id6",
      "paymentTypes": [
        "bank-card",
        "bank-transfer",
        "credit",
        "cash",
        "e-money"
      ],
      "integrationCode": "boxberry-6",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "boxberrys": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "BoxberryS",
      "code": "boxberrys",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "description": "BoxberryS",
      "paymentTypes": [
        "bank-card",
        "bank-transfer",
        "credit",
        "cash",
        "e-money"
      ],
      "integrationCode": "boxberry-6",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "dict-deliverytypes-1571123788": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": false,
      "isAutoNetCostCalculation": false,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "Bbbdict-deliverytypes-1571123788",
      "code": "dict-deliverytypes-1571123788",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "dict-deliverytypes-1575878849": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": false,
      "isAutoNetCostCalculation": false,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "Bbbdict-deliverytypes-1575878849",
      "code": "dict-deliverytypes-1575878849",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "test2": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": false,
      "isAutoNetCostCalculation": false,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "test",
      "code": "test2",
      "active": true,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [
        "bank-card",
        "bank-transfer",
        "credit",
        "e-money"
      ],
      "deliveryServices": [

      ],
      "defaultForCrm": false,
      "vatRate": "none"
    },
    "iml-1-integra": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "iml-1-integra",
      "code": "iml-1-integra",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "description": "iml-1-integra",
      "paymentTypes": [
        "bank-card",
        "bank-transfer",
        "credit",
        "cash",
        "e-money"
      ],
      "integrationCode": "iml-7",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "peshkariki": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "??????????????????",
      "code": "peshkariki",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [
        "bank-card",
        "bank-transfer",
        "credit",
        "cash",
        "e-money"
      ],
      "integrationCode": "rs_peshkariki",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "guru": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "Dostavka.GURU",
      "code": "guru",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [
        "bank-card",
        "bank-transfer",
        "credit",
        "cash",
        "e-money"
      ],
      "integrationCode": "ts-delivery-integration-dostavka-guru",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "iml-2-integra": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "iml-2-integra",
      "code": "iml-2-integra",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "description": "iml-2-integra",
      "paymentTypes": [
        "cash",
        "e-money"
      ],
      "integrationCode": "iml-8",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "dict-deliverytypes-1571123849": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": false,
      "isAutoNetCostCalculation": false,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "Bbbdict-deliverytypes-1571123849",
      "code": "dict-deliverytypes-1571123849",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "dict-deliverytypes-1571124853": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": false,
      "isAutoNetCostCalculation": false,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "Bbbdict-deliverytypes-1571124853",
      "code": "dict-deliverytypes-1571124853",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "dict-deliverytypes-1575878958": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": false,
      "isAutoNetCostCalculation": false,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "Bbbdict-deliverytypes-1575878958",
      "code": "dict-deliverytypes-1575878958",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "boxberrys-1": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "BoxberryS",
      "code": "boxberrys-1",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [
        "bank-card",
        "bank-transfer",
        "credit",
        "cash",
        "e-money"
      ],
      "integrationCode": "boxberry-17",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "iml": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": false,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "IML",
      "code": "iml",
      "active": true,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "integrationCode": "iml-1",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "dict-deliverytypes-1571134090": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": false,
      "isAutoNetCostCalculation": false,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "Bbbdict-deliverytypes-1571134090",
      "code": "dict-deliverytypes-1571134090",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "dict-deliverytypes-1571134207": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": false,
      "isAutoNetCostCalculation": false,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "Bbbdict-deliverytypes-1571134207",
      "code": "dict-deliverytypes-1571134207",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "dict-deliverytypes-1581413625": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": false,
      "isAutoNetCostCalculation": false,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "Bbbdict-deliverytypes-1581413625",
      "code": "dict-deliverytypes-1581413625",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "dict-deliverytypes-1581413628": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": false,
      "isAutoNetCostCalculation": false,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "Bbbdict-deliverytypes-1581413628",
      "code": "dict-deliverytypes-1581413628",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "ozon-seller-5e60fe6f05190": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": false,
      "isAutoNetCostCalculation": false,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? OZON",
      "code": "ozon-seller-5e60fe6f05190",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "description": "???????????????? OZON",
      "paymentTypes": [

      ],
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "dict-deliverytypes-1581413632": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": false,
      "isAutoNetCostCalculation": false,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "Bbbdict-deliverytypes-1581413632",
      "code": "dict-deliverytypes-1581413632",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "ems": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": false,
      "isAutoNetCostCalculation": false,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "EMS ?????????? ????????????",
      "code": "ems",
      "active": true,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [
        "bank-card",
        "bank-transfer",
        "e-money",
        "credit"
      ],
      "deliveryServices": [
        "2"
      ],
      "defaultForCrm": false
    },
    "dict-deliverytypes-1581413636": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": false,
      "isAutoNetCostCalculation": false,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "Bbbdict-deliverytypes-1581413636",
      "code": "dict-deliverytypes-1581413636",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "boxberry": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": false,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "Boxberry-249",
      "code": "boxberry",
      "active": true,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "integrationCode": "boxberry-249",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "dict-deliverytypes-1581413758": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": false,
      "isAutoNetCostCalculation": false,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "Bbbdict-deliverytypes-1581413758",
      "code": "dict-deliverytypes-1581413758",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "dict-deliverytypes-1581413764": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": false,
      "isAutoNetCostCalculation": false,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "Bbbdict-deliverytypes-1581413764",
      "code": "dict-deliverytypes-1581413764",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "dict-deliverytypes-1581413767": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": false,
      "isAutoNetCostCalculation": false,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "Bbbdict-deliverytypes-1581413767",
      "code": "dict-deliverytypes-1581413767",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "dict-deliverytypes-1581413776": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": false,
      "isAutoNetCostCalculation": false,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "Bbbdict-deliverytypes-1581413776",
      "code": "dict-deliverytypes-1581413776",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "easyway": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "easyway",
      "code": "easyway",
      "active": true,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [
        "bank-card",
        "bank-transfer",
        "credit",
        "cash",
        "e-money"
      ],
      "integrationCode": "easyway",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "goods-order-packing": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "?????????? ?????????? Goods",
      "code": "goods-order-packing",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [
        "bank-card",
        "bank-transfer",
        "credit",
        "cash",
        "e-money"
      ],
      "integrationCode": "goods",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "test": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": false,
      "isAutoNetCostCalculation": false,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "Test",
      "code": "test",
      "active": true,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [
        "bank-card"
      ],
      "deliveryServices": [

      ],
      "defaultForCrm": false,
      "vatRate": "18.00"
    },
    "ozon-seller-5f2432a06a903": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": false,
      "isAutoNetCostCalculation": false,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? OZON",
      "code": "ozon-seller-5f2432a06a903",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "description": "???????????????? OZON",
      "paymentTypes": [
        "dict-paymenttypes-1571123795",
        "dict-paymenttypes-1575878858",
        "dict-paymenttypes-1571123853",
        "dict-paymenttypes-1571124860",
        "dict-paymenttypes-1575878963",
        "dict-paymenttypes-1571124919",
        "dict-paymenttypes-1571134100",
        "dict-paymenttypes-1571134212",
        "dict-paymenttypes-1581413638",
        "dict-paymenttypes-1581413641",
        "dict-paymenttypes-1581413645",
        "dict-paymenttypes-1581413649",
        "dict-paymenttypes-1581413768",
        "dict-paymenttypes-1581413773",
        "dict-paymenttypes-1581413777",
        "dict-paymenttypes-1581413785",
        "cash",
        "bank-card",
        "e-money",
        "bank-transfer",
        "credit",
        "bonuses-sl",
        "7"
      ],
      "integrationCode": "ozon-seller-5f2432a06a903",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "ozon-seller-5f2bc923ebb33": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": false,
      "isAutoNetCostCalculation": false,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? OZON",
      "code": "ozon-seller-5f2bc923ebb33",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "description": "???????????????? OZON",
      "paymentTypes": [

      ],
      "integrationCode": "ozon-seller-5f2bc923ebb33",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "ozon-seller-5f689f4509b2f": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": false,
      "isAutoNetCostCalculation": false,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? OZON",
      "code": "ozon-seller-5f689f4509b2f",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "description": "???????????????? OZON",
      "paymentTypes": [
        "cash",
        "bank-card",
        "e-money",
        "bank-transfer",
        "credit",
        "bonuses-sl",
        "dict-paymenttypes-1571123795",
        "dict-paymenttypes-1575878858",
        "dict-paymenttypes-1571123853",
        "dict-paymenttypes-1571124860",
        "dict-paymenttypes-1575878963",
        "dict-paymenttypes-1571124919",
        "dict-paymenttypes-1571134100",
        "dict-paymenttypes-1571134212",
        "7",
        "dict-paymenttypes-1581413638",
        "dict-paymenttypes-1581413641",
        "dict-paymenttypes-1581413645",
        "dict-paymenttypes-1581413649",
        "dict-paymenttypes-1581413768",
        "dict-paymenttypes-1581413773",
        "dict-paymenttypes-1581413777",
        "dict-paymenttypes-1581413785",
        "423361",
        "442099",
        "555159",
        "555161",
        "776106",
        "818241",
        "897285",
        "442100"
      ],
      "integrationCode": "ozon-seller-5f689f4509b2f",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "ozon-seller-228": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": false,
      "isAutoNetCostCalculation": false,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? OZON",
      "code": "ozon-seller-228",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "description": "???????????????? OZON",
      "paymentTypes": [
        "e-money",
        "555161",
        "555159",
        "776106",
        "442099",
        "442100",
        "423361",
        "cash",
        "818241",
        "credit",
        "bank-transfer",
        "897285",
        "bank-card",
        "bonuses-sl",
        "7"
      ],
      "integrationCode": "ozon-seller-5f22c13b25e4d",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "dw-courierist": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "dw_courierist",
      "code": "dw-courierist",
      "active": false,
      "defaultCost": 200,
      "defaultNetCost": 0,
      "paymentTypes": [
        "bank-card",
        "bank-transfer",
        "credit",
        "cash",
        "e-money"
      ],
      "integrationCode": "courierist-2-5f771025370b2",
      "deliveryServices": [

      ],
      "defaultForCrm": false,
      "vatRate": "none"
    },
    "dw-courierist123": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "dw_courierist123",
      "code": "dw-courierist123",
      "active": false,
      "defaultCost": 200,
      "defaultNetCost": 200,
      "paymentTypes": [
        "bank-card",
        "bank-transfer",
        "credit",
        "cash",
        "e-money"
      ],
      "integrationCode": "courierist-4-5f77202ca9d60",
      "deliveryServices": [

      ],
      "defaultForCrm": false,
      "vatRate": "none"
    },
    "courierist1": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "?????????????????? ????????????????",
      "code": "courierist1",
      "active": false,
      "defaultCost": 220,
      "defaultNetCost": 220,
      "paymentTypes": [

      ],
      "integrationCode": "courierist-6-5f772ad6d0b48",
      "deliveryServices": [

      ],
      "defaultForCrm": false,
      "vatRate": "none"
    },
    "dw-courierist1231212": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "?????????????????? ???????????????? 111",
      "code": "dw-courierist1231212",
      "active": false,
      "defaultCost": 11,
      "defaultNetCost": 11,
      "paymentTypes": [
        "bank-card",
        "bank-transfer",
        "credit",
        "cash",
        "e-money"
      ],
      "integrationCode": "courierist-7-5f7738a177805",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "cour": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "??????????????????",
      "code": "cour",
      "active": false,
      "defaultCost": 22,
      "defaultNetCost": 22,
      "paymentTypes": [
        "bank-card",
        "bank-transfer",
        "credit",
        "cash",
        "e-money"
      ],
      "integrationCode": "courierist-6-5f7ae74ae5fa4",
      "deliveryServices": [

      ],
      "defaultForCrm": false,
      "vatRate": "none"
    },
    "apiship": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "ApiShip",
      "code": "apiship",
      "active": true,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [
        "bank-card",
        "cash"
      ],
      "integrationCode": "apiship",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "dwboxberry": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": false,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "dw boxberry",
      "code": "dwboxberry",
      "active": true,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [
        "cash",
        "e-money",
        "credit",
        "bank-transfer",
        "bank-card"
      ],
      "integrationCode": "boxberry-1-5f97ee0d246e3",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "ozon-seller-5f9bfac6a9855": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": false,
      "isAutoNetCostCalculation": false,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? OZON",
      "code": "ozon-seller-5f9bfac6a9855",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "description": "???????????????? OZON",
      "paymentTypes": [
        "cash",
        "bank-card",
        "e-money",
        "bank-transfer",
        "credit",
        "bonuses-sl",
        "dict-paymenttypes-1571123795",
        "dict-paymenttypes-1575878858",
        "dict-paymenttypes-1571123853",
        "dict-paymenttypes-1571124860",
        "dict-paymenttypes-1575878963",
        "dict-paymenttypes-1571124919",
        "dict-paymenttypes-1571134100",
        "dict-paymenttypes-1571134212",
        "7",
        "dict-paymenttypes-1581413638",
        "dict-paymenttypes-1581413641",
        "dict-paymenttypes-1581413645",
        "dict-paymenttypes-1581413649",
        "dict-paymenttypes-1581413768",
        "dict-paymenttypes-1581413773",
        "dict-paymenttypes-1581413777",
        "dict-paymenttypes-1581413785",
        "442099",
        "555159",
        "555161",
        "776106",
        "818241",
        "897285",
        "442100",
        "423361"
      ],
      "integrationCode": "ozon-seller-5f9bfac6a9855",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "ozon-seller-5f8fe602d193b": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": false,
      "isAutoNetCostCalculation": false,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? OZON",
      "code": "ozon-seller-5f8fe602d193b",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "description": "???????????????? OZON",
      "paymentTypes": [
        "cash",
        "bank-card",
        "e-money",
        "bank-transfer",
        "credit",
        "bonuses-sl",
        "dict-paymenttypes-1571123795",
        "dict-paymenttypes-1575878858",
        "dict-paymenttypes-1571123853",
        "dict-paymenttypes-1571124860",
        "dict-paymenttypes-1575878963",
        "dict-paymenttypes-1571124919",
        "dict-paymenttypes-1571134100",
        "dict-paymenttypes-1571134212",
        "7",
        "dict-paymenttypes-1581413638",
        "dict-paymenttypes-1581413641",
        "dict-paymenttypes-1581413645",
        "dict-paymenttypes-1581413649",
        "dict-paymenttypes-1581413768",
        "dict-paymenttypes-1581413773",
        "dict-paymenttypes-1581413777",
        "dict-paymenttypes-1581413785",
        "442099",
        "555159",
        "555161",
        "776106",
        "818241",
        "897285",
        "442100",
        "423361"
      ],
      "integrationCode": "ozon-seller-5f8fe602d193b",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "delivery-callback-check": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": false,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "delivery-callback-check",
      "code": "delivery-callback-check",
      "active": true,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "description": "delivery-callback-check\r\n\"baseUrl\":\"https://webhook.site/87b71464-b6e8-4e39-8c98-9519fc82aa44\"\r\n\"actions\":{\"shipmentPointList\":\"?action=shipmentPointList\",\"calculate\":\"?action=calculate\",\"save\":\"?action=save\",\"delete\":\"?action=delete\",\"print\":\"?action=print\"}",
      "paymentTypes": [
        "bank-card",
        "bank-transfer",
        "credit",
        "cash",
        "e-money",
        "7",
        "bonuses-sl",
        "897285",
        "818241",
        "423361",
        "442100",
        "442099",
        "776106",
        "555159",
        "555161"
      ],
      "integrationCode": "delivery-callback-check",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "ozon-seller": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": false,
      "isAutoNetCostCalculation": false,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? OZON",
      "code": "ozon-seller",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "description": "???????????????? OZON 5f4766d858f78",
      "paymentTypes": [
        "cash",
        "bank-card",
        "e-money",
        "bank-transfer",
        "credit",
        "bonuses-sl",
        "dict-paymenttypes-1571123795",
        "dict-paymenttypes-1575878858",
        "dict-paymenttypes-1571123853",
        "dict-paymenttypes-1571124860",
        "dict-paymenttypes-1575878963",
        "dict-paymenttypes-1571124919",
        "dict-paymenttypes-1571134100",
        "dict-paymenttypes-1571134212",
        "7",
        "dict-paymenttypes-1581413638",
        "dict-paymenttypes-1581413641",
        "dict-paymenttypes-1581413645",
        "dict-paymenttypes-1581413649",
        "dict-paymenttypes-1581413768",
        "dict-paymenttypes-1581413773",
        "dict-paymenttypes-1581413777",
        "dict-paymenttypes-1581413785",
        "423361",
        "442099",
        "442100",
        "555159",
        "555161",
        "776106",
        "818241",
        "897285"
      ],
      "integrationCode": "ozon-seller-5f4766d858f78",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "ozon-seller-5f6affbcb5617": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": false,
      "isAutoNetCostCalculation": false,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? OZON",
      "code": "ozon-seller-5f6affbcb5617",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "description": "???????????????? OZON",
      "paymentTypes": [
        "cash",
        "bank-card",
        "e-money",
        "bank-transfer",
        "credit",
        "bonuses-sl",
        "dict-paymenttypes-1571123795",
        "dict-paymenttypes-1575878858",
        "dict-paymenttypes-1571123853",
        "dict-paymenttypes-1571124860",
        "dict-paymenttypes-1575878963",
        "dict-paymenttypes-1571124919",
        "dict-paymenttypes-1571134100",
        "dict-paymenttypes-1571134212",
        "7",
        "dict-paymenttypes-1581413638",
        "dict-paymenttypes-1581413641",
        "dict-paymenttypes-1581413645",
        "dict-paymenttypes-1581413649",
        "dict-paymenttypes-1581413768",
        "dict-paymenttypes-1581413773",
        "dict-paymenttypes-1581413777",
        "dict-paymenttypes-1581413785",
        "423361",
        "442099",
        "555159",
        "555161",
        "776106",
        "818241",
        "897285",
        "442100"
      ],
      "integrationCode": "ozon-seller-5f6affbcb5617",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "dw-boxberry": {
      "isDynamicCostCalculation": true,
      "isAutoCostCalculation": false,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "dw_boxberry",
      "code": "dw-boxberry",
      "active": true,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [
        "bank-card",
        "bank-transfer",
        "credit",
        "cash",
        "e-money"
      ],
      "integrationCode": "boxberry-1-5f97ee0d246e3",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "self-delivery": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": false,
      "isAutoNetCostCalculation": false,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "?????????????????? ??????-20",
      "code": "self-delivery",
      "active": true,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [
        "bank-card",
        "bank-transfer",
        "e-money",
        "credit",
        "cash",
        "alfa-bank-payment",
        "checkbox-payment",
        "checkbox-payment-two",
        "invoice-payment"
      ],
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "russian-post": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": false,
      "isAutoNetCostCalculation": false,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "?????????? ????????????",
      "code": "russian-post",
      "active": true,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [
        "bank-card",
        "bank-transfer",
        "e-money",
        "credit",
        "alfa-bank-payment",
        "checkbox-payment",
        "checkbox-payment-two",
        "invoice-payment"
      ],
      "deliveryServices": [
        "3"
      ],
      "defaultForCrm": false
    },
    "dhl": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": false,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "DHL",
      "code": "dhl",
      "active": true,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [
        "bank-card",
        "bank-transfer",
        "credit",
        "cash",
        "e-money"
      ],
      "integrationCode": "dhl-5bbde05af3c11",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "ozon-seller-2": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": false,
      "isAutoNetCostCalculation": false,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? OZON",
      "code": "ozon-seller-2",
      "active": true,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "description": "???????????????? OZON",
      "paymentTypes": [
        "cash",
        "bank-card",
        "e-money",
        "bank-transfer",
        "credit",
        "bonuses-sl",
        "dict-paymenttypes-1571123795",
        "dict-paymenttypes-1575878858",
        "dict-paymenttypes-1571123853",
        "dict-paymenttypes-1571124860",
        "dict-paymenttypes-1575878963",
        "dict-paymenttypes-1571124919",
        "dict-paymenttypes-1571134100",
        "dict-paymenttypes-1571134212",
        "7",
        "dict-paymenttypes-1581413638",
        "dict-paymenttypes-1581413641",
        "dict-paymenttypes-1581413645",
        "dict-paymenttypes-1581413649",
        "dict-paymenttypes-1581413768",
        "dict-paymenttypes-1581413773",
        "dict-paymenttypes-1581413777",
        "dict-paymenttypes-1581413785",
        "423361",
        "442099",
        "555159",
        "555161",
        "776106",
        "818241",
        "897285",
        "442100",
        "test-mc-payment",
        "test-mc-cashin",
        "test-mc-prepayment",
        "12",
        "13",
        "18",
        "14",
        "19",
        "15",
        "16",
        "17",
        "20",
        "test-payment-integration",
        "invoice-payment",
        "alfa-bank-payment",
        "checkbox-payment",
        "checkbox-payment-two",
        "wallete-one-payment-vog-old"
      ],
      "integrationCode": "ozon-seller-2",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "ozon-seller-3": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": false,
      "isAutoNetCostCalculation": false,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? OZON",
      "code": "ozon-seller-3",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "description": "???????????????? OZON",
      "paymentTypes": [
        "cash",
        "bank-card",
        "e-money",
        "bank-transfer",
        "credit",
        "bonuses-sl",
        "dict-paymenttypes-1571123795",
        "dict-paymenttypes-1575878858",
        "dict-paymenttypes-1571123853",
        "dict-paymenttypes-1571124860",
        "dict-paymenttypes-1575878963",
        "dict-paymenttypes-1571124919",
        "dict-paymenttypes-1571134100",
        "dict-paymenttypes-1571134212",
        "7",
        "dict-paymenttypes-1581413638",
        "dict-paymenttypes-1581413641",
        "dict-paymenttypes-1581413645",
        "dict-paymenttypes-1581413649",
        "dict-paymenttypes-1581413768",
        "dict-paymenttypes-1581413773",
        "dict-paymenttypes-1581413777",
        "dict-paymenttypes-1581413785",
        "442099",
        "555159",
        "555161",
        "776106",
        "818241",
        "897285",
        "442100",
        "423361"
      ],
      "integrationCode": "ozon-seller-3",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "ozon-seller-5f9d10257668e": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": false,
      "isAutoNetCostCalculation": false,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? OZON",
      "code": "ozon-seller-5f9d10257668e",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "description": "???????????????? OZON",
      "paymentTypes": [
        "cash",
        "bank-card",
        "e-money",
        "bank-transfer",
        "credit",
        "bonuses-sl",
        "dict-paymenttypes-1571123795",
        "dict-paymenttypes-1575878858",
        "dict-paymenttypes-1571123853",
        "dict-paymenttypes-1571124860",
        "dict-paymenttypes-1575878963",
        "dict-paymenttypes-1571124919",
        "dict-paymenttypes-1571134100",
        "dict-paymenttypes-1571134212",
        "7",
        "dict-paymenttypes-1581413638",
        "dict-paymenttypes-1581413641",
        "dict-paymenttypes-1581413645",
        "dict-paymenttypes-1581413649",
        "dict-paymenttypes-1581413768",
        "dict-paymenttypes-1581413773",
        "dict-paymenttypes-1581413777",
        "dict-paymenttypes-1581413785",
        "442099",
        "555159",
        "555161",
        "776106",
        "818241",
        "897285",
        "442100",
        "423361"
      ],
      "integrationCode": "ozon-seller-5f9d10257668e",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "servientrega-test": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": false,
      "isAutoNetCostCalculation": false,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "Servientrega test",
      "code": "servientrega-test",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [
        "bank-card",
        "bank-transfer",
        "credit",
        "e-money",
        "cash",
        "7",
        "bonuses-sl",
        "897285",
        "818241",
        "423361",
        "442100",
        "442099",
        "776106",
        "555159",
        "555161"
      ],
      "integrationCode": "servientrega-1",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "yandex-beru-delivery": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? ????????",
      "code": "yandex-beru-delivery",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "integrationCode": "yandex_beru",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "yandex-beru-delivery-1": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? ????????",
      "code": "yandex-beru-delivery-1",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "integrationCode": "yandex_beru",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "yandex-beru-delivery-2": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? ????????",
      "code": "yandex-beru-delivery-2",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "integrationCode": "yandex_beru",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "yandex-beru-delivery-3": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? ????????",
      "code": "yandex-beru-delivery-3",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "integrationCode": "yandex_beru",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "yandex-beru-delivery-4": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? ????????",
      "code": "yandex-beru-delivery-4",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "integrationCode": "yandex_beru",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "yandex-beru-delivery-5": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? ????????",
      "code": "yandex-beru-delivery-5",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "integrationCode": "yandex_beru",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "yandex-beru-delivery-6": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? ????????",
      "code": "yandex-beru-delivery-6",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "integrationCode": "yandex_beru",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "yandex-beru-delivery-7": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? ????????",
      "code": "yandex-beru-delivery-7",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "integrationCode": "yandex_beru",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "yandex-beru-delivery-8": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? ????????",
      "code": "yandex-beru-delivery-8",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "integrationCode": "yandex_beru",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "yandex-beru-delivery-9": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? ????????",
      "code": "yandex-beru-delivery-9",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "integrationCode": "yandex_beru",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "yandex-beru-delivery-10": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? ????????",
      "code": "yandex-beru-delivery-10",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "integrationCode": "yandex_beru",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "yandex-beru-delivery-11": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? ????????",
      "code": "yandex-beru-delivery-11",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "integrationCode": "yandex_beru",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "yandex-beru-delivery-12": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? ????????",
      "code": "yandex-beru-delivery-12",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "integrationCode": "yandex_beru",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "yandex-beru-delivery-14": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? ????????",
      "code": "yandex-beru-delivery-14",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "integrationCode": "yandex_beru",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "yandex-beru-delivery-13": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? ????????",
      "code": "yandex-beru-delivery-13",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "integrationCode": "yandex_beru",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "yandex-beru-delivery-15": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? ????????",
      "code": "yandex-beru-delivery-15",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "integrationCode": "yandex_beru",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "yandex-beru-delivery-16": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? ????????",
      "code": "yandex-beru-delivery-16",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "integrationCode": "yandex_beru",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "yandex-beru-delivery-17": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? ????????",
      "code": "yandex-beru-delivery-17",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "integrationCode": "yandex_beru",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "yandex-beru-delivery-18": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? ????????",
      "code": "yandex-beru-delivery-18",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "integrationCode": "yandex_beru",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "yandex-beru-delivery-19": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? ????????",
      "code": "yandex-beru-delivery-19",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "integrationCode": "yandex_beru",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "yandex-beru-delivery-20": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? ????????",
      "code": "yandex-beru-delivery-20",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "integrationCode": "yandex_beru",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "yandex-beru-delivery-21": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? ????????",
      "code": "yandex-beru-delivery-21",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "integrationCode": "yandex_beru",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "yandex-beru-delivery-22": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? ????????",
      "code": "yandex-beru-delivery-22",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "integrationCode": "yandex_beru",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "yandex-beru-delivery-23": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? ????????",
      "code": "yandex-beru-delivery-23",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "integrationCode": "yandex_beru",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "yandex-beru-delivery-28": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? ????????",
      "code": "yandex-beru-delivery-28",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "integrationCode": "yandex_beru",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "yandex-beru-delivery-24": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? ????????",
      "code": "yandex-beru-delivery-24",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "integrationCode": "yandex_beru",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "yandex-beru-delivery-25": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? ????????",
      "code": "yandex-beru-delivery-25",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "integrationCode": "yandex_beru",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "yandex-beru-delivery-29": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? ????????",
      "code": "yandex-beru-delivery-29",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "integrationCode": "yandex_beru",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "yandex-beru-delivery-32": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? ????????",
      "code": "yandex-beru-delivery-32",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "integrationCode": "yandex_beru",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "yandex-beru-delivery-26": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? ????????",
      "code": "yandex-beru-delivery-26",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "integrationCode": "yandex_beru",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "yandex-beru-delivery-27": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? ????????",
      "code": "yandex-beru-delivery-27",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "integrationCode": "yandex_beru",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "yandex-beru-delivery-30": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? ????????",
      "code": "yandex-beru-delivery-30",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "integrationCode": "yandex_beru",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "yandex-beru-delivery-31": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? ????????",
      "code": "yandex-beru-delivery-31",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "integrationCode": "yandex_beru",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "yandex-beru-delivery-33": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? ????????",
      "code": "yandex-beru-delivery-33",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "integrationCode": "yandex_beru",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "yandex-beru-delivery-34": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? ????????",
      "code": "yandex-beru-delivery-34",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "integrationCode": "yandex_beru",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "serv-test": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": false,
      "isAutoNetCostCalculation": false,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "serv test",
      "code": "serv-test",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [
        "7",
        "bonuses-sl",
        "bank-card",
        "897285",
        "bank-transfer",
        "credit",
        "818241",
        "cash",
        "423361",
        "442100",
        "442099",
        "776106",
        "555159",
        "555161",
        "e-money"
      ],
      "integrationCode": "servientrega-1",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "boxberry-ma": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "Boxberry Test MA",
      "code": "boxberry-ma",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [
        "bank-card",
        "bank-transfer",
        "credit",
        "cash",
        "e-money"
      ],
      "integrationCode": "boxberry-1-5fd86cedab879",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "boxberry-fake": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "Boxberry Fake",
      "code": "boxberry-fake",
      "active": true,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [
        "bank-card",
        "bank-transfer",
        "credit",
        "cash",
        "e-money"
      ],
      "integrationCode": "boxberry-2-5fe1ee565a534",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "boxberry-fake2": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "Boxberry Fake",
      "code": "boxberry-fake2",
      "active": true,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [
        "bank-card",
        "bank-transfer",
        "credit",
        "cash",
        "e-money"
      ],
      "integrationCode": "boxberry-4-5fe1f01c5469c",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "boxberry-ma-2": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "Boxberry Test MA",
      "code": "boxberry-ma-2",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [
        "bank-card",
        "bank-transfer",
        "credit",
        "cash",
        "test-mc-payment",
        "test-mc-prepayment",
        "test-mc-cashin",
        "e-money"
      ],
      "integrationCode": "boxberry-2-5fd8719995974",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "boxberry-delivery-10": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "Boxberry Test MA",
      "code": "boxberry-delivery-10",
      "active": true,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [
        "bank-card",
        "bank-transfer",
        "credit",
        "cash",
        "e-money"
      ],
      "integrationCode": "boxberry-10",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "boxt": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "boxt",
      "code": "boxt",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "integrationCode": "boxberry-8",
      "deliveryServices": [

      ],
      "defaultForCrm": false,
      "vatRate": "none"
    },
    "boxt2": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": false,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "boxt2",
      "code": "boxt2",
      "active": true,
      "defaultCost": 50,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "integrationCode": "boxberry-9",
      "deliveryServices": [

      ],
      "defaultForCrm": false,
      "vatRate": "none"
    },
    "boxberry-throwaway-12": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "Boxberry Throwaway",
      "code": "boxberry-throwaway-12",
      "active": true,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [
        "bank-card",
        "bank-transfer",
        "credit",
        "cash",
        "e-money"
      ],
      "integrationCode": "boxberry-12",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "dict-deliverytypes-1571124916": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": false,
      "isAutoNetCostCalculation": false,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "Test Type",
      "code": "dict-deliverytypes-1571124916",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [
        "cash",
        "bank-card",
        "e-money",
        "bank-transfer",
        "credit"
      ],
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "boxberry-9": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": false,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "boxberry-9",
      "code": "boxberry-9",
      "active": true,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "integrationCode": "boxberry-9",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "test-cour": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? ??????????????????",
      "code": "test-cour",
      "active": false,
      "defaultCost": 22,
      "defaultNetCost": 22,
      "paymentTypes": [
        "bank-card",
        "bank-transfer",
        "credit",
        "cash",
        "e-money"
      ],
      "integrationCode": "courierist-6-5f7ae74ae5fa4",
      "deliveryServices": [

      ],
      "defaultForCrm": false,
      "vatRate": "20.00"
    },
    "courierist-new-test": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "courierist-Pekar",
      "code": "courierist-new-test",
      "active": true,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "integrationCode": "courierist-10",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "courier-internal": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": false,
      "isAutoNetCostCalculation": false,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? (???????? ??????????????)",
      "code": "courier-internal",
      "active": true,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [
        "bank-card",
        "cash"
      ],
      "integrationCode": "courier",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "test-ma": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? ????",
      "code": "test-ma",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [
        "bank-card",
        "cash",
        "bank-transfer"
      ],
      "integrationCode": "test-integration_70156",
      "deliveryServices": [

      ],
      "defaultForCrm": false,
      "vatRate": "20.00"
    },
    "iml-double-156": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "IML-double-156",
      "code": "iml-double-156",
      "active": true,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "integrationCode": "iml-156",
      "deliveryServices": [

      ],
      "defaultForCrm": false,
      "vatRate": "20.00"
    },
    "sdek": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "????????",
      "code": "sdek",
      "active": true,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [
        "bank-card",
        "bank-transfer",
        "credit",
        "cash",
        "e-money"
      ],
      "integrationCode": "sdek",
      "deliveryServices": [

      ],
      "defaultForCrm": false,
      "defaultTariffCode": "139",
      "defaultTariffType": "courier",
      "defaultTariffName": "?????????????? ??????????-??????????"
    },
    "yandex-beru-delivery-35": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? ????????",
      "code": "yandex-beru-delivery-35",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "integrationCode": "yandex_beru",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "yandex-beru-delivery-36": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? ????????",
      "code": "yandex-beru-delivery-36",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "integrationCode": "yandex_beru",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "yandex-beru-delivery-39": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? ????????",
      "code": "yandex-beru-delivery-39",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "integrationCode": "yandex_beru",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "yandex-beru-delivery-37": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? ????????",
      "code": "yandex-beru-delivery-37",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "integrationCode": "yandex_beru",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "yandex-beru-delivery-38": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? ????????",
      "code": "yandex-beru-delivery-38",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "integrationCode": "yandex_beru",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "yandex-beru-delivery-40": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? ????????",
      "code": "yandex-beru-delivery-40",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "integrationCode": "yandex_beru",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "yandex-beru-delivery-41": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? ????????",
      "code": "yandex-beru-delivery-41",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "integrationCode": "yandex_beru",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "yandex-beru-delivery-42": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? ????????",
      "code": "yandex-beru-delivery-42",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "integrationCode": "yandex_beru",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "yandex-beru-delivery-43": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? ????????",
      "code": "yandex-beru-delivery-43",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "integrationCode": "yandex_beru",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "yandex-beru-delivery-44": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? ????????",
      "code": "yandex-beru-delivery-44",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "integrationCode": "yandex_beru",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "yandex-beru-delivery-45": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? ????????",
      "code": "yandex-beru-delivery-45",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "integrationCode": "yandex_beru",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "yandex-beru-delivery-46": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? ????????",
      "code": "yandex-beru-delivery-46",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "integrationCode": "yandex_beru",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "yandex-beru-delivery-47": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? ????????",
      "code": "yandex-beru-delivery-47",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "integrationCode": "yandex_beru",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "yandex-beru-delivery-48": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? ????????",
      "code": "yandex-beru-delivery-48",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "integrationCode": "yandex_beru",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "yandex-beru-delivery-49": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? ????????",
      "code": "yandex-beru-delivery-49",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "integrationCode": "yandex_beru",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "yandex-beru-delivery-50": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? ????????",
      "code": "yandex-beru-delivery-50",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "integrationCode": "yandex_beru",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "yandex-beru-delivery-51": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? ????????",
      "code": "yandex-beru-delivery-51",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "integrationCode": "yandex_beru",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "ozon-seller-5ff57fbceb6b2": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": false,
      "isAutoNetCostCalculation": false,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? OZON",
      "code": "ozon-seller-5ff57fbceb6b2",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "description": "???????????????? OZON",
      "paymentTypes": [
        "cash",
        "bank-card",
        "e-money",
        "bank-transfer",
        "credit",
        "bonuses-sl",
        "dict-paymenttypes-1571123795",
        "dict-paymenttypes-1575878858",
        "dict-paymenttypes-1571123853",
        "dict-paymenttypes-1571124860",
        "dict-paymenttypes-1575878963",
        "dict-paymenttypes-1571124919",
        "dict-paymenttypes-1571134100",
        "dict-paymenttypes-1571134212",
        "7",
        "dict-paymenttypes-1581413638",
        "dict-paymenttypes-1581413641",
        "dict-paymenttypes-1581413645",
        "dict-paymenttypes-1581413649",
        "dict-paymenttypes-1581413768",
        "dict-paymenttypes-1581413773",
        "dict-paymenttypes-1581413777",
        "dict-paymenttypes-1581413785",
        "442099",
        "555159",
        "555161",
        "776106",
        "818241",
        "897285",
        "423361",
        "442100",
        "test-mc-payment",
        "test-mc-cashin",
        "test-mc-prepayment"
      ],
      "integrationCode": "ozon-seller-5ff57fbceb6b2",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "ozon-seller-6023f9ca1d880": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": false,
      "isAutoNetCostCalculation": false,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? OZON",
      "code": "ozon-seller-6023f9ca1d880",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "description": "???????????????? OZON",
      "paymentTypes": [
        "cash",
        "bank-card",
        "e-money",
        "bank-transfer",
        "credit",
        "bonuses-sl",
        "dict-paymenttypes-1571123795",
        "dict-paymenttypes-1575878858",
        "dict-paymenttypes-1571123853",
        "dict-paymenttypes-1571124860",
        "dict-paymenttypes-1575878963",
        "dict-paymenttypes-1571124919",
        "dict-paymenttypes-1571134100",
        "dict-paymenttypes-1571134212",
        "7",
        "dict-paymenttypes-1581413638",
        "dict-paymenttypes-1581413641",
        "dict-paymenttypes-1581413645",
        "dict-paymenttypes-1581413649",
        "dict-paymenttypes-1581413768",
        "dict-paymenttypes-1581413773",
        "dict-paymenttypes-1581413777",
        "dict-paymenttypes-1581413785",
        "442099",
        "555159",
        "555161",
        "776106",
        "818241",
        "897285",
        "423361",
        "442100",
        "test-mc-payment",
        "test-mc-cashin",
        "test-mc-prepayment",
        "12",
        "13",
        "18",
        "14",
        "19",
        "15",
        "16",
        "17",
        "20",
        "checkbox-payment",
        "checkbox-payment-two"
      ],
      "integrationCode": "ozon-seller-6023f9ca1d880",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "ozon-seller-5f9bff5857b35": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": false,
      "isAutoNetCostCalculation": false,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? OZON",
      "code": "ozon-seller-5f9bff5857b35",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "description": "???????????????? OZON",
      "paymentTypes": [
        "cash",
        "bank-card",
        "e-money",
        "bank-transfer",
        "credit",
        "bonuses-sl",
        "dict-paymenttypes-1571123795",
        "dict-paymenttypes-1575878858",
        "dict-paymenttypes-1571123853",
        "dict-paymenttypes-1571124860",
        "dict-paymenttypes-1575878963",
        "dict-paymenttypes-1571124919",
        "dict-paymenttypes-1571134100",
        "dict-paymenttypes-1571134212",
        "7",
        "dict-paymenttypes-1581413638",
        "dict-paymenttypes-1581413641",
        "dict-paymenttypes-1581413645",
        "dict-paymenttypes-1581413649",
        "dict-paymenttypes-1581413768",
        "dict-paymenttypes-1581413773",
        "dict-paymenttypes-1581413777",
        "dict-paymenttypes-1581413785",
        "442099",
        "555159",
        "555161",
        "776106",
        "818241",
        "897285",
        "442100",
        "423361"
      ],
      "integrationCode": "ozon-seller-5f9bff5857b35",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "ozon-seller-5fda1144d3c1e": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": false,
      "isAutoNetCostCalculation": false,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? OZON",
      "code": "ozon-seller-5fda1144d3c1e",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "description": "???????????????? OZON",
      "paymentTypes": [
        "cash",
        "bank-card",
        "e-money",
        "bank-transfer",
        "credit",
        "bonuses-sl",
        "dict-paymenttypes-1571123795",
        "dict-paymenttypes-1575878858",
        "dict-paymenttypes-1571123853",
        "dict-paymenttypes-1571124860",
        "dict-paymenttypes-1575878963",
        "dict-paymenttypes-1571124919",
        "dict-paymenttypes-1571134100",
        "dict-paymenttypes-1571134212",
        "7",
        "dict-paymenttypes-1581413638",
        "dict-paymenttypes-1581413641",
        "dict-paymenttypes-1581413645",
        "dict-paymenttypes-1581413649",
        "dict-paymenttypes-1581413768",
        "dict-paymenttypes-1581413773",
        "dict-paymenttypes-1581413777",
        "dict-paymenttypes-1581413785",
        "442099",
        "555159",
        "555161",
        "776106",
        "818241",
        "897285",
        "423361",
        "442100",
        "test-mc-payment",
        "test-mc-cashin",
        "test-mc-prepayment"
      ],
      "integrationCode": "ozon-seller-5fda1144d3c1e",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "ozon-seller-6023fbcea632c": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": false,
      "isAutoNetCostCalculation": false,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? OZON",
      "code": "ozon-seller-6023fbcea632c",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "description": "???????????????? OZON",
      "paymentTypes": [
        "cash",
        "bank-card",
        "e-money",
        "bank-transfer",
        "credit",
        "bonuses-sl",
        "dict-paymenttypes-1571123795",
        "dict-paymenttypes-1575878858",
        "dict-paymenttypes-1571123853",
        "dict-paymenttypes-1571124860",
        "dict-paymenttypes-1575878963",
        "dict-paymenttypes-1571124919",
        "dict-paymenttypes-1571134100",
        "dict-paymenttypes-1571134212",
        "7",
        "dict-paymenttypes-1581413638",
        "dict-paymenttypes-1581413641",
        "dict-paymenttypes-1581413645",
        "dict-paymenttypes-1581413649",
        "dict-paymenttypes-1581413768",
        "dict-paymenttypes-1581413773",
        "dict-paymenttypes-1581413777",
        "dict-paymenttypes-1581413785",
        "442099",
        "555159",
        "555161",
        "776106",
        "818241",
        "897285",
        "423361",
        "442100",
        "test-mc-payment",
        "test-mc-cashin",
        "test-mc-prepayment",
        "12",
        "13",
        "18",
        "14",
        "19",
        "15",
        "16",
        "17",
        "20",
        "checkbox-payment",
        "checkbox-payment-two"
      ],
      "integrationCode": "ozon-seller-6023fbcea632c",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "ozon-seller-6024d760b14f8": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": false,
      "isAutoNetCostCalculation": false,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? OZON",
      "code": "ozon-seller-6024d760b14f8",
      "active": true,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "description": "???????????????? OZON",
      "paymentTypes": [
        "cash",
        "bank-card",
        "e-money",
        "bank-transfer",
        "credit",
        "bonuses-sl",
        "dict-paymenttypes-1571123795",
        "dict-paymenttypes-1575878858",
        "dict-paymenttypes-1571123853",
        "dict-paymenttypes-1571124860",
        "dict-paymenttypes-1575878963",
        "dict-paymenttypes-1571124919",
        "dict-paymenttypes-1571134100",
        "dict-paymenttypes-1571134212",
        "7",
        "dict-paymenttypes-1581413638",
        "dict-paymenttypes-1581413641",
        "dict-paymenttypes-1581413645",
        "dict-paymenttypes-1581413649",
        "dict-paymenttypes-1581413768",
        "dict-paymenttypes-1581413773",
        "dict-paymenttypes-1581413777",
        "dict-paymenttypes-1581413785",
        "442099",
        "555159",
        "555161",
        "776106",
        "818241",
        "897285",
        "423361",
        "442100",
        "test-mc-payment",
        "test-mc-cashin",
        "test-mc-prepayment",
        "12",
        "13",
        "18",
        "14",
        "19",
        "15",
        "16",
        "17",
        "20",
        "checkbox-payment",
        "checkbox-payment-two",
        "test-payment-integration",
        "invoice-payment",
        "alfa-bank-payment",
        "wallete-one-payment-vog-old"
      ],
      "integrationCode": "ozon-seller-6024d760b14f8",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "yandex-beru-delivery-52": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? ????????",
      "code": "yandex-beru-delivery-52",
      "active": true,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "integrationCode": "yandex_beru",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "yandex-beru-delivery-53": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? ????????",
      "code": "yandex-beru-delivery-53",
      "active": true,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "integrationCode": "yandex_beru",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "yandex-beru-delivery-54": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? ????????",
      "code": "yandex-beru-delivery-54",
      "active": true,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "integrationCode": "yandex_beru",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "yandex-beru-delivery-55": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? ????????",
      "code": "yandex-beru-delivery-55",
      "active": true,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "integrationCode": "yandex_beru",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "yandex-beru-delivery-56": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? ????????",
      "code": "yandex-beru-delivery-56",
      "active": true,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "integrationCode": "yandex_beru",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "yandex-beru-delivery-57": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? ????????",
      "code": "yandex-beru-delivery-57",
      "active": true,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "integrationCode": "yandex_beru",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "cdek-dev-2": {
      "isDynamicCostCalculation": true,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "CDEK dev",
      "code": "cdek-dev-2",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "integrationCode": "cdek_dev_4",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "nova-poshta-standart": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "?????????? ?????????? ??????????????????????",
      "code": "nova-poshta-standart",
      "active": true,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [
        "bank-card",
        "bank-transfer",
        "credit",
        "cash",
        "e-money"
      ],
      "integrationCode": "newpost",
      "deliveryServices": [

      ],
      "defaultForCrm": false,
      "vatRate": "20.00"
    },
    "cdek-dev": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "CDEK dev",
      "code": "cdek-dev",
      "active": false,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "integrationCode": "cdek_dev",
      "deliveryServices": [

      ],
      "defaultForCrm": false,
      "vatRate": "10.00"
    },
    "cdek-dev-1": {
      "isDynamicCostCalculation": true,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "CDEK dev",
      "code": "cdek-dev-1",
      "active": false,
      "defaultCost": 100,
      "defaultNetCost": 0,
      "paymentTypes": [
        "bank-card",
        "cash",
        "442100"
      ],
      "integrationCode": "cdek_dev_2",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "yandex-beru-delivery-58": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? ????????",
      "code": "yandex-beru-delivery-58",
      "active": true,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "integrationCode": "yandex_beru",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    },
    "courier": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": false,
      "isAutoNetCostCalculation": false,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "???????????????? ????????????????",
      "code": "courier",
      "active": true,
      "defaultCost": 300,
      "defaultNetCost": 0,
      "paymentTypes": [
        "cash",
        "bank-card",
        "bank-transfer",
        "e-money",
        "credit",
        "test-payment-integration"
      ],
      "deliveryServices": [
        "1",
        "pvz-out2",
        "pvz-out3"
      ],
      "defaultForCrm": false,
      "vatRate": "10.50"
    },
    "cdek-dev-3": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "CDEK dev",
      "code": "cdek-dev-3",
      "active": true,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [
        "bank-card",
        "cash",
        "442100"
      ],
      "integrationCode": "cdek_dev_2",
      "deliveryServices": [

      ],
      "defaultForCrm": false,
      "vatRate": "20.00"
    },
    "crrst-24": {
      "isDynamicCostCalculation": false,
      "isAutoCostCalculation": true,
      "isAutoNetCostCalculation": true,
      "isCostDependsOnRegionAndWeightAndSum": false,
      "isCostDependsOnDateTime": false,
      "name": "?????????????????? 24",
      "code": "crrst-24",
      "active": true,
      "defaultCost": 0,
      "defaultNetCost": 0,
      "paymentTypes": [

      ],
      "integrationCode": "courierist-24",
      "deliveryServices": [

      ],
      "defaultForCrm": false
    }
  }
}
EOF;

        $mock = static::createApiMockBuilder('reference/delivery-types');
        $mock->matchMethod(RequestMethod::GET)
            ->reply(200)
            ->withBody($json);

        $client   = TestClientFactory::createClient($mock->getClient());
        $response = $client->references->deliveryTypes();

        self::assertModelsCallback($json, $response, static function ($expected, $actual) {
            foreach ($expected['deliveryTypes'] as $key => $deliveryType) {
                $actual['deliveryTypes'][$key]['isDynamicCostCalculation'] = $deliveryType['isDynamicCostCalculation'];
                $actual['deliveryTypes'][$key]['isAutoCostCalculation'] = $deliveryType['isAutoCostCalculation'];
                $actual['deliveryTypes'][$key]['isAutoNetCostCalculation'] = $deliveryType['isAutoNetCostCalculation'];
                $actual['deliveryTypes'][$key]['isCostDependsOnRegionAndWeightAndSum']
                    = $deliveryType['isCostDependsOnRegionAndWeightAndSum'];
                $actual['deliveryTypes'][$key]['isCostDependsOnDateTime'] = $deliveryType['isCostDependsOnDateTime'];
            }

            self::assertEquals($expected, $actual);
        });
    }

    public function testDeliveryTypesEdit(): void
    {
        $json = <<<'EOF'
{
  "success": true
}
EOF;

        $entity                 = new DeliveryType();
        $entity->name           = 'Test Type';
        $entity->active         = false;
        $entity->defaultCost    = 0;
        $entity->defaultNetCost = 0;
        $entity->paymentTypes   = [
            'bank-card',
            'bank-transfer',
            'credit',
            'cash',
            'e-money'
        ];

        $request = new DeliveryTypesEditRequest($entity);

        $mock = static::createApiMockBuilder('reference/delivery-types/dict-deliverytypes-1571124916/edit');
        $mock->matchMethod(RequestMethod::POST)
            ->matchBody(self::encodeForm($request))
            ->reply(200)
            ->withBody($json);

        $client   = TestClientFactory::createClient($mock->getClient());
        $response = $client->references->deliveryTypesEdit('dict-deliverytypes-1571124916', $request);

        self::assertModelEqualsToResponse($json, $response);
    }

    public function testLegalEntities(): void
    {
        $json = <<<'EOF'
{
  "success": true,
  "legalEntities": [
    {
      "contragentType": "enterpreneur",
      "legalName": "NDS",
      "code": "NDS",
      "countryIso": "RU",
      "vatRate": "20.00"
    },
    {
      "contragentType": "enterpreneur",
      "legalName": "???? ?????????????????? ?????????????? ??????????????????????????",
      "INN": "773719480154",
      "code": "Vog_Gallery",
      "countryIso": "RU"
    },
    {
      "contragentType": "legal-entity",
      "legalName": "?????? \"????????????????\"",
      "code": "kazemat",
      "countryIso": "RU",
      "vatRate": "18.00"
    },
    {
      "contragentType": "legal-entity",
      "legalName": "?????? \"??????????????????\"",
      "code": "necropol",
      "countryIso": "RU",
      "vatRate": "20.00"
    }
  ]
}
EOF;

        $mock = static::createApiMockBuilder('reference/legal-entities');
        $mock->matchMethod(RequestMethod::GET)
            ->reply(200)
            ->withBody($json);

        $client   = TestClientFactory::createClient($mock->getClient());
        $response = $client->references->legalEntities();

        self::assertModelEqualsToResponse($json, $response);
    }

    public function testLegalEntitiesEdit(): void
    {
        $json = <<<'EOF'
{
  "success": true
}
EOF;

        $entity                 = new LegalEntity();
        $entity->contragentType = "legal-entity";
        $entity->legalName      = "?????? \"??????????????????\"";
        $entity->countryIso     = CountryCodeIso3166::RUSSIAN_FEDERATION;
        $entity->vatRate        = "20.00";

        $request = new LegalEntityEditRequest($entity);

        $mock = static::createApiMockBuilder('reference/legal-entities/necropol/edit');
        $mock->matchMethod(RequestMethod::POST)
            ->matchBody(self::encodeForm($request))
            ->reply(200)
            ->withBody($json);

        $client   = TestClientFactory::createClient($mock->getClient());
        $response = $client->references->legalEntitiesEdit('necropol', $request);

        self::assertModelEqualsToResponse($json, $response);
    }

    public function testMgChannels(): void
    {
        $json = <<<'EOF'
{
  "success": true,
  "mgChannels": [
    {
      "id": 6,
      "externalId": 3,
      "type": "viber",
      "active": false,
      "name": "AzgalotNeBot"
    },
    {
      "id": 7,
      "externalId": 6,
      "type": "fbmessenger",
      "active": false
    },
    {
      "id": 8,
      "externalId": 7,
      "type": "viber",
      "active": false,
      "name": "transportest"
    },
    {
      "id": 9,
      "externalId": 9,
      "type": "whatsapp",
      "active": false,
      "name": "transports"
    }
  ]
}
EOF;

        $mock = static::createApiMockBuilder('reference/mg-channels');
        $mock->matchMethod(RequestMethod::GET)
            ->reply(200)
            ->withBody($json);

        $client   = TestClientFactory::createClient($mock->getClient());
        $response = $client->references->mgChannels();

        self::assertModelEqualsToResponse($json, $response);
    }

    public function testOrderMethods(): void
    {
        $json = <<<'EOF'
{
  "success": true,
  "orderMethods": {
    "2": {
      "name": "2",
      "code": "2",
      "active": true,
      "defaultForCrm": false,
      "defaultForApi": false,
      "isFromPos": false
    },
    "phone": {
      "name": "???? ????????????????",
      "code": "phone",
      "active": true,
      "defaultForCrm": true,
      "defaultForApi": false,
      "isFromPos": false
    },
    "shopping-cart": {
      "name": "?????????? ??????????????",
      "code": "shopping-cart",
      "active": true,
      "defaultForCrm": false,
      "defaultForApi": true,
      "isFromPos": false
    },
    "one-click": {
      "name": "?? ???????? ????????",
      "code": "one-click",
      "active": true,
      "defaultForCrm": false,
      "defaultForApi": false,
      "isFromPos": false
    },
    "price-decrease-request": {
      "name": "???????????? ???? ?????????????????? ????????",
      "code": "price-decrease-request",
      "active": true,
      "defaultForCrm": false,
      "defaultForApi": false,
      "isFromPos": false
    },
    "landing-page": {
      "name": "???????????? ?? ???????????????????? ????????????????",
      "code": "landing-page",
      "active": true,
      "defaultForCrm": false,
      "defaultForApi": false,
      "isFromPos": false
    },
    "offline": {
      "name": "??????????????",
      "code": "offline",
      "active": true,
      "defaultForCrm": false,
      "defaultForApi": false,
      "isFromPos": false
    },
    "app": {
      "name": "?????????????????? ????????????????????",
      "code": "app",
      "active": true,
      "defaultForCrm": false,
      "defaultForApi": false,
      "isFromPos": false
    },
    "live-chat": {
      "name": "????????????-??????????????????????",
      "code": "live-chat",
      "active": true,
      "defaultForCrm": false,
      "defaultForApi": false,
      "isFromPos": false
    },
    "terminal": {
      "name": "????????????????",
      "code": "terminal",
      "active": true,
      "defaultForCrm": false,
      "defaultForApi": false,
      "isFromPos": false
    },
    "missed-call": {
      "name": "?????????????????????? ????????????",
      "code": "missed-call",
      "active": true,
      "defaultForCrm": false,
      "defaultForApi": false,
      "isFromPos": false
    },
    "messenger": {
      "name": "??????????????????????",
      "code": "messenger",
      "active": true,
      "defaultForCrm": false,
      "defaultForApi": false,
      "isFromPos": false
    }
  }
}
EOF;

        $mock = static::createApiMockBuilder('reference/order-methods');
        $mock->matchMethod(RequestMethod::GET)
            ->reply(200)
            ->withBody($json);

        $client   = TestClientFactory::createClient($mock->getClient());
        $response = $client->references->orderMethods();

        self::assertModelEqualsToResponse($json, $response);
    }

    public function testOrderMethodsEdit(): void
    {
        $json = <<<'EOF'
{
  "success": true
}
EOF;

        $entity         = new OrderMethod();
        $entity->name   = 'Test Method';
        $entity->active = true;

        $request = new OrderMethodsEditRequest($entity);

        $mock = static::createApiMockBuilder('reference/order-methods/2/edit');
        $mock->matchMethod(RequestMethod::POST)
            ->matchBody(self::encodeForm($request))
            ->reply(200)
            ->withBody($json);

        $client   = TestClientFactory::createClient($mock->getClient());
        $response = $client->references->orderMethodsEdit('2', $request);

        self::assertModelEqualsToResponse($json, $response);
    }

    public function testOrderTypes(): void
    {
        $json = <<<'EOF'
{
  "success": true,
  "orderTypes": {
    "1": {
      "name": "1",
      "code": "1",
      "active": true,
      "defaultForCrm": false,
      "defaultForApi": false,
      "ordering": 990
    },
    "eshop-individual": {
      "name": "???????????????????? ????????",
      "code": "eshop-individual",
      "active": true,
      "defaultForCrm": true,
      "defaultForApi": false,
      "ordering": 990
    },
    "test": {
      "name": "test",
      "code": "test",
      "active": true,
      "defaultForCrm": false,
      "defaultForApi": false,
      "ordering": 990
    },
    "eshop-legal": {
      "name": "?????????????????????? ????????",
      "code": "eshop-legal",
      "active": true,
      "defaultForCrm": false,
      "defaultForApi": false,
      "ordering": 990
    },
    "dict-ordertypes-1571123791": {
      "name": "Bbbdict-ordertypes-1571123791",
      "code": "dict-ordertypes-1571123791",
      "active": false,
      "defaultForCrm": false,
      "defaultForApi": false,
      "ordering": 990
    },
    "dict-ordertypes-1575878853": {
      "name": "Bbbdict-ordertypes-1575878853",
      "code": "dict-ordertypes-1575878853",
      "active": false,
      "defaultForCrm": false,
      "defaultForApi": false,
      "ordering": 990
    },
    "dict-ordertypes-1571123851": {
      "name": "Bbbdict-ordertypes-1571123851",
      "code": "dict-ordertypes-1571123851",
      "active": false,
      "defaultForCrm": false,
      "defaultForApi": false,
      "ordering": 990
    },
    "dict-ordertypes-1571124857": {
      "name": "Bbbdict-ordertypes-1571124857",
      "code": "dict-ordertypes-1571124857",
      "active": false,
      "defaultForCrm": false,
      "defaultForApi": false,
      "ordering": 990
    },
    "dict-ordertypes-1575878961": {
      "name": "Bbbdict-ordertypes-1575878961",
      "code": "dict-ordertypes-1575878961",
      "active": false,
      "defaultForCrm": false,
      "defaultForApi": false,
      "ordering": 990
    },
    "dict-ordertypes-1571124917": {
      "name": "Bbbdict-ordertypes-1571124917",
      "code": "dict-ordertypes-1571124917",
      "active": false,
      "defaultForCrm": false,
      "defaultForApi": false,
      "ordering": 990
    },
    "dict-ordertypes-1571134095": {
      "name": "Bbbdict-ordertypes-1571134095",
      "code": "dict-ordertypes-1571134095",
      "active": false,
      "defaultForCrm": false,
      "defaultForApi": false,
      "ordering": 990
    },
    "dict-ordertypes-1571134209": {
      "name": "Bbbdict-ordertypes-1571134209",
      "code": "dict-ordertypes-1571134209",
      "active": false,
      "defaultForCrm": false,
      "defaultForApi": false,
      "ordering": 990
    },
    "dict-ordertypes-1581413631": {
      "name": "Bbbdict-ordertypes-1581413631",
      "code": "dict-ordertypes-1581413631",
      "active": false,
      "defaultForCrm": false,
      "defaultForApi": false,
      "ordering": 990
    },
    "dict-ordertypes-1581413635": {
      "name": "Bbbdict-ordertypes-1581413635",
      "code": "dict-ordertypes-1581413635",
      "active": false,
      "defaultForCrm": false,
      "defaultForApi": false,
      "ordering": 990
    },
    "dict-ordertypes-1581413638": {
      "name": "Bbbdict-ordertypes-1581413638",
      "code": "dict-ordertypes-1581413638",
      "active": false,
      "defaultForCrm": false,
      "defaultForApi": false,
      "ordering": 990
    },
    "dict-ordertypes-1581413643": {
      "name": "Bbbdict-ordertypes-1581413643",
      "code": "dict-ordertypes-1581413643",
      "active": false,
      "defaultForCrm": false,
      "defaultForApi": false,
      "ordering": 990
    },
    "dict-ordertypes-1581413763": {
      "name": "Bbbdict-ordertypes-1581413763",
      "code": "dict-ordertypes-1581413763",
      "active": false,
      "defaultForCrm": false,
      "defaultForApi": false,
      "ordering": 990
    },
    "dict-ordertypes-1581413769": {
      "name": "Bbbdict-ordertypes-1581413769",
      "code": "dict-ordertypes-1581413769",
      "active": false,
      "defaultForCrm": false,
      "defaultForApi": false,
      "ordering": 990
    },
    "dict-ordertypes-1581413772": {
      "name": "Bbbdict-ordertypes-1581413772",
      "code": "dict-ordertypes-1581413772",
      "active": false,
      "defaultForCrm": false,
      "defaultForApi": false,
      "ordering": 990
    },
    "dict-ordertypes-1581413780": {
      "name": "Bbbdict-ordertypes-1581413780",
      "code": "dict-ordertypes-1581413780",
      "active": false,
      "defaultForCrm": false,
      "defaultForApi": false,
      "ordering": 990
    },
    "mc-order": {
      "name": "?????????? ?????? ????",
      "code": "mc-order",
      "active": true,
      "defaultForCrm": false,
      "defaultForApi": false,
      "ordering": 990
    }
  }
}
EOF;

        $mock = static::createApiMockBuilder('reference/order-types');
        $mock->matchMethod(RequestMethod::GET)
            ->reply(200)
            ->withBody($json);

        $client   = TestClientFactory::createClient($mock->getClient());
        $response = $client->references->orderTypes();

        self::assertModelEqualsToResponse($json, $response);
    }

    public function testOrderTypesEdit(): void
    {
        $json = <<<'EOF'
{
  "success": true
}
EOF;

        $entity         = new OrderType();
        $entity->name   = 'Test Type';
        $entity->active = true;

        $request = new OrderTypesEditRequest($entity);

        $mock = static::createApiMockBuilder('reference/order-types/1/edit');
        $mock->matchMethod(RequestMethod::POST)
            ->matchBody(self::encodeForm($request))
            ->reply(200)
            ->withBody($json);

        $client   = TestClientFactory::createClient($mock->getClient());
        $response = $client->references->orderTypesEdit('1', $request);

        self::assertModelEqualsToResponse($json, $response);
    }

    public function testPaymentStatuses(): void
    {
        $json = <<<'EOF'
{
  "success": true,
  "paymentStatuses": {
    "dict-paymentstatuses-1571123793": {
      "name": "Bbbdict-paymentstatuses-1571123793",
      "code": "dict-paymentstatuses-1571123793",
      "active": false,
      "defaultForCrm": false,
      "defaultForApi": false,
      "paymentComplete": false,
      "ordering": 990,
      "paymentTypes": [
        "cash"
      ]
    },
    "dict-paymentstatuses-1571123852": {
      "name": "Bbbdict-paymentstatuses-1571123852",
      "code": "dict-paymentstatuses-1571123852",
      "active": false,
      "defaultForCrm": false,
      "defaultForApi": false,
      "paymentComplete": false,
      "ordering": 990,
      "paymentTypes": [
        "cash"
      ]
    },
    "dict-paymentstatuses-1571124858": {
      "name": "Bbbdict-paymentstatuses-1571124858",
      "code": "dict-paymentstatuses-1571124858",
      "active": false,
      "defaultForCrm": false,
      "defaultForApi": false,
      "paymentComplete": false,
      "ordering": 990,
      "paymentTypes": [
        "cash"
      ]
    },
    "dict-paymentstatuses-1571124918": {
      "name": "Bbbdict-paymentstatuses-1571124918",
      "code": "dict-paymentstatuses-1571124918",
      "active": false,
      "defaultForCrm": false,
      "defaultForApi": false,
      "paymentComplete": false,
      "ordering": 990,
      "paymentTypes": [
        "cash"
      ]
    },
    "payment-start": {
      "name": "???????????? ????????????????",
      "code": "payment-start",
      "active": true,
      "defaultForCrm": false,
      "defaultForApi": false,
      "paymentComplete": false,
      "ordering": 30,
      "paymentTypes": [
        "bonuses-sl",
        "test-payment-integration",
        "alfa-bank-payment",
        "bank-card",
        "bank-transfer",
        "credit",
        "cash",
        "invoice-payment",
        "wallete-one-payment-vog",
        "test-mc-payment",
        "test-mc-cashin",
        "e-money"
      ]
    },
    "paid": {
      "name": "??????????????",
      "code": "paid",
      "active": true,
      "defaultForCrm": false,
      "defaultForApi": false,
      "paymentComplete": true,
      "ordering": 60,
      "paymentTypes": [
        "bonuses-sl",
        "17",
        "test-payment-integration",
        "alfa-bank-payment",
        "bank-card",
        "897285",
        "bank-transfer",
        "12",
        "credit",
        "818241",
        "cash",
        "13",
        "423361",
        "442100",
        "442099",
        "14",
        "19",
        "18",
        "checkbox-payment",
        "checkbox-payment-two",
        "invoice-payment",
        "wallete-one-payment-vog",
        "776106",
        "15",
        "20",
        "16",
        "test-mc-payment",
        "test-mc-prepayment",
        "test-mc-cashin",
        "555159",
        "555161",
        "e-money"
      ]
    },
    "credit-check": {
      "name": "???????????????? ???????????????????? ???? ????????????",
      "code": "credit-check",
      "active": true,
      "defaultForCrm": false,
      "defaultForApi": false,
      "paymentComplete": false,
      "ordering": 40,
      "paymentTypes": [
        "bonuses-sl",
        "credit",
        "cash"
      ]
    },
    "credit-approved": {
      "name": "???????????? ??????????????",
      "code": "credit-approved",
      "active": true,
      "defaultForCrm": false,
      "defaultForApi": false,
      "paymentComplete": false,
      "ordering": 50,
      "paymentTypes": [
        "bonuses-sl",
        "credit",
        "cash"
      ]
    },
    "fail": {
      "name": "????????????",
      "code": "fail",
      "active": true,
      "defaultForCrm": false,
      "defaultForApi": false,
      "paymentComplete": false,
      "ordering": 55,
      "paymentTypes": [
        "bonuses-sl",
        "bank-card",
        "bank-transfer",
        "credit",
        "cash",
        "e-money"
      ]
    },
    "not-paid": {
      "name": "???? ??????????????",
      "code": "not-paid",
      "active": true,
      "defaultForCrm": false,
      "defaultForApi": false,
      "paymentComplete": false,
      "ordering": 10,
      "paymentTypes": [
        "bonuses-sl",
        "17",
        "bank-card",
        "bank-transfer",
        "12",
        "credit",
        "cash",
        "13",
        "14",
        "19",
        "18",
        "checkbox-payment",
        "15",
        "20",
        "16",
        "test-mc-payment",
        "test-mc-cashin",
        "e-money"
      ]
    },
    "dict-paymentstatuses-1571134098": {
      "name": "Bbbdict-paymentstatuses-1571134098",
      "code": "dict-paymentstatuses-1571134098",
      "active": false,
      "defaultForCrm": false,
      "defaultForApi": false,
      "paymentComplete": false,
      "ordering": 990,
      "paymentTypes": [
        "cash"
      ]
    },
    "dict-paymentstatuses-1571134211": {
      "name": "Bbbdict-paymentstatuses-1571134211",
      "code": "dict-paymentstatuses-1571134211",
      "active": false,
      "defaultForCrm": false,
      "defaultForApi": false,
      "paymentComplete": false,
      "ordering": 990,
      "paymentTypes": [
        "cash"
      ]
    },
    "dict-paymentstatuses-1575878855": {
      "name": "Bbbdict-paymentstatuses-1575878855",
      "code": "dict-paymentstatuses-1575878855",
      "active": false,
      "defaultForCrm": false,
      "defaultForApi": false,
      "paymentComplete": false,
      "ordering": 990,
      "paymentTypes": [
        "cash"
      ]
    },
    "dict-paymentstatuses-1575878962": {
      "name": "Bbbdict-paymentstatuses-1575878962",
      "code": "dict-paymentstatuses-1575878962",
      "active": false,
      "defaultForCrm": false,
      "defaultForApi": false,
      "paymentComplete": false,
      "ordering": 990,
      "paymentTypes": [
        "cash"
      ]
    },
    "dict-paymentstatuses-1581413635": {
      "name": "Bbbdict-paymentstatuses-1581413635",
      "code": "dict-paymentstatuses-1581413635",
      "active": false,
      "defaultForCrm": false,
      "defaultForApi": false,
      "paymentComplete": false,
      "ordering": 990,
      "paymentTypes": [
        "cash"
      ]
    },
    "dict-paymentstatuses-1581413638": {
      "name": "Bbbdict-paymentstatuses-1581413638",
      "code": "dict-paymentstatuses-1581413638",
      "active": false,
      "defaultForCrm": false,
      "defaultForApi": false,
      "paymentComplete": false,
      "ordering": 990,
      "paymentTypes": [
        "cash"
      ]
    },
    "dict-paymentstatuses-1581413641": {
      "name": "Bbbdict-paymentstatuses-1581413641",
      "code": "dict-paymentstatuses-1581413641",
      "active": false,
      "defaultForCrm": false,
      "defaultForApi": false,
      "paymentComplete": false,
      "ordering": 990,
      "paymentTypes": [
        "cash"
      ]
    },
    "dict-paymentstatuses-1581413646": {
      "name": "Bbbdict-paymentstatuses-1581413646",
      "code": "dict-paymentstatuses-1581413646",
      "active": false,
      "defaultForCrm": false,
      "defaultForApi": false,
      "paymentComplete": false,
      "ordering": 990,
      "paymentTypes": [
        "cash"
      ]
    },
    "dict-paymentstatuses-1581413765": {
      "name": "Bbbdict-paymentstatuses-1581413765",
      "code": "dict-paymentstatuses-1581413765",
      "active": false,
      "defaultForCrm": false,
      "defaultForApi": false,
      "paymentComplete": false,
      "ordering": 990,
      "paymentTypes": [
        "cash"
      ]
    },
    "dict-paymentstatuses-1581413771": {
      "name": "Bbbdict-paymentstatuses-1581413771",
      "code": "dict-paymentstatuses-1581413771",
      "active": false,
      "defaultForCrm": false,
      "defaultForApi": false,
      "paymentComplete": false,
      "ordering": 990,
      "paymentTypes": [
        "cash"
      ]
    },
    "dict-paymentstatuses-1581413775": {
      "name": "Bbbdict-paymentstatuses-1581413775",
      "code": "dict-paymentstatuses-1581413775",
      "active": false,
      "defaultForCrm": false,
      "defaultForApi": false,
      "paymentComplete": false,
      "ordering": 990,
      "paymentTypes": [
        "cash"
      ]
    },
    "dict-paymentstatuses-1581413783": {
      "name": "Bbbdict-paymentstatuses-1581413783",
      "code": "dict-paymentstatuses-1581413783",
      "active": false,
      "defaultForCrm": false,
      "defaultForApi": false,
      "paymentComplete": false,
      "ordering": 990,
      "paymentTypes": [
        "cash"
      ]
    },
    "pending": {
      "name": "???? ??????????????",
      "code": "pending",
      "active": true,
      "defaultForCrm": false,
      "defaultForApi": false,
      "paymentComplete": false,
      "ordering": 990,
      "paymentTypes": [
        "897285",
        "bank-transfer",
        "818241",
        "cash",
        "423361",
        "442100",
        "442099",
        "checkbox-payment-two",
        "776106",
        "555159",
        "555161"
      ]
    },
    "invoice": {
      "name": "?????????????????? ????????",
      "code": "invoice",
      "active": true,
      "defaultForCrm": false,
      "defaultForApi": false,
      "paymentComplete": false,
      "ordering": 20,
      "paymentTypes": [
        "bonuses-sl",
        "test-payment-integration",
        "alfa-bank-payment",
        "bank-card",
        "bank-transfer",
        "credit",
        "cash",
        "invoice-payment",
        "wallete-one-payment-vog",
        "test-mc-payment",
        "test-mc-cashin",
        "e-money"
      ]
    },
    "fiskalizirovan": {
      "name": "????????????????????????????",
      "code": "fiskalizirovan",
      "active": true,
      "defaultForCrm": false,
      "defaultForApi": false,
      "paymentComplete": true,
      "ordering": 990,
      "paymentTypes": [
        "checkbox-payment",
        "checkbox-payment-two"
      ]
    },
    "check-refund": {
      "name": "?????????????? ????????",
      "code": "check-refund",
      "active": true,
      "defaultForCrm": false,
      "defaultForApi": false,
      "paymentComplete": false,
      "ordering": 990,
      "paymentTypes": [
        "test-payment-integration",
        "alfa-bank-payment",
        "checkbox-payment",
        "checkbox-payment-two",
        "invoice-payment",
        "wallete-one-payment-vog"
      ]
    },
    "check-refund-after": {
      "name": "?????? ??????????????????",
      "code": "check-refund-after",
      "active": true,
      "defaultForCrm": false,
      "defaultForApi": false,
      "paymentComplete": false,
      "ordering": 990,
      "paymentTypes": [
        "test-payment-integration",
        "alfa-bank-payment",
        "checkbox-payment",
        "checkbox-payment-two",
        "invoice-payment",
        "wallete-one-payment-vog"
      ]
    }
  }
}
EOF;

        $mock = static::createApiMockBuilder('reference/payment-statuses');
        $mock->matchMethod(RequestMethod::GET)
            ->reply(200)
            ->withBody($json);

        $client   = TestClientFactory::createClient($mock->getClient());
        $response = $client->references->paymentStatuses();

        self::assertModelEqualsToResponse($json, $response);
    }

    public function testPaymentStatusesEdit(): void
    {
        $json = <<<'EOF'
{
  "success": true
}
EOF;

        $entity               = new PaymentStatus();
        $entity->name         = 'Test Status';
        $entity->active       = true;
        $entity->ordering     = 990;
        $entity->paymentTypes = ['cash'];

        $request = new PaymentStatusesEditRequest($entity);

        $mock = static::createApiMockBuilder('reference/payment-statuses/dict-paymentstatuses-1571123793/edit');
        $mock->matchMethod(RequestMethod::POST)
            ->matchBody(self::encodeForm($request))
            ->reply(200)
            ->withBody($json);

        $client   = TestClientFactory::createClient($mock->getClient());
        $response = $client->references->paymentStatusesEdit('dict-paymentstatuses-1571123793', $request);

        self::assertModelEqualsToResponse($json, $response);
    }

    public function testPaymentTypes(): void
    {
        $json = <<<'EOF'
{
  "success": true,
  "paymentTypes": {
    "7": {
      "name": "7",
      "code": "7",
      "active": true,
      "defaultForCrm": false,
      "defaultForApi": false,
      "deliveryTypes": [
        "2",
        "ozon-seller-5f2432a06a903",
        "ozon-seller",
        "ozon-seller-5f689f4509b2f",
        "ozon-seller-228",
        "ozon-seller-2",
        "ozon-seller-5f6affbcb5617",
        "servientrega-test",
        "ozon-seller-5f8fe602d193b",
        "ozon-seller-5f9bfac6a9855",
        "ozon-seller-5f9bff5857b35",
        "delivery-callback-check",
        "ozon-seller-5f9d10257668e",
        "ozon-seller-3",
        "serv-test",
        "ozon-seller-5fda1144d3c1e",
        "ozon-seller-5ff57fbceb6b2",
        "ozon-seller-6023f9ca1d880",
        "ozon-seller-6023fbcea632c",
        "ozon-seller-6024d760b14f8"
      ],
      "paymentStatuses": [

      ]
    },
    "12": {
      "name": "?????????????????????? ????????????",
      "code": "12",
      "active": true,
      "defaultForCrm": false,
      "defaultForApi": false,
      "description": "",
      "deliveryTypes": [
        "3",
        "ozon-seller-6023f9ca1d880",
        "ozon-seller-6023fbcea632c",
        "ozon-seller-6024d760b14f8"
      ],
      "paymentStatuses": [
        "not-paid",
        "paid"
      ]
    },
    "13": {
      "name": "??????????????????",
      "code": "13",
      "active": true,
      "defaultForCrm": false,
      "defaultForApi": false,
      "description": "",
      "deliveryTypes": [
        "11",
        "8",
        "9",
        "3",
        "ozon-seller-6023f9ca1d880",
        "ozon-seller-6023fbcea632c",
        "ozon-seller-6024d760b14f8"
      ],
      "paymentStatuses": [
        "not-paid",
        "paid"
      ]
    },
    "14": {
      "name": "????????????-???????????? ???????????????????? ????????????",
      "code": "14",
      "active": true,
      "defaultForCrm": false,
      "defaultForApi": false,
      "description": "",
      "deliveryTypes": [
        "8",
        "9",
        "3",
        "ozon-seller-6023f9ca1d880",
        "ozon-seller-6023fbcea632c",
        "ozon-seller-6024d760b14f8"
      ],
      "paymentStatuses": [
        "not-paid",
        "paid"
      ]
    },
    "15": {
      "name": "???????????? ?????? ??????????????????",
      "code": "15",
      "active": true,
      "defaultForCrm": false,
      "defaultForApi": false,
      "description": "",
      "deliveryTypes": [
        "11",
        "3",
        "ozon-seller-6023f9ca1d880",
        "ozon-seller-6023fbcea632c",
        "ozon-seller-6024d760b14f8"
      ],
      "paymentStatuses": [
        "not-paid",
        "paid"
      ]
    },
    "wallete-one-payment-vog": {
      "name": "???????????? Wallet One (Vog)",
      "code": "wallete-one-payment-vog",
      "active": true,
      "defaultForCrm": false,
      "defaultForApi": false,
      "deliveryTypes": [

      ],
      "paymentStatuses": [
        "invoice",
        "payment-start",
        "paid",
        "check-refund",
        "check-refund-after"
      ],
      "integrationModule": {
        "active": true,
        "name": "Wallet One",
        "logo": "http://argo.inet-cool.ru/img/w1.svg"
      }
    }
  }
}
EOF;

        $mock = static::createApiMockBuilder('reference/payment-types');
        $mock->matchMethod(RequestMethod::GET)
            ->reply(200)
            ->withBody($json);

        $client   = TestClientFactory::createClient($mock->getClient());
        $response = $client->references->paymentTypes();

        self::assertModelEqualsToResponse($json, $response);
    }

    public function testPaymentTypesEdit(): void
    {
        $json = <<<'EOF'
{
  "success": true
}
EOF;

        $entity                  = new PaymentType();
        $entity->name            = "Test Integration Payment";
        $entity->code            = "test-payment-integration";
        $entity->active          = true;
        $entity->defaultForCrm   = false;
        $entity->defaultForApi   = false;
        $entity->paymentStatuses = [
            "invoice",
            "payment-start",
            "paid",
            "check-refund",
            "check-refund-after"
        ];

        $request = new PaymentTypesEditRequest($entity);

        $mock = static::createApiMockBuilder('reference/payment-types/test-payment-integration/edit');
        $mock->matchMethod(RequestMethod::POST)
            ->matchBody(self::encodeForm($request))
            ->reply(200)
            ->withBody($json);

        $client   = TestClientFactory::createClient($mock->getClient());
        $response = $client->references->paymentTypesEdit('test-payment-integration', $request);

        self::assertModelEqualsToResponse($json, $response);
    }

    public function testPriceTypes(): void
    {
        $json = <<<'EOF'
{
  "success": true,
  "priceTypes": [
    {
      "id": 7,
      "code": "sample_v4_price_code",
      "name": "Sample v4 price type",
      "active": true,
      "default": false,
      "geo": [

      ],
      "groups": [

      ],
      "ordering": 500
    },
    {
      "id": 5,
      "code": "name",
      "name": "????????????????",
      "active": true,
      "default": false,
      "geo": [

      ],
      "groups": [

      ],
      "ordering": 990
    },
    {
      "id": 6,
      "code": "test2",
      "name": "test2",
      "active": true,
      "default": false,
      "geo": [

      ],
      "groups": [

      ],
      "ordering": 990
    },
    {
      "id": 4,
      "code": "base",
      "name": "??????????????",
      "active": true,
      "default": true,
      "geo": [

      ],
      "groups": [

      ],
      "ordering": 991
    }
  ]
}
EOF;

        $mock = static::createApiMockBuilder('reference/price-types');
        $mock->matchMethod(RequestMethod::GET)
            ->reply(200)
            ->withBody($json);

        $client   = TestClientFactory::createClient($mock->getClient());
        $response = $client->references->priceTypes();

        self::assertModelEqualsToResponse($json, $response);
    }

    public function testPriceTypesEdit(): void
    {
        $json = <<<'EOF'
{
  "success": true
}
EOF;

        $entity           = new PriceType();
        $entity->name     = "Test Price Type";
        $entity->active   = true;
        $entity->ordering = 980;

        $request = new PriceTypesEditRequest($entity);

        $mock = static::createApiMockBuilder('reference/price-types/test2/edit');
        $mock->matchMethod(RequestMethod::POST)
            ->matchBody(self::encodeForm($request))
            ->reply(200)
            ->withBody($json);

        $client   = TestClientFactory::createClient($mock->getClient());
        $response = $client->references->priceTypesEdit('test2', $request);

        self::assertModelEqualsToResponse($json, $response);
    }

    public function testProductStatuses(): void
    {
        $json = <<<'EOF'
{
  "success": true,
  "productStatuses": {
    "1": {
      "code": "1",
      "ordering": 140,
      "active": true,
      "createdAt": "2019-02-07 16:55:29",
      "cancelStatus": false,
      "name": "1"
    },
    "4": {
      "code": "4",
      "ordering": 270,
      "active": false,
      "createdAt": "2019-10-01 11:24:08",
      "cancelStatus": false,
      "name": "4"
    },
    "otmena": {
      "code": "otmena",
      "ordering": 360,
      "active": true,
      "createdAt": "2020-01-14 11:34:24",
      "cancelStatus": true,
      "name": "otmena"
    },
    "new": {
      "code": "new",
      "ordering": 10,
      "active": true,
      "createdAt": "2018-09-04 16:35:03",
      "cancelStatus": false,
      "name": "????????????????"
    },
    "confirming": {
      "code": "confirming",
      "ordering": 20,
      "active": true,
      "createdAt": "2018-09-04 16:35:03",
      "cancelStatus": false,
      "name": "?????????????????????????? ??????????????"
    },
    "in-reserve": {
      "code": "in-reserve",
      "ordering": 30,
      "active": true,
      "createdAt": "2018-09-04 16:35:03",
      "cancelStatus": false,
      "name": "?? ??????????????"
    },
    "assembly-delivery": {
      "code": "assembly-delivery",
      "ordering": 40,
      "active": true,
      "createdAt": "2018-09-04 16:35:03",
      "cancelStatus": false,
      "name": "???????????????????????? ???? ?????????? ????????????"
    },
    "ready-for-assembly": {
      "code": "ready-for-assembly",
      "ordering": 50,
      "active": true,
      "createdAt": "2018-09-04 16:35:03",
      "cancelStatus": false,
      "name": "?????????? ?? ????????????????????????"
    },
    "completed": {
      "code": "completed",
      "ordering": 60,
      "active": true,
      "createdAt": "2018-09-04 16:35:03",
      "cancelStatus": false,
      "name": "??????????????????????????"
    },
    "saled": {
      "code": "saled",
      "ordering": 70,
      "active": true,
      "createdAt": "2018-09-04 16:35:03",
      "orderStatusByProductStatus": "complete",
      "orderStatusForProductStatus": "complete",
      "cancelStatus": false,
      "name": "????????????"
    },
    "out-of-stock": {
      "code": "out-of-stock",
      "ordering": 80,
      "active": true,
      "createdAt": "2018-09-04 16:35:03",
      "orderStatusByProductStatus": "no-product",
      "orderStatusForProductStatus": "no-product",
      "cancelStatus": true,
      "name": "?????? ?? ??????????????"
    },
    "failure": {
      "code": "failure",
      "ordering": 90,
      "active": true,
      "createdAt": "2018-09-04 16:35:03",
      "cancelStatus": true,
      "name": "?????????? ??????????????"
    },
    "product-is-damaged": {
      "code": "product-is-damaged",
      "ordering": 100,
      "active": true,
      "createdAt": "2018-09-04 16:35:03",
      "cancelStatus": true,
      "name": "?????????? ??????????????????"
    },
    "assembly-not-delivery": {
      "code": "assembly-not-delivery",
      "ordering": 110,
      "active": true,
      "createdAt": "2018-09-04 16:35:03",
      "cancelStatus": true,
      "name": "???? ?????????????????? ???? ?????????? ????????????"
    },
    "lost": {
      "code": "lost",
      "ordering": 120,
      "active": true,
      "createdAt": "2018-09-04 16:35:03",
      "cancelStatus": true,
      "name": "??????????????"
    },
    "returned": {
      "code": "returned",
      "ordering": 130,
      "active": true,
      "createdAt": "2018-09-04 16:35:03",
      "cancelStatus": true,
      "name": "??????????????"
    },
    "1-1": {
      "code": "1-1",
      "ordering": 150,
      "active": true,
      "createdAt": "2019-02-07 16:55:38",
      "cancelStatus": false,
      "name": "1"
    },
    "dsad-dsad-sdsad-ds-ds-ad-d": {
      "code": "dsad-dsad-sdsad-ds-ds-ad-d",
      "ordering": 160,
      "active": true,
      "createdAt": "2019-02-07 16:56:10",
      "orderStatusByProductStatus": "test",
      "orderStatusForProductStatus": "test",
      "cancelStatus": false,
      "name": "dsad\"Ds'aD\"Sdsad-Ds___ds-ad-=d"
    },
    "": {
      "code": "",
      "ordering": 170,
      "active": true,
      "createdAt": "2019-02-07 16:56:10",
      "cancelStatus": false,
      "name": "+/-+/*++-+-/*+-+/*"
    },
    "-1": {
      "code": "-1",
      "ordering": 180,
      "active": true,
      "createdAt": "2019-02-07 16:56:10",
      "cancelStatus": false,
      "name": "{}\"{>:<>?1"
    },
    "-2": {
      "code": "-2",
      "ordering": 190,
      "active": true,
      "createdAt": "2019-02-07 16:57:56",
      "cancelStatus": false,
      "name": "+/-+/*++-+-/*+-+/*+/-+/*++-+-/*+-+/*"
    },
    "-3": {
      "code": "-3",
      "ordering": 200,
      "active": true,
      "createdAt": "2019-02-07 16:58:09",
      "cancelStatus": false,
      "name": "+/-+/*++-+-/*+-+/*"
    },
    "-4": {
      "code": "-4",
      "ordering": 210,
      "active": true,
      "createdAt": "2019-02-07 16:58:20",
      "cancelStatus": false,
      "name": "+/-+/*++-+-/*+-+/*"
    },
    "fdsfsdfsdfsd": {
      "code": "fdsfsdfsdfsd",
      "ordering": 220,
      "active": true,
      "createdAt": "2019-02-07 16:58:20",
      "cancelStatus": false,
      "name": "&nbsp;"
    },
    "-5": {
      "code": "-5",
      "ordering": 230,
      "active": true,
      "createdAt": "2019-02-07 16:58:20",
      "cancelStatus": false,
      "name": "{}\"{>:<>?"
    },
    "nbsp": {
      "code": "nbsp",
      "ordering": 240,
      "active": false,
      "createdAt": "2019-02-07 17:16:55",
      "cancelStatus": false,
      "name": "&nbsp;"
    },
    "yvayva": {
      "code": "yvayva",
      "ordering": 250,
      "active": false,
      "createdAt": "2019-02-07 17:16:55",
      "cancelStatus": false,
      "name": "????????????"
    },
    "-6": {
      "code": "-6",
      "ordering": 260,
      "active": false,
      "createdAt": "2019-02-21 10:50:17",
      "cancelStatus": false,
      "name": "+/-+/*++-+-/*+-+/*"
    },
    "dict-productstatuses-1571134214": {
      "code": "dict-productstatuses-1571134214",
      "ordering": 280,
      "active": false,
      "createdAt": "2019-10-15 13:10:15",
      "cancelStatus": false,
      "name": "Bbbdict-productstatuses-1571134214"
    },
    "dict-productstatuses-1571134105": {
      "code": "dict-productstatuses-1571134105",
      "ordering": 290,
      "active": false,
      "createdAt": "2019-10-15 13:08:25",
      "cancelStatus": false,
      "name": "Bbbdict-productstatuses-1571134105"
    },
    "dict-productstatuses-1571124920": {
      "code": "dict-productstatuses-1571124920",
      "ordering": 300,
      "active": false,
      "createdAt": "2019-10-15 10:35:20",
      "cancelStatus": false,
      "name": "Bbbdict-productstatuses-1571124920"
    },
    "dict-productstatuses-1571124862": {
      "code": "dict-productstatuses-1571124862",
      "ordering": 310,
      "active": false,
      "createdAt": "2019-10-15 10:34:22",
      "cancelStatus": false,
      "name": "Bbbdict-productstatuses-1571124862"
    },
    "dict-productstatuses-1571123853": {
      "code": "dict-productstatuses-1571123853",
      "ordering": 320,
      "active": false,
      "createdAt": "2019-10-15 10:17:34",
      "cancelStatus": false,
      "name": "Bbbdict-productstatuses-1571123853"
    },
    "dict-productstatuses-1571123796": {
      "code": "dict-productstatuses-1571123796",
      "ordering": 330,
      "active": false,
      "createdAt": "2019-10-15 10:16:36",
      "cancelStatus": false,
      "name": "Bbbdict-productstatuses-1571123796"
    },
    "dict-productstatuses-1575878965": {
      "code": "dict-productstatuses-1575878965",
      "ordering": 340,
      "active": false,
      "createdAt": "2019-12-09 11:09:25",
      "cancelStatus": false,
      "name": "Bbbdict-productstatuses-1575878965"
    },
    "dict-productstatuses-1575878860": {
      "code": "dict-productstatuses-1575878860",
      "ordering": 350,
      "active": false,
      "createdAt": "2019-12-09 11:07:40",
      "cancelStatus": false,
      "name": "Bbbdict-productstatuses-1575878860"
    },
    "dict-productstatuses-1581413641": {
      "code": "dict-productstatuses-1581413641",
      "ordering": 990,
      "active": false,
      "createdAt": "2020-02-11 12:34:02",
      "cancelStatus": false,
      "name": "Bbbdict-productstatuses-1581413641"
    },
    "dict-productstatuses-1581413644": {
      "code": "dict-productstatuses-1581413644",
      "ordering": 990,
      "active": false,
      "createdAt": "2020-02-11 12:34:05",
      "cancelStatus": false,
      "name": "Bbbdict-productstatuses-1581413644"
    },
    "dict-productstatuses-1581413648": {
      "code": "dict-productstatuses-1581413648",
      "ordering": 990,
      "active": false,
      "createdAt": "2020-02-11 12:34:09",
      "cancelStatus": false,
      "name": "Bbbdict-productstatuses-1581413648"
    },
    "dict-productstatuses-1581413652": {
      "code": "dict-productstatuses-1581413652",
      "ordering": 990,
      "active": false,
      "createdAt": "2020-02-11 12:34:14",
      "cancelStatus": false,
      "name": "Bbbdict-productstatuses-1581413652"
    },
    "dict-productstatuses-1581413770": {
      "code": "dict-productstatuses-1581413770",
      "ordering": 990,
      "active": false,
      "createdAt": "2020-02-11 12:36:11",
      "cancelStatus": false,
      "name": "Bbbdict-productstatuses-1581413770"
    },
    "dict-productstatuses-1581413776": {
      "code": "dict-productstatuses-1581413776",
      "ordering": 990,
      "active": false,
      "createdAt": "2020-02-11 12:36:17",
      "cancelStatus": false,
      "name": "Bbbdict-productstatuses-1581413776"
    },
    "dict-productstatuses-1581413780": {
      "code": "dict-productstatuses-1581413780",
      "ordering": 990,
      "active": false,
      "createdAt": "2020-02-11 12:36:21",
      "cancelStatus": false,
      "name": "Bbbdict-productstatuses-1581413780"
    },
    "dict-productstatuses-1581413788": {
      "code": "dict-productstatuses-1581413788",
      "ordering": 990,
      "active": false,
      "createdAt": "2020-02-11 12:36:29",
      "cancelStatus": false,
      "name": "Bbbdict-productstatuses-1581413788"
    }
  }
}
EOF;

        $mock = static::createApiMockBuilder('reference/product-statuses');
        $mock->matchMethod(RequestMethod::GET)
            ->reply(200)
            ->withBody($json);

        $client   = TestClientFactory::createClient($mock->getClient());
        $response = $client->references->productStatuses();

        self::assertModelEqualsToResponse($json, $response);
    }

    public function testProductStatusesEdit(): void
    {
        $json = <<<'EOF'
{
  "success": true
}
EOF;

        $entity           = new OrderProductStatus();
        $entity->name     = "Test Product Status";
        $entity->active   = true;
        $entity->ordering = 980;

        $request = new ProductStatusesEditRequest($entity);

        $mock = static::createApiMockBuilder('reference/product-statuses/nbsp/edit');
        $mock->matchMethod(RequestMethod::POST)
            ->matchBody(self::encodeForm($request))
            ->reply(200)
            ->withBody($json);

        $client   = TestClientFactory::createClient($mock->getClient());
        $response = $client->references->productStatusesEdit('nbsp', $request);

        self::assertModelEqualsToResponse($json, $response);
    }

    public function testSites(): void
    {
        $json = <<<'EOF'
{
  "success": true,
  "sites": {
    "test": {
      "name": "test",
      "url": "https://example.com",
      "code": "test",
      "defaultForCrm": false,
      "ymlUrl": "https://example.com",
      "loadFromYml": true,
      "catalogUpdatedAt": "2021-02-24 12:30:46",
      "catalogLoadingAt": "2021-03-02 15:09:24",
      "contragent": {
        "contragentType": "enterpreneur",
        "legalName": "???? ?????????????????? ?????????????? ??????????????????????????",
        "INN": "773719480254",
        "code": "test",
        "countryIso": "RU"
      },
      "countryIso": "RU",
      "senderEmail": "test@example.com",
      "senderName": "test",
      "catalogId": "catalog",
      "isCatalogMainSite": true
    }
  }
}
EOF;

        $mock = static::createApiMockBuilder('reference/sites');
        $mock->matchMethod(RequestMethod::GET)
            ->reply(200)
            ->withBody($json);

        $client   = TestClientFactory::createClient($mock->getClient());
        $response = $client->references->sites();

        self::assertModelsCallback($json, $response, static function (array $expected, array $actual) {
            foreach ($actual['sites'] as $key => $site) {
                if ($site['contragent'] === $expected['sites'][$key]['contragent']['code']) {
                    $actual['sites'][$key]['contragent'] = $expected['sites'][$key]['contragent'];
                }
            }

            self::assertEquals($expected, $actual);
        });
    }

    public function testSitesEdit(): void
    {
        $json = <<<'EOF'
{
  "success": true
}
EOF;

        $entity                   = new Site();
        $entity->name             = "Test Shop";
        $entity->url              = "https://example.com";
        $entity->defaultForCrm    = false;
        $entity->ymlUrl           = "https://example.com/test_catalog.xml";
        $entity->loadFromYml      = true;
        $entity->countryIso       = CountryCodeIso3166::RUSSIAN_FEDERATION;
        $entity->contragentCode   = 'test-contragent';

        $request = new SitesEditRequest($entity);

        $mock = static::createApiMockBuilder('reference/sites/test-site/edit');
        $mock->matchMethod(RequestMethod::POST)
            ->matchBody(self::encodeForm($request))
            ->reply(200)
            ->withBody($json);

        $client   = TestClientFactory::createClient($mock->getClient());
        $response = $client->references->sitesEdit('test-site', $request);

        self::assertModelEqualsToResponse($json, $response);
    }

    public function testStatusGroups(): void
    {
        $json = <<<'EOF'
{
  "success": true,
  "statusGroups": {
    "new": {
      "name": "??????????",
      "code": "new",
      "active": true,
      "ordering": 10,
      "process": false,
      "statuses": [
        "new",
        "dict-statuses-1571123798",
        "dict-statuses-1571123854",
        "dict-statuses-1571124864",
        "dict-statuses-1571124921",
        "dict-statuses-1571134107",
        "dict-statuses-1571134216",
        "dict-statuses-1575878862",
        "dict-statuses-1575878966",
        "dict-statuses-1581413644",
        "dict-statuses-1581413648",
        "dict-statuses-1581413651",
        "dict-statuses-1581413656",
        "dict-statuses-1581413772",
        "dict-statuses-1581413778",
        "dict-statuses-1581413782",
        "dict-statuses-1581413790"
      ]
    },
    "approval": {
      "name": "????????????????????????",
      "code": "approval",
      "active": true,
      "ordering": 20,
      "process": true,
      "statuses": [
        "availability-confirmed",
        "offer-analog",
        "client-confirmed",
        "prepayed"
      ]
    },
    "assembling": {
      "name": "????????????????????????",
      "code": "assembling",
      "active": true,
      "ordering": 30,
      "process": true,
      "statuses": [
        "send-to-assembling",
        "assembling",
        "assembling-complete",
        "test"
      ]
    },
    "delivery": {
      "name": "????????????????",
      "code": "delivery",
      "active": true,
      "ordering": 40,
      "process": true,
      "statuses": [
        "send-to-delivery",
        "delivering",
        "redirect",
        "delivered"
      ]
    },
    "complete": {
      "name": "????????????????",
      "code": "complete",
      "active": true,
      "ordering": 50,
      "process": false,
      "statuses": [
        "complete",
        "3"
      ]
    },
    "cancel": {
      "name": "??????????????",
      "code": "cancel",
      "active": true,
      "ordering": 60,
      "process": false,
      "statuses": [
        "no-call",
        "no-product",
        "already-buyed",
        "delyvery-did-not-suit",
        "prices-did-not-suit",
        "cancel-other",
        "testdubl",
        "abandoned-cart"
      ]
    }
  }
}
EOF;

        $mock = static::createApiMockBuilder('reference/status-groups');
        $mock->matchMethod(RequestMethod::GET)
            ->reply(200)
            ->withBody($json);

        $client   = TestClientFactory::createClient($mock->getClient());
        $response = $client->references->statusGroups();

        self::assertModelEqualsToResponse($json, $response);
    }

    public function testStatuses(): void
    {
        $json = <<<'EOF'
{
  "success": true,
  "statuses": {
    "3": {
      "name": "3",
      "code": "3",
      "active": true,
      "ordering": 990,
      "group": "complete"
    },
    "new": {
      "name": "??????????",
      "code": "new",
      "active": true,
      "ordering": 10,
      "group": "new"
    },
    "complete": {
      "name": "????????????????",
      "code": "complete",
      "active": true,
      "ordering": 10,
      "group": "complete"
    },
    "availability-confirmed": {
      "name": "?????????????? ????????????????????????",
      "code": "availability-confirmed",
      "active": true,
      "ordering": 10,
      "group": "approval"
    },
    "offer-analog": {
      "name": "???????????????????? ????????????",
      "code": "offer-analog",
      "active": true,
      "ordering": 20,
      "group": "approval"
    },
    "client-confirmed": {
      "name": "?????????????????????? ?? ????????????????",
      "code": "client-confirmed",
      "active": true,
      "ordering": 30,
      "group": "approval"
    },
    "prepayed": {
      "name": "???????????????????? ??????????????????",
      "code": "prepayed",
      "active": true,
      "ordering": 40,
      "group": "approval"
    },
    "send-to-assembling": {
      "name": "???????????????? ?? ????????????????????????",
      "code": "send-to-assembling",
      "active": true,
      "ordering": 10,
      "group": "assembling"
    },
    "assembling": {
      "name": "??????????????????????????",
      "code": "assembling",
      "active": true,
      "ordering": 20,
      "group": "assembling"
    },
    "assembling-complete": {
      "name": "??????????????????????????",
      "code": "assembling-complete",
      "active": true,
      "ordering": 30,
      "group": "assembling"
    },
    "send-to-delivery": {
      "name": "?????????????? ?? ????????????????",
      "code": "send-to-delivery",
      "active": true,
      "ordering": 10,
      "group": "delivery"
    },
    "delivering": {
      "name": "????????????????????????",
      "code": "delivering",
      "active": true,
      "ordering": 20,
      "group": "delivery"
    },
    "redirect": {
      "name": "???????????????? ????????????????????",
      "code": "redirect",
      "active": true,
      "ordering": 30,
      "group": "delivery"
    },
    "no-call": {
      "name": "????????????????",
      "code": "no-call",
      "active": true,
      "ordering": 10,
      "group": "cancel"
    },
    "no-product": {
      "name": "?????? ?? ??????????????",
      "code": "no-product",
      "active": true,
      "ordering": 20,
      "group": "cancel"
    },
    "already-buyed": {
      "name": "?????????? ?? ???????????? ??????????",
      "code": "already-buyed",
      "active": true,
      "ordering": 30,
      "group": "cancel"
    },
    "delyvery-did-not-suit": {
      "name": "???? ???????????????? ????????????????",
      "code": "delyvery-did-not-suit",
      "active": true,
      "ordering": 40,
      "group": "cancel"
    },
    "prices-did-not-suit": {
      "name": "???? ???????????????? ????????",
      "code": "prices-did-not-suit",
      "active": true,
      "ordering": 50,
      "group": "cancel"
    },
    "cancel-other": {
      "name": "??????????????",
      "code": "cancel-other",
      "active": true,
      "ordering": 60,
      "group": "cancel"
    },
    "testdubl": {
      "name": "testDubl",
      "code": "testdubl",
      "active": true,
      "ordering": 990,
      "group": "cancel"
    },
    "test": {
      "name": "test",
      "code": "test",
      "active": true,
      "ordering": 990,
      "group": "assembling"
    },
    "dict-statuses-1571123798": {
      "name": "Bbbdict-statuses-1571123798",
      "code": "dict-statuses-1571123798",
      "active": false,
      "ordering": 990,
      "group": "new"
    },
    "dict-statuses-1571123854": {
      "name": "Bbbdict-statuses-1571123854",
      "code": "dict-statuses-1571123854",
      "active": false,
      "ordering": 990,
      "group": "new"
    },
    "dict-statuses-1571124864": {
      "name": "Bbbdict-statuses-1571124864",
      "code": "dict-statuses-1571124864",
      "active": false,
      "ordering": 990,
      "group": "new"
    },
    "dict-statuses-1571124921": {
      "name": "Bbbdict-statuses-1571124921",
      "code": "dict-statuses-1571124921",
      "active": false,
      "ordering": 990,
      "group": "new"
    },
    "dict-statuses-1571134107": {
      "name": "Bbbdict-statuses-1571134107",
      "code": "dict-statuses-1571134107",
      "active": false,
      "ordering": 990,
      "group": "new"
    },
    "dict-statuses-1571134216": {
      "name": "Bbbdict-statuses-1571134216",
      "code": "dict-statuses-1571134216",
      "active": false,
      "ordering": 990,
      "group": "new"
    },
    "abandoned-cart": {
      "name": "?????????????????? ??????????????",
      "code": "abandoned-cart",
      "active": true,
      "ordering": 990,
      "group": "cancel"
    },
    "dict-statuses-1575878862": {
      "name": "Bbbdict-statuses-1575878862",
      "code": "dict-statuses-1575878862",
      "active": false,
      "ordering": 990,
      "group": "new"
    },
    "dict-statuses-1575878966": {
      "name": "Bbbdict-statuses-1575878966",
      "code": "dict-statuses-1575878966",
      "active": false,
      "ordering": 990,
      "group": "new"
    },
    "dict-statuses-1581413644": {
      "name": "Bbbdict-statuses-1581413644",
      "code": "dict-statuses-1581413644",
      "active": false,
      "ordering": 990,
      "group": "new"
    },
    "dict-statuses-1581413648": {
      "name": "Bbbdict-statuses-1581413648",
      "code": "dict-statuses-1581413648",
      "active": false,
      "ordering": 990,
      "group": "new"
    },
    "dict-statuses-1581413651": {
      "name": "Bbbdict-statuses-1581413651",
      "code": "dict-statuses-1581413651",
      "active": false,
      "ordering": 990,
      "group": "new"
    },
    "dict-statuses-1581413656": {
      "name": "Bbbdict-statuses-1581413656",
      "code": "dict-statuses-1581413656",
      "active": false,
      "ordering": 990,
      "group": "new"
    },
    "dict-statuses-1581413772": {
      "name": "Bbbdict-statuses-1581413772",
      "code": "dict-statuses-1581413772",
      "active": false,
      "ordering": 990,
      "group": "new"
    },
    "dict-statuses-1581413778": {
      "name": "Bbbdict-statuses-1581413778",
      "code": "dict-statuses-1581413778",
      "active": false,
      "ordering": 990,
      "group": "new"
    },
    "dict-statuses-1581413782": {
      "name": "Bbbdict-statuses-1581413782",
      "code": "dict-statuses-1581413782",
      "active": false,
      "ordering": 990,
      "group": "new"
    },
    "dict-statuses-1581413790": {
      "name": "Bbbdict-statuses-1581413790",
      "code": "dict-statuses-1581413790",
      "active": false,
      "ordering": 990,
      "group": "new"
    },
    "delivered": {
      "name": "??????????????????",
      "code": "delivered",
      "active": true,
      "ordering": 40,
      "group": "delivery"
    }
  }
}
EOF;

        $mock = static::createApiMockBuilder('reference/statuses');
        $mock->matchMethod(RequestMethod::GET)
            ->reply(200)
            ->withBody($json);

        $client   = TestClientFactory::createClient($mock->getClient());
        $response = $client->references->statuses();

        self::assertModelEqualsToResponse($json, $response);
    }

    public function testStatusesEdit(): void
    {
        $json = <<<'EOF'
{
  "success": true
}
EOF;

        $entity           = new Status();
        $entity->name     = "Test Status";
        $entity->active   = true;
        $entity->ordering = 990;
        $entity->group    = "assembling";

        $request = new StatusesEditRequest($entity);

        $mock = static::createApiMockBuilder('reference/statuses/test/edit');
        $mock->matchMethod(RequestMethod::POST)
            ->matchBody(self::encodeForm($request))
            ->reply(200)
            ->withBody($json);

        $client   = TestClientFactory::createClient($mock->getClient());
        $response = $client->references->statusesEdit('test', $request);

        self::assertModelEqualsToResponse($json, $response);
    }

    public function testStores(): void
    {
        $json = <<<'EOF'
{
  "success": true,
  "stores": [
    {
      "description": "????????????",
      "type": "store-type-warehouse",
      "inventoryType": "integer",
      "address": {
        "countryIso": "RU",
        "region": "???????????? ??????????",
        "regionId": 55,
        "city": "????????????",
        "cityId": 4995,
        "cityType": "??.",
        "street": "????????????",
        "streetId": 1777155,
        "streetType": "????.",
        "building": "23",
        "text": "????. ????????????, ??. 23",
        "coordinates": {
          "latitude": 51.660781,
          "longitude": 39.200269
        }
      },
      "active": true,
      "phone": {

      },
      "code": "main12",
      "workTime": {
        "mo": [

        ],
        "tu": [

        ],
        "we": [

        ],
        "th": [

        ],
        "fr": [

        ],
        "sa": [

        ],
        "su": [

        ]
      },
      "name": "????????????????"
    },
    {
      "description": "??????????-??????????????????",
      "type": "store-type-warehouse",
      "inventoryType": "available",
      "address": {
        "index": "196626",
        "countryIso": "RU",
        "region": "??????????-?????????????????? ??????????",
        "regionId": 48,
        "city": "??????????-??????????????????",
        "cityId": 5000,
        "cityType": "??.",
        "streetType": "????."
      },
      "active": true,
      "phone": {

      },
      "code": "food",
      "workTime": {
        "mo": [

        ],
        "tu": [

        ],
        "we": [

        ],
        "th": [

        ],
        "fr": [

        ],
        "sa": [

        ],
        "su": [

        ]
      },
      "name": "??????????????????????"
    },
    {
      "description": "??????????",
      "email": "luchshe@pozvonit.chemukogotozanimat",
      "type": "store-type-warehouse",
      "inventoryType": "integer",
      "address": {
        "index": "170040",
        "countryIso": "RU",
        "region": "???????????????? ??????????????",
        "regionId": 74,
        "city": "??????????",
        "cityId": 4833,
        "cityType": "??.",
        "street": "????. 2-?? ????????????????????????",
        "building": "50",
        "flat": "13",
        "house": "5",
        "housing": "1",
        "text": "????. 2-?? ????????????????????????, ??. 50, ??????. 5, ????????. 1, ????./???????? 13"
      },
      "active": true,
      "phone": {
        "number": "88005553535"
      },
      "code": "tver",
      "workTime": {
        "mo": [

        ],
        "tu": [

        ],
        "we": [

        ],
        "th": [

        ],
        "fr": [

        ],
        "sa": [

        ],
        "su": [

        ]
      },
      "name": "??????????"
    },
    {
      "type": "store-type-warehouse",
      "inventoryType": "integer",
      "address": {
        "countryIso": "RU",
        "region": "???????????? ????????????????????",
        "regionId": 26,
        "city": "????????????",
        "cityId": 1,
        "cityType": "??.",
        "street": "????????????",
        "streetId": 961854,
        "streetType": "????.",
        "building": "50",
        "flat": "13",
        "house": "1",
        "housing": "2",
        "text": "????. ????????????, ??. 50, ??????. 1, ????????. 2, ????./???????? 13"
      },
      "active": true,
      "phone": {

      },
      "code": "antisklad",
      "workTime": {
        "mo": [

        ],
        "tu": [

        ],
        "we": [

        ],
        "th": [

        ],
        "fr": [

        ],
        "sa": [

        ],
        "su": [

        ]
      },
      "name": "??????????????????"
    },
    {
      "type": "store-type-warehouse",
      "inventoryType": "integer",
      "address": {
        "countryIso": "RU",
        "region": "???????????? ??????????",
        "regionId": 55,
        "city": "????????????",
        "cityId": 4995,
        "cityType": "??.",
        "street": "??????????????????????????",
        "streetId": 1780121,
        "streetType": "????.",
        "building": "48",
        "flat": "30",
        "metro": "???????????????????? ??????????????/????????????????????",
        "text": "????. ??????????????????????????, ??. 48, ????./???????? 30, ?????????? ???????????????????? ??????????????/????????????????????"
      },
      "active": true,
      "phone": {

      },
      "code": "castan",
      "workTime": {
        "mo": [

        ],
        "tu": [

        ],
        "we": [

        ],
        "th": [

        ],
        "fr": [

        ],
        "sa": [

        ],
        "su": [

        ]
      },
      "name": "??????????????????????????"
    },
    {
      "inventoryType": "integer",
      "address": {

      },
      "active": true,
      "code": "test-store-v4",
      "workTime": {
        "mo": [

        ],
        "tu": [

        ],
        "we": [

        ],
        "th": [

        ],
        "fr": [

        ],
        "sa": [

        ],
        "su": [

        ]
      },
      "name": "Test Store V4"
    },
    {
      "type": "store-type-warehouse",
      "inventoryType": "integer",
      "address": {
        "countryIso": "RU"
      },
      "active": true,
      "phone": {

      },
      "code": "test-store-v5",
      "workTime": {
        "mo": [

        ],
        "tu": [

        ],
        "we": [

        ],
        "th": [

        ],
        "fr": [

        ],
        "sa": [

        ],
        "su": [

        ]
      },
      "name": "Test Store V5"
    },
    {
      "type": "store-type-warehouse",
      "inventoryType": "integer",
      "address": {
        "countryIso": "UA",
        "region": "?????????????????? ??????????????",
        "regionId": 104,
        "city": "??????????????",
        "cityId": 16054,
        "cityType": "??.",
        "street": "??????????????",
        "streetId": 1814071,
        "streetType": "????.",
        "building": "12",
        "text": "????. ??????????????, ??. 12"
      },
      "active": true,
      "phone": {

      },
      "code": "weiser_store",
      "workTime": {
        "mo": [

        ],
        "tu": [

        ],
        "we": [

        ],
        "th": [

        ],
        "fr": [

        ],
        "sa": [

        ],
        "su": [

        ]
      },
      "name": "weiser store"
    },
    {
      "type": "store-type-warehouse",
      "inventoryType": "integer",
      "address": {
        "countryIso": "RU",
        "region": "???????????????????? ??????????????",
        "regionId": 73,
        "city": "????????????-????-????????",
        "cityId": 4298,
        "cityType": "??."
      },
      "active": true,
      "phone": {

      },
      "code": "rostov",
      "workTime": {
        "mo": [

        ],
        "tu": [

        ],
        "we": [

        ],
        "th": [

        ],
        "fr": [

        ],
        "sa": [

        ],
        "su": [

        ]
      },
      "name": "????????????"
    },
    {
      "description": "??????????????????????",
      "type": "store-type-warehouse",
      "inventoryType": "integer",
      "address": {
        "countryIso": "RU",
        "region": "?????????????????????????????? ??????????????",
        "regionId": 30,
        "city": "??????????????????????",
        "cityId": 2798,
        "cityType": "??.",
        "street": "9 ????????????",
        "streetId": 1341484,
        "streetType": "????.",
        "building": "5",
        "text": "????. 9 ????????????, ??. 5"
      },
      "active": true,
      "phone": {

      },
      "code": "klgd",
      "workTime": {
        "mo": [

        ],
        "tu": [

        ],
        "we": [

        ],
        "th": [

        ],
        "fr": [

        ],
        "sa": [

        ],
        "su": [

        ]
      },
      "name": "??????????????????????"
    }
  ]
}
EOF;

        $mock = static::createApiMockBuilder('reference/stores');
        $mock->matchMethod(RequestMethod::GET)
            ->reply(200)
            ->withBody($json);

        $client   = TestClientFactory::createClient($mock->getClient());
        $response = $client->references->stores();

        self::assertModelEqualsToResponse($json, $response);
    }

    public function testStoresEdit(): void
    {
        $json = <<<'EOF'
{
  "success": true
}
EOF;

        $entity                      = new Store();
        $entity->address             = new StoreAddress();
        $entity->address->countryIso = "UA";
        $entity->address->region     = "?????????????????? ??????????????";
        $entity->address->regionId   = 104;
        $entity->address->city       = "??????????????";
        $entity->address->cityId     = 16054;
        $entity->address->cityType   = "??.";
        $entity->address->street     = "??????????????";
        $entity->address->streetId   = 1814071;
        $entity->address->streetType = "????.";
        $entity->address->building   = "12";
        $entity->address->text       = "????. ??????????????, ??. 12";
        $entity->workTime            = new SerializedStoreWeekOpeningHours(
            [new StoreWorkTime('9:00', '18:00', '12:00', '13:00')],
            [new StoreWorkTime('9:00', '18:00', '12:00', '13:00')],
            [new StoreWorkTime('9:00', '18:00', '12:00', '13:00')],
            [new StoreWorkTime('9:00', '18:00', '12:00', '13:00')],
            [new StoreWorkTime('9:00', '18:00', '12:00', '13:00')]
        );
        $entity->type                = StoreType::STORE_TYPE_WAREHOUSE;
        $entity->inventoryType       = StoreInventoryType::INTEGER;
        $entity->active              = true;
        $entity->phone               = new StorePhone('88005553123');
        $entity->name                = 'Test Store';

        $request = new StoresEditRequest($entity);

        $mock = static::createApiMockBuilder('reference/stores/test/edit');
        $mock->matchMethod(RequestMethod::POST)
            ->matchBody(self::encodeForm($request))
            ->reply(200)
            ->withBody($json);

        $client   = TestClientFactory::createClient($mock->getClient());
        $response = $client->references->storesEdit('test', $request);

        self::assertModelEqualsToResponse($json, $response);
    }

    public function testUnits(): void
    {
        $json = <<<'EOF'
{
  "success": true,
  "units": {
    "6": {
      "code": "6",
      "name": "????????",
      "sym": "??",
      "default": false,
      "active": true
    },
    "112": {
      "code": "112",
      "name": "????????",
      "sym": "??.",
      "default": false,
      "active": true
    },
    "163": {
      "code": "163",
      "name": "??????????",
      "sym": "??",
      "default": false,
      "active": true
    },
    "166": {
      "code": "166",
      "name": "??????????????????",
      "sym": "????",
      "default": false,
      "active": true
    },
    "366": {
      "code": "366",
      "name": "??????",
      "sym": "??; ????",
      "default": false,
      "active": true
    },
    "796": {
      "code": "796",
      "name": "??????????",
      "sym": "????",
      "default": false,
      "active": true
    },
    "797": {
      "code": "797",
      "name": "??????. ??",
      "sym": "??????. ",
      "default": false,
      "active": true
    },
    "798": {
      "code": "798",
      "name": "???????? 10 ????",
      "sym": "???????? ",
      "default": false,
      "active": true
    },
    "799": {
      "code": "799",
      "name": "????????",
      "sym": "????????",
      "default": false,
      "active": true
    },
    "800": {
      "code": "800",
      "name": "???? (2 ??????. ??)",
      "sym": "???? (2",
      "default": false,
      "active": true
    },
    "801": {
      "code": "801",
      "name": "??????????",
      "sym": "??????????",
      "default": false,
      "active": true
    },
    "802": {
      "code": "802",
      "name": "??????",
      "sym": "??????",
      "default": false,
      "active": true
    },
    "803": {
      "code": "803",
      "name": "?????????? (10 ??????)",
      "sym": "??????????",
      "default": false,
      "active": true
    },
    "805": {
      "code": "805",
      "name": "??????.",
      "sym": "??????.",
      "default": false,
      "active": true
    },
    "806": {
      "code": "806",
      "name": "??????????",
      "sym": "??????????",
      "default": false,
      "active": true
    },
    "808": {
      "code": "808",
      "name": "???? (2 ??)",
      "sym": "???? (2",
      "default": false,
      "active": true
    },
    "809": {
      "code": "809",
      "name": "??2",
      "sym": "??2",
      "default": false,
      "active": true
    },
    "810": {
      "code": "810",
      "name": "???????? (10 ????)",
      "sym": "???????? ",
      "default": false,
      "active": true
    },
    "811": {
      "code": "811",
      "name": "?????????? (15 ??)",
      "sym": "??????????",
      "default": false,
      "active": true
    },
    "812": {
      "code": "812",
      "name": "?????????? (25 ??)",
      "sym": "??????????",
      "default": false,
      "active": true
    },
    "813": {
      "code": "813",
      "name": "?????????? (20 ??)",
      "sym": "??????????",
      "default": false,
      "active": true
    },
    "815": {
      "code": "815",
      "name": "???? (4 ??????. ??)",
      "sym": "???? (4",
      "default": false,
      "active": true
    },
    "816": {
      "code": "816",
      "name": "?????????? (100 ??)",
      "sym": "??????????",
      "default": false,
      "active": true
    },
    "817": {
      "code": "817",
      "name": "?????????? (50 ??)",
      "sym": "??????????",
      "default": false,
      "active": true
    },
    "818": {
      "code": "818",
      "name": "???????? (5 ????)",
      "sym": "???????? ",
      "default": false,
      "active": true
    },
    "819": {
      "code": "819",
      "name": "?????????? (50 ??????. ??)",
      "sym": "??????????",
      "default": false,
      "active": true
    },
    "820": {
      "code": "820",
      "name": "?????????? (30 ??????. ??)",
      "sym": "??????????",
      "default": false,
      "active": true
    },
    "821": {
      "code": "821",
      "name": "?????????? (25 ??????. ??)",
      "sym": "??????????",
      "default": false,
      "active": true
    },
    "824": {
      "code": "824",
      "name": "???????? (2 ????)",
      "sym": "???????? ",
      "default": false,
      "active": true
    },
    "825": {
      "code": "825",
      "name": "??????????",
      "sym": "??????????",
      "default": false,
      "active": true
    },
    "830": {
      "code": "830",
      "name": "??????????",
      "sym": "??????????",
      "default": false,
      "active": true
    },
    "831": {
      "code": "831",
      "name": "??????",
      "sym": "??????",
      "default": false,
      "active": true
    },
    "832": {
      "code": "832",
      "name": "??????????",
      "sym": "??????????",
      "default": false,
      "active": true
    },
    "833": {
      "code": "833",
      "name": "??????????",
      "sym": "??????????",
      "default": false,
      "active": true
    },
    "834": {
      "code": "834",
      "name": "??????",
      "sym": "??????",
      "default": false,
      "active": true
    },
    "838": {
      "code": "838",
      "name": "???????? (5 ????)",
      "sym": "???????? ",
      "default": false,
      "active": true
    },
    "843": {
      "code": "843",
      "name": "??2 (3,99 ????)",
      "sym": "??2 (3",
      "default": false,
      "active": true
    },
    "844": {
      "code": "844",
      "name": "????????",
      "sym": "????????",
      "default": false,
      "active": true
    },
    "847": {
      "code": "847",
      "name": "??2 (4,048 ????)",
      "sym": "??2 (4",
      "default": false,
      "active": true
    },
    "849": {
      "code": "849",
      "name": "?????????? (??????????????)",
      "sym": "??????????",
      "default": false,
      "active": true
    },
    "850": {
      "code": "850",
      "name": "????????.",
      "sym": "????????.",
      "default": false,
      "active": true
    },
    "852": {
      "code": "852",
      "name": "??????.",
      "sym": "??????.",
      "default": false,
      "active": true
    },
    "853": {
      "code": "853",
      "name": "???? (3 ??????. ??)",
      "sym": "???? (3",
      "default": false,
      "active": true
    },
    "862": {
      "code": "862",
      "name": "???? (2,2 ??????. ??)",
      "sym": "???? (2",
      "default": false,
      "active": true
    },
    "865": {
      "code": "865",
      "name": "???? (0,016 ??3)",
      "sym": "???? (0",
      "default": false,
      "active": true
    },
    "867": {
      "code": "867",
      "name": "????????????",
      "sym": "??????????",
      "default": false,
      "active": true
    },
    "869": {
      "code": "869",
      "name": "???? (75 ??2)",
      "sym": "???? (7",
      "default": false,
      "active": true
    },
    "871": {
      "code": "871",
      "name": "???????? (4 ????)",
      "sym": "???????? ",
      "default": false,
      "active": true
    },
    "873": {
      "code": "873",
      "name": "???? (2,5 ??????. ??)",
      "sym": "???? (2",
      "default": false,
      "active": true
    },
    "874": {
      "code": "874",
      "name": "?????? (25 ??2)",
      "sym": "?????? (",
      "default": false,
      "active": true
    },
    "875": {
      "code": "875",
      "name": "??2 (4,055 ????)",
      "sym": "??2 (4",
      "default": false,
      "active": true
    },
    "876": {
      "code": "876",
      "name": "??????",
      "sym": "??????",
      "default": false,
      "active": true
    },
    "884": {
      "code": "884",
      "name": "???????? (3 ????)",
      "sym": "???????? ",
      "default": false,
      "active": true
    },
    "886": {
      "code": "886",
      "name": "??.",
      "sym": "??.",
      "default": false,
      "active": true
    },
    "887": {
      "code": "887",
      "name": "??2 (4,047 ????)",
      "sym": "??2 (4",
      "default": false,
      "active": true
    },
    "891": {
      "code": "891",
      "name": "???? (2,06 ??????. ??)",
      "sym": "???? (2",
      "default": false,
      "active": true
    },
    "899": {
      "code": "899",
      "name": "???? (0,047 ??3)",
      "sym": "???? (0",
      "default": false,
      "active": true
    },
    "900": {
      "code": "900",
      "name": "?????? (0,2 ????)",
      "sym": "?????? (",
      "default": false,
      "active": true
    },
    "910": {
      "code": "910",
      "name": "?????? (75 ??2)",
      "sym": "?????? (",
      "default": false,
      "active": true
    },
    "912": {
      "code": "912",
      "name": "???? (0,045 ??3)",
      "sym": "???? (0",
      "default": false,
      "active": true
    },
    "915": {
      "code": "915",
      "name": "???????? (6 ????)",
      "sym": "???????? ",
      "default": false,
      "active": true
    },
    "1429": {
      "code": "1429",
      "name": "???? (1 ????????)",
      "sym": "???? (1",
      "default": false,
      "active": true
    },
    "1712": {
      "code": "1712",
      "name": "???????? (10 ????)",
      "sym": "???????? ",
      "default": false,
      "active": true
    },
    "1761": {
      "code": "1761",
      "name": "?????? (40??2)",
      "sym": "?????? (",
      "default": false,
      "active": true
    },
    "2203": {
      "code": "2203",
      "name": "??????. ?? (0,125 ??????)",
      "sym": "??????. ",
      "default": false,
      "active": true
    },
    "2204": {
      "code": "2204",
      "name": "??????????",
      "sym": "??????????",
      "default": false,
      "active": true
    },
    "2543": {
      "code": "2543",
      "name": "???????? (5,25 ??2)",
      "sym": "???????? ",
      "default": false,
      "active": true
    },
    "2544": {
      "code": "2544",
      "name": "???????? (8,4 ??2)",
      "sym": "???????? ",
      "default": false,
      "active": true
    },
    "2558": {
      "code": "2558",
      "name": "??????. ?? (2 ??)",
      "sym": "??????. ",
      "default": false,
      "active": true
    },
    "2559": {
      "code": "2559",
      "name": "???????? (6 ??2)",
      "sym": "???????? ",
      "default": false,
      "active": true
    },
    "2568": {
      "code": "2568",
      "name": "??. (9 ????)",
      "sym": "??. (9",
      "default": false,
      "active": true
    },
    "2576": {
      "code": "2576",
      "name": "???????? (0,84 ??2)",
      "sym": "???????? ",
      "default": false,
      "active": true
    },
    "2579": {
      "code": "2579",
      "name": "?????? (50 ??2)",
      "sym": "?????? (",
      "default": false,
      "active": true
    },
    "2582": {
      "code": "2582",
      "name": "???????? (5 ??2)",
      "sym": "???????? ",
      "default": false,
      "active": true
    },
    "2589": {
      "code": "2589",
      "name": "?????? (16,5 ??2)",
      "sym": "?????? (",
      "default": false,
      "active": true
    },
    "2590": {
      "code": "2590",
      "name": "???? (0,107 ??2)",
      "sym": "???? (0",
      "default": false,
      "active": true
    },
    "2591": {
      "code": "2591",
      "name": "??????. ?? (0,5 ??2)",
      "sym": "??????. ",
      "default": false,
      "active": true
    },
    "2592": {
      "code": "2592",
      "name": "??????. ?? (0,35 ??2)",
      "sym": "??????. ",
      "default": false,
      "active": true
    },
    "2593": {
      "code": "2593",
      "name": "??????. ?? (0,25 ??2)",
      "sym": "??????. ",
      "default": false,
      "active": true
    },
    "2595": {
      "code": "2595",
      "name": "?????????? (10 ??)",
      "sym": "??????????",
      "default": false,
      "active": true
    },
    "2599": {
      "code": "2599",
      "name": "???????? (0,76 ??????. ??)",
      "sym": "???????? ",
      "default": false,
      "active": true
    },
    "2600": {
      "code": "2600",
      "name": "???????? (1,04 ??2)",
      "sym": "???????? ",
      "default": false,
      "active": true
    },
    "2602": {
      "code": "2602",
      "name": "???????? (1,2 ??????. ??)",
      "sym": "???????? ",
      "default": false,
      "active": true
    },
    "2604": {
      "code": "2604",
      "name": "???????? (0,79 ??2)",
      "sym": "???????? ",
      "default": false,
      "active": true
    },
    "2609": {
      "code": "2609",
      "name": "?? (0,01 ??????????)",
      "sym": "?? (0,",
      "default": false,
      "active": true
    },
    "2610": {
      "code": "2610",
      "name": "???????? (1,08 ??????. ??)",
      "sym": "???????? ",
      "default": false,
      "active": true
    },
    "2611": {
      "code": "2611",
      "name": "???????? (0,98 ??????. ??)",
      "sym": "???????? ",
      "default": false,
      "active": true
    },
    "2612": {
      "code": "2612",
      "name": "???????? (1,05 ??????. ??)",
      "sym": "???????? ",
      "default": false,
      "active": true
    },
    "2613": {
      "code": "2613",
      "name": "???????? (1,5 ??????. ??)",
      "sym": "???????? ",
      "default": false,
      "active": true
    },
    "2629": {
      "code": "2629",
      "name": "???????? (0,57 ??2)",
      "sym": "???????? ",
      "default": false,
      "active": true
    },
    "2637": {
      "code": "2637",
      "name": "??????????(25 ??????.??.)",
      "sym": "??????????",
      "default": false,
      "active": true
    },
    "2653": {
      "code": "2653",
      "name": "???????? (5 ??)",
      "sym": "???????? ",
      "default": false,
      "active": true
    },
    "pc": {
      "code": "pc",
      "name": "??????????",
      "sym": "????.",
      "default": true,
      "active": true
    },
    "kg": {
      "code": "kg",
      "name": "??????????????????",
      "sym": "????",
      "default": false,
      "active": true
    },
    "m": {
      "code": "m",
      "name": "????????",
      "sym": "??",
      "default": false,
      "active": true
    },
    "l": {
      "code": "l",
      "name": "????????",
      "sym": "??.",
      "default": false,
      "active": true
    },
    "pkg": {
      "code": "pkg",
      "name": "????????????????",
      "sym": "????????.",
      "default": false,
      "active": true
    },
    "pcs": {
      "code": "pcs",
      "name": "??????????",
      "sym": "????.",
      "default": false,
      "active": true
    },
    "nuqbm77fiiEsB0aZNoiik2": {
      "code": "nuqbm77fiiEsB0aZNoiik2",
      "name": "azgalot",
      "sym": "azgal",
      "default": false,
      "active": true
    },
    "pce": {
      "code": "pce",
      "name": "??????????",
      "sym": "????",
      "default": false,
      "active": true
    },
    "nmp": {
      "code": "nmp",
      "name": "????????????????",
      "sym": "????????",
      "default": false,
      "active": true
    },
    "kgm": {
      "code": "kgm",
      "name": "??????????????????",
      "sym": "????",
      "default": false,
      "active": true
    },
    "mtr": {
      "code": "mtr",
      "name": "????????",
      "sym": "??",
      "default": false,
      "active": true
    },
    "nbb": {
      "code": "nbb",
      "name": "????????????",
      "sym": "??????",
      "default": false,
      "active": true
    },
    "npl": {
      "code": "npl",
      "name": "??????????",
      "sym": "??????",
      "default": false,
      "active": true
    },
    "azg": {
      "code": "azg",
      "name": "azgalot",
      "sym": "azg",
      "default": false,
      "active": true
    },
    "cen": {
      "code": "cen",
      "name": "100 ????????",
      "sym": "100????",
      "default": false,
      "active": true
    }
  }
}
EOF;

        $mock = static::createApiMockBuilder('reference/units');
        $mock->matchMethod(RequestMethod::GET)
            ->reply(200)
            ->withBody($json);

        $client   = TestClientFactory::createClient($mock->getClient());
        $response = $client->references->units();

        self::assertModelEqualsToResponse($json, $response);
    }

    public function testUnitsEdit(): void
    {
        $json = <<<'EOF'
{
  "success": true
}
EOF;

        $entity         = new SerializedUnit();
        $entity->name   = "????????????";
        $entity->sym    = "??????";
        $entity->active = true;

        $request = new UnitsEditRequest($entity);

        $mock = static::createApiMockBuilder('reference/units/nbb/edit');
        $mock->matchMethod(RequestMethod::POST)
            ->matchBody(self::encodeForm($request))
            ->reply(200)
            ->withBody($json);

        $client   = TestClientFactory::createClient($mock->getClient());
        $response = $client->references->unitsEdit('nbb', $request);

        self::assertModelEqualsToResponse($json, $response);
    }
}
