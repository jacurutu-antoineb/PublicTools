# Obsidian SDK REST API Shim
This repository aims to create a RESTful API to access the Obsidian SDK. The JSON objects map very directly to how they are modelled in the SDK, so you'll certainly want to reference the Obsidian SDK documentation.
https://docs.obsidiansecurity.com/obsidian/connections/custom-connections/introduction-to-datatypes-and-endpoints

## Application Gateway Container
This is a rudimentary container that lumps in the python wheel, a flask http app and redirects queries to the SDK. It parses the Obsidian API key and session id / tenant ID from the underlying API body as opposed to at the parameter level. I have removed JWT signature validation...this is not meant to be production grade, but if you need a RESTful API to the Obsidian platform this will achieve that end.

### API Endpoints:
#### LIST API Endpoints
&nbsp;&nbsp;&nbsp;&nbsp;**GET** /
#### Update application settings
&nbsp;&nbsp;&nbsp;&nbsp;**POST** /settings  
&nbsp;&nbsp;&nbsp;&nbsp;**Request Body** Application/json  
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;It will be beneficial to refer to the official Obsidian documentation for datatypes to understand how to format this object. https://docs.obsidiansecurity.com/obsidian/connections/custom-connections/introduction-to-datatypes-and-endpoints/platform-settings

&nbsp;&nbsp;&nbsp;&nbsp;**Schema**  
  
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
&nbsp;&nbsp;&nbsp;&nbsp;**POST** /users  
&nbsp;&nbsp;&nbsp;&nbsp;**Request Body** Application/json  
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;It will be beneficial to refer to the
official Obsidian documentation for datatypes to understand how to format this o
bject. https://docs.obsidiansecurity.com/obsidian/connections/custom-connections/introduction-to-datatypes-and-endpoints/accounts

&nbsp;&nbsp;&nbsp;&nbsp;**Schema**

    {
      APIKEY: string
      SERVICEID: string
      TENANTID: string
      USERS: [
        [
          string,                # Unique identifier for user  
          string,                # User's full name  
          string,                # User's e-mail address        
          string,                # User's username  
          string,                # User's department  
          string,                # User's job title  
          set[string],           # Array of user's group membership         
          set[string],           # Array of user's roles         
          set[string],           # Array of user's permissions        
          string[bool],          # Indicates if user is a service integration account
          string[bool],          # Indicates if the account is enabled
          string[bool],          # Indicates if local login is enabled for the user
          string[bool],          # Indicates if Single Sign-On is enabled for the user
          string[bool],          # Indicates if Single Sign-On is enforced for the user        
          string[bool],          # Indicates if Multi-Factor Authentication is enforced for the user  
          int,                   # User's session timeout in minutes
          string[datetime],      # Date and time of the user's last successful login
          string[datetime],      # Date and time the user was created
          string,                # E-mail of the user or process that created the user
          string[datetime],      # Date and time the user was last updated
          string,                # E-mail of the user or process that updated the user
        ]
    }


### test/testapp_api.sh:
Under the test directory you can see a functional example of using the REST API.
The shell script testapp_api.sh requires that you add an endpoint URL (functioning example at https://obsapi.faboucha.info).   
testapp_api.sh:  
&nbsp;&nbsp;**URL** - Configure to your API URL (working example at https://obsapi.faboucha.info)  
&nbsp;&nbsp;**LOCAL** - Optional parameter in case you want to clone the json files and update with your parameters.  

settings.json  
&nbsp;&nbsp;&nbsp;Requires you populate APIKEY and SERVICEID. Optionally rename TENANTID.  
&nbsp;&nbsp;&nbsp;Feel free to add/modify settings.  

users.json  
&nbsp;&nbsp;&nbsp;Requires you populate APIKEY and SERVICEID. Optionally rename TENANTID.  
&nbsp;&nbsp;&nbsp;Feel free to add/modify actual data

