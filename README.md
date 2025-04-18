# Accesses

### Grafana
http://localhost:3001/


### Jaeger
http://localhost:16686/


### PHP
http://localhost:8080/

http://localhost:8080/metrics => prometheus metrics


### Node
http://localhost:3000/

http://localhost:3000/error => capture trace

http://localhost:3000/increment => capture metric


---


### Install dependencies and build

`docker compose run php composer install`

`docker compose run npm install`

`docker compose build`


---


### Run

`docker compose up`


---


## How it works

🔄 OpenTelemetry Collector: receives, processes, and exports data to other systems.

🔵 Prometheus: stores metrics.

🟡 Jaeger: stores traces.

📈 Grafana: reads from Jaeger and Prometheus to build graphs.

1) Applications send data to the OpenTelemetry Collector.

2) In Grafana:
- Click on `Menu`, `Explore`.
- Select the source (Jaeger, Prometheus...).
- Select the metric or service, and click on Run query.
- Click on `Add to dashboard`, and if the dashboard is already created, click on `Existing dashboard` and select it.
- Click on `Open dashboard`,
- Edit the new dashboard, and change the title and other information.
- Click on apply to apply the changes to the dashboard, and click on the 💾 `Save dashboard` button at the top to apply the changes to the dashboard.


---


## File Tree
```
├── .docker
│   └── otel-collector-config.yaml
├── docker-compose.yml
├── .gitignore
├── grafana
├── .infra
│   ├── otel-collector-config.yaml
│   └── prometheus.yaml
├── node
│   └── app
│       ├── index.js
│       ├── instrumentation.js
│       ├── node_modules
│       ├── package.json
│       └── package-lock.json
├── otel
│   └── config
├── php
│   ├── app
│   │   └── src
│   ├── config
│   │   ├── 000-default.conf
│   │   └── .htaccess
│   └── Dockerfile
└── README.md
```
