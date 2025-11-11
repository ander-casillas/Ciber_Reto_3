# Usa Debian como base
FROM debian:stable-slim
 
# Evita prompts interactivos
ENV DEBIAN_FRONTEND=noninteractive
 
# Instala Apache, PHP, OpenSSL y soporte LDAP
RUN apt-get update && \
    apt-get install -y apache2 openssl \
    php php-cli libapache2-mod-php php-ldap php-mysqli php-zip php-curl php-xml php-mbstring php-gd && \
    apt-get clean && rm -rf /var/lib/apt/lists/*
 
# Habilita módulos necesarios (incluyendo LDAP)
RUN a2enmod ssl && \
    a2enmod rewrite && \
    a2enmod ldap && \
    a2enmod authnz_ldap
 
# Crea el directorio de certificados
RUN mkdir /etc/apache2/ssl
 
# Genera un certificado autofirmado válido por 1 año
RUN openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
    -keyout /etc/apache2/ssl/apache.key \
    -out /etc/apache2/ssl/apache.crt \
    -subj "/C=ES/ST=Madrid/L=Madrid/O=MiOrg/OU=IT/CN=localhost"
 
# Crea el directorio protegido (para pruebas)
RUN mkdir -p /var/www/html/test && chown -R www-data:www-data /var/www/html
 
# Configura el VirtualHost SSL con autenticación LDAP
RUN printf '%s\n' \
    '<VirtualHost _default_:443>' \
    '    ServerAdmin admin@localhost' \
    '    DocumentRoot /var/www/html' \
    '' \
    '    SSLEngine on' \
    '    SSLCertificateFile /etc/apache2/ssl/apache.crt' \
    '    SSLCertificateKeyFile /etc/apache2/ssl/apache.key' \
    '' \
    '    <Directory /var/www/html>' \
    '        Options Indexes FollowSymLinks' \
    '        AllowOverride All' \
    '        Require all granted' \
    '    </Directory>' \
    '' \
    '    <Directory /var/www/html/test>' \
    '        AuthType Basic' \
    '        AuthName "Autenticación LDAP"' \
    '        AuthBasicProvider ldap' \
    '        AuthLDAPURL "ldap://ldap:389/ou=Users,dc=txiribiton,dc=local?uid?sub?(objectClass=*)"' \
    '        AuthLDAPBindDN "cn=admin,dc=txiribiton,dc=local"' \
    '        AuthLDAPBindPassword admin' \
    '        Require valid-user' \
    '    </Directory>' \
    '' \
    '    ErrorLog ${APACHE_LOG_DIR}/error.log' \
    '    CustomLog ${APACHE_LOG_DIR}/access.log combined' \
    '</VirtualHost>' \
> /etc/apache2/sites-available/default-ssl.conf
 
# Habilita el sitio SSL
RUN a2ensite default-ssl
 
# Expone los puertos HTTP y HTTPS
EXPOSE 80 443
 
# Inicia Apache en primer plano
CMD ["apachectl", "-D", "FOREGROUND"]