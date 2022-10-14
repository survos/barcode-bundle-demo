.PHONY: \
	docker_rabbitmq \
	docker_db

docker_rabbitmq:
	docker stop barcode-rabbitmq || true; docker rm barcode-rabbitmq || true
	docker run --name barcode-rabbitmq \
		-e RABBITMQ_DEFAULT_USER=guest \
		-e RABBITMQ_DEFAULT_PASS=guest \
		-p 5662:5672 \
		-p 15662:15672 \
		--restart=unless-stopped \
		-d rabbitmq:3.8-management-alpine

docker_db:
	docker stop barcode-postgres || true && docker rm barcode-postgres || true
	docker volume rm barcode_postgres_data || true

	docker volume create barcode_postgres_data
	docker run --name barcode-postgres \
		-e POSTGRES_DB=barcode \
		-e POSTGRES_USER=main \
		-e POSTGRES_PASSWORD=main \
		-v barcode_postgres_data:/var/lib/postgresql/data \
		-p 5432:5432 \
		--restart=unless-stopped \
		-d postgres:14.5-alpine

elastic:
	docker stop barcode-elastic || true; docker rm barcode-elastic || true
	docker run --name barcode-elastic \
		-e "discovery.type=single-node" \
		-p 9210:9200 \
		-p 9321:9300 \
		--restart=unless-stopped \
		-d docker.elastic.co/elasticsearch/elasticsearch:7.17.6
