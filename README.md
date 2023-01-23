# Dove SMS by Aifrruis Labs

>> `What is Dove SMS ?` 

Dove SMS is a system developed by Aifrruis Labs and made to be open from 
29th November 2022. This System helps you to send sms from your applications to
your clients by using smart phones which are connected to Dove SMS Web Interface.

This Repo contains Dove SMS Mobile Interface and Web Interface for Configurations
and Analytics for SMS going through one or many smartphones

`Checking and Resending SMS Failed to Deliver dlr Reports`
cron_url = http://your_host/api/v1/reset/not/delivered/messages?apiKey=your_api_key

Example
your_host = dovesms.aifrruislabs.com
your_api_key = TFt66gUiyUGuy

This should run in cron job likes

`* * * * * curl --silent cron_url`

`For Demo Please Check`
Link : https://dovesms.aifrruislabs.com

`API Documentation`
https://dovesms.aifrruislabs.com/api/documentation