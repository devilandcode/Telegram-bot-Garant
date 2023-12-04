<p align="center"><img src="./tg.png" width="400" alt="Journal"></p>
<h1 align="center"><strong>Bot Garant</strong></h1>


## Instruments

- PHP 8.2
- guzzle http

## Get started

1) Set Webhook

   - Install ngrok
   - Open your project directory via terminal and write - "ngrok http 80"
   - Get your ngrok https link
   
    For set webhook write in ur browser this link (example):
https://api.telegram.org/botBOT_TOKEN/setWebhook?url=https://61b2-46-53-245-38.ngrok-free.app/index.php
    WHERE: 
    - BOT_TOKEN - get ur bot token from botfather bot
    - https://61b2-46-53-245-38.ngrok-free.app - an example of ngrok https link
   
   Response from tg : {"ok":true,"result":true,"description":"Webhook is already set"}
    
    Done!

2) 
