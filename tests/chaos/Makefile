DURATION := 30s

build:
	docker-compose build

up: build
	docker-compose up -d

version_%:
	curl -L -o ./tracer-versions/datadog-php-tracer_$(*)_amd64.deb https://github.com/DataDog/dd-trace-php/releases/download/$(*)/datadog-php-tracer_$(*)_amd64.deb

load:
	@ echo "Running load tests for ${DURATION}."
	@ echo "GET http://localhost:8000" | vegeta attack -duration=${DURATION} | tee results.bin | vegeta report
