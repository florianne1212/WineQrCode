#!/usr/bin/env bash

# Function to check MySQL readiness
check_mysql() {
  until mysql -h db -u"$MYSQL_USER" -p"$MYSQL_PASSWORD" -e 'SHOW DATABASES;' &> /dev/null; do
    echo "Waiting for MySQL to be ready..."
    sleep 2
  done
  echo "MySQL is up and running!"
}

# Check MySQL readiness
check_mysql

echo "Granting privileges to 'wordpress' user..."
mysql -h db -u"$MYSQL_ROOT_USER" -p"$MYSQL_ROOT_PASSWORD" <<-EOSQL
  GRANT ALL PRIVILEGES ON *.* TO 'wordpress'@'%' IDENTIFIED BY 'wordpress' WITH GRANT OPTION;
  FLUSH PRIVILEGES;
EOSQL
echo "Privileges granted."

 
php bin/console doctrine:database:create --if-not-exists
php bin/console make:migration   
php bin/console doctrine:migrations:migrate --no-interaction
# bin/console doctrine:fixtures:load --no-interaction
 
exec "$@"