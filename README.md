# Aplicação web + Amazon RDS (MariaDB)

App PHP simples (tutorial AWS) para criar e listar produtos no banco sample (Amazon RDS MariaDB).

## Arquitetura
- EC2: Apache + PHP
- RDS: MariaDB (endpoint configurado no dbinfo.inc)
- Security Groups: EC2 (22/80), RDS (3306) permitindo tráfego do SG da EC2

## Tabela extra
products:
- id INT PK AUTO_INCREMENT
- name VARCHAR(100)
- price DECIMAL(10,2)
- quantity INT
- release_date DATE

## Páginas
- aws-webapp/create_product.php – formulário para inserir em products
- aws-webapp/list_products.php – lista os registros

## Como rodar na EC2
## 1) Pré-requisitos

- Instância **EC2** ativa.
- Instância **RDS MariaDB** acessível pela EC2.
- **Security Groups**:
  - EC2 libera **22 (SSH)** e **80 (HTTP)**.
  - RDS libera **3306** **a partir do SG da EC2**.

---

## 2) Instalar pacotes na EC2

**Amazon Linux 2**

```bash
sudo yum update -y
sudo amazon-linux-extras enable php8.2
sudo yum install -y httpd php php-mysqli
sudo systemctl enable --now httpd
```

**Ubuntu**

```bash
sudo apt update
sudo apt install -y apache2 php php-mysql
sudo systemctl enable --now apache2
```

---

## 3) Deploy da aplicação

```bash
# criar pasta do app (document root padrão)
sudo mkdir -p /var/www/html/aws-webapp

# se este repositório foi clonado em ~/aws-webapp-rds
sudo cp -r ~/aws-webapp-rds/aws-webapp/* /var/www/html/aws-webapp/
```

Arquivos esperados no servidor:

- `/var/www/html/aws-webapp/create_product.php`
- `/var/www/html/aws-webapp/list_products.php`
- `/var/www/html/aws-webapp/index.php` (opcional)

---

## 4) Configurar credenciais do banco (NÃO versionar)

```bash
sudo mkdir -p /var/www/inc
sudo tee /var/www/inc/dbinfo.inc >/dev/null <<'PHP'
<?php
define('DB_SERVER','SEU_ENDPOINT_RDS');     // ex.: tutorial-db-instance.xxxx.us-east-1.rds.amazonaws.com
define('DB_USERNAME','SEU_USUARIO');        // ex.: tutorial_user
define('DB_PASSWORD','SUA_SENHA');
define('DB_DATABASE','sample');             // nome do schema usado pela aplicação
?>
PHP

# permissões seguras
sudo chown apache:apache /var/www/inc/dbinfo.inc 2>/dev/null || sudo chown www-data:www-data /var/www/inc/dbinfo.inc
sudo chmod 640 /var/www/inc/dbinfo.inc
```

> O arquivo `dbinfo.inc` fica **fora** do document root (`/var/www/inc`) e **não** deve ir para o Git.

---

## 5) (Opcional) Criar DB/tabela caso não existam

Conectar ao RDS:

```bash
mysql -h SEU_ENDPOINT_RDS -P 3306 -u SEU_USUARIO -p
```

Criar schema e tabela:

```sql
CREATE DATABASE IF NOT EXISTS sample;
USE sample;

CREATE TABLE IF NOT EXISTS products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  quantity INT NOT NULL,
  release_date DATE DEFAULT NULL
) ENGINE=InnoDB;
```

---

## 6) Ajustar permissões e reiniciar o Apache

```bash
sudo chown -R apache:apache /var/www/html/aws-webapp 2>/dev/null || sudo chown -R www-data:www-data /var/www/html/aws-webapp
sudo systemctl restart httpd 2>/dev/null || sudo systemctl restart apache2
```

---

## 7) Acessar a aplicação

```
http://SEU_DNS_EC2/aws-webapp/list_products.php
```

Use **“Adicionar novo”** para criar registros em `products`.

---

## Vídeo
URL: [Demonstração](https://drive.google.com/file/d/1JUK1m-AZB5CjtEu1BMZ3PKpFKtnyhVCo/view?usp=sharing)
