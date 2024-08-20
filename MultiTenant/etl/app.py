import time
import os
import sys
import mysql.connector
from mysql.connector import Error
import requests


def PullAPITokens():
  
  try:
     # Establish the connection
     connection = mysql.connector.connect(
        host=os.getenv('MYSQL_HOST'),  # Taken from docker-compose name
        database=os.getenv('MYSQL_DATABASE'),
        user=os.getenv('MYSQL_USER'),
        password=os.getenv('MYSQL_PASSWORD') 
     )
     if connection.is_connected():
       # Create a cursor object using the cursor() method
       cursor = connection.cursor()

       # Define the SQL query you want to execute
       sql_query = "SELECT token_id,api_endpoint,token FROM apitoken_data"

       # Execute the SQL query
       cursor.execute(sql_query)

       # Fetch all the rows from the result of the query
       records = cursor.fetchall()

       cursor.close()
       connection.close()

  except Error as e:
      print(f"Database connection exception Error: {e}")
    
  return records or {};

def ExtractDataForToken(token):
    # 3 elements
    print("Extract data for token " + str(token[0]))
    api_endpoint = token[1];
    api_token = token[2];

    query = ""
    variables = "{}"
    
    with open("queries/postureScoresAllApps.query", 'r') as file:
        query = file.read()


    variables = { "interval": "DAILY",
                  "filter" : {
                       "AND": [
                           {
                               "datetime": {
                                   "GTE": "2024-06-25T06:00:00.000Z"
                                   }
                               }
                           ]
                       }
                  }

    payload = {
        'query': query,
        'variables': variables or {}
    }

    headers = {
        "Authorization": "Bearer " + api_token
    }

    try:
        # Make the GraphQL call
        response = requests.post(api_endpoint, json=payload, headers=headers or {})

        # Print the result
        if response.status_code == 200:
            print(response.json())
            print("\n\n")
        else:
           raise Exception(f"Query failed to run by returning code of {response.status_code}. {response.text}")

    except Exception as e:
        print(f"An error occurred: {e}")

  
while True:

   # Pull complete list of API tokens.
   tokens = PullAPITokens()

   for token in tokens:
       ExtractDataForToken(token)

   sys.stdout.flush();
   time.sleep(160);
