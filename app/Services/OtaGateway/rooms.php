<?php
$rooms = '{
   "roomtypes":{
      "0":{
         "id":47078,
         "name":"CHLT",
         "description":"CHALET LOS PINOS"
      },
      "1":{
         "id":47067,
         "name":"DDE",
         "description":"2 Dor. Est"
      },
      "4":{
         "id":47073,
         "name":"ESTG",
         "description":"EST.G"
      },
      "6":{
         "id":47068,
         "name":"DDL",
         "description":"2 Dor. Lujo",
         "account_id":4768
      },
      "7":{
         "id":47069,
         "name":"EstS",
         "description":"ESTUDIOS ESTÁNDAR"
      },
      "8":{
         "id":47070,
         "name":"EstL",
         "description":"ESTUDIOS LUJO"
      },
      "9":{
         "id":47074,
         "name":"7J",
         "description":"7J",
         "account_id":4768
      },
      "11":{
         "id":47075,
         "name":"9R",
         "description":"9R"
      },
      "12":{
         "id":47076,
         "name":"9F",
         "description":"9F"
      },
      "13":{
         "id":47077,
         "name":"10I",
         "description":"10I"
      }
   }
}';

$rooms = json_decode($rooms,true);