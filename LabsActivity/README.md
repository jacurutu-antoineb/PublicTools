Obsidian Lab demo setup

Controller (sets up the scenario based on json)

One container for each use case
- Share nothing philosophy
- Sets up monitoring / Logs
- Notifies slack channel if any errors

# Controller
The controller runs and performs the following:
- Parse Scenario.json
- Create a docker-compose to launch the activity containers
- Runs docker-compose up to start them.
- Runs docker-compose logs and sends to ./logs (for monitor)

## monitor
  - Web app that exposes the logs
  - listens on port 8080
  - google login supported (will filter on obsidian)
    - google app uses labmon.com as callback, add addr to /etc/hosts
  - Reads from logs directory (volume shared to container)

## Workers
  - One container per app/use case
