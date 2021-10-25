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
use RetailCrm\Api\Model\Entity\Orders\Order;
use RetailCrm\Api\Model\Request\Customers\CustomersCreateRequest;
use RetailCrm\Api\Model\Request\Orders\OrdersCreateRequest;

require $_SERVER['DOCUMENT_ROOT'].'\RetailCrmApiTestCase\testTaskRetailCRM\vendor\autoload.php';

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
        $request->order = new Order();

        $request->site = 'test'; //магазин
        $request->order->orderType = "fzik"; // тип заказа
        $request->order->orderMethod = "test"; // способ оформления
        $request->order->number = "19032000"; // номер заказа
        $request->order->firstName = "Davlatbek"; // Фамилия
        $request->order->lastName = "Ushurbakiyev"; // Имя
        $request->order->customFields['prim'] = "тестовое задание"; // примичание
        $request->order->payments[0]->comment = "https://github.com/UshurbakiyevDavlat/RetailCrmApiTestCase"; // коммент
        $request->order->items[0]->offer->article = "AZ105R"; // артикул товара
        $request->order->company->brand = "Azalita"; // бренд


        $client = self::connection();
        $response = [];

        try {
            $response = $client->orders->create($request);
        } catch (AccountDoesNotExistException $e) {
        } catch (ApiErrorException $e) {
        } catch (MissingCredentialsException $e) {
        } catch (MissingParameterException $e) {
        } catch (ValidationException $e) {
        } catch (HandlerException $e) {
        } catch (HttpClientException $e) {
        } catch (ApiExceptionInterface $e) {
        } catch (ClientExceptionInterface $e) {
        }
        echo "Status" . $response->success;
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
} catch (BuilderException $e) {
} catch (ClientExceptionInterface $e) {
}

