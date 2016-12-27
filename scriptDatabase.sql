create database banca;

use banca;

create table tb_ficha (
	ficha_id int auto_increment not null,
    qtdpares int not null,
    numeroplano varchar(10) not null,
    numeroficha varchar(10) not null,
    dataentrada timestamp not null,
    datasaida datetime,
    modelo_id int not null,
    primary key(ficha_id)
    
) collate utf8_general_ci;

create table tb_modelo (
	modelo_id int auto_increment not null,
    numeromodelo varchar(10) not null,
    preco double not null,
    fabrica_id int not null,
    primary key(modelo_id)
    
) collate utf8_general_ci;


create table tb_fabrica (
	fabrica_id int auto_increment not null,
    razaosocial varchar(50) not null,
    nomefantasia varchar(50) not null,
    endereco varchar(80) not null,
    cnpj char(16) not null,
    primary key(fabrica_id)
    
) collate utf8_general_ci;


create table tb_image (
	image_id int auto_increment not null,
    path varchar(100) not null,
    imagenome varchar(50) not null,
    modelo_id int not null,
    primary key(image_id)
    
) collate utf8_general_ci;

alter table tb_ficha add constraint foreign key 
fk_tb_modelo(modelo_id) references tb_modelo(modelo_id);

alter table tb_modelo add constraint foreign key 
fk_tb_fabrica(fabrica_id) references tb_fabrica(fabrica_id);

alter table tb_image add constraint foreign key 
fk_tb_image(modelo_id) references tb_modelo(modelo_id);
