-- LEMBRAR QUE A SENHA DEVE SER CRIPTOGREFADA
-- COISAS A MUDAR EM CASA O VSHOST DO XAP; AS VARIAVEIS DE CONTA DO WINDOWS; O HOST DO WINDOWS;

-- senha site P&TCZCcMmZ%yJxE5H5!x
-- senha do bd no host HZ=F@I#4$7VT8q2B
create database icatalogo;

use icatalogo;

create table tbl_produto(
    id int primary key auto_increment,
    descricao varchar(255) not null,
    peso decimal(10,2) not null,
    quantidade int not null,
    cor varchar(100) not null,
    tamanho varchar(100),
    valor decimal(10,2) not null,
    desconto int,
    imagem varchar(500)
);

create table tbl_administrador(
	id int primary key auto_increment,
	nome varchar(255) not null,
	usuario varchar(255) not null,
	senha varchar(255) not null
);

INSERT INTO tbl_administrador (nome, usuario, senha) VALUES ('usuario2','usuario2' ,'321#@!');

create table tbl_categoria (
id int primary key auto_increment,
descricao varchar(255) not null
);

select * From tbl_administrador;

SELECT *FROM tbl_produto;

ALTER TABLE tbl_produto
ADD COLUMN categoria_id int,
ADD FOREIGN KEY (categoria_id) REFERENCES tbl_categoria(id);

TRUNCATE tbl_produto;

SELECT p.*,c.descricao as categoria FROM tbl_produto p
INNER JOIN tbl_categoria c ON p.categoria_id = c.id
ORDER BY p.id DESC;

drop table tbl_produto;