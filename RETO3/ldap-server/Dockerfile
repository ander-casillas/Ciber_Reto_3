FROM debian:bullseye
ENV DEBIAN_FRONTEND=noninteractive

# Paquetes
RUN apt-get update \
 && apt-get install -y debconf-utils gettext-base \
 && echo "slapd slapd/no_configuration boolean true" | debconf-set-selections \
 && apt-get install -y slapd ldap-utils \
 && rm -rf /var/lib/apt/lists/*

# Archivos de plantilla y entrypoint
COPY slapd.conf.template /etc/ldap/slapd.conf.template
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Dirs y permisos
RUN mkdir -p /var/lib/ldap /var/run/slapd \
 && chown -R openldap:openldap /var/lib/ldap /var/run/slapd

EXPOSE 389

ENTRYPOINT ["/entrypoint.sh"]
