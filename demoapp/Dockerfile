FROM ubuntu:20.04
ENV TZ=Etc/UTC
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone
RUN apt update
RUN apt install -y apache2 php libapache2-mod-php php-mysql
COPY ./source/demoapp.php /var/www/html/
CMD /usr/sbin/apache2ctl -D FOREGROUND
