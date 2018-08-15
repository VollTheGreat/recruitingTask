FROM nginx:1.13.5-alpine

COPY ./src/nginx.conf /etc/nginx/
COPY ./src/conf.d/ /etc/nginx/conf.d/

CMD sh -c "envsubst '\$NGINX_HOST' < /etc/nginx/conf.d/vhost.template > /etc/nginx/conf.d/default.conf && nginx;"
