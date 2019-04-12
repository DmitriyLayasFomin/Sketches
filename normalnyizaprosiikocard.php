$sum = (float)$order->fullSum - (float)$order->bonus;
      $orderArr = array(
        'phone' => $phone,
        'items' => $items,
        'comment' => $order->post_excerpt,
        'fullSum' => $order->fullSum,
        'isSelfService' => $isSelfService,
        //'discountCardTypeId'=>'8f6b28bd-7a61-443e-a452-586f1e64e9d3',
        //'discountOrIncrea'=>$order->bonus,
        // 'date' => date('Y-m-d H:i:s P'),
        'date' => date('Y-m-d H:i:s P', time()+18000), // прибавляем 5 часов так как время сайта почему то -5 часов
        'paymentItems' => [
          
          [
            'isProcessedExternally' => false,
            'sum' =>  $order->bonus,
            'paymentType' => [
              'id' => '8f6b28bd-7a61-443e-a452-586f1e64e9d3',
              'combine' => true,
              
              
            ],
              'additionalData'=>'{"searchScope": "PHONE", "credential": "'.$phone.'"}' //v
          ],
          [
            
            'isProcessedExternally' => false,
            'sum' =>  $sum,
            'isExternal'=> true,
            'isProcessdExternally'=>false,
            'isPreliminary'=>true,
            'paymentType' => [
              'id' => $paymentType,
              'combine' => true,
              // "applicableMarketingCampaigns"=> ["d2cef933-3661-11e8-80e0-d8d38565926f"],
            ]
            ],
            
          ]
        
      );
      
      
      
      
      
