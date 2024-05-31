from flask import Flask, request, jsonify
import jwt
import logging
import uuid

from posture_api.sdk.setting import PlatformSettingV1_0
from posture_api.sdk.account import PlatformAccountV1_0
from posture_api.sdk.client import ObsidianBYODClient

app = Flask(__name__)

# Replace this secret key with your actual secret key for JWT verification
SECRET_KEY = 'your_secret_key'

@app.route('/', methods=['GET'])
def handle_base_api():
    return jsonify({
        '/': 'List API capbilitiesHandle user-related requests',
        '/settings': 'Push settings level information',
        '/users': 'Update users/account level information'
    }), 200

@app.route('/settings', methods=['POST'])
def handle_settings():
    return handle_post_logic(request, 'settings')

@app.route('/users', methods=['POST'])
def handle_users():
    return handle_post_logic(request, 'users')

def handle_post_logic(request, endpoint):
    try:
        # Get the JSON data from the request
        data = request.get_json()

        # Check if the request contains JSON data
        if data is not None:
            # Check for the presence of APIKEY, SERVICEID, and TENANTID
            if 'APIKEY' in data and 'SERVICEID' in data and 'TENANTID' in data:
                # Verify the JWT token in APIKEY
                jwt_token = data['APIKEY']
                #decoded_token = jwt.decode(jwt_token, algorithms=['HS256'], options={"verify_signature": False})

                # Process the JSON data as needed
                result = process_json(data, endpoint)

                # Return a JSON response
                return jsonify(result), 200
            else:
                return jsonify({'error': 'Missing required parameters'}), 400

        else:
            return jsonify({'error': 'No JSON data provided'}), 400

    except jwt.ExpiredSignatureError:
        return jsonify({'error': 'JWT token has expired'}), 401
    except jwt.InvalidTokenError:
        return jsonify({'error': 'Invalid JWT token'}), 401
    except Exception as e:
        return jsonify({'error': str(e)}), 500

def process_json(json_data, endpoint):
    if endpoint == "settings":
        if 'SETTINGS' in json_data:
           settings = json_data['SETTINGS']
           up_settings: list[PlatformSettingV1_0] = []
           for i in settings:
               obj = PlatformSettingV1_0(id=i[0], name=i[1], type=i[2], value=i[3])
               up_settings.append(obj)

           client = ObsidianBYODClient(json_data['APIKEY'])
           client.upload_settings(
                   service_id=json_data['SERVICEID'], tenant_id=json_data['TENANTID'], version="1.0", records=up_settings)

    elif endpoint == "users":
        if 'USERS' in json_data:
           users = json_data['USERS']
           schema = PlatformAccountV1_0.__fields__.keys()

           up_users: list[PlatformAccountV1_0] = []
           for i in users:
               # Create a dictionary of user data
               row_dict = dict(zip(schema, i))

               # Convert to model and perform validation
               obj = PlatformAccountV1_0.parse_obj(row_dict)

               # Append to users list
               up_users.append(obj)

           # Create Obsidian Security BYOD Client
           client = ObsidianBYODClient(json_data['APIKEY'])

           # Upload settings
           client.upload_accounts(service_id=json_data['SERVICEID'], tenant_id=json_data['TENANTID'], version="1.0", records=up_users)

           client.close()
    return {'message': f'JSON data for {endpoint} processed successfully'}

if __name__ == '__main__':
    # Run the application on http://127.0.0.1:5000/
    app.run(host='0.0.0.0', port=80)
