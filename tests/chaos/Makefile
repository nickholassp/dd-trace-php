DURATION := 30s

build:
	docker-compose build

up: build
	docker-compose up -d

load:
	@ echo "Running load tests for ${DURATION}."
	@ echo "GET http://localhost:8000" | vegeta attack -duration=${DURATION} | tee results.bin | vegeta report
