#!/bin/bash

LOCAL=""

URL=""
curl -X GET -H "Content-Type: application/json" $URL/

curl -X POST -H "Content-Type: application/json" -d @settings$LOCAL.json $URL/settings

curl -X POST -H "Content-Type: application/json" -d @users$LOCAL.json $URL/users

