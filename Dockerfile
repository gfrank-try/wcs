FROM node:18

RUN npm install -g @angular/cli@15

WORKDIR /srv/app

COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 4200
ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
