# Istruzioni all'uso del sito

1)ESEGUIRE LE ISTRUZIONE PER AVVIARE DOCKER (gli indirizzi possono variare a seconda della posizione della cartella mio-sito) <br>
-docker run -d -p 8080:80 --name my-apache-php-app --rm  -v /home/informatica/Desktop/mio-sito:/var/www/html zener79/php:7.4-apache<br>
-creare la cartella 'mysqldata'<br>
-docker run --name my-mysql-server --rm -v /home/informatica/Desktop/mio-sito/mysqldata:/var/lib/mysql -v /home/informatica/Desktop/mio-sito/dump:/dump -e MYSQL_ROOT_PASSWORD=my-secret-pw -p 3306:3306 -d mysql:latest<br>
-docker exec -it my-mysql-server bash<br>
-mysql -u root -p < /dump/create_employee.sql<br>
-Inserire password=my-secret-pw<br>
-exit;<br><br>

2)Cercare nell'URL "http://localhost:8080/frontEnd/index.html" <br>

-Sia BackEnd che FrontEnd sono stati messi nella stessa cartella 'mio-sito' per semplificare l'utilizzo (chiesto in classe al professore Scialpi)
