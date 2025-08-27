### Pré-requisitos
- Docker
- Docker Compose
- Git

### Passos:
1. **Copy the environment file:**
``` bash
cp .env.example .env
```
2. **Run Sail without having PHP/Composer installed:**
``` bash
# This command downloads dependencies using Docker
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php84-composer:latest \
    composer install --ignore-platform-reqs
```
3. **Start the containers:**
``` bash
./vendor/bin/sail up -d
```
4. **Run the configuration commands:**
``` bash
# Generate application key
./vendor/bin/sail artisan key:generate

# Run migrations
./vendor/bin/sail artisan migrate

# Install Node.js dependencies
./vendor/bin/sail npm install

# Compile assets
./vendor/bin/sail npm run dev
```
5. **Run the queue::**
``` bash
# Open a new terminal
./vendor/bin/sail artisan queue:work
```
6. **Run the scheduler:**
``` bash
# Open a new terminal
./vendor/bin/sail artisan schedule:work
```
