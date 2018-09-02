FROM lapsatech/centos7-httpd-php
MAINTAINER "Vadim Isaev" <vadim.o.isaev@gmail.com>

COPY sites/ /var/www/
COPY conf.d/ /etc/httpd/conf.d/
COPY pki/ /etc/pki/tls/

ENV EURASIA36_WS_URL https://payara.local:443/
ENV EURASIA36_WS_USER admin
ENV EURASIA36_WS_PASSWORD admin

WORKDIR /var/www/
