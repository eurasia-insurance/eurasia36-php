FROM lapsatech/centos7-httpd-php
MAINTAINER "Vadim Isaev" <vadim.o.isaev@gmail.com>

COPY docroot/ /var/www/
COPY conf/ /etc/httpd/conf.d/
COPY pki/ /etc/pki/tls/

ENV PAYARA_AJP_HOST payara.local
ENV PAYARA_AJP_PORT 8009

WORKDIR /var/www/
