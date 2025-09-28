FROM php:8.3-apache
# نصب ابزارها و اکستنشن‌های لازم
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
       libzip-dev \
       unzip \
       zlib1g-dev \
       libpng-dev \
       libxml2-dev \
       libonig-dev \
       git \
    && docker-php-ext-configure zip \
    && docker-php-ext-install \
       pdo_mysql \
       zip \
       bcmath \
       mbstring \
       xml \
       ctype \
    && rm -rf /var/lib/apt/lists/*

# فعال کردن mod_rewrite
RUN a2enmod rewrite

# تنظیم DocumentRoot لاراول
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

WORKDIR /var/www/html

# کپی کردن کل پروژه
COPY . .
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress
# پرمیژن‌های لازم برای لاراول
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80
