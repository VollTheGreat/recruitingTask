# Image must be built with context set to components directory
FROM nginx:1.13.5-alpine
COPY ./server/src/nginx.conf /etc/nginx/
COPY ./server/src/conf.d/ /etc/nginx/conf.d/
WORKDIR /var/www
COPY ./app/src/ ./
COPY ./app/dist ./public/
CMD sh -c "envsubst '\$NGINX_HOST' < /etc/nginx/conf.d/vhost.template > /etc/nginx/conf.d/default.conf && nginx;"
