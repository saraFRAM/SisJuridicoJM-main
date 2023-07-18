

CREATE TABLE IF NOT EXISTS `cat_ayudas` (
  `idAyuda` int(11) NOT NULL AUTO_INCREMENT,
  `alias` varchar(50) DEFAULT NULL,
  `titulo` varchar(150) DEFAULT NULL,
  `descripcion` text,
  `fechaCreacion` datetime DEFAULT NULL,
  `tipo` int(1) NOT NULL DEFAULT '1' COMMENT '0 = ayuda app, 1 = ayuda web',
  PRIMARY KEY (`idAyuda`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Volcado de datos para la tabla `cat_ayudas`
--

INSERT INTO `cat_ayudas` (`idAyuda`, `alias`, `titulo`, `descripcion`, `fechaCreacion`, `tipo`) VALUES
(1, 'web_inicio', 'Inicio', '<h1>Inicio</h1>\n<p style="text-align: justify;">Se muestran los accesos a los m&oacute;dulos del sistema que tiene permiso de visualizar.</p>\n<p style="text-align: justify;">Dar clic en cualquier a de ellos para ir a la vista correspondiente.</p>', '2018-10-09 00:00:00', 1),
(2, 'catalogo_usuarios', 'Catalogo usuarios', '<p>En este cat&aacute;logo se muestran los usuarios que pueden acceder al sistema.</p>\n<p><strong>Nuevo</strong></p>\n<p>Abre una vista para agregar un nuevo usuario</p>\n<p>&nbsp;</p>\n<p><strong>Opciones en cada registro</strong></p>\n<ul>\n<li style="text-align: justify;"><strong>Editar</strong>: Abre una vista donde se podr&aacute;n editar los datos del usuario.</li>\n<li style="text-align: justify;"><strong>Eliminar</strong>: Elimina el usuario del sistema.</li>\n</ul>\n<p>&nbsp;</p>', '2018-10-09 00:00:00', 1),
(3, 'catalogo_roles', 'Catalogo roles', '<p>Se muestran los <strong>roles</strong> determinados en el sistema.</p>\n<p>Puede dar clic en <strong>Editar</strong> para cambiar el nombre de cada Rol.</p>', '2018-10-09 00:00:00', 1),
(4, 'catalogo_ayudas', 'Catalogo Ayudas', '<p>En este cat&aacute;logo se pueden editar la informaci&oacute;n que contienen las ayudas.</p>\n<p>Seleccionar la Ayuda que se desea visualizar.</p>\n<p>En el editor de texto, escribir el contenido de la ayuda que se desea editar.</p>\n<p>Dar clic en <strong>Guardar</strong> para guardar los cambios.</p>', '2018-10-09 00:00:00', 1),
(5, 'web_usuario', 'Usuario', '<p>En esta vista se puede agregar o editar un usuario.</p>\n<p>Deber&aacute; llenar los datos del formulario:</p>\n<ul>\n<li><strong>Rol</strong>: El tipo de usuario con el que ingresa al sistema, de este depende las pantallas que el usuario podr&aacute; visualizar.</li>\n<li><strong>Nombre</strong>: Escribir el nombre del usuario.</li>\n<li><strong>E-mail</strong>: Escribir el correo del usuario, no se puede repetir dos veces el mismo correo en el sistema.</li>\n<li><strong>Contrase&ntilde;a</strong>: La contrase&ntilde;a de acceso al sistema del usuario.</li>\n<li><strong>Activo</strong>: Determina si el usuario se encuentra activo en el sistema y por lo tanto si puede ingresar cuando inicie sesi&oacute;n.</li>\n</ul>\n<p>Dar clic en <strong>Guardar</strong> para salvar los cambios o agregar el usuario si se trata de uno nuevo.</p>', '2018-10-09 00:00:00', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cat_configuraciones`
--

CREATE TABLE IF NOT EXISTS `cat_configuraciones` (
  `idConfiguracion` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(150) DEFAULT NULL,
  `valor` text,
  `fechaCreacion` datetime DEFAULT NULL COMMENT 'fecha en que fue dado de alta',
  `fechaAct` datetime DEFAULT NULL COMMENT 'fecha en que fue actualizada',
  PRIMARY KEY (`idConfiguracion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comunicados`
--

CREATE TABLE IF NOT EXISTS `comunicados` (
  `idComunicado` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(100) DEFAULT NULL,
  `descripcionCorta` text,
  `contenido` text,
  `urlComunicado` varchar(250) DEFAULT NULL,
  `urlVideo` varchar(250) DEFAULT NULL COMMENT 'Url video',
  `imgComunicado` varchar(100) DEFAULT NULL,
  `opcVisto` char(1) DEFAULT NULL,
  `vistoPor` text,
  `opcTipo` char(1) DEFAULT '0' COMMENT '0=capsulas',
  `compartir` char(1) DEFAULT NULL,
  `activo` char(1) DEFAULT NULL,
  `fechaPublicacion` datetime DEFAULT NULL,
  `fechaDespublicacion` datetime DEFAULT NULL,
  `fechaCreacion` datetime DEFAULT NULL,
  `idUsuarioCmb` int(11) DEFAULT NULL,
  `fechaUltCambio` datetime DEFAULT NULL,
  PRIMARY KEY (`idComunicado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
  `idRol` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identificador',
  `rol` varchar(30) DEFAULT NULL COMMENT 'nombre del rol',
  `fechaCreacion` datetime DEFAULT NULL COMMENT 'fecha en que se dio de alta',
  PRIMARY KEY (`idRol`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`idRol`, `rol`, `fechaCreacion`) VALUES
(1, 'Super Administrador', '2017-02-20 05:01:29'),
(2, 'Administrador', '2017-02-20 05:02:05'),
(3, 'Cliente', '2017-02-20 17:05:42');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE IF NOT EXISTS `usuarios` (
  `idUsuario` int(11) NOT NULL AUTO_INCREMENT COMMENT 'identificador',
  `idRol` int(11) DEFAULT NULL COMMENT 'identificador del rol',
  `nombre` varchar(150) DEFAULT NULL COMMENT 'nombre del usuario',
  `email` varchar(100) DEFAULT NULL COMMENT 'correo del usuario',
  `password` varchar(50) DEFAULT NULL COMMENT 'contraseña',
  `activo` int(1) NOT NULL DEFAULT '1' COMMENT '1 usuario activo, 0 inactivo',
  `permisohistorico` int(1) NOT NULL DEFAULT '1',
  `fechaCreacion` datetime DEFAULT NULL COMMENT 'fecha en que fue dado de alta',
  `fechaAct` datetime DEFAULT NULL COMMENT 'fecha en que fue actualizada',
  PRIMARY KEY (`idUsuario`),
  KEY `idRol` (`idRol`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Volcado de datos para la tabla `usuarios`
--ALTER TABLE usuarios ADD COLUMN permisohistorico INT;

INSERT INTO `usuarios` (`idUsuario`, `idRol`, `nombre`, `email`, `password`, `activo`, `permisohistorico`, `fechaCreacion`, `fechaAct`)
VALUES
(1, 1, 'Super Administrador', 'superadmin@framelova.com', 'superadmin_pass', 1, 1, '2017-02-20 17:00:00', NULL),
(2, 2, 'Administrador', 'admin@framelova.com', 'admin_pass', 1, 1, '2017-02-20 17:00:00', NULL),
(3, 3, 'Cliente', 'cliente@framelova.com', 'cliente_pass', 1, 1, '2017-02-20 17:00:00', NULL);
