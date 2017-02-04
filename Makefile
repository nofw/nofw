BUILD_IMAGES=app

# Setup environment
setup: build

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
	@rm -rf vendor/ var/cache/*
	@docker-compose rm --force

# Run test suite
test:
	@composer test

# Install dependencies
install:
	@docker-compose run --rm composer install

.PHONY: setup build start stop clean test install