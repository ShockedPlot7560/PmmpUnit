# Build stage for compilation. Build tools like g++ will not be copied into the final stage to reduce image size.
FROM ubuntu:22.04
ARG PMMP_TAG
ARG PMMP_REPO=pmmp/PocketMine-MP
ARG PHP_VERSION

RUN test ! -z ${PHP_VERSION} || (echo "Missing build-arg PHP_VERSION" && false)

RUN apt-get update && apt-get install --no-install-recommends -y curl ca-certificates build-essential m4 gzip bzip2 bison git cmake autoconf automake pkg-config libtool libtool-bin re2c

RUN mkdir /build
WORKDIR /build
RUN git clone https://github.com/${PMMP_REPO}.git -b ${PMMP_TAG} --recursive .
WORKDIR /build/build/php
RUN curl -L https://github.com/pmmp/PHP-Binaries/releases/download/php-$PHP_VERSION-latest/PHP-Linux-x86_64-PM5.tar.gz | tar -xz --strip-components=1
RUN ln -s /build/build/php/php7/bin/php /usr/bin/php

WORKDIR /build
RUN curl -L https://getcomposer.org/installer | php
RUN mv composer.phar /usr/bin/composer

RUN composer install --classmap-authoritative --no-dev --prefer-source
RUN if test -d build/preprocessor; then php build/preprocessor/PreProcessor.php --path=src --multisize; else echo "Skipping preprocessor step (preprocessor not found)"; fi
RUN php $(test -d vendor/pocketmine/bedrock-data && echo -n vendor/pocketmine/bedrock-data || echo -n src/pocketmine/resources/vanilla)/.minify_json.php

RUN php -dphar.readonly=0 build/server-phar.php --git $(git rev-parse HEAD)
# Just to make sure DevTools didn't false-positive-exit
RUN test -f /build/PocketMine-MP.phar

WORKDIR /build
COPY ./ /build/pmmpunit
WORKDIR /build/pmmpunit
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer install --no-dev --prefer-source --no-interaction --optimize-autoloader
RUN php -dphar.readonly=0 build/plugin-phar.php PmmpUnit

FROM ubuntu:22.04
LABEL maintainer="ShockedPlot7560 <no-reply@tchallon.fr>"

RUN apt-get update && apt-get install --no-install-recommends -y ca-certificates wget

RUN groupadd -g 1000 pocketmine
RUN useradd -r -d /pocketmine -p "" -u 1000 -m -g pocketmine pocketmine

WORKDIR /pocketmine
COPY --from=0 /build/build/php/php7 /usr/php
RUN grep -q '^extension_dir' /usr/php/bin/php.ini && \
	sed -ibak "s{^extension_dir=.*{extension_dir=\"$(find /usr/php -name *debug-zts*)\"{" /usr/php/bin/php.ini || echo "extension_dir=\"$(find /usr/php -name *debug-zts*)\"" >> /usr/php/bin/php.ini
RUN ln -s /usr/php/bin/php /usr/bin/php
COPY --from=0 /build/PocketMine-MP.phar PocketMine-MP.phar
COPY --from=0 /build/pmmpunit/PmmpUnit.phar /pocketmine/default_plugins/PmmpUnit.phar
ADD start.sh /usr/bin/start-pocketmine
RUN chmod +x /usr/bin/start-pocketmine

RUN mkdir /plugins /data
RUN chown 1000:1000 /plugins /data . -R
RUN chmod o+x /usr/bin/php

USER pocketmine

ENV TERM=xterm

EXPOSE 19132/tcp
EXPOSE 19132/udp

VOLUME ["/data", "/plugins"]

CMD ["start-pocketmine"]