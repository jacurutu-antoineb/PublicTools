This repo aims to create a REST shim layer to interface with Obsidian's
SDK.

API Endpoints:
 /
 /settings
 /users

API:

List API Endoints

GET /


Update settings for an application

POST /settings


Update Accounts for an application

POST /users


EXAMPLES:
Under the test directory you can see a functional example of using the REST API.
The shell script testapp_api.sh requires that you add an endpoint URL (functioning example at https://obsapi.faboucha.info). 
testapp_api.sh:
   URL - Configure to your API URL (working example at https://obsapi.faboucha.info)
   LOCAL - Optional parameter in case you want to clone the json files and update with your parameters.

settings.json
  Requires you populate APIKEY and SERVICEID. Optionally rename TENANTID.
  Feel free to add/modify settings.
