# Obsidian SDK REST API Shim
This repository aims to create a RESTful API to access the Obsidian SDK. The JSON objects map very directly to how they are modelled in the SDK, so you'll certainly want to reference the Obsidian SDK documentation.
https://docs.obsidiansecurity.com/obsidian/connections/custom-connections/introduction-to-datatypes-and-endpoints

### API Endpoints:
#### LIST API Endpoints
&nbsp;&nbsp;&nbsp;&nbsp;**GET** /
#### Update application settings
&nbsp;&nbsp;&nbsp;&nbsp;**POST** /settings  
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
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;It will be beneficial to refer to the
official Obsidian documentation for datatypes to understand how to format this o
bject. https://docs.obsidiansecurity.com/obsidian/connections/custom-connections/introduction-to-datatypes-and-endpoints/accounts

    id: str                      # Unique identifier for user
    name: str                    # User's full name
    email: str                   # User's e-mail address
    username: str                # User's username
    department: str | None       # User's department
    title: str                   # User's job title
    groups: set[str]             # Array of user's group membership
    roles: set[str]              # Array of user's roles
    permissions: set[str]        # Array of user's permissions
    integration_user: bool       # Indicates if user is a service integration account
    enabled: bool                # Indicates if the account is enabled
    local_login_enabled: bool    # Indicates if local login is enabled for the user
    sso_enabled: bool            # Indicates if Single Sign-On is enabled for the user
    sso_enforced: bool           # Indicates if Single Sign-On is enforced for the user
    mfa_enforced: bool           # Indicates if Multi-Factor Authentication is enforced for the user
    timeout: int                 # User's session timeout in minutes
    last_successful_login: datetime | None  # Date and time of the user's last successful login
    created_on: datetime         # Date and time the user was created
    created_by: str              # E-mail of the user or process that created the user
    updated_on: datetime         # Date and time the user was last updated
    updated_by: str | None       # E-mail of the user or process that updated the user

    {
      APIKEY: string
      SERVICEID: string
      TENANTID: string
      USERS: [
        [
          string,              The setting as seen in the app
          string,              Description of the setting
          string,              Datatype of the setting, defines next parameter
          value                The value of the setting
        ]
    }


## EXAMPLES:
Under the test directory you can see a functional example of using the REST API.
The shell script testapp_api.sh requires that you add an endpoint URL (functioning example at https://obsapi.faboucha.info). 
testapp_api.sh:
   URL - Configure to your API URL (working example at https://obsapi.faboucha.info)
   LOCAL - Optional parameter in case you want to clone the json files and update with your parameters.

settings.json
  Requires you populate APIKEY and SERVICEID. Optionally rename TENANTID.
  Feel free to add/modify settings.
