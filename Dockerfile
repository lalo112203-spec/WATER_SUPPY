FROM php:8.2-cli

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy application files
COPY . .

# Setup environment and database
RUN cp .env.example .env
RUN touch database/database.sqlite

# Install PHP dependencies
RUN composer install --no-interaction --optimize-autoloader

# Generate app key
RUN php artisan key:generate

# Run migrations (for SQLite)
RUN php artisan migrate --force

# Install Node dependencies and build assets
RUN npm install && npm run build

# Expose port and start the application
ENV PORT=8080
EXPOSE 8080

CMD APP_ENV=local APP_DEBUG=true LOG_CHANNEL=stderr php artisan serve --host=0.0.0.0 --port=$PORT
