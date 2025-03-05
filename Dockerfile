# Используем базовый образ Ubuntu для ARM64
FROM arm64v8/ubuntu:20.04

# Устанавливаем временную зону по умолчанию
ENV TZ=Etc/UTC
RUN ln -fs /usr/share/zoneinfo/$TZ /etc/localtime && \
    apt-get update && \
    DEBIAN_FRONTEND=noninteractive apt-get install -y tzdata && \
    dpkg-reconfigure --frontend noninteractive tzdata

# Устанавливаем необходимые пакеты
RUN apt-get update && \
    DEBIAN_FRONTEND=noninteractive apt-get install -y \
        snmp \
        snmpd \
        snmptrapd \
        nginx \
        php7.4-fpm \
        php-cli \
        php-curl \
        php-snmp && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

# Настраиваем SNMP
RUN mkdir -p /run/snmp && \
    touch /var/log/snmpd.log /var/log/snmptrapd.log && \
    chmod 644 /var/log/snmpd.log /var/log/snmptrapd.log

# Настраиваем Nginx
RUN mkdir -p /run/nginx && \
    touch /var/log/nginx/access.log /var/log/nginx/error.log && \
    chmod 644 /var/log/nginx/access.log /var/log/nginx/error.log

# Настраиваем PHP-FPM
RUN mkdir -p /run/php && \
    touch /var/log/php7.4-fpm.log && \
    chmod 644 /var/log/php7.4-fpm.log

# Изменяем конфигурацию PHP-FPM
RUN sed -i 's/^listen = .*/listen = 127.0.0.1:9000/' /etc/php/7.4/fpm/pool.d/www.conf

# Изменяем конфигурацию snmptrapd.service
# RUN sed -i 's/OPTIONS="-Lsd"/OPTIONS="-On"/' /etc/default/snmptrapd

COPY snmptrapd  /etc/default/snmptrapd


# Настраиваем Nginx
COPY default.conf /etc/nginx/sites-available/default
RUN mkdir -p /etc/nginx/sites-enabled && \
    ln -sf /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default

# Создаем директорию для веб-приложения
RUN mkdir -p /var/www/html && \
    chown -R www-data:www-data /var/www/html

# Создаем файл сервиса
#COPY 13.service /etc/systemd/system/13.service
#RUN chmod 660 /etc/systemd/system/13.service


# Открываем порты
EXPOSE 80
EXPOSE 161
EXPOSE 162

# Запускаем сервисы
CMD ["sh", "-c", "service snmpd start && service snmptrapd start && service php7.4-fpm start && service nginx start && /usr/bin/php /var/www/html/not/13.php 'daemon off;'"]
