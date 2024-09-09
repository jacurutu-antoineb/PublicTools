#!/usr/bin/python3

import json
import yaml
import time
import subprocess
import os

# Remove old logs
os.system("mkdir -f logs")
os.system("rm -f logs/*")
os.system("docker-compose down")

# Load JSON data
with open('Scenario.json') as f:
    data = json.load(f)

# Define base Docker Compose structure
compose = {
    'version': '3',
    'services': {}
}

# Generate services for AppUser
for app in data.get('AppUser', []):
    app_dir = app.split('/')[-1]
    service_name = app_dir.lower() + "_user"
    compose['services'][service_name] = {
        'image': 'app-container:latest',  
        'env_file': ['environment.env'],
        'environment' : ['AppName='+service_name],
        'command': ['python3', app_dir + "/UserActivity.py"],
        'restart': 'always'
    }

# Generate services for AppAPI
for app in data.get('AppAPI', []):
    app_dir = app.split('/')[-1]
    service_name = app_dir.lower() + "_app"
    if service_name not in compose['services']:
        compose['services'][service_name] = {
            'image': 'app-container:latest',  
            'env_file': ['environment.env'],
            'environment' : ['AppName='+service_name],
            'command': ['python3', app_dir + "/AppActivity.py"],
            'restart': 'always'
        }

compose['services']['webmon'] = {
        'image': 'obslabux:v1.0',
        'env_file': ['environment.env'],
        'volumes': ['./logs:/logs:ro'],
        'ports': ['0.0.0.0:5000:8080']
}

# Write to docker-compose.yml
with open('docker-compose.yml', 'w') as f:
    yaml.dump(compose, f, default_flow_style=False)

subprocess.run(['docker-compose', 'up', '-d'], check=True)

services = [service for service in compose['services'].keys() if service != 'webmon']

processes = {}

for service in services:
    stdout_log = open(f'logs/{service}.stdout.log', 'w')
    stderr_log = open(f'logs/{service}.stderr.log', 'w')
    
    processes[service] = subprocess.Popen(
        ['docker-compose', 'logs', '-f', service],
        stdout=stdout_log,
        stderr=stderr_log
    )

try:
    while True:
        # Here you could add any logic to monitor processes, etc.
        time.sleep(1)
except KeyboardInterrupt:
    # Clean up: terminate all subprocesses on interrupt
    for service, process in processes.items():
        process.terminate()
        process.wait()  # Ensure process has fully terminated

    subprocess.run(['docker-compose', 'down'], check=True)
