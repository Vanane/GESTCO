FROM ubuntu:latest

RUN \
	apt-get update && \
	apt-get install apache2 php libapache2-mod-php php-mysql && \
	apt install php-curl php-gd php-intl php-json php-mbstring php-xml php-zip && \
	a2ensite example.com && \
	systemctl reload apache2 && \
	apt-get update && \
	DEBIAN_FRONTEND=noninteractive apt-get install -y mysql-server && \
	rm -rf /var/lib/apt/lists/* && \
	sed -i 's/^\(bind-address\s.*\)/# \1/' /etc/mysql/my.cnf && \
	sed -i 's/^\(log_error\s.*\)/# \1/' /etc/mysql/my.cnf && \
	echo "mysqld_safe &" > /tmp/config && \
	echo "mysqladmin --silent --wait=30 ping || exit 1" >> /tmp/config && \
	echo "mysql -e 'GRANT ALL PRIVILEGES ON *.* TO \"root\"@\"%\" WITH GRANT OPTION;'" >> /tmp/config && \
	bash /tmp/config && \
	rm -f /tmp/config

	
EXPOSE 3306