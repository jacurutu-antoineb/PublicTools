# Obsidian SDK REST API Shim
This repository aims to create a RESTful API to access the Obsidian SDK. 

### API Endpoints:
#### LIST API Endpoints
  **GET** /
#### Update application settings
**POST** /settings

**Schema**  
  
    {  
      APIKEY: string  
      SERVICEID: string  
      TENANTID: string  
      SETTINGS: [  
        [  
          string,              The setting as seen in the app  
          string,              Description of the setting  
          string,              Datatype of the setting, defines next parameter  
          value                The value of the setting  
        ]  
    }  

        
#### Update application accounts
  **POST** /users


EXAMPLES:
Under the test directory you can see a functional example of using the REST API.
The shell script testapp_api.sh requires that you add an endpoint URL (functioning example at https://obsapi.faboucha.info). 
testapp_api.sh:
   URL - Configure to your API URL (working example at https://obsapi.faboucha.info)
   LOCAL - Optional parameter in case you want to clone the json files and update with your parameters.

settings.json
  Requires you populate APIKEY and SERVICEID. Optionally rename TENANTID.
  Feel free to add/modify settings.
