<?php

return [
  'start' => array(
      array(
          array('text' => '💀 Мой Профиль'),
          array('text' => '👀 Поиск Пользователя')
      ),
      array(
          array('text' => '🔥 Активные Сделки'),
          array('text' => '📪 Служба Поддержки')
      )
  ),

  'goHome' => array(
      array(
          array('text' => 'Главное Меню', 'callback_data' => 'cancel')
      )
  ),

  'createDeal' =>  array(
      array(
          array('text' => 'Создать Сделку', 'callback_data' => 'startDeal'),
          array('text' => 'Отмена', 'callback_data' => 'cancel')
      )
  ),

  'confirmDeal' => array(
      array(
          array('text' => 'Подтвердить сделку', 'callback_data' => 'confirmAndSendToSeller'),
          array('text' => 'Отмена', 'callback_data' => 'cancel')
      )
  ),

  'acceptDeal' => array(
      array(
          array('text' => 'Принять Сделку', 'callback_data' => 'acceptDealFromBuyer'),
          array('text' => 'Отмена', 'callback_data' => 'cancelInvitationBySeller')
      )
  ),

  'isPaid' => array(
      array(
          array('text' => 'Оплачено', 'callback_data' => 'paidToEscrowByBuyer'),
          array('text' => 'Отмена', 'callback_data' => 'cancelDealByBuyer')
      )
  ),

  'admin' => array(
      array(
          array('text' => 'Взнос получен', 'callback_data' => $idOfDeal . 'adminReceivedMoney'),
          array('text' => 'Написать в сделку', 'callback_data' => $idOfDeal . 'sendMessageToDeal')
      ),
      array(
          array('text' => 'Написать в бот ', 'callback_data' => 'sendMessageToBot'),
          array('text' => 'Закрыть сделку', 'callback_data' => $idOfDeal . 'dealIsResolved')
      )
  ),

  'isCompleteByBuyer' => array(
      array(
          array('text' => 'Подтвердить', 'callback_data' => 'completeByBuyer'),
          array('text' => 'Открыть спор', 'callback_data' => 'openDisputeByBuyer')
      )
  ),

  'isCompleteBySeller' => array(
      array(
          array('text' => 'Выполнено', 'callback_data' => 'completeBySeller'),
          array('text' => 'Открыть спор', 'callback_data' => 'openDisputeBySeller')
      )
  ),


];
