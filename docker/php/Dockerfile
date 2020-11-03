FROM matiux/php-swoole:7.4.11-cli-buster-dev

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
