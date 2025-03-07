# ref https://davescripts.com/docker-container-with-centos-7-apache-php-72
FROM centos:7

# CentOS 미러 URL을 vault로 변경 (EOL 문제 해결)
RUN sed -i 's/mirrorlist/#mirrorlist/g' /etc/yum.repos.d/CentOS-* && \
    sed -i 's|#baseurl=http://mirror.centos.org|baseurl=http://vault.centos.org|g' /etc/yum.repos.d/CentOS-*

# Install Apache
RUN yum -y update && yum -y install epel-release
RUN yum -y install httpd httpd-tools net-tools

# Install REMI Repo (수정된 부분)
RUN yum -y install https://rpms.remirepo.net/enterprise/remi-release-7.rpm

# 웹태틱 저장소는 더 이상 사용할 수 없으므로 제거하거나 대체해야 합니다
# Install PHP
RUN yum -y install yum-utils
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

# 빌드 및 실행 명령어 (참고용 주석)
# docker build -t ins-img .
# docker run -tid -p 80:80 --name insplus -v "C:\Users\yjh_z\Desktop\코드아이디어\인슈플러스\Insuplus":/var/www/html ins-img
# or
# docker run -tid -p 80:80 --name insplus ins-img
# docker cp . insplus:/var/www/html