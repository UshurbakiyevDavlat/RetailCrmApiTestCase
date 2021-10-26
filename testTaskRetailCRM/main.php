<?php

use RetailCrm\Api\Exception\Api\AccountDoesNotExistException;
use RetailCrm\Api\Exception\Api\ApiErrorException;
use RetailCrm\Api\Exception\Api\MissingCredentialsException;
use RetailCrm\Api\Exception\Api\MissingParameterException;
use RetailCrm\Api\Exception\Api\ValidationException;
use RetailCrm\Api\Exception\Client\BuilderException;
use RetailCrm\Api\Exception\Client\HandlerException;
use RetailCrm\Api\Exception\Client\HttpClientException;
use RetailCrm\Api\Factory\SimpleClientFactory;
use RetailCrm\Api\Interfaces\ApiExceptionInterface;
use RetailCrm\Api\Interfaces\ClientExceptionInterface;
use RetailCrm\Api\Model\Entity\Customers\Customer;
use RetailCrm\Api\Model\Entity\Orders\Items\Offer;
use RetailCrm\Api\Model\Entity\Orders\Items\OrderProduct;
use RetailCrm\Api\Model\Entity\Orders\Items\PriceType;
use RetailCrm\Api\Model\Entity\Orders\Items\Unit;
use RetailCrm\Api\Model\Entity\Orders\Order;
use RetailCrm\Api\Model\Entity\Orders\Payment;
use RetailCrm\Api\Model\Request\Customers\CustomersCreateRequest;
use RetailCrm\Api\Model\Request\Orders\OrdersCreateRequest;

require $_SERVER['DOCUMENT_ROOT'] . '\RetailCrmApiTestCase\testTaskRetailCRM\vendor\autoload.php';

class RetailCRMApi
{


    /**
     * @throws BuilderException
     * @throws ClientExceptionInterface
     */
    public function connection()
    {
        return SimpleClientFactory::createClient('https://superposuda.retailcrm.ru/', 'QlnRWTTWw9lv3kjxy1A8byjUmBQedYqb');
    }


    /**
     * @throws ClientExceptionInterface
     * @throws BuilderException
     */
    public function createOrderRequest()
    {

        $request = new OrdersCreateRequest();
        $order = new Order();
        $offer = new Offer();
        $items = new OrderProduct();
        $payment = new Payment();

        //оплата
        $payment->type = 'bank-card';
        $payment->status = 'paid';
        $payment->amount = 1000;
        $payment->paidAt = new DateTime();

        //характеристика товара
        $offer->name = 'AZ105R Azalita';
        $offer->displayName = 'Маникюрный набор AZ105R Azalita';
        $offer->xmlId = 'tGunLo27jlPGmbA8BrHxY2';
        $offer->article = 'AZ105R';
        $offer->unit = new Unit('AZ105R', 'Azalita', 'Azalita');

        // товар
        $items->offer = $offer;
        $items->priceType = new PriceType('base');
        $items->quantity = 1;
        $items->purchasePrice = 60;

        // заказ
        $order->items = [$items];
        $order->payments = [$payment];
        $order->status = "trouble";
        $order->orderType = "fzik"; // тип заказа
        $order->orderMethod = "test";// способ оформления
        $order->number = "19032000"; // номер заказа
        $order->firstName = "Davlatbek"; // Фамилия
        $order->lastName = "Ushurbakiyev"; // Имя
        $order->customFields = [
            "prim" => "тестовое задание"
        ]; // примечание
        $order->managerComment = "https://github.com/UshurbakiyevDavlat/RetailCrmApiTestCase"; // коммент


        $request->order = $order; // инициализация товара
        $request->site = "test"; // магазин

        $client = self::connection(); // запуск соединения
        $response = [];

        try {
            $response = $client->orders->create($request); // создание запроса на создание товара
        } catch (AccountDoesNotExistException | ApiErrorException | MissingCredentialsException | MissingParameterException | ValidationException | HandlerException | HttpClientException | ApiExceptionInterface | ClientExceptionInterface $e) {
        }
        printf(
            'Created order id = %d with the following data: %s',
            $response->id, // вывод
            print_r($response->order, true)
        );
    }

    /**
     * @throws ClientExceptionInterface
     * @throws BuilderException
     */
    public function main()
    {
        self::createOrderRequest();
    }

}

$callManager = new RetailCRMApi();

try {
    $callManager->main();
} catch (BuilderException | ClientExceptionInterface $e) {
}

