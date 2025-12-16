#!/usr/bin/env bash
set -e

APP_NAME="buscaleis"
APP_DIR="/var/www/$APP_NAME"
PHP_VERSION="8.2"

echo "🔄 Atualizando sistema..."
apt update && apt upgrade -y

echo "🐘 Instalando PHP e extensões..."
apt install -y software-properties-common
add-apt-repository ppa:ondrej/php -y
apt update

apt install -y \
  php$PHP_VERSION \
  php$PHP_VERSION-cli \
  php$PHP_VERSION-fpm \
  php$PHP_VERSION-mysql \
  php$PHP_VERSION-curl \
  php$PHP_VERSION-mbstring \
  php$PHP_VERSION-xml \
  php$PHP_VERSION-zip \
  php$PHP_VERSION-gd \
  php$PHP_VERSION-intl \
  unzip git curl

echo "🐬 Instalando MariaDB..."
apt install -y mariadb-server mariadb-client
systemctl enable mariadb
systemctl start mariadb

echo "📦 Instalando Composer..."
apt install -y composer

echo "📁 Clonando repositório..."
mkdir -p /var/www
cd /var/www

git clone https://github.com/dmoraesn/buscaleis.git $APP_NAME
cd $APP_NAME

echo "📦 Instalando dependências do Laravel..."
composer install --no-dev --optimize-autoloader

echo "⚙️ Criando .env..."
cp .env.example .env

echo "🔑 Gerando APP_KEY..."
php artisan key:generate

echo "🛢️ Criando banco e usuário..."
mysql <<EOF
CREATE DATABASE IF NOT EXISTS buscaleis
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

CREATE USER IF NOT EXISTS 'buscaleis_user'@'localhost'
  IDENTIFIED BY 'ALTERAR_SENHA';

GRANT ALL PRIVILEGES ON buscaleis.* TO 'buscaleis_user'@'localhost';
FLUSH PRIVILEGES;
EOF

echo "🗄️ Rodando migrations..."
php artisan migrate --force || true

echo "🧹 Otimizando aplicação..."
php artisan optimize || true

echo "🔐 Ajustando permissões..."
chown -R www-data:www-data /var/www/$APP_NAME
chmod -R 755 /var/www/$APP_NAME
chmod -R 775 storage bootstrap/cache

echo "✅ Instalação finalizada."
echo "👉 Agora configure o .env (DB, OpenAI, APP_URL) e o EasyPanel."
