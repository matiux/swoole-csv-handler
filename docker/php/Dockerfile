FROM matiux/php-swoole:4.5.7-7.4.12-buster-dev

RUN apt-get update && apt-get install -y --no-install-recommends \
    htop

COPY conf/xdebug-starter.sh /usr/local/bin/xdebug-starter
RUN chmod +x /usr/local/bin/xdebug-starter
RUN /usr/local/bin/xdebug-starter

RUN /usr/local/bin/composer self-update

RUN rm /usr/local/etc/php/conf.d/docker-php-memlimit.ini
ADD conf/php.ini /usr/local/etc/php

#COPY ./rootfilesystem/ /
#RUN chmod +x /customized-entrypoint.sh
#
#ENTRYPOINT ["/customized-entrypoint.sh"]
