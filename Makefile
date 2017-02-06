BUILD_IMAGES=app

# Setup environment
setup: build
	mkdir -p var/docker

# Build images
build:
ifeq ($(FORCE), true)
	@docker-compose build --force-rm $(BUILD_IMAGES)
else
	@docker-compose build $(BUILD_IMAGES)
endif

# Start the environment
start:
	@docker-compose up -d

# Stop the environment
stop:
ifeq ($(FORCE), true)
	@docker-compose kill
else
	@docker-compose stop
endif

# Clean environment
clean: stop
	@rm -rf vendor/ var/cache/* var/docker
	@docker-compose rm --force

# Run test suite
test:
	@docker-compose run --rm test

# Install dependencies
install:
	@docker-compose run --rm composer install --ignore-platform-reqs

.PHONY: setup build start stop clean test install
