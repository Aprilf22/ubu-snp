# ubu-snp
# для архитектуры arm64/v8
Ubuntu-snmp-nginx-php
Контейнер для приема сообщений SNMP трапов , и отправки в телеграмм! Так же поддерживается прием сообщений через get запрос 
пример get  запроса
http://IP_Docker/not/not.php?message=test_messege

# сборка образа 
docker buildx build --load  --platform linux/arm64/v8 -f ./Dockerfile --tag asnp-ubu-arm64v8 . 
не забудьте установить поддержку для многопроцессорной архитектуры. 

# Запуск образа 

docker run \
-d \
--restart=always \
--network=bridge \
--privileged \
--log-opt max-size=200M \
--name=asnp \
--volume /etc/mydoc/snmp/snmp:/etc/snmp \
--volume /etc/mydoc/snmp/html:/var/www/html \
--publish 8080:80/tcp \
--publish 161:161/udp \
--publish 162:162/udp \
asnp-debian_2004
