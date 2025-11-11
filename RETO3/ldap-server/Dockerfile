FROM debian:bullseye
ENV DEBIAN_FRONTEND=noninteractive

# 1) Instalar sin prompts
RUN apt-get update \
 && apt-get install -y debconf-utils \
 && echo "slapd slapd/no_configuration boolean true" | debconf-set-selections \
 && apt-get install -y slapd ldap-utils \
 && rm -rf /var/lib/apt/lists/*

# 2) Config clásica
COPY slapd.conf /etc/ldap/slapd.conf

# 3) Dirs y permisos
RUN mkdir -p /var/lib/ldap /var/run/slapd \
 && chown -R openldap:openldap /var/lib/ldap /var/run/slapd

# 4) Sembrar la base (crea dc=txiribiton,dc=local y cn=admin)
RUN cat > /tmp/base.ldif <<'EOF' && \
slapadd -f /etc/ldap/slapd.conf -l /tmp/base.ldif && \
chown -R openldap:openldap /var/lib/ldap
dn: dc=txiribiton,dc=local
objectClass: top
objectClass: dcObject
objectClass: organization
o: txiribiton
dc: txiribiton

dn: cn=admin,dc=txiribiton,dc=local
objectClass: simpleSecurityObject
objectClass: organizationalRole
cn: admin
description: Directory Manager
userPassword: admin123
EOF

EXPOSE 389

# 5) Mantener el proceso en primer plano
CMD ["slapd","-d","stats","-f","/etc/ldap/slapd.conf","-h","ldap:///"]
