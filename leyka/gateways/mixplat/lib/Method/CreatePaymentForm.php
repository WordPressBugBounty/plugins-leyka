<?php

namespace MixplatClient\Method;

use MixplatClient\Configuration;

class CreatePaymentForm extends MixplatMethod
{
    /**
     * ID шаблона платёжной формы, созданного в Личном Кабинете MIXPLAT.
     * Все настройки платёжной формы по умолчанию будут взяты из этого шаблона.
     * Если не передан – будет использован шаблон платёжной формы в ЛК, настроенной по умолчанию.
     * Необязательный параметр. Определяется в ЛК.
     * @var string|null
     */
    public $paymentFormId;

    /**
     * Уникальный идентификатор запроса, задаваемый ТСП, обеспечивающий идемпотентность вызовов
     * (повторные запросы с тем же request_id не будут приводить к созданию нового платежа,
     * а параметры ответа будут полностью повторять параметры ответа первоначального вызова с данным request_id).
     * Рекомендуется передавать этот параметр, чтобы защититься от дублирования платежей в результате сетевых проблем,
     * задержек ответа и т. п.
     * В качестве request_id можно использовать идентификатор платежа в системе ТСП (если он уникален),
     * или хеш от ключевых параметров запроса.
     * Проверка наличия другого запроса с данным request_id осуществляется за последние 30 дней.
     * От 1 до 64 символов. Необязательный параметр.
     * @var string|null
     */
    public $requestId;

    /**
     * Платёжный метод, который будет использован для совершения оплаты
     * Необязательный, по умолчанию не задан (все доступные методы).
     * \MixplatClient\MixplatVars::PAYMENT_METHOD_*
     * @var string|null
     */
    public $paymentMethod;

    /**
     * ID платежа в ТСП. Если передан, то это же значение параметра будет приходить в уведомлениях payment_status
     * От 1 до 256 символов. Необязательный параметр.
     * @var string|null
     */
    public $merchantPaymentId;

    /**
     * Произвольные данные ТСП, связанные с платежом.
     * Если передан, то это же значение параметра будет приходить в уведомлениях payment_status
     * От 1 до 256 символов. Необязательный параметр.
     * @var string|null
     */
    public $merchantData;

    /**
     * Массив дополнительных сведений о транзакции, которые ТСП может передать при создании платежа.
     * При уведомлении о статусе оплаты этот массив будет возвращен вместе с остальными параметрами
     * в уведомлении payment_status на Callback URL.
     * Может применяться для передачи сопутствующих данных о плательщике или товаре: по значениям
     * в массиве возможна фильтрация платежей в личном кабинете и выгружаемых XLS отчетах.
     * Необязательный параметр.
     * @var array|null
     */
    public $merchantFields;

    /**
     * Признак тестового платежа.
     * 1: Платёж тестовый
     * 0: Платёж реальный
     * Для тестовых платежей необходимо указывать специальные номера банковских карт/номера телефонов
     * Необязательный параметр, по умолчанию 0 (Платёж реальный).
     * @var int|null
     */
    public $test;

    /**
     * Описание платежа. Отображается на платёжной форме и в личном кабинете ТСП.
     * От 3 до 256 символов. Необязательный параметр, по умолчанию "Оплата заказа".
     * @var string|null
     */
    public $description;

    /**
     * Язык платёжной формы, сервисных сообщений
     * Необязательный, по умолчанию "RU" или "EN" в зависимости от IP пользователя.
     * \MixplatClient\MixplatVars::LANGUAGE_*
     * @var string|null
     */
    public $language;

    /**
     * Валюта платежа
     * Необязательный, по умолчанию RUB.
     * \MixplatClient\MixplatVars::CURRENCY_*
     * @var string|null
     */
    public $currency;

    /**
     * Сумма платежа (в минорных единицах, копейках).
     * От 100 до 50000000 для payment_method = card, bank
     * От 1000 до 1500000 для payment_method = mobile, wallet
     * Обязательный параметр
     * @var int
     */
    public $amount;

    /**
     * Данные для чека
     * Необязательный параметр.
     * @var array|null
     */
    public $items;

    /**
     * Email Плательщика. Будет использован для отправки информации о совершённом платеже,
     * если функционал уведомлений активирован для проекта
     * Необязательный параметр.
     * @var string|null
     */
    public $userEmail;

    /**
     * Номер телефона Плательщика в международном формате без символа "+"
     * Обязательный при payment_method = mobile
     * @var string|null
     */
    public $userPhone;

    /**
     * URL, на который будет перенаправлен Плательщик после успешной оплаты
     * Необязательный параметр. Если не передан, используются настройки платёжной формы, заданные в ЛК.
     * @var string|null
     */
    public $urlSuccess;

    /**
     * URL, на который будет перенаправлен Плательщик после неуспешной оплаты
     * Необязательный параметр. Если не передан, используются настройки платёжной формы, заданные в ЛК.
     * @var string|null
     */
    public $urlFailure;

    /**
     * Схема проведения платежа по банковским картам
     * Необязательный, по умолчанию "sms".
     * \MixplatClient\MixplatVars::CARD_SHEME_*
     * @var string|null
     */
    public $paymentScheme;

    /**
     * Для создания подписки передать "recurrent_payment": 1.
     * Подписка создаётся только в случае, если установочный платёж успешен.
     * После проведения установочного платежа вы получите идентификатор подписки recurrent_id в уведомлении payment_status.
     * @var int|null
     */
    public $recurrentPayment;


    /**
     * @return string
     */
    public function getMethod()
    {
        return 'create_payment_form';
    }

    /**
     * @param Configuration $config
     * @return array
     */
    public function getParams($config)
    {
        $signature = $this->encryptSignature(
            $this->requestId .
            $config->projectId .
            $this->merchantPaymentId .
            $config->apiKey
        );

        $params = $this->parseParams();
        $params['signature'] = $signature;
        $params['api_version'] = $config->apiVersion;
        $params['project_id'] = $config->projectId;

        return $params;
    }
}
