FROM php:8.2

RUN docker-php-ext-install pcntl

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN composer self-update

RUN bash -c "curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.40.2/install.sh | bash \
   && source '/root/.nvm/nvm.sh' \
   && nvm install 22"

RUN apt -y autoremove \
   && apt clean \
   && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN sed -i 's/^# umask .*/umask 000/' /root/.bashrc

ENTRYPOINT ["bash", "-c", "source /root/.bashrc && exec \"$@\"", "--"]
