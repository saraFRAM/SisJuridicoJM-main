alter TABLE usuarios add saldo double DEFAULT 0;


CREATE TABLE `conceptos` (
  `idConcepto` int(11) NOT NULL AUTO_INCREMENT,
  `usuarioId` int(11) DEFAULT NULL,
  `tipoId` int(11) DEFAULT NULL COMMENT '1 cargo, 2 abono',
  `catConceptoId` int(11) DEFAULT NULL,
  `descripcion` varchar(100) DEFAULT NULL,
  `monto` double DEFAULT NULL,
  `saldo` double DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `fechaCreacion` TIMESTAMP,
  PRIMARY KEY (`idConcepto`),
  KEY `usuarioId` (`usuarioId`),
  KEY `tipoId` (`tipoId`),
  KEY `catConceptoId` (`catConceptoId`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;



-- jair 16/11/2021
ALTER TABLE casos ADD descripcion TEXT NULL AFTER autorizadosIds;


-- 19/11/2021
CREATE TABLE `caso_acciones` (
  `idAccion` int(11) NOT NULL,
  `casoId` int(11) DEFAULT NULL COMMENT 'identificador del caso',
  `nombre` varchar(150) DEFAULT NULL COMMENT 'nombre de la accion',
  `comentarios` text COMMENT 'Comentarios',
  `fechaAlta` date DEFAULT NULL COMMENT 'fecha alta del caso',
  `fechaAct` datetime DEFAULT NULL COMMENT 'fecha cuando se actualizo el cliente',
  `fechaCreacion` timestamp COMMENT 'fecha en que fue dado de alta'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `caso_acciones`
  ADD PRIMARY KEY (`idAccion`);


ALTER TABLE `caso_acciones`
  MODIFY `idAccion` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;


ALTER TABLE caso_acciones ADD tipo INT(1) DEFAULT NULL  COMMENT '1 de fondo, 2 seguimiento' AFTER casoId;
ALTER TABLE caso_acciones ADD importancia INT(1) DEFAULT NULL  COMMENT '1 media, 2 normal, 3 alta' AFTER tipo;
ALTER TABLE caso_acciones ADD fechaCompromiso DATETIME DEFAULT NULL   AFTER importancia;
ALTER TABLE caso_acciones ADD recordatorio INT(1) DEFAULT NULL  COMMENT '0 no, 1 si' AFTER fechaCompromiso;
ALTER TABLE caso_acciones ADD fechaRealizado DATETIME DEFAULT NULL  AFTER fechaCompromiso;


-- 26/11/2021
ALTER TABLE usuarios ADD numAbogado VARCHAR(50) ;



-- 1/12/2021
create table tblWeekName(
id int,
spanish varchar(20)
);


insert into tblWeekName values
(0, 'Domingo'),
(1, 'Lunes'),
(2, 'Martes'),
(3, 'Miercoles'),
(4, 'Jueves'),
(5, 'Viernes'),
(6, 'Sabado');


create table tblMonthName(
id int,
spanish1 varchar(30),
spanish2 varchar(3)
);


insert into tblMonthName values
(1, 'Enero', 'Ene'),
(2, 'Febrero', 'Feb'),
(3, 'Marzo', 'Mar'),
(4, 'Abril', 'Abr'),
(5, 'Mayo', 'May'),
(6, 'Junio', 'Jun'),
(7, 'Julio', 'Jul'),
(8, 'Agosto', 'Ago'),
(9, 'Septiembre', 'Sep'),
(10, 'Octubre', 'Oct'),
(11, 'Noviembre', 'Nov'),
(12, 'Diciembre', 'Dic');


-- 2/12/2021
ALTER TABLE conceptos ADD comprobante TEXT NULL;


-- 3/12/2021
ALTER TABLE casos ADD internos TEXT NULL AFTER descripcion;
ALTER TABLE `caso_acciones` ADD internos TEXT NULL AFTER comentarios;





-- 9/12/2021
ALTER TABLE `cat_conceptos` ADD tipo INT(2) NULL COMMENT '1 de entradas, 2 salidas 3 gastos admin' AFTER idConcepto;
-- Actualizar los conceptos existentes como de salidas
UPDATE `cat_conceptos` SET `tipo` = '2' ; 


-- Insertar conceptos de salidas
INSERT INTO cat_conceptos (tipo, nombre, activo) VALUES
(1, 'Pago a cuenta', 1),
(1, 'Prestamo de companero', 1),
(1, 'Correccion', 1);


-- Framelova (solo insertar uno en cada bd ya que la consulta se hace por id)
-- INSERT INTO `cat_configuraciones` (`idConfiguracion`, `nombre`, `valor`, `fechaCreacion`, `fechaAct`) VALUES (NULL, 'captcha_clave_sitio', '6Le2xhcTAAAAAJ3dOzgi7JdEidLqwOvSVczxFoKv', '2021-12-09 00:00:00', NULL), (NULL, 'captcha_clave_secreta', '6Le2xhcTAAAAAPgKSk5RiXT0v7qa_NZ5SCulufh1', '2021-12-09 00:00:00', NULL);


-- Juridico productivo (solo insertar uno en cada bd ya que la consulta se hace por id)
INSERT INTO `cat_configuraciones` (`idConfiguracion`, `nombre`, `valor`, `fechaCreacion`, `fechaAct`) VALUES (NULL, 'captcha_clave_sitio', '6LdTB5AUAAAAAGT7tCBAuQ8of5ZEwVizyjpKd_Fd', '2021-12-09 00:00:00', NULL), (NULL, 'captcha_clave_secreta', '6LdTB5AUAAAAAFSCeNM-WvUx4zpikFidJoWC9haC', '2021-12-09 00:00:00', NULL);



-- 10/12/2021
ALTER TABLE clientes ADD aka TEXT NULL AFTER empresa;


ALTER TABLE casos ADD usuarioAltaId INT NULL AFTER internos;
ALTER TABLE casos ADD numExpediente VARCHAR(20) NULL AFTER usuarioAltaId;
ALTER TABLE casos ADD numExpedienteJuzgado VARCHAR(20) NULL AFTER numExpediente;
ALTER TABLE casos ADD estatusGeneral INT(2) NULL COMMENT '1 verde, 2 amarillo 3 rojo' AFTER numExpedienteJuzgado;
ALTER TABLE casos ADD titular INT NULL AFTER estatusGeneral;
ALTER TABLE casos ADD velocidad INT(2) NULL COMMENT '1 Normal, 2 Media 3 Alta' AFTER titular;
ALTER TABLE casos ADD contrario TEXT NULL AFTER velocidad;


ALTER TABLE `casos` CHANGE `titular` `titularId2` INT(11) NULL DEFAULT NULL;
ALTER TABLE `casos` CHANGE `titularId` `titularId` INT(11) NULL DEFAULT NULL COMMENT 'identificador del responsable rol abogado';



-- 14/12/2021
ALTER TABLE caso_acciones ADD padreAccionId INT DEFAULT 0;
ALTER TABLE caso_acciones ADD usuarioId INT NULL AFTER casoId;


-- 15/12/2021
ALTER TABLE `casos` CHANGE `estatusGeneral` `saludExpediente` INT(2) NULL DEFAULT NULL COMMENT '1 verde, 2 amarillo 3 rojo';
ALTER TABLE `casos` CHANGE `titularId` `responsableId` INT(11) NULL DEFAULT NULL COMMENT 'identificador del responsable rol abogado';


CREATE TABLE `cat_partes` (
  `idParte` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(150) DEFAULT NULL,
  `activo` char(1) NOT NULL DEFAULT '0',
  `fechaCreacion` datetime DEFAULT NULL,
  `fechaAct` datetime DEFAULT NULL,
  PRIMARY KEY (`idParte`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;


CREATE TABLE `cat_materias` (
  `idMateria` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(150) DEFAULT NULL,
  `activo` char(1) NOT NULL DEFAULT '0',
  `fechaCreacion` datetime DEFAULT NULL,
  `fechaAct` datetime DEFAULT NULL,
  PRIMARY KEY (`idMateria`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;


CREATE TABLE `cat_juicios` (
  `idJuicio` int(11) NOT NULL AUTO_INCREMENT,
  materiaId INT NULL,
  `nombre` varchar(150) DEFAULT NULL,
  `activo` char(1) NOT NULL DEFAULT '0',
  `fechaCreacion` datetime DEFAULT NULL,
  `fechaAct` datetime DEFAULT NULL,
  PRIMARY KEY (`idJuicio`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;


CREATE TABLE `cat_distritos` (
  `idDistrito` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(150) DEFAULT NULL,
  `activo` char(1) NOT NULL DEFAULT '0',
  `fechaCreacion` datetime DEFAULT NULL,
  `fechaAct` datetime DEFAULT NULL,
  PRIMARY KEY (`idDistrito`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;


CREATE TABLE `cat_juzgados` (
  `idJuzgado` int(11) NOT NULL AUTO_INCREMENT,
  distritoId INT NULL,
  `nombre` varchar(150) DEFAULT NULL,
  `activo` char(1) NOT NULL DEFAULT '0',
  `fechaCreacion` datetime DEFAULT NULL,
  `fechaAct` datetime DEFAULT NULL,
  PRIMARY KEY (`idJuzgado`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;


CREATE TABLE `cat_contactos` (
  `idContacto` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(150) DEFAULT NULL,
  `activo` char(1) NOT NULL DEFAULT '0',
  `fechaCreacion` datetime DEFAULT NULL,
  `fechaAct` datetime DEFAULT NULL,
  PRIMARY KEY (`idContacto`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;


-- 16/12/2021
ALTER TABLE `casos` ADD `estatusId` INT(1) NOT NULL DEFAULT '1' COMMENT '1 activo, 2 suspendido, 3 baja, 4 terminado' AFTER `contrario`, ADD `parteId` INT NULL DEFAULT NULL AFTER `estatusId`, ADD `domicilioEmplazar` TEXT NULL DEFAULT NULL AFTER `parteId`, ADD `materiaId` INT NULL DEFAULT NULL AFTER `domicilioEmplazar`, ADD `juicioId` INT NULL DEFAULT NULL AFTER `materiaId`, ADD `distritoId` INT NULL DEFAULT NULL AFTER `juicioId`, ADD `juzgadoId` INT NULL DEFAULT NULL AFTER `distritoId`;


-- 17/12/2021
ALTER TABLE `caso_acciones` ADD `estatusId` INT(1) NOT NULL DEFAULT '1' COMMENT '1 activo, 2 terminado' AFTER `padreAccionId`, ADD `esperoInstrucciones` INT(1) NOT NULL DEFAULT '0' COMMENT '1 si, 0 no' AFTER `estatusId`;


ALTER TABLE `caso_acciones` CHANGE `estatusId` `estatusId` INT(1) NOT NULL DEFAULT '1' COMMENT '1 activo, 2 en proceso, 3 espero instrucciones, 4 terminado';


-- 22/12/2021
ALTER TABLE `cat_contactos` ADD `casoId` INT NULL DEFAULT NULL COMMENT 'id del expediente' AFTER `idContacto`;
ALTER TABLE `cat_contactos` ADD `telefono` VARCHAR(12) NULL DEFAULT NULL AFTER `nombre`, ADD `email` VARCHAR(200) NULL DEFAULT NULL AFTER `telefono`;
ALTER TABLE `cat_contactos` CHANGE `fechaCreacion` `fechaCreacion` TIMESTAMP NULL DEFAULT NULL;
ALTER TABLE `cat_contactos` CHANGE `fechaAct` `fechaAct` TIMESTAMP on update CURRENT_TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE `cat_contactos` CHANGE `fechaCreacion` `fechaCreacion` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP;


ALTER TABLE `caso_acciones` CHANGE `nombre` `nombre` VARCHAR(300) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'nombre de la accion';
ALTER TABLE `clientes` CHANGE `direccion` `direccion` VARCHAR(300) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;
ALTER TABLE `casos` CHANGE `numExpedienteJuzgado` `numExpedienteJuzgado` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;


--23/12/2021
CREATE TABLE `mensajes` (
  `idMensaje` int(11) NOT NULL AUTO_INCREMENT,
  `usuarioId` int(11) DEFAULT NULL,
  `tipo` int(11) NOT NULL DEFAULT 0 COMMENT '1 = expediente, 2 = actividad, 3 = comentario',
  `idRegistro` int(11) NOT NULL DEFAULT 0 COMMENT 'id del registro de la tabla correspondiente',
  `leido` int(11) DEFAULT NULL,
  `mostrar` int(11) DEFAULT NULL,
  `titulo` varchar(50) DEFAULT NULL,
  `contenido` text,
  `fechaCreacion` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `fechaAct` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`idMensaje`)
) ENGINE=InnoDB AUTO_INCREMENT=167 DEFAULT CHARSET=utf8;


ALTER TABLE caso_acciones ADD KEY (casoId);
ALTER TABLE caso_acciones ADD KEY (usuarioId);
ALTER TABLE caso_acciones ADD KEY (tipo);
ALTER TABLE caso_acciones ADD KEY (importancia);
ALTER TABLE caso_acciones ADD KEY (padreAccionId);
ALTER TABLE caso_acciones ADD KEY (padreAccionId);
ALTER TABLE caso_acciones ADD KEY (estatusId);


ALTER TABLE casos ADD KEY (clienteId);
ALTER TABLE casos ADD KEY (tipoId);
ALTER TABLE casos ADD KEY (responsableId);
ALTER TABLE casos ADD KEY (usuarioAltaId);
ALTER TABLE casos ADD KEY (numExpediente);
ALTER TABLE casos ADD KEY (numExpedienteJuzgado);
ALTER TABLE casos ADD KEY (saludExpediente);
ALTER TABLE casos ADD KEY (titularId2);
ALTER TABLE casos ADD KEY (velocidad);
ALTER TABLE casos ADD KEY (estatusId);
ALTER TABLE casos ADD KEY (parteId);
ALTER TABLE casos ADD KEY (materiaId);
ALTER TABLE casos ADD KEY (juicioId);
ALTER TABLE casos ADD KEY (distritoId);
ALTER TABLE casos ADD KEY (juzgadoId);


ALTER TABLE cat_juicios ADD KEY (materiaId);
ALTER TABLE cat_juzgados ADD KEY (distritoId);
ALTER TABLE cat_contactos ADD KEY (casoId);
ALTER TABLE cat_conceptos ADD KEY (tipo);


ALTER TABLE conceptos ADD KEY (usuarioId);
ALTER TABLE conceptos ADD KEY (tipoId);
ALTER TABLE conceptos ADD KEY (catConceptoId);


ALTER TABLE usuarios ADD KEY (numAbogado);



ALTER TABLE mensajes ADD KEY (usuarioId);
ALTER TABLE mensajes ADD KEY (tipo);
ALTER TABLE mensajes ADD KEY (idRegistro);
ALTER TABLE mensajes ADD KEY (leido);
ALTER TABLE mensajes ADD KEY (mostrar);


-- 6/1/2022
ALTER TABLE casos ADD procesalId INT NULL AFTER juzgadoId;
ALTER TABLE casos ADD KEY (procesalId);


ALTER TABLE caso_acciones ADD responsableId INT NULL AFTER estatusId;
ALTER TABLE caso_acciones ADD KEY (responsableId);


ALTER TABLE usuarios ADD titularTodos INT NULL AFTER numAbogado;


-- 7/1/2022
ALTER TABLE casos ADD contundencia INT NULL COMMENT '1 Fuerte, 2 Muy Fuerte, 3 Implacable' AFTER procesalId ;
ALTER TABLE casos ADD KEY (contundencia);


ALTER TABLE casos ADD comentariosTitular TEXT NULL AFTER internos;


-- 12/1/2022
ALTER TABLE `cat_contactos` ADD `domicilio` TEXT NULL DEFAULT NULL AFTER `email`, ADD `notas` TEXT NULL DEFAULT NULL AFTER `domicilio`;


-- 13/1/2022
ALTER TABLE casos ADD correonot VARCHAR(50) NULL AFTER contundencia;


CREATE TABLE `cat_acciones` (
  `idAccion` int(11) NOT NULL AUTO_INCREMENT,
  materiaId INT NULL,
  `nombre` varchar(150) DEFAULT NULL,
  `activo` char(1) NOT NULL DEFAULT '0',
  `fechaCreacion` datetime DEFAULT NULL,
  `fechaAct` datetime DEFAULT NULL,
  PRIMARY KEY (`idAccion`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;


ALTER TABLE `cat_materias` ADD `tieneAcciones` INT(1) NOT NULL DEFAULT '0' AFTER `activo`;


ALTER TABLE casos ADD accionId INT NULL AFTER correonot;


-- 14/1/2022
ALTER TABLE `mensajes` ADD `usuarioIdAlta` INT NULL DEFAULT NULL AFTER `contenido`;


ALTER TABLE `caso_acciones` ADD `reporte` TEXT NULL DEFAULT NULL AFTER `internos`;



-- 20/1/2022
ALTER TABLE `mensajes` ADD `campo` VARCHAR(100) NULL DEFAULT NULL COMMENT 'Campo que se modifico en la tabla que corresponda ' AFTER `usuarioIdAlta`, ADD `cambioId` INT NULL DEFAULT NULL COMMENT 'Id al que se actualizo el registro' AFTER `campo`;


-- 21/1/2022

CREATE TABLE `cat_documentos` (
  `idDocumento` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(250) DEFAULT NULL,
  `condiciones` TEXT DEFAULT NULL,
  `fechaRecepcion` datetime DEFAULT NULL,
  `fechaRetorno` datetime DEFAULT NULL,
  `fechaCreacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `fechaAct` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`idDocumento`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;


ALTER TABLE `cat_documentos` ADD `casoId` INT NULL DEFAULT NULL COMMENT 'id del expediente' AFTER `idDocumento`;

ALTER TABLE `caso_acciones` ADD `tipo2` INT(1) NOT NULL DEFAULT '1' COMMENT '1 = actividad, 2 = reporte' AFTER `tipo`;


-- 26/1/2022
CREATE TABLE `tareas` (
  `idTarea` int(11) NOT NULL AUTO_INCREMENT,
  `usuarioId` int(11) DEFAULT NULL,
  `tipo` int(1) DEFAULT NULL COMMENT '1 de fondo, 2 seguimiento',
  `tipo2` int(1) NOT NULL DEFAULT '1' COMMENT '1 = actividad, 2 = reporte',
  `importancia` int(1) DEFAULT NULL COMMENT '1 media, 2 normal, 3 alta',
  `fechaCompromiso` datetime DEFAULT NULL,
  `fechaRealizado` datetime DEFAULT NULL,
  `recordatorio` int(1) DEFAULT NULL COMMENT '0 no, 1 si',
  `nombre` varchar(300) CHARACTER SET utf8 DEFAULT NULL COMMENT 'nombre de la accion',
  `comentarios` text CHARACTER SET utf8 COMMENT 'Comentarios',
  `internos` text COLLATE utf8_unicode_ci,
  `reporte` text COLLATE utf8_unicode_ci,
  `fechaAlta` date DEFAULT NULL COMMENT 'fecha alta del caso',
  `fechaAct` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'fecha cuando se actualizo el cliente',
  `fechaCreacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'fecha en que fue dado de alta',
  `padreTareaId` int(11) DEFAULT '0',
  `estatusId` int(1) NOT NULL DEFAULT '1' COMMENT '1 activo, 2 en proceso, 3 espero instrucciones, 4 terminado',
  `responsableId` int(11) DEFAULT NULL,
  `esperoInstrucciones` int(1) NOT NULL DEFAULT '0' COMMENT '1 si, 0 no',
  PRIMARY KEY (`idTarea`),
  KEY `usuarioId` (`usuarioId`),
  KEY `tipo` (`tipo`),
  KEY `importancia` (`importancia`),
  KEY `padreTareaId` (`padreTareaId`),
  KEY `estatusId` (`estatusId`),
  KEY `responsableId` (`responsableId`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- 4/2/2022
ALTER TABLE `casos` ADD `ultimaActividad` DATETIME NULL DEFAULT NULL COMMENT 'Fecha de la ultima actividad o comentario registrada' AFTER `fechaCreacion`;

ALTER TABLE `caso_acciones` ADD `avanzo` INT(1) NOT NULL DEFAULT '0' COMMENT '0 No avanzo, 1 avanzo' AFTER `esperoInstrucciones`;

-- Ejecutar desde aqui
-- 11/2/2022
ALTER TABLE `caso_acciones` ADD `leido` INT(1) NULL DEFAULT '0' COMMENT '0 no leido, 1 leido (solo para comentarios)' AFTER `avanzo`;

-- Ejecutar desde aqui
-- 12/2/2022
ALTER TABLE `casos` ADD `representado` VARCHAR(150) NOT NULL DEFAULT '' AFTER `autorizadosIds`;
UPDATE casos SET representado=(SELECT nombre FROM clientes WHERE idCliente=clienteId)

-- Ejecutar hasta aqui
ALTER TABLE `usuarios` ADD `coordinadorId` INT NULL DEFAULT NULL COMMENT 'Id del usuario coordinador del titular' AFTER `titularTodos`;

CREATE TABLE `digitales` (
  `idDocumento` int(11) NOT NULL AUTO_INCREMENT,
  `casoId` INT DEFAULT NULL,
  `tipo` INT DEFAULT NULL COMMENT '1 Escrito, 2 Expediente, 3 Audiencias, 4 Otros',
  `nombre` varchar(250) DEFAULT NULL,
  `url` TEXT DEFAULT NULL,
  `fechaCreacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `fechaAct` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`idDocumento`),
  KEY `casoId` (`casoId`),
  KEY `tipo` (`tipo`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

-- 10/3/2022
ALTER TABLE casos ADD autorizadosJuzgados TEXT NULL AFTER autorizadosIds;
-- 11/3/2022
INSERT INTO `roles` (`idRol`, `rol`, `fechaCreacion`) VALUES (NULL, 'Abogado externo', '2022-03-11 00:00:00');


-- 18/3/2022
ALTER TABLE `casos` ADD `generado` INT(1) NOT NULL DEFAULT '0' COMMENT 'Para saber si se ha generado un pdf merge de expediente digital 0 = no, 1 = si' AFTER `accionId`;

ALTER TABLE `digitales` ADD `orden` INT NULL DEFAULT NULL AFTER `url`;

ALTER TABLE `mensajes` CHANGE `tipo` `tipo` INT(11) NOT NULL DEFAULT '0' COMMENT '1 = expediente, 2 = actividad, 3 = comentario, 4 = tarea, 5 = comentario tarea, 6 = comunicado, -1 Aviso removido de expediente';


-- 25/3/2022
CREATE TABLE `cat_comunicados` (
  `idComunicado` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(150) DEFAULT NULL,
  `contenido` TEXT DEFAULT NULL,
  `activo` char(1) NOT NULL DEFAULT '0',
  `fechaCreacion` datetime DEFAULT NULL,
  `fechaAct` datetime DEFAULT NULL,
  PRIMARY KEY (`idComunicado`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

ALTER TABLE `mensajes` ADD `fechaCaducidad` DATETIME NULL DEFAULT NULL AFTER `fechaAct`;

-- 31/3/2022
ALTER TABLE `casos` ADD `cobro` INT NULL DEFAULT NULL COMMENT '1 Probono, 2 Sin Cobro temporal, 3 Pago Activo' AFTER `contundencia`;

-- 1/4/2022
CREATE TABLE `comentarios` (
  `idComentario` int(11) NOT NULL AUTO_INCREMENT,
  `casoId` int(11) DEFAULT NULL COMMENT 'Id del caso',
  `comentario` TEXT DEFAULT NULL,
  `fechaCreacion` datetime DEFAULT NULL,
  `fechaAct` datetime DEFAULT NULL,
  PRIMARY KEY (`idComentario`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

ALTER TABLE `comentarios` ADD `usuarioId` INT NULL DEFAULT NULL AFTER `casoId`, ADD `leido` INT(1) NOT NULL DEFAULT '0' AFTER `usuarioId`;

-- 8/4/2022
ALTER TABLE `digitales` ADD `usuarioId` INT NULL DEFAULT NULL AFTER `orden`;

-- 13/4/2022
ALTER TABLE `tareas` CHANGE `tipo` `tipo` INT(1) NULL DEFAULT NULL COMMENT '1 administrativa, 2 otros, 100 asignacion';

-- 21/4/2022
CREATE TABLE `cat_bancos` (
  `idBanco` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(150) DEFAULT NULL,
  `activo` char(1) NOT NULL DEFAULT '0',
  `fechaCreacion` datetime DEFAULT NULL,
  `fechaAct` datetime DEFAULT NULL,
  PRIMARY KEY (`idBanco`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

CREATE TABLE `cat_metodos` (
  `idMetodo` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(150) DEFAULT NULL,
  `activo` char(1) NOT NULL DEFAULT '0',
  `fechaCreacion` datetime DEFAULT NULL,
  `fechaAct` datetime DEFAULT NULL,
  PRIMARY KEY (`idMetodo`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

CREATE TABLE `cuentas` (
  `idCuenta` int(11) NOT NULL AUTO_INCREMENT,
  `casoId` int(11) DEFAULT NULL COMMENT 'identificador del caso',
  `clienteId` int(11) DEFAULT NULL COMMENT 'identificador del cliente',
  `tipoCobro` int(11) DEFAULT NULL COMMENT 'identificador del tipo cobro ',
  `planPagos` int(11) DEFAULT NULL COMMENT 'identificador del plan de pagos',
  `monto` double DEFAULT NULL ,
  `saldo` double DEFAULT NULL ,
  `comentarios` text DEFAULT NULL,
  `cobrosJson` text DEFAULT NULL,
  `fechaCreacion` datetime DEFAULT NULL,
  `fechaAct` datetime DEFAULT NULL,
  PRIMARY KEY (`idCuenta`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

CREATE TABLE `pagos` (
  `idPago` int(11) NOT NULL AUTO_INCREMENT,
  `cuentaId` int(11) DEFAULT NULL COMMENT 'identificador del cuenta',
  `metodoId` int(11) DEFAULT NULL COMMENT 'identificador del metodo',
  `bancoId` int(11) DEFAULT NULL COMMENT 'identificador del banco',
  `monto` double DEFAULT NULL ,
  `comentarios` text DEFAULT NULL,
  `fechaPago` datetime DEFAULT NULL,
  `fechaCreacion` datetime DEFAULT NULL,
  `fechaAct` datetime DEFAULT NULL,
  PRIMARY KEY (`idPago`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

-- Jair 28/4/2022
ALTER TABLE `cuentas` ADD `avance` DOUBLE NULL DEFAULT NULL COMMENT 'porcentaje de avance del caso' AFTER `planPagos`;

-- Jair 29/4/2022
ALTER TABLE `cat_metodos` ADD `requiereBanco` INT(1) NOT NULL DEFAULT '0' AFTER `idMetodo`;
ALTER TABLE `pagos` ADD `recibo` TEXT NULL DEFAULT NULL AFTER `monto`;

-- Jair 6/5/2022
ALTER TABLE `cuentas` ADD `montoAux` DOUBLE NOT NULL COMMENT 'Auxiliar del monto, para montos subsecuentes o monto inicial segun el modo de cobro' AFTER `monto`;
ALTER TABLE `cuentas` ADD `numMeses` INT(2) NULL DEFAULT NULL AFTER `montoAux`, ADD `diaCobro` INT(2) NULL DEFAULT NULL AFTER `numMeses`;

ALTER TABLE `pagos` ADD `tipo` INT(1) NOT NULL DEFAULT '1' COMMENT '1 = pago normal. 2 = pago de adicionales' AFTER `idPago`;
ALTER TABLE `casos` ADD `akaAsunto` VARCHAR(500) NULL DEFAULT NULL AFTER `contrario`;

-- Jair 1/6/2022
ALTER TABLE `usuarios` ADD `camposGridExp` TEXT NULL DEFAULT NULL COMMENT 'Campos configurados, que desea ver el usuario del grid de expedientes' AFTER `coordinadorId`;

-- LDAH 11/08/2022
ALTER TABLE usuarios CHANGE numAbogado numAbogado INT NULL DEFAULT NULL;
ALTER TABLE `digitales` ADD `descripcion` TEXT NULL AFTER `fechaAct`;
ALTER TABLE `casos` ADD `nombreJuez` VARCHAR(50) NULL AFTER `ultimaActividad`;
ALTER TABLE `casos` ADD `nombreSecretaria` VARCHAR(50) NULL AFTER `nombreJuez`;

-- JGP 07/10/2022
ALTER TABLE `casos` ADD `idPadre` INT NULL DEFAULT NULL COMMENT 'ID del caso Padre' AFTER `idCaso`;
ALTER TABLE `casos` ADD `idPadreMain` INT NULL DEFAULT NULL AFTER `idPadre`;

-- CMPB 21/04/23

CREATE TABLE `notas_voz` (
  `idNotaVoz` int(11) NOT NULL,
  `idUsuario` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `url` varchar(150) DEFAULT NULL,
  `idCaso` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


ALTER TABLE `notas_voz`
  ADD PRIMARY KEY (`idNotaVoz`);
  
ALTER TABLE `notas_voz`
  MODIFY `idNotaVoz` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;


-- JGP 20/04/23
ALTER TABLE `notas_voz` ADD `descripcion` TEXT NULL AFTER `idCaso`;


-- CMPB 05/05/2023 eliminar clientes sin casos
DELETE FROM clientes
WHERE idCliente IN (1, 2, 8, 10, 11, 12, 13, 15, 22, 26, 27, 28, 34, 35, 50, 62, 71, 80, 81, 84,  95, 105, 133, 145, 235, 274, 275, 278, 285, 299, 300, 301, 302, 303, 308, 309, 311, 323, 324, 330, 349, 356, 359, 361, 376, 404, 418, 442, 451, 462, 468, 473, 476, 478, 487, 488, 490, 492, 501, 517, 529, 530, 531, 532, 533, 541, 577, 581, 583, 593, 594, 595, 596, 597, 598, 599, 600, 601, 608, 631, 666, 667, 671, 692, 693, 694, 695, 696);

-- CMPB 26/05/2023 agregar columna para mostrar por tipo de audiencia los audios de los casos
-- audienciaTipo=0(lo pueden oir todos) audienciaTipo=1(solo lo puedon oir titulares)
ALTER TABLE `notas_voz` 
ADD COLUMN `audienciaTipo` INT NULL DEFAULT 0 AFTER `descripcion`; 



-- JGP 16/06/23
CREATE TABLE `cuentas_casos` (
  `idCuentaCaso` INT NOT NULL AUTO_INCREMENT , 
  `cuentaId` INT NOT NULL , 
  `casoId` INT NOT NULL , 
  `fechaCreacion` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , 
  PRIMARY KEY (`idCuentaCaso`)) ENGINE = MyISAM;

 -- JGP 01/07/23
 ALTER TABLE `caso_acciones` CHANGE `tipo` `tipo` INT(1) NULL DEFAULT NULL COMMENT '1 de fondo, 2 seguimiento, 3 Audiencias, 4 Termino, 5 Citaciones, 6 Cita Simple, 7 Escritos de termino, 8 Pagos, 9 Cobros, 10 Citas Sociales';

 --CSAT 14/07/23
 ALTER TABLE `usuarios` ADD `permisohistorico` INT(11) NOT NULL DEFAULT '0';


