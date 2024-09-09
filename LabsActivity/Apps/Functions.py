from datetime import datetime, timedelta
import requests
import os

# Global variable to store the last alert time
lastAlertTime = None

def sendNotification(notification):
   global lastAlertTime
   slackWebhook = os.getenv('SLACK_WH')
   app = os.getenv('AppName')
   if (app is None or slackWebhook is None):
       print("CRIT: Notification setup env files fail", flush=True)
   elif lastAlertTime is None or datetime.now() - lastAlertTime > timedelta(hours=1):
      payload = {
                   "text": "Error with " + os.getenv('AppName') + " - " +notification
      }
      response = requests.post(slackWebhook, json=payload, headers={'Content-Type': 'application/json'})
      lastAlertTime = datetime.now()
    

def Log(log):
    print("LOG: " + log, flush=True)
def Err(log):
    print("ERR: " + log, flush=True)
    sendNotification(log)
