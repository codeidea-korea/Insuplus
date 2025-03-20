# ref https://davescripts.com/docker-container-with-centos-7-apache-php-72

FROM centos:7

# Install Apache
RUN yum -y update && yum -y install epel-release
RUN yum -y install httpd httpd-tools net-tools

# Install EPEL, REMI Repo
RUN rpm -Uvh https://dl.fedoraproject.org/pub/epel/epel-release-latest-7.noarch.rpm \
 && rpm -Uvh https://mirror.webtatic.com/yum/el7/webtatic-release.rpm \
 && rpm -Uvh https://rpms.remirepo.net/enterprise/remi-release-7.rpm

RUN yum -y install yum-utils
#yum -y install https://rpms.remirepo.net/enterprise/remi-release-7.rpm

# Install PHP
RUN yum-config-manager --enable remi-php70 && yum -y install php
RUN yum --enablerepo=remi -y install php-gd php-json php-mbstring php-mcrypt php-mysqlnd php-pdo php-pecl-zip php-soap php-xml php-xmlrpc

# Update Apache Configuration
RUN sed -E -i -e '/<Directory "\/var\/www\/html">/,/<\/Directory>/s/AllowOverride None/AllowOverride All/' /etc/httpd/conf/httpd.conf
RUN sed -E -i -e 's/DirectoryIndex (.*)$/DirectoryIndex index.php \1/g' /etc/httpd/conf/httpd.conf
RUN sed -E -i -e 's/short_open_tag = Off/short_open_tag = On/g' /etc/php.ini

RUN localedef -f UTF-8 -i ko_KR ko_KR.utf8 && ln -sf /usr/share/zoneinfo/Asia/Seoul /etc/localtime
ENV LANG ko_KR.utf8
ENV LC_ALL ko_KR.utf8

EXPOSE 80

# Start Apache
CMD ["/usr/sbin/httpd","-D","FOREGROUND"]

# docker build -t ins-img .

# docker run -tid -p 80:80 --name insplus -v "C:\Workspaces\PhpProjects\biz_insplus":/var/www/html ins-img
# or
# docker run -tid -p 80:80 --name insplus ins-img
# docker cp . insplus:/var/www/html