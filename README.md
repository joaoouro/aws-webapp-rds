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
1. Copiar a pasta aws-webapp/ para /var/www/html/aws-webapp
2. Criar /var/www/inc/dbinfo.inc (NÃO subir no Git) com:
   <?php
   define('DB_SERVER','SEU_ENDPOINT_RDS');
   define('DB_USERNAME','SEU_USUARIO');
   define('DB_PASSWORD','SUA_SENHA');
   define('DB_DATABASE','sample');
   ?>
3. Acessar: http://SEU_DNS_EC2/aws-webapp/list_products.php

## Vídeo
URL: [Demonstração](https://drive.google.com/file/d/1JUK1m-AZB5CjtEu1BMZ3PKpFKtnyhVCo/view?usp=sharing)
