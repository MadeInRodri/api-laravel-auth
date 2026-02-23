# Version stable y en el sistema base de apline
FROM nginx:stable-alpine
# Copiamos la config del nginx
COPY nginx/default.conf /etc/nginx/conf.d/default.conf
# Copia el CONTENIDO de public, no la carpeta
COPY api-laravel-auth/public /var/www/html/public 
WORKDIR /var/www/html
EXPOSE 80