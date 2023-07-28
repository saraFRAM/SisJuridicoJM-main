/*
 * Date: 5/05/2017
 * Actualizado: 07/12/2020
 * Funciones generales y especificas de javascript
 */

$(document).ready(function(){

  // Evento cada vez que actualiza el listbox
  $('#c_autorizados').change(function() {
    if($(this).val()!=null){
        $("#c_idsautorizados").val($(this).val());
    }else{
        $("#c_idsautorizados").val("");
    }
  });

  $('#c_autorizadosj').change(function() {
    if($(this).val()!=null){
        $("#c_idsautorizadosj").val($(this).val());
    }else{
        $("#c_idsautorizadosj").val("");
    }
  });

  // Popup tipo
  $('.agregarCliente').click(function() {
    clearForm("formCrearCliente");
  });

  $('.agregarPago').click(function() {
    clearForm("formPago");
  });

  // Popup tipo
  $('.agregarTipo').click(function() {
    clearForm("formCrearTipo");
  });

  $("#pg_monto").change(function (){
    $(this).val(accounting.formatMoney( accounting.unformat($(this).val()) ));
  });

  $("#fechaCompromiso").change(function (){
    if($("#pa_accion").val() == "Audiencia"){
      $("#fechaRealizado").val($("#fechaCompromiso").val());
    }
  });
});

//para reducir el tamaño en columnas con mucho contenido, mostrar solo 
//una porción
function searhColMaxLength(){

    //Removemos el link donde no se necesite
    $('.wrapper-col-contenido').each(function() {
    
      var current = $(this).find('.small-col-contenido');
      
      if(current.text().length <= 60){
        
          $(this).find('.linktoogle-contenido').hide();
          current.removeClass('small-col-contenido');
      }

    });

      $('.wrapper-col-contenido').find('a[href="#"]').on('click', function (e) {
        e.preventDefault();
        this.expand = !this.expand;
        $(this).text(this.expand?" - ":" + ");
        $(this).closest('.wrapper-col-contenido').find('.small-col-contenido, .big-col-contenido').toggleClass('small-col-contenido big-col-contenido');
    });
    
}

function obtListaContactos(){
  showLoading("busca_contactos");
  var usuarioIdCreador = 0; 

  var idTabla = "grid_contactos";

  var params = {funct: 'obtListaContactos', idTabla:idTabla, expedienteId:$("#c_id").val(), idRol: idRol};
  ajaxData(params, function(data){
    hideLoading("busca_contactos");
    // console.log(data);

    if(data.success){
      $("#divTablaContactos").html(data.tablaContactos);
     
      //grid
      var datosLista = initGridBTListas(idTabla, true);
    }else{
      // alertify.error("No existe ning&uacute;n registro para mostrar.");
    }
  });
}

function obtListaDocumentos(){
  showLoading("busca_documentos");
  var usuarioIdCreador = 0; 

  var idTabla = "grid_documentos";

  var params = {funct: 'obtListaDocumentos', idTabla:idTabla, expedienteId:$("#c_id").val()};
  ajaxData(params, function(data){
    hideLoading("busca_documentos");
    // console.log(data);

    if(data.success){
      $("#divTablaDocumentos").html(data.tablaDocumentos);
     
      //grid
      var datosLista = initGridBTListas(idTabla, true);
    }else{
      // alertify.error("No existe ning&uacute;n registro para mostrar.");
    }
  });
}

function obtListaDigitales(){
  console.log("digital");
  showLoading("busca_digitales");
  var usuarioIdCreador = 0; 

  var idTabla = "grid_digitales";

  var params = {funct: 'obtListaDigitales', idTabla:idTabla, expedienteId:$("#c_id").val()};
  ajaxData(params, function(data){
    hideLoading("busca_digitales");
    console.log(data);

    if(data.success){
      $("#divTablaDigitales").html(data.tablaDigitales);
     
      //grid
      var datosLista = initGridBTListas(idTabla, true);
      setTimeout(() => {
        inicializaFancyGral();
      }, 2000);
    }else{
      // alertify.error("No existe ning&uacute;n registro para mostrar.");
    }
  });
}


// Inicio obtener clientes
var datosListaClientes;
function obtListaClientes(){
  showLoading("busca_clientes");
  var usuarioIdCreador = 0; //accounting.unformat($("#usuarioIdCreador").val());
  // console.log(usuarioIdCreador);

  $("#id_sel_cliente").val(0);
  var idTabla = "grid_listaclientes";

  var params = {funct: 'tblListaClientes', idTabla:idTabla, idUsuario:usuarioIdCreador};
  ajaxData(params, function(data){
    hideLoading2("busca_clientes");
    // console.log(data);

    if(data.success){
      $("#cont_listaclientes").html(data.tblListaClientes);
      $('#modalListaClientes').modal('show');
      //grid
      var datosLista = initGridBTListas(idTabla, true);

      //Accion para tomar el seleccionado
      $('#'+idTabla+' tbody').on('click', 'tr', function() {
        var data = datosLista.row(this).data();
        datosListaClientes = data;
        $("#id_sel_cliente").val(data[0]);
        $("#textoSeleccionado").html(`
        <div class="alert alert-info">
          <strong>Seleccionado: </strong> ${datosListaClientes[1]}.
        </div>
        `);

        $("table tr").removeClass('bg_tr_selected');
        $(this).addClass('bg_tr_selected');
      });
    }else{
      alertify.error("No existe ning&uacute;n registro para mostrar.");
    }
  });
}
function btnObtIdCliente(){
  var selRow = accounting.unformat($("#id_sel_cliente").val());

  if(selRow>0){
    $("#c_idcliente").val(selRow);
    $("#c_cliente").val(datosListaClientes[1]);
    $('.modal').modal('hide');
    muestraDatosCliente(datosListaClientes[1], datosListaClientes[2], datosListaClientes[3], datosListaClientes[4], datosListaClientes[5], datosListaClientes[5]);
    
  }else{
    alertify.error("Debe seleccionar alg&uacute;n registro.");
  }
}
// Fin obtener clientes

// Inicio obtener titulares
var datosListaTitulares;
function obtListaTitulares(){
  showLoading("busca_titular");
  var usuarioIdCreador = 0; //accounting.unformat($("#usuarioIdCreador").val());
  // console.log(usuarioIdCreador);

  $("#id_sel_titular").val(0);
  var idTabla = "grid_listatitulares";

  var params = {funct: 'tblListaTitulares', idTabla:idTabla, idUsuario:usuarioIdCreador};
  ajaxData(params, function(data){
    hideLoading2("busca_titular");
    // console.log(data);

    if(data.success){
      $("#cont_listatitulares").html(data.tblListaTitulares);
      $('#modalListaTitulares').modal('show');
      //grid
      var datosLista = initGridBTListas(idTabla);

      //Accion para tomar el seleccionado
      $('#'+idTabla+' tbody').on('click', 'tr', function() {
        var data = datosLista.row(this).data();
        datosListaTitulares = data;
console.log(data);        
        $("#id_sel_titular").val(data[0]);
        
        $("#textoSeleccionadoTit").html(`
        <div class="alert alert-info">
          <strong>Seleccionado: </strong> ${datosListaTitulares[2]}.
        </div>
        `);

        $("table tr").removeClass('bg_tr_selected');
        $(this).addClass('bg_tr_selected');
      });
    }else{
      alertify.error("No existe ning&uacute;n registro para mostrar.");
    }
  });
}
function btnObtIdTitular(){
  var selRow = accounting.unformat($("#id_sel_titular").val());

  if(selRow>0){
    $("#c_idtitular").val(selRow);
    $("#c_titular").val(datosListaTitulares[2]);
    $('.modal').modal('hide');
  }else{
    alertify.error("Debe seleccionar alg&uacute;n registro.");
  }
}
// Fin obtener titulares

// Crear y editar caso
function crearCaso(){
  var validator = $("#formCaso").validate({ });
  // btnCreaEditaAccion
  tinyMCE.triggerSave();
  $('#descripcion').val(tinyMCE.get('descripcion').getContent());
  if(idRol == 1 || idRol == 2 ){
    $('#internos').val(tinyMCE.get('internos').getContent());
    $('#comentariosTitular').val(tinyMCE.get('comentariosTitular').getContent());
  }
  var valorAviso = $("#descripcion").val();
  var internos = $("#internos").val();
  var comentariosTitular = $("#comentariosTitular").val();
  // $("#valorAviso").val("");
  $("#descripcionHd").val($.base64.encode(valorAviso));
  $("#internosHd").val($.base64.encode(internos));
  $("#comentariosTitularHd").val($.base64.encode(comentariosTitular));
// console.log($('#descripcion').val());
  //Validar formulario
  if($("#formCaso").valid()){
    var htmlOriginal = showLoading('btnCrearCaso');

    // var datosForm = $("#formCaso").serializeJSON();
    // console.log(datosForm);
    // params = paramsB64(datosForm);
    var formElement = document.getElementById("formCaso");
    var params = new FormData(formElement);
    // params['funct'] = 'crearCaso';
    $('#descripcion').val("");
    $('#internos').val("");
    $('#comentariosTitular').val("");
    // console.log(params);
    // return false;
    var urlDel = '../ajaxcall/ajaxFunctions.php?funct=crearCaso';
    ajaxDataPost(urlDel, params, function(data){
    // ajaxData(params, function(data){
      console.log(data);

      if(data.success){
        let c_id = ( accounting.unformat($("#c_id").val()) > 0)?"editado":"creado";
        alertify.success("Registro "+c_id+" correctamente.");
        setTimeout(function(){
          location.href="frmExpedienteEdit.php?id="+data.id+"";
        }, 500);
      }else{
        alertify.error("El registro no fue "+c_id+", intentar nuevamente.");
      }
    });
  }else{
    validator.focusInvalid();
    return false;
  }
}

// Popup para crear el cliente
function btnCrearCliente() {
  var validator = $("#formCrearCliente").validate({ });
  let clienteEditId = $("#clienteEditId").val();
  //Validar formulario
  if($("#formCrearCliente").valid()){
    // var htmlOriginal = showLoading('btnCrearCliente');

    var datosForm = $("#formCrearCliente").serializeJSON();
    // console.log(datosForm);
    params = paramsB64(datosForm);
    params['funct'] = 'crearCliente';
    console.log(params);
    // return false;
    ajaxData(params, function(data){
      console.log(data);

      $('.modal').modal('hide');
      if(data.success){
        if(clienteEditId > 0){
          alertify.success("Registro editado correctamente.");
        }else{
        alertify.success("Registro creado correctamente.");
        }
        $("#c_idcliente").val(data.idCliente);
        $("#c_cliente").val(data.nombre);
        muestraDatosCliente(data.nombre, data.email, data.telefono, data.empresa, data.direccion, data.aka);
      }else{
        alertify.error("El registro no fue creado, intentar nuevamente.");
      }
    });
  }else{
    validator.focusInvalid();
    return false;
  }
}

function muestraDatosCliente(nombre, email, telefono, empresa, direccion, aka){
  $("#rowDivDatosCliente").html(`
    <div class="col-xs-12 col-md-12">
    <div class="alert alert-info">
      <strong>Datos cliente</strong>
      <p><b>Nombre: </b>${nombre}</p>
      <p><b>Email: </b>${email}</p>
      <p><b>Tel&eacute;fono: </b>${telefono}</p>
      <p><b>Empresa: </b>${empresa}</p>
      <p><b>Direcci&oacute;n </b>${direccion}</p>
      <p class="divInfoInterno" style="display:none;"><b>Aka: </b>${aka}</p>
    </div>
    </div>
  `);
  //Por default el representado es el nombre del cliente
  let clienteEditId = $("#clienteEditId").val();
  if(clienteEditId == 0){
    $('#representado').val(nombre);
  }
}

// Popup para crear tipo
function btnCrearTipo() {
  var validator = $("#formCrearTipo").validate({ });
  //Validar formulario
  if($("#formCrearTipo").valid()){
    // var htmlOriginal = showLoading('btnCrearCliente');

    var datosForm = $("#formCrearTipo").serializeJSON();
    // console.log(datosForm);
    params = paramsB64(datosForm);
    params['funct'] = 'crearTipo';
    // console.log(params);
    // return false;
    ajaxData(params, function(data){
      console.log(data);

      $('.modal').modal('hide');
      if(data.success){
        let opcion = data.opcion;
        // Agregar opciones tipo
        $("#c_tipo").append('<option value="'+opcion.id+'">'+opcion.val+'</option>');
        alertify.success("Registro creado correctamente.");
      }else{
        alertify.error("El registro no fue creado, intentar nuevamente.");
      }
    });
  }else{
    validator.focusInvalid();
    return false;
  }
}


// Popup crear y editar accion
function popupCreaEditaAccion(casoId, idAccion){
  clearForm("formCrearAccion");
  // console.log(idAccion);
  $('#pa_idaccion').val( idAccion );
  $('#popup_modalCrearAccion').modal('show');

  if(idAccion>0){
    //Recuperar datos para la edicion
    let params = {funct: 'obtDatosAccion', idAccion:idAccion};
    ajaxData(params, function(data){
      // console.log(data);
      if(data.success){
        $("#pa_fechaaccion").val(data.datos.fechaAlta2);
        $("#pa_accion").val(convertHTMLEntity(data.datos.nombre));
        $("#pa_comentario").val(convertHTMLEntity(data.datos.comentarios));
        $("#tipoactividad").val(data.datos.tipo);
        $("#importanciaactividad").val(data.datos.importancia);
        $("#pa_internos").val(convertHTMLEntity(data.datos.internos));
      }
    });

    //Cargar lista de gastos
    showLoading("cont_listagastos");
    setTimeout(function(){
      obtListaGastos();
    }, 800);
  }else{
    $("#cont_gastos").hide();
  }
}

function agregarComentarioActividad(){
  var formElement = document.getElementById("formComentario");
  var params = new FormData(formElement);
  var urlDel = '../ajaxcall/ajaxFunctions.php?funct=creaEditaAccion2';
  ajaxDataPost(urlDel, params, function(data){
  // ajaxData(params, function(data){
    console.log(data);

    $('.modal').modal('hide');
    if(data.success){
      alertify.success("Registro creado correctamente.");
      location.href = "actividad.php?expId="+$("#pa_casoid").val()+"&actId="+$("#pa_idaccion").val();
    }else{
      alertify.error("El registro no fue creado, intentar nuevamente.");
    }
  });
}

function ActiveDatePicker(){
  $('.inputfechaGral').datepicker({
    showOn: "button",
    buttonImage: '../images/calendar.gif',
    buttonImageOnly: true,
    buttonText: "Select date",
    autoclose: true,
    // minDate: getCurrentDate(1),
    // maxDate: "+2M"
  });
}


//03/06/22
function cambiaTipoActividad(tipo, first=false){
  // Seguimiento
  // Reporte/Actividad
  // Normal
  // Terminado
  // Fechas default

  // Audiencias
  // Actividad -> Audiencia | Citaciones
  // Fechas en blanco
  // Por realizar

  switch(parseInt(tipo)){
  
    case 2:
      $("#pa_accion").val("Reporte/Actividad");
      //$("#pa_comentario").val("");
      $("#importanciaactividad").val(1);
      $("#estatusId").val(4);
      if(!first){
        $("#fechaCompromiso").val($("#pa_fechaaccion").val());
        $("#fechaRealizado").val($("#pa_fechaaccion").val());
      }
    break;
    case 3:
    case 7:
      if(parseInt(tipo) == 7){
         $("#pa_accion").val("Escritos de termino"); 
      }else{
        $("#pa_accion").val("Audiencia");    
      }    
      
      //$("#pa_comentario").val("");
      $("#importanciaactividad").val(1);
      $("#estatusId").val(4);
      
      if(!first){
        $("#fechaCompromiso").val("");
        $("#fechaRealizado").val("");  
      }
      $("#fechaCompromiso").addClass("inputfechaGralHora");
      $("#fechaRealizado").addClass("inputfechaGralHora");
      inicializaDatePickerHora();
    break;

    case 5:
      $("#pa_accion").val("Citaciones");
      //$("#pa_comentario").val("");
      $("#importanciaactividad").val(1);
      $("#estatusId").val(1);
      if(!first){
        $("#fechaCompromiso").val("");
        $("#fechaRealizado").val("");  
      }
      $("#fechaCompromiso").addClass("inputfechaGralHora");
      $("#fechaRealizado").addClass("inputfechaGralHora");
      inicializaDatePickerHora();
    break;
  }
}

// Popup para crear accion
function btnCreaEditaAccion() {
  var validator = $("#formCrearAccion").validate({ignore: ':hidden:not(.required)', });
  console.log("si voy a editar");  
  tinyMCE.triggerSave();
  $('#pa_comentario').val(tinyMCE.get('pa_comentario').getContent());
  $('#pa_internos').val(tinyMCE.get('pa_internos').getContent());
  $('#pa_reporte').val(tinyMCE.get('pa_reporte').getContent());
  var valorAviso = $("#pa_comentario").val();
  var internos = $("#pa_internos").val();
  var reporte = $("#pa_reporte").val();
  // $("#valorAviso").val("");
  $("#pa_comentario_hd").val($.base64.encode(valorAviso));
  $("#pa_internos_hd").val($.base64.encode(internos));
  $("#pa_reporte_hd").val($.base64.encode(reporte));
  //Validar formulario
  if($("#formCrearAccion").valid()){
    var htmlOriginal = showLoading('btnCrearTipo');

    // var datosForm = $("#formCrearAccion").serializeJSON();
    // console.log(datosForm);
    // params = paramsB64(datosForm);
    // params['funct'] = 'creaEditaAccion';
    // console.log(params);
    // return false;
    $('#pa_comentario').val("");
    $('#pa_internos').val("");
    $('#pa_reporte').val("");
    var formElement = document.getElementById("formCrearAccion");
    var params = new FormData(formElement);
    var urlDel = '../ajaxcall/ajaxFunctions.php?funct=creaEditaAccion';
console.log("Termine de validar voy a dar post");    
    ajaxDataPost(urlDel, params, function(data){
    // ajaxData(params, function(data){
      console.log(data);

      $('.modal').modal('hide');
      if(data.success){
        // caso_acciones.refresh();
        // caso_acciones.commit();
        if(data.opc == "crear"){
          console.log("crear");
          if(data.idaccion > 0){
            console.log(data.idaccion);
            alertify.success("Cambios guardados.");
          }else{

          }
        }else{
          console.log("edit");
          if(data.resp > 0){
            console.log(data.resp);
            alertify.success("Cambios guardados.");
          }
        }
        setTimeout(function(){
          if($("#abrirotra").val() == 1){
            window.open("actividad.php?expId="+$("#pa_casoid").val(), "_blank");
          }
          location.href = "actividad.php?expId="+$("#pa_casoid").val()+"&actId="+data.idaccion;
        }, 2000);

        
      }else{
        alertify.error("El registro no fue creado, intentar nuevamente.");
      }
    });
  }else{
    validator.focusInvalid();
    return false;
  }
}

function creaEditaTarea() {
  var validator = $("#formCrearTarea").validate({ignore: ':hidden:not(.required)', });
  tinyMCE.triggerSave();
  $('#pa_comentario').val(tinyMCE.get('pa_comentario').getContent());
  $('#pa_internos').val(tinyMCE.get('pa_internos').getContent());
  $('#pa_reporte').val(tinyMCE.get('pa_reporte').getContent());
  var valorAviso = $("#pa_comentario").val();
  var internos = $("#pa_internos").val();
  var reporte = $("#pa_reporte").val();
  // $("#valorAviso").val("");
  $("#pa_comentario_hd").val($.base64.encode(valorAviso));
  $("#pa_internos_hd").val($.base64.encode(internos));
  $("#pa_reporte_hd").val($.base64.encode(reporte));
  //Validar formulario
  if($("#formCrearTarea").valid()){
    var htmlOriginal = showLoading('btnCrearTarea');

    // var datosForm = $("#formCrearTarea").serializeJSON();
    // console.log(datosForm);
    // params = paramsB64(datosForm);
    // params['funct'] = 'creaEditaAccion';
    // console.log(params);
    // return false;
    $('#pa_comentario').val("");
    $('#pa_internos').val("");
    $('#pa_reporte').val("");
    var formElement = document.getElementById("formCrearTarea");
    var params = new FormData(formElement);
    var urlDel = '../ajaxcall/ajaxFunctions.php?funct=creaEditaTarea';
    ajaxDataPost(urlDel, params, function(data){
    // ajaxData(params, function(data){
      console.log(data);

      $('.modal').modal('hide');
      if(data.success){
        // caso_acciones.refresh();
        // caso_acciones.commit();
        if(data.opc == "crear"){
          console.log("crear");
          if(data.idtarea > 0){
            console.log(data.idtarea);
            alertify.success("Cambios guardados.");
          }else{

          }
        }else{
          console.log("edit");
          if(data.resp > 0){
            console.log(data.resp);
            alertify.success("Cambios guardados.");
          }
        }
        setTimeout(function(){
          if($("#tipotarea").val() == 100){
            location.href = "asignacion.php?asignacionId="+data.idtarea;
          }else{
            location.href = "tarea.php?tareaId="+data.idtarea;
          }
        }, 2000);
        
      }else{
        alertify.error("El registro no fue creado, intentar nuevamente.");
      }
    });
  }else{
    validator.focusInvalid();
    return false;
  }
}

// Inicio obtener grid de gastos
var datosListaGastos;
function obtListaGastos(){
  // showLoading("cont_listagastos");
  let usuarioIdCreador = 0; //accounting.unformat($("#usuarioIdCreador").val());
  let idAccion = accounting.unformat($("#pa_idaccion").val());
  let colAccion = accounting.unformat($("#c_colaccion").val());
  // console.log(idAccion);
  // console.log(colAccion);

  var idTabla = "grid_listagastos";

  var params = {funct: 'tblListaGastos', idTabla:idTabla, idUsuario:usuarioIdCreador, idAccion:idAccion, colAccion:colAccion};
  ajaxData(params, function(data){
    hideLoading2("cont_listagastos");
    // console.log(data);

    if(data.success){
      $("#cont_listagastos").html(data.tblListaGastos);
      $("#cont_gastos").show();
      //grid
      var datosLista = initGridBTListas(idTabla);

      //Accion para tomar el seleccionado
      $('#'+idTabla+' tbody').on('click', 'tr', function() {
        var data = datosLista.row(this).data();
        datosListaGastos = data;
        $("table tr").removeClass('bg_tr_selected');
        // $(this).addClass('bg_tr_selected');
      });
    }else{
      alertify.error("No existe ning&uacute;n registro para mostrar.");
    }
  });
}
// Fin obtener grid de gastos


// Popup crear y editar gasto
function popupCreaEditaGasto(idGasto, casoId, idAccion, accion){
  clearForm("formCrearGasto");
  $("#pg_accion").val(accion);
  $("#pg_idaccion").val(idAccion);
  $("#pg_idgasto").val(idGasto);
  $('#popup_modalCrearGasto').modal('show');

  if(idGasto>0){
    //Recuperar datos para el gasto
    let params = {funct: 'obtDatosGasto', idGasto:idGasto};
    ajaxData(params, function(data){
      console.log(data);
      if(data.success){
        $("#pg_fechagasto").val(data.datos.fechaAlta2);
        $("#pg_idconcepto").val(data.datos.conceptoId);
        $("#pg_monto").val( accounting.formatMoney(accounting.unformat(data.datos.monto)) );
      }
    });
  }
}
// Crear y editar gasto
function btnCreaEditaGasto(){
  let idAccion = accounting.unformat($("#pg_idaccion").val());
  // console.log(idAccion);

  var validator = $("#formCrearGasto").validate({ });
  //Validar formulario
  if($("#formCrearGasto").valid()){
    // var htmlOriginal = showLoading('btnCrearGasto');
    // showLoading("btnCrearGasto");

    var datosForm = $("#formCrearGasto").serializeJSON();
    // console.log(datosForm);
    params = paramsB64(datosForm);
    params['funct'] = 'creaEditaGasto';
    // console.log(params);
    // return false;
    ajaxData(params, function(data){
      console.log(data);
      // hideLoading2("btnCrearGasto");

      // $('.modal').modal('hide');
      $('#popup_modalCrearGasto').modal('hide');
      if(data.success){
        caso_acciones.refresh();
        caso_acciones.commit();
        //Recargar gastos
        showLoading("cont_listagastos");
        obtListaGastos();
        obtTotalGastos(accounting.unformat($("#c_id").val()));
        alertify.success("Registro creado correctamente.");
      }else{
        alertify.error("El registro no fue creado, intentar nuevamente.");
      }
    });
  }else{
    validator.focusInvalid();
    return false;
  }
}

// Eliminar gasto
function eliminargasto(idGasto){
  alertify.confirm("<strong> Desea borrar este registro?</strong>", function(){
    let params = {funct: 'eliminarGasto', idGasto:idGasto};

    ajaxData(params, function(data){
      // console.log(data);
      if(data.success){
        caso_acciones.refresh();
        caso_acciones.commit();
        //Recargar gastos
        obtListaGastos();
        obtTotalGastos(accounting.unformat($("#c_id").val()));
        alertify.success("Registro eliminado correctamente.");
      }
    });
  },function(){
  }).set({labels:{ok:'Aceptar', cancel: 'Cancelar'}, padding: false});
}

// Eliminar accion
function eliminarAccion(idAccion){
  alertify.confirm("<strong> Desea borrar este registro, si confirma se eliminar&aacute;n sus gastos asociados?</strong>", function(){
    let params = {funct: 'eliminarAccion', idAccion:idAccion};

    ajaxData(params, function(data){
      // console.log(data);
      if(data.success){
        caso_acciones.refresh();
        caso_acciones.commit();
        obtTotalGastos(accounting.unformat($("#c_id").val()));
        alertify.success("Registro eliminado correctamente.");
      }
    });
  },function(){
  }).set({labels:{ok:'Aceptar', cancel: 'Cancelar'}, padding: false});
}

// Obtener el total general de los gastos
function obtTotalGastos(idCaso){
  let params = {funct: 'obtTotalGastos', idCaso:idCaso};
  ajaxData(params, function(data){
    // console.log(data);
    if(data.success){
      $("#c_tgastos").val(data.tGastos);
    }
  });
}

// Imp. 08/01/21
function sincronizarEventos(idCliente, idCaso, cliente){
    // console.log(idCliente);
    // console.log(idCaso);
    // console.log(cliente);

    clearForm("formEventosCal");
    $('#evecal_clienteid').val( idCliente );
    $('#evcal_casoid').val( idCaso );
    $('#cliente_ev').val( cliente );
    // $('#popup_vereventoscal').modal('show');
    $("#cont_eventos").html("");
    showLoading("cont_eventos");

    let params = {funct: 'obtEventosGoogleCal'};
    ajaxData(params, function(data){
      // console.log(data);
      if(data.success){
        let tabla = '';

        data.eventos.forEach(function(v, i) {
          dtstart = v.dtstart;
          dtstart = dtstart.replace(" ", "T");
          dtend = v.dtend;
          dtend = dtend.replace(" ", "T");

          // console.log(dtstart, dtend, v.summary);
          tabla += `
            <tr>
                <td><input type="checkbox" class="selfilaevento" id="filaev_sel_`+i+`"></td>
                <td><input type="datetime-local" class="form-control required" value="`+dtstart+`" id="filaev_fecha_`+i+`" readonly></td>
                <td>
                    <input type="text" class="form-control required" value="`+v.summary+`" id="filaev_evento_`+i+`" readonly>
                    <input type="hidden" class="form-control" value="`+v.description+`" id="filaev_comentario_`+i+`">
                </td>
            </tr>`;
        });

        $("#cont_eventos").html(tabla);
      }
      hideLoading2("cont_eventos");
    });
}

// Imp. 08/01/21
function btnSalvarEventos(){
  showLoading("btnSalvarEventos");
  let eventosSel = [];
  $(".selfilaevento").each(function(i,v){
    if($(this).is(":checked")){
      // Obtener valores
      let idSel = $(this).attr("id");
      idSel = accounting.unformat(idSel.replace("filaev_sel_", ""));
      eventosSel.push(paramsB64({fecha:$("#filaev_fecha_"+idSel).val(), evento:$("#filaev_evento_"+idSel).val(), comentario:$("#filaev_comentario_"+idSel).val()}));
    }
  });
  // console.log(eventosSel);

  // console.log(datosForm);
  // let params = paramsB64(eventosSel);
  let params = {funct: 'salvarEventos', eventosSel:eventosSel, idCliente:$('#evecal_clienteid').val(), idCaso:$('#evcal_casoid').val()};
  // console.log(params);
  // return false;
  ajaxData(params, function(data){
    console.log(data);
    hideLoading2("btnSalvarEventos");
    $('.modal').modal('hide');

    if(data.success){
      alertify.success("Acci&oacute;n creada correctamente.");
    }else{
      alertify.error("La Acci&oacute;n no fue creada, intentar nuevamente.");
    }
  });
}

function verTablaConceptos(idUsuario, abonos, cargos, saldo, saldoH){
  var params = {
    funct: 'verTablaConceptos',
    desde:  $("#desde").val(),
    hasta:  $("#hasta").val(),
    idUsuario:  idUsuario
  };

  htmlOriginal = showLoading('btnVerConceptos');

  ajaxData(params, function(data){
      hideLoading('btnVerConceptos', htmlOriginal);

      if(data.success){
          $("#contenidoConceptos").html(data.html);
          setTimeout(function(){
              $('[data-toggle="tooltip"]').tooltip();   
              $("#spanTotalAbonosAbg").html(accounting.formatMoney(abonos));
              $("#spanTotalCargosAbg").html(accounting.formatMoney(cargos));
              $("#spanSaldoTotalAbg").html(accounting.formatMoney(saldo));
              $("#spanSaldoHistoricoAbg").html(accounting.formatMoney(saldoH));
          }, 500);
      }
      else{
        alertify.error("Error realizando esta accion","Error");
      }
  });
}

function guardarConcepto(formId, prefijo){
  var validator = $("#"+formId).validate({ });
  if($("#"+formId).valid()){
    var htmlOriginal = showLoading('btnGuardarConcepto'+prefijo);
  
    var arrCamposDF = ["tipoId"+prefijo, "usuarioId"+prefijo, "conceptoId"+prefijo, "descripcion"+prefijo, "monto"+prefijo, "fecha"+prefijo, "comprobante"+prefijo];
    var data = new FormData();
      
      arrCamposDF.forEach(function(campo){
        if(campo == "empresa"){
          data.append(campo, $("#"+campo).val().toString());
        }else{
          if($("#"+campo).length){
            if(campo == 'comprobante'+prefijo){
              var file1 = $('input#'+campo)[0].files[0];
              if(typeof file1 !== 'undefined'){
                data.append(campo, file1);
              }
            }else{
              console.log( $("#"+campo).val());
              console.log(campo);
              data.append(campo, $("#"+campo).val());
            }
          }
        }
      });
  
      data.append("prefijo", prefijo);
console.log(data);
      var urlDel = '../ajaxcall/ajaxFunctions.php?funct=guardarConcepto';
      ajaxDataPost(urlDel, data, function(data){
        hideLoading('btnGuardarConcepto'+prefijo,  htmlOriginal);
        parent.$.fancybox.close();
        // console.log(data);
           if(data.success){
              if(data.res > 0){
                alertify.success("Se han guardado los datos");
                
                gastosGrid.refresh();
                gastosGrid.commit();
              
                var cargos = accounting.unformat($("#spanTotalCargos").html());
                var abonos = accounting.unformat($("#spanTotalAbonos").html());
                var saldo = accounting.unformat($("#spanSaldoTotal").html());
                var saldoH = accounting.unformat($("#spanSaldoHistorico").html());
                
                var monto = accounting.unformat($("#monto"+prefijo).val());
                if(prefijo == "_c"){
                  console.log("if");
                  cargos += monto;
                  saldo -= monto;
                  saldoH -= monto;
                }else{
                  console.log("else");
                  abonos += monto;
                  saldo += monto;
                  saldoH += monto;
                }

                $("#spanTotalCargos").html(accounting.formatMoney(cargos));
                $("#spanTotalAbonos").html(accounting.formatMoney(abonos));
                $("#spanSaldoTotal").html(accounting.formatMoney(saldo));
                $("#spanSaldoHistorico").html(accounting.formatMoney(saldoH));
              }
  
              if(data.upd > 0){
                alertify.success("Se han actualizado los datos");
                gastosGrid.refresh();
                gastosGrid.commit();
              }
           }
           setTimeout(function(){
            inicializaFancyGral();
         }, 1000);
      });
  }else{
      console.log("invalido");
      validator.focusInvalid();
      return false;
  }
}

function seleccionaAbogado(idUsuario, prefijo, montoRD){
  setTimeout(function(){
    if(idUsuario > 0){
      console.log(idUsuario);console.log(prefijo);
      $("#usuarioId"+prefijo).val(idUsuario);
      $("#usuarioId"+prefijo).addClass('fondoGris');
    }else{
      $("#usuarioId"+prefijo).removeClass('fondoGris');
    }
  }, 500);

  if(typeof montoRD !== "undefined"){
    $("#monto"+prefijo).attr("readonly", true);
  }else{
    $("#monto"+prefijo).attr("readonly", false);
  }
}

function imprimirGastos(idUsuario, vista){
  let desde = $("#desde").val();
  let hasta = $("#hasta").val();
  $("#desde_"+idUsuario).val(desde);
  $("#hasta_"+idUsuario).val(hasta);
  $("#formImprimir_"+idUsuario).validate({
    submitHandler: function(form) {           
      form.submit();
    }
    });
  $("#formImprimir_"+idUsuario).submit();
}

function revisaCheckInternos(){
  let c_id = $("#c_id").val();
  if($("#checkinterno").is(':checked')){
    $("#rowInternos").show();
    $(".divInfoInterno").show();
    $("#btnImprimir").attr("href", "expediente.php?id="+c_id+"&int="+1);
  }else{
    $("#rowInternos").hide();
    $(".divInfoInterno").hide();
    $("#btnImprimir").attr("href", "expediente.php?id="+c_id+"&int="+0);
  }
}

function revisaCheckComTit(){
  // console.log();
  let c_id = $("#c_id").val();
  if($("#checkcomtit").is(':checked')){
    $("#rowComTit").show();
    $(".divInfoComTit").show();
    $(".rowComTit").removeClass("oculto");
    // $("#btnImprimir").attr("href", "expediente.php?id="+c_id+"&int="+1);
  }else{
    $("#rowComTit").hide();
    $(".divInfoComTit").hide();
    $(".rowComTit").removeClass("oculto");
    $(".rowComTit").addClass("oculto");
    // $("#btnImprimir").attr("href", "expediente.php?id="+c_id+"&int="+0);
  }
}

function creaMovimientoContrario(tipo, monto, idAbogado){
  let tipo2 = (tipo == 1)?2:1;
  let prefijo = (tipo2 == 1)?'_c':'_a';
  parent.$.fancybox.close();
  let idBoton = (tipo2 == 1)?'btnSalida':'btnEntrada';
  // let idFrom = (tipo2 == 1)?'formCargo':'formAbono';
  $("#"+idBoton).trigger("click");
  $("#tipoId"+prefijo).val(tipo2);
  // $("#conceptoId"+prefijo).val();
  $('#conceptoId'+prefijo).selectpicker('destroy');
  $('#conceptoId'+prefijo+' option:contains(orrecc)').each(function(){
      console.log($(this).val());
      $("#conceptoId"+prefijo).val($(this).val());
        // $(this).attr('selected', 'selected');
        // return false;
      
  });
  // $('#conceptoId'+prefijo+' option:contains(orecc)');
  $('#conceptoId'+prefijo).selectpicker();
  $("#descripcion"+prefijo).val("Ajuste por equivocacion en captura");
  $("#monto"+prefijo).val(monto);
  

  setTimeout(function(){
    seleccionaAbogado(idAbogado, prefijo, 1);
  }, 500);

  

}

function cambiaEstActividad(estatusId){
  // var idAccion = $("#co_idaccion").val();
  // if(estatusId < 4){
  //   $(".rowFechaCompromiso").show();
  //   $(".rowFechaRealizado").hide();
  // }else if(estatusId < 4 && idAccion == 0){
  //   $(".rowFechaRealizado").show();
  //   $(".rowFechaCompromiso").hide();
  // }else if(estatusId < 4 && idAccion > 0){

  // }
}

function preparaComentario(){
  reseteaFormulario('formComentario');

  var params = {selector:"#co_comentarios", height:"230", btnImg:true};
  opcionesTinymce(params);
}

function guardaComentarioExpediente(){
  var validator = $("#formComentario").validate({ });
  tinyMCE.triggerSave();
  $('#co_comentarios').val(tinyMCE.get('co_comentarios').getContent());
 
  var co_comentarios = $("#co_comentarios").val();
 
  // $("#valorAviso").val("");
  $("#co_comentarios_hd").val($.base64.encode(co_comentarios));
  
  if($("#formComentario").valid()){
    var htmlOriginal = showLoading('btnGuardarComentario');
    $('#co_comentarios').val("");
    var formElement = document.getElementById("formComentario");
    var params = new FormData(formElement);
    var urlDel = '../ajaxcall/ajaxFunctions.php?funct=guardaComentarioExpediente';
    ajaxDataPost(urlDel, params, function(data){
    // ajaxData(params, function(data){
      console.log(data);
  
      $('.modal').modal('hide');
      if(data.success){
        alertify.success("Registro creado correctamente.");
        // location.href = "actividad.php?expId="+$("#pa_casoid").val()+"&actId="+$("#pa_idaccion").val();
        
        setTimeout(() => {
          location.reload();
        }, 3000);
      }else{
        alertify.error("El registro no fue creado, intentar nuevamente.");
      }
    });
  }
    


}

function guardaComentarioActividad(){
  var validator = $("#formComentario").validate({ });
  tinyMCE.triggerSave();
  $('#co_comentarios').val(tinyMCE.get('co_comentarios').getContent());
 
  var co_comentarios = $("#co_comentarios").val();
 
  // $("#valorAviso").val("");
  $("#co_comentarios_hd").val($.base64.encode(co_comentarios));
  
  if($("#formComentario").valid()){
    var htmlOriginal = showLoading('btnGuardarComentario');
    $('#co_comentarios').val("");
    var formElement = document.getElementById("formComentario");
    var params = new FormData(formElement);
    var urlDel = '../ajaxcall/ajaxFunctions.php?funct=creaEditaAccion2';
    ajaxDataPost(urlDel, params, function(data){
    // ajaxData(params, function(data){
      console.log(data);
  
      $('.modal').modal('hide');
      if(data.success){
        alertify.success("Registro creado correctamente.");
        location.href = "actividad.php?expId="+$("#pa_casoid").val()+"&actId="+$("#pa_idaccion").val();
      }else{
        alertify.error("El registro no fue creado, intentar nuevamente.");
      }
    });
  }
    


}

function guardaComentarioTarea(){
  var validator = $("#formComentario").validate({ });
  tinyMCE.triggerSave();
  $('#co_comentarios').val(tinyMCE.get('co_comentarios').getContent());
 
  var co_comentarios = $("#co_comentarios").val();
 
  // $("#valorAviso").val("");
  $("#co_comentarios_hd").val($.base64.encode(co_comentarios));
  
  if($("#formComentario").valid()){
    $('#co_comentarios').val("");
    var formElement = document.getElementById("formComentario");
    var params = new FormData(formElement);
    var urlDel = '../ajaxcall/ajaxFunctions.php?funct=creaComentarioTarea';
    ajaxDataPost(urlDel, params, function(data){
    // ajaxData(params, function(data){
      console.log(data);
  
      $('.modal').modal('hide');
      if(data.success){
        alertify.success("Registro creado correctamente.");
        location.href = "tarea.php?tareaId="+$("#pa_idtarea").val();
      }else{
        alertify.error("El registro no fue creado, intentar nuevamente.");
      }
    });
  }
    


}

function crearContacto() {
  var validator = $("#formCrearContacto").validate({ });
  //Validar formulario
  if($("#formCrearContacto").valid()){
    var htmlOriginal = showLoading('btnCrearContacto');

    var datosForm = $("#formCrearContacto").serializeJSON();
    // console.log(datosForm);
    params = paramsB64(datosForm);
    params['funct'] = 'crearContacto';
    console.log(params);
    // return false;
    ajaxData(params, function(data){
      console.log(data);
      hideLoading('btnCrearContacto', htmlOriginal);
      $('.modal').modal('hide');
      if(data.success){
        alertify.success("Registro creado correctamente.");
        setTimeout(function(){
          location.reload();
        }, 2000);
      }else{
        alertify.error("El registro no fue creado, intentar nuevamente.");
      }
    });
  }else{
    validator.focusInvalid();
    return false;
  }
}

function crearDocumento() {
  var validator = $("#formCrearDocumento").validate({ });
  //Validar formulario
  if($("#formCrearDocumento").valid()){
    // var htmlOriginal = showLoading('btnCrearCliente');

    var datosForm = $("#formCrearDocumento").serializeJSON();
    // console.log(datosForm);
    params = paramsB64(datosForm);
    params['funct'] = 'crearDocumento';
    console.log(params);
    // return false;
    ajaxData(params, function(data){
      console.log(data);

      $('.modal').modal('hide');
      if(data.success){
        alertify.success("Registro creado correctamente.");
        setTimeout(function(){
          location.reload();
        }, 2000);
      }else{
        alertify.error("El registro no fue creado, intentar nuevamente.");
      }
    });
  }else{
    validator.focusInvalid();
    return false;
  }
}

function crearDigital() {
  var validator = $("#formCrearDigital").validate({ });
  //Validar formulario
  if($("#formCrearDigital").valid()){
    // var htmlOriginal = showLoading('btnCrearCliente');

    var urlDel = '../ajaxcall/ajaxFunctions.php?funct=crearDigital';
    var formElement = document.getElementById("formCrearDigital");
    var data = new FormData(formElement);
    //var file1 = $('input#digi_file')[0].files[0];
    var file1 = $('#subido').val();
    if(typeof file1 !== 'undefined'){
      data.append('file', file1);
    }

    var htmlOriginal = showLoading('btnCrearDigital');
    console.log(data);
    ajaxDataPost(urlDel, data, function(data){
      hideLoading('btnCrearDigital', htmlOriginal);
      console.log(data);

      if(data.opc == "add"){
        if(data.add > 0){
          alertify.success("Se ha agregado el nuevo documento");
          setTimeout(function(){
            // location.reload();
            document.getElementById('console').appendChild(document.createTextNode("Se ha guardado el documento"));
            $("#btnCrearDigital").prop("disabled", true);
            $("#btnCrearDigital").prop("onclick", null).off("click");
          }, 2000);

        }else{
          alertify.warning("No se ha guardado el nuevo documento");
        }
      }else{
        if(data.res > 0){
          alertify.success("Se han guardado los cambios correctamente.");
          setTimeout(function(){
            location.reload();
          }, 2000);
        }else{
          alertify.warning("No hay cambios que guardar.");
        }
      }
    });
  }else{
    validator.focusInvalid();
    return false;
  }
}

function actualizarGridDigitales(){
  location.reload();  
  //digitales.refresh();
  //digitales.commit();
}


function marcarComoLeido(idMensaje){
  let params = {funct: 'marcarComoLeido', idMensaje:idMensaje};
  // console.log(params);
  // return false;
  var htmlOriginal = showLoading('contForLoading');
  ajaxData(params, function(data){
    console.log(data);
    alertify.success("Se han marcado como leidas "+data.cont+" notificaciones");
    var arrIds = String(idMensaje).split(",");
    for(itemId of arrIds){
      $("#item_"+itemId).remove();
    }
    setTimeout(function(){
      hideLoading('contForLoading', htmlOriginal);
      recalcularContadores();
      // location.reload();
    }, 1000);
  });
}

function multiLeido(){
  alertify.confirm(
    'Marcar como leido', 
    'Esta seguro de marcar como leido todas las notificaciones seleccionadas?', 
    function(){ 
      var ids = $("#selected_ids").val();
      marcarComoLeido(ids);
     }
    , function(){});

}

function imprimirReporte(imprimir){
  var reporte = $("#reporte").val();
  if($("#formReporte").valid()){
    if(reporte == 1){
      var abogado = $("#abogado").val();
      var estatus = $("#estatus").val();
      var fecha = $("#fecha").val();
      var from = $("#from").val();
      var to = $("#to").val();
      var mostrar = $("#mostrar").val();
      window.open("sabana.php?abogado="+abogado+"&estatus="+estatus+"&fecha="+fecha+"&from="+from+"&to="+to+"&mostrar="+mostrar+"&imprimir="+imprimir, "_blank");
      // window.open("sabanaPdf.php?abogado="+abogado+"&estatus="+estatus+"&fecha="+fecha+"&from="+from+"&to="+to+"&mostrar="+mostrar, "_blank");
    }

    if(reporte == 2){
      var abogado = $("#abogado").val();
      var from = $("#from").val();
      var to = $("#to").val();
      window.open("rep_gastos.php?abogado="+abogado+"&from="+from+"&to="+to+"&imprimir="+imprimir, "_blank");
    }
  }
}

function cambiaCorreoNot(correonot){
  console.log(correonot);

  if(correonot == "otro"){
    $("#otro").attr("readonly", false);
    $("#otro").removeClass("required");
    $("#otro").addClass("required");
  }else{
    $("#otro").attr("readonly", true);
    $("#otro").val("");
    $("#otro").removeClass("required");
  }
}

function muestraAccion(materia){
  // data-tieneacc
  var tiene = materia.getAttribute("data-tieneacc");
  if(tiene == 1){
    $(".rowAccion").removeClass('oculto');
  }else{
    $(".rowAccion").removeClass('oculto');
    $(".rowAccion").addClass('oculto');
  }
}

//
// function cambiaEstatusActividad(estatus){
//   if(estatus == 4){
//     $("#pa_reporte").removeClass('required');
//     $("#pa_reporte").addClass('required');
//     $("#fechaRealizado").removeClass('required');
//     $("#fechaRealizado").addClass('required');
//     // $("#pa_reporte").focus();
//   }else{
//     $("#pa_reporte").removeClass('required');
//     $("#fechaRealizado").removeClass('required');
    
//   }
// }


var arrIdsNotSel = [];
function muestraNoficaciones(clase){
  if(typeof clase !== "undefined" && clase != ""){
    // $("."+clase).show();
    var arrClases = clase.split("_");
    $("#class_selected").val(clase);

    //Mostrar solo los seleccionados
    $( ".alertas .item" ).each(function( index ) {

      for (let itemClase of arrClases) {
        var numItems = $('.'+itemClase).length;
        $("#spanBadge").html(numItems);
        // console.log(itemClase);
        // console.log(this);
        if($( this ).hasClass(itemClase)){
          if(itemClase == 'titular'){//si la clase es titular, validar solo mostrar propias Jair 26/1/2022
            if($( this ).hasClass('ajena')){
              console.log("titular ajena");
              $(this).show();
              $(this).removeClass('itemFiltered');
              $(this).addClass('itemFiltered');
            }else{
              console.log("titular propia");
              $(this).hide();
              $(this).removeClass('itemFiltered');
            }
          }else{//Si no tiene clase titular solo validar la clase buscada
            // console.log("mostrar");
              $(this).show();
              $(this).removeClass('itemFiltered');
              $(this).addClass('itemFiltered');
          }
        }else{
          // console.log("ocultar");
          $(this).hide();
          $(this).removeClass('itemFiltered');
        }
      }
     
    });

    //Buscar ids de los seleccionados
    arrIdsNotSel = [];
    for (let itemClase of arrClases) {
      console.log(itemClase);
      $( ".not_"+itemClase ).each(function( index ) {
          // console.log($(this).val());
          arrIdsNotSel.push($(this).val());
      });
    }
    // console.log(arrIdsNotSel);
    $("#selected_ids").val(arrIdsNotSel.join(","));
  }



}

function cambiaRadioNot(radio){
  $("#search_not").val("");
  if($(radio).is(':checked')){
    muestraNoficaciones($(radio).val());
  }
}

function cambiaEstatusActividad(estatus){
  if(estatus == 3){//espero instrucciones
    $("#fechaCompromiso").removeClass('required');
    $("#fechaRealizado").removeClass('required');

    $("#pa_reporte").removeClass('required');
    $(".rowReporte").hide();

    //4/2/2022 Jair Campo Avanzo
    $(".rowAvanzo").removeClass('oculto');
    $(".rowAvanzo").addClass('oculto');
    $("#avanzo").removeClass('required');
  }

  if(estatus == 1){//por realizar
    $("#fechaCompromiso").removeClass('required');
    $("#fechaCompromiso").addClass('required');

    $("#fechaRealizado").removeClass('required');

    $("#pa_reporte").removeClass('required');
    $(".rowReporte").hide();

    //4/2/2022 Jair Campo Avanzo
    $(".rowAvanzo").removeClass('oculto');
    $(".rowAvanzo").addClass('oculto');
    $("#avanzo").removeClass('required');
  }

  $("#abrirotra").val(0);
  if(estatus == 4){//terminado
    $("#fechaCompromiso").removeClass('required');

    $("#fechaRealizado").removeClass('required');
    $("#fechaRealizado").addClass('required');

    //JGP ojo las funcione de reporte no están activas
    //$("#pa_reporte").removeClass('required');
    //$("#pa_reporte").addClass('required');
    //$(".rowReporte").show();

    //4/2/2022 Jair Campo Avanzo
    $(".rowAvanzo").removeClass('oculto');
    $("#avanzo").removeClass('required');
    $("#avanzo").addClass('required');
    $("#avanzo").val('');

    //25/2/2022 
    alertify.confirm('Abrir otra tarea despues de guardar?', 
    function(){ 
      $("#abrirotra").val(1);
     }
    , function(){ 
      $("#abrirotra").val(0);
    }).set('labels', {ok:'Si', cancel:'No'}); ;
  }
}

function cambiaReporte(numReporte){
  if(numReporte == 1){
    $("#estatus").removeClass('required');
    $("#fecha").removeClass('required');
    $("#mostrar").removeClass('required');
    $("#estatus").addClass('required');
    $("#fecha").addClass('required');
    $("#mostrar").addClass('required');

    $(".rowEstatus").show();
    $(".rowFecha").show();
    $(".rowMostrar").show();
  }
  else if(numReporte == 2){
    $("#estatus").removeClass('required');
    $("#fecha").removeClass('required');
    $("#mostrar").removeClass('required');
    
    $(".rowEstatus").hide();
    $(".rowFecha").hide();
    $(".rowMostrar").hide();
  }
}

function cambiaTipoActividad2(tipo2){
  console.log(tipo2);
  if(tipo2 == 1){//actividad
    console.log("actividad");
    $("#importanciaactividad").val("");
    $("#estatusId").val("");
    $("#estatusId").trigger("change");
  }
  if(tipo2 == 2){//reporte
    console.log("reporte");
    $("#importanciaactividad").val(1);
    $("#estatusId").val(4);
    $("#estatusId").trigger("change");
  }

}

//busqueda dentro del contenido html especial para las notificaciones
function buscarEnContenidoNot(idInputSearch, targetClassNodes) {
  var input = document.getElementById(idInputSearch);
  var filter = input.value.toLowerCase();
  var nodes = document.getElementsByClassName(targetClassNodes);
  // $("."+targetClassNodes).hide();
  // console.log(nodes);
  arrIdsNotSel = [];//reinicializar ids seleccionados
  var numItems = 0;
  var instance = new Mark(document.querySelector(".recent-activities"));
  instance.unmark();
  if(filter != ""){
    for (let node of nodes) {
        let innerText = node.innerText.normalize("NFD").replace(/[\u0300-\u036f]/g, '').toLowerCase();

        if (innerText.includes(filter)) {
          var arrId = node.id.split("_");//Obtener id del elemento en array
          arrIdsNotSel.push(parseInt(arrId[1]));//Agregar al arreglo el id de la notificacion
          // node.classList.remove(targetClassNodes);
          // node.classList.add(targetClassNodes);//actualizar clase (itemSelected)
          node.style.display = "block";
          numItems++;
        } else {
          // node.classList.remove(targetClassNodes);//Quitar clase (itemSelected)
          node.style.display = "none";
        }
    }
  
    $("#spanBadge").html(nodes.length+" ("+numItems+")");//Actualizar numero de elementos seleccionados
  
    $("#selected_ids").val(arrIdsNotSel.join(","));//actualizar ids seleccionados
    console.log(arrIdsNotSel);

    // var myHilitor = new Hilitor("recent-activities"); // id of the element to parse
    // myHilitor.apply(filter);
    var instance = new Mark(document.querySelector(".recent-activities"));
    instance.mark(filter);
  }else{
    for (let node of nodes) {
      node.style.display = "block";
    }
    $("#spanBadge").html(nodes.length);//Actualizar numero de elementos seleccionados

    // var myHilitor = new Hilitor("recent-activities");
    // myHilitor.remove();
    var instance = new Mark(document.querySelector(".recent-activities"));
    instance.unmark();
  }
}

function recalcularContadores(){
  var arrClases = ["propia", "ajena", "titular", "espero", "comentario"];

  for(itemClase of arrClases){
    var cont = $("."+itemClase).length;
    $("#spanBadge_"+itemClase).html(cont);
  }
}

function marcarLeidoComentario(idRegistro, tipo){
  let params = {funct: 'marcarLeidoComentario', idRegistro:idRegistro, tipo: tipo};
  // console.log(params);
  // return false;
  var htmlOriginal = showLoading('contForLoading');
  ajaxData(params, function(data){
    console.log(data);
    alertify.success("Se han marcado como leidos "+data.cont+" comentarios");
    var arrIds = String(idRegistro).split(",");
    for(itemId of arrIds){
      $("#btn_comentario_"+itemId).remove();
      
      //Jair 17/2/2022
      //Obtener el id de notificacion y el id del registro de la notificacion
      var notificacionId = $("#notificacionId").val();
      var idRegistroNot = $("#idRegistro").val();
      if(notificacionId > 0 && itemId == idRegistroNot){//Si el id del comentario que se marco como leido, coincide con el registro de la notificacion, se regresa el aviso a la pagina padre
        var data = {id: notificacionId, message: 'Se ha marcado como leido el comentario y la notificacion', accion: 'comentario'};
        window.opener.ProcessChildMessage(data);
      }
    }
    setTimeout(function(){
      hideLoading('contForLoading', htmlOriginal);
      // recalcularContadores();
      // location.reload();

    }, 1000);
  });
}
function Handle_OnPageChange(sender,args)
{
  // console.log(sender);
  // console.log(args);
  // var _tableview = args["TableView"];
  // var _pageindex = args["PageIndex"];		
  // alert("Tableview changed page.");

  if(sender.id == 'caso_acciones'){
    // console.log("pi");
    setTimeout(function(){
      // console.log("pi");
      searhColMaxLength();
      Handle_OnLoad(sender,args);
    }, 1000);
  }

  if(sender.id == 'digitales'){
      inicializaFancyGral();
      Handle_OnLoad(sender,args);
  }

}

function Handle_OnLoad(sender,args)
{
  console.log("load");
		if(sender.id == 'caso_acciones'){
      searhColMaxLength();
    }
    if(sender.id == 'grid_actividades'){
      $('td:contains("Alta")').css('font-weight', 'bold');
      $( 'td:contains("Alta")' ).parent().css( 'font-weight', 'bold' );
    }

    if(sender.id == 'grid_tareas'){
      $('td:contains("Alta")').css('font-weight', 'bold');
      $( 'td:contains("Alta")' ).parent().css( 'font-weight', 'bold' );
    }

    if(sender.id == 'digitales'){
      inicializaFancyGral();
    }

}

function Handle_OnInit(sender,args)
	{
		console.log("init");
    if(sender.id == 'digitales'){
      inicializaFancyGral();
    }
	}

let child_window_handle = null;
function ventanaNotificacion(url, notificacionId, idRegistro, tipoNot){
  if(tipoNot == 3){
    child_window_handle = window.open(url+'&notificacionId='+notificacionId+'&idRegistro='+idRegistro, '_blank');
  }else{
    window.open(url+'', '_blank');
  }
}

//Jair 17/2/2022 Recibir aviso de pestania hija
function ProcessChildMessage(message) {
  // do something with the message
  console.log(message);
  if(message.accion == 'comentario' && message.id > 0){//Si se marco como leido un mensaje y se recibe un id mayor a 0, se marca como leida la notificacion correspondiente
      marcarComoLeido(message.id);
      alertify.alert(message.message, function(){  });
  }
}

function muestraDivsClase(clase){
  $("."+clase).removeClass('oculto');
  $("."+clase).show();
}

function editarContacto(idContacto, nombre, email, telefono, domicilio, notas){
  $("#contactoId").val(idContacto);
  $("#c_nombre").val(nombre);
  $("#c_telefono").val(email);
  $("#c_email").val(telefono);
  $("#c_domicilio").val(domicilio);
  $("#c_notas").val(notas);
}

function nuevoContacto(){
  $("#contactoId").val(0);
  $("#c_nombre").val('');
  $("#c_telefono").val('');
  $("#c_email").val('');
  $("#c_domicilio").val('');
  $("#c_notas").val('');
}

function filtrarExpedientes(){
  let responsables = ($("#fil_responsableId").val() != null)?$("#fil_responsableId").val():'';
  let clientes = ($("#fil_clienteId").val() != null)?$("#fil_clienteId").val():'';
  let estatus = ($("#fil_estatusId").val() != null)?$("#fil_estatusId").val():'';
  let juicios = ($("#fil_juicioId").val() != null)?$("#fil_juicioId").val():'';
  let juzgados = ($("#fil_juzgadoId").val() != null)?$("#fil_juzgadoId").val():'';
  let materias = ($("#fil_materiaId").val() != null)?$("#fil_materiaId").val():'';
  let camposIds = ($("#fil_campos").val() != null)?$("#fil_campos").val():'';
  let clientesno = ($("#fil_clientenoId").val() != null)?$("#fil_clientenoId").val():'';
  let distritos = ($("#fil_distritoId").val() != null)?$("#fil_distritoId").val():'';
  let representado = ($("#fil_representadoId").val() != null)?$("#fil_representadoId").val():'';
  let mostrar = ($("#fil_mostrar").val() != null)?$("#fil_mostrar").val():'';//Mostrar campos titular
  let camposGrid = ($("#fil_camposgrid").val() != null)?$("#fil_camposgrid").val():'';//Mostrar campos grid
  
  
  let filtros = [];
  if(responsables != ''){
    filtros.push('responsables='+responsables);
  }
  if(clientes != ''){
    filtros.push('clientes='+clientes);
  }
  if(estatus != ''){
    filtros.push('estatus='+estatus);
  }
  if(materias != ''){
    filtros.push('materias='+materias);
  }

  if(juicios != ''){
    filtros.push('juicios='+juicios);
  }

  if(juzgados != ''){
    filtros.push('juzgados='+juzgados);
  }

  if(mostrar != ''){
    filtros.push('mostrarCamposTitular='+mostrar);
  }

  if(camposIds != ''){
    filtros.push('camposIds='+camposIds);
  }
  if(clientesno !=''){
    filtros.push('clientesno='+clientesno)
  }
  if(representado !=''){
    filtros.push('representado='+representado)
  }

  if(distritos != ''){
    filtros.push('distritos='+distritos);
  }

  if(camposGrid != ''){
    filtros.push('mostrarCamposGrid='+camposGrid);
  }

  if(filtros.length > 0){
    location.href = "expedientes.php?"+filtros.join("&");
  }else{
    alertify.warning("No ha seleccionado ningun filtro");
  }
}

function asignaCoordinador(este){
  let coordinadorId = $(este).find(':selected').attr('data-coordinador');
  if(coordinadorId > 0){
    console.log(coordinadorId);
    $("#c_autorizados").val(coordinadorId);
    $("#c_autorizados").bootstrapDualListbox("refresh");
    $("#c_autorizados").trigger("change");
    console.log($("#c_autorizados").val());
    alertify.success("Se ha asignado al coordinador como autorizado");

  }else{
    alertify.warning("No hay coordinador para asignar");
  }
}

var expedientesSel = [];
function cambiaSeleccionaCaso(expedienteId){
  if ($('#seleccionar_'+expedienteId).is(':checked')) {
    if(!expedientesSel.includes(expedienteId)){
      expedientesSel.push(expedienteId);
    }
  }else{
    if(expedientesSel.includes(expedienteId)){
      var index = expedientesSel.indexOf(expedienteId);
      if (index !== -1) {
        expedientesSel.splice(index, 1);
      }
    }
  }

  $("#seleccionados").val(expedientesSel.join(","));
}

function recargaViewer(idDigital, expedienteId){
  let urlPdf = "../upload/expedientes/"+$("#url_"+idDigital).val();
  $("#url_selected").val(urlPdf);
  $(".btnDigital").removeClass("active");
  $("#btnDigital_"+idDigital).addClass("active");
  var DOMContentLoaded_event = document.createEvent("Event")
  DOMContentLoaded_event.initEvent("DOMContentLoaded", true, true)
  window.document.dispatchEvent(DOMContentLoaded_event)
  // location.href="digital.php?expedienteId="+expedienteId+"&digitalId="+idDigital;
}


function recargaViewer2(idDigital, expedienteId){
  let urlPdf = $("#url_"+idDigital).val();
  console.log(urlPdf);
  $("#url_selected").val(urlPdf);
  $(".btnDigital").removeClass("active");
  $("#btnDigital_"+idDigital).addClass("active");
//   document.getElementById('example-url').innerText = urlPdf;
    update_viewer(urlPdf);
}

function revisaArchivo(fileLink, tipo, guardar){
  console.log("tipo", tipo);
  let folder = '';
  switch (parseInt(tipo)) {
    case 1: folder = 'escritos'; break;
    case 2: folder = 'expedientes'; break;
    case 3: folder = 'audiencias'; break;
    case 4: folder = 'otros'; break;
    case 5: folder = 'audios'; break;
    
  }
  let url = '../upload/'+folder+'/'+fileLink;
  let params = {funct: 'revisaArchivo', url:url};
  
  
  ajaxData(params, function(data, msg){
    
    console.log(data);
    if(data.success){
      alertify.success("Archivo cargado correctamente");
      if(guardar){
        //$("#btnCrearDigital").trigger("click");
        crearDigital();
      }
    }
    else{
      alertify.error("Error al cargar el archivo, volver a subir");
      $("#subido").val("");
      document.getElementById('console').appendChild(document.createTextNode("Error al cargar el archivo, volver a subir"));

      $("#barra_progreso").attr("style", "width: 0%");
      $("#barra_progreso").attr("aria-valuenow", 0);
    }

  });
}



function expanderDiv(target){
  $("#"+target).toggle();
}

function unirPdf(idCaso){
  let params = {funct: 'unirPdf', idCaso:idCaso};
  var htmlOriginal = showLoading('btnDescargar');
  alertify.success("Por favor espere mientras se genera el documento");
  ajaxData(params, function(data, msg){
    hideLoading('btnDescargar', htmlOriginal);
    console.log(data);
    if(msg == 'error'){
      alertify.warning('ocurrio un error al intentar unir los pdf, por favor verifique el formato de los archivos');
    }else{
      if(data.success){
        if(data.contNoCompatibles > 0){
          alertify.success("Se detectaron "+data.contNoCompatibles+" archivos no compatibles, que se omitieron al generar el archivo");
        }
        setTimeout(() => {
          window.open(data.outputName, "_blank");
        }, 3000);
      }else{
        alertify.warning("Error generando pdf");
      }
    }
    
  });
}

function ordenarDocs(idCaso){
  var dataIds = $("#sortable").sortable("toArray");
  console.log(dataIds);
  let order = 1;
  let arrOrder = [];
  for (const itemId of dataIds) {
    let arrIdDocumento = itemId.split("_");
    let idDocumento = arrIdDocumento[1];
    arrOrder.push({'idDocumento' : idDocumento, 'order' : order});
    order++;
  }

  let params = {funct: 'ordenarDocs', json: JSON.stringify(arrOrder), idCaso: idCaso};
  var htmlOriginal = showLoading('btnOrdenar');
  
  ajaxData(params, function(data){
    hideLoading('btnOrdenar', htmlOriginal);
    console.log(data);

    if(data.success && data.cont > 0){
      alertify.success("Cambios guardados");
    }else{
      alertify.warning("No hay cambios que guardar");
    }

    $( "#sortable" ).sortable({
        update: function( event, ui ) {ordenarDocs(idCaso)}
    });
    
  });

  console.log(JSON.stringify(arrOrder));
}

function cambiaNombreDigital(valor){
  let digi_tipo = $("#digi_tipo").val();
  if(valor != "" && digi_tipo != ""){
    $("#pickfiles").attr("disabled", false);
  }else{
    $("#pickfiles").attr("disabled", true);
  }
}

function cambiaDescripDigital(valor){ // LDAH 16/08/2022 IMP para nuevo campo de descripcion de archivo
  let digi_descrip = $("#digi_descrip").val();
  if(valor != "" && digi_descrip != ""){
    $("#pickfiles").attr("disabled", false);
  }else{
    $("#pickfiles").attr("disabled", true);
  }
}
var uploader;
function cambiaTipoExp(tipoExp){
  let digi_tipo = $("#digi_tipo").val();
  let digi_nombre = $("#digi_nombre").val();
  if(digi_nombre != "" && digi_tipo != ""){
    $("#pickfiles").attr("disabled", false);
  }else{
    $("#pickfiles").attr("disabled", true);
  }
  console.log("tipo exp");
  let mimetpye = "";
  switch (parseInt(tipoExp)) {
    case 1: 
      $("#digi_file").attr("accept", "application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/msword,application/pdf");
      mimetype = [
        { title : "Escritos: Word y pdf", extensions : "doc,docx,pdf" },
      ];
      
      $("#divArchivosAceptados").html("Escritos: Word y pdf");
    break;//escritos, word y pdf
    case 2: 
      $("#digi_file").attr("accept", "application/pdf"); 
      mimetype = [
        { title : "Expediente digital: pdf", extensions : "pdf" },
      ];
      $("#divArchivosAceptados").html("Expediente digital: Pdf");
    break;//pdf expediente digital
    case 3: 
      $("#digi_file").attr("accept", "video/mp4,video/x-m4v,video/*"); 
      mimetype = [
        { title : "Audiencias: Videos", extensions : "mp4, mov" },
      ];
      $("#divArchivosAceptados").html("Audiciencias: Video, Mp4, mov");
    break;//videos audiencias
    case 4:
      $("#digi_file").attr("accept", "*"); 
      mimetype = [
        { title : "Otros: todos los archivos", extensions : "*" },
      ];
      $("#divArchivosAceptados").html("Otros: Todos los archivos");
    case 5:
        $("#digi_file").attr("accept", "audio/mp3,audio/mpeg"); 
        mimetype = [
          { title : "Audios: MP3", extensions : "mp3" },
        ];
        $("#divArchivosAceptados").html("Audios: MP3");  
    break;//todos los archivos
    default:console.log("default");
      break;
  }

  if(uploader){
    uploader.destroy();
  }
  uploader = new plupload.Uploader({
    runtimes : 'html5,flash,silverlight,html4',
    browse_button : 'pickfiles', // you can pass an id...
    container: document.getElementById('containerFile'), // ... or DOM Element itself
    url : './../plupload.php?tipoExp='+tipoExp,
    chunk_size: '200kb',
    flash_swf_url : '../libs/plupload/Moxie.swf',
    silverlight_xap_url : '../libs/plupload/Moxie.xap',
    multi_selection: false,
    unique_names: true,
    filters : {
      max_file_size : '7000mb',
      filters: {
        mime_types : mimetpye
      }
    },
  
    init: {
      PostInit: function() {
        document.getElementById('filelist').innerHTML = '';
  
        document.getElementById('uploadfiles').onclick = function() {
          $("#btnCrearDigital").attr("disabled", true);
          $("#btnCrearDigital").prop("onclick", null).off("click");
          uploader.start();
          return false;
        };
      },
  
      FilesAdded: function(up, files) {
        plupload.each(files, function(file) {
          document.getElementById('filelist').innerHTML += '<div id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b></div>';
          // $("#subido").val();
        });
      },
  
      UploadProgress: function(up, file) {
        document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
        $("#barra_progreso").attr("style", "width: "+file.percent+"%");
        $("#barra_progreso").attr("aria-valuenow", file.percent);
      },
  
      Error: function(up, err) {
        document.getElementById('console').appendChild(document.createTextNode("\nError #" + err.code + ": " + err.message));
      },
      FileUploaded: function(up, file, result) {
        let json = JSON.parse(result.response);
        console.log(json);
          $("#subido").val(json.fileParam);
          //settimeout
          setTimeout(() => {
            console.log($("#digi_tipo").val());
            revisaArchivo(json.fileParam, $("#digi_tipo").val(), true);
          }, 500);
        
      }
    }
  });
  
  uploader.init();
}

function cambiaComunicadoGeneral(comunicadoId){
  let titulo = $("#listComunicados option:selected").text();
  let contenido = $("#listComunicados option:selected").attr("data-contenido");

  $("#tituloMensaje").val(titulo);
  $("#contenidoMensaje").val(contenido);
}

function edicionDigital(idDocumento, tipo, nombre,  fileName, descrip){ // LDAH 18/08/2022 IMP para nuevo campo de descripcion de archivo
  console.log(descrip);
  $("#digi_id").val(idDocumento);
  $("#digi_tipo").val(tipo);
  $("#digi_nombre").val(nombre);
  $("#btnCrearDigital").attr("disabled", false);
  $("#btnCrearDigital").prop("disabled", false);
  //$('#btnCrearDigital').on('click', crearDigital());
  //$('#btnCrearDigital').click(crearDigital());

  $("#pickfiles").attr("disabled", true);
  $("#barra_progreso").attr("style", "width: 0%");
  $("#barra_progreso").attr("aria-valuenow", 0);
  $("#digi_descrip").val(descrip);
  
  if(idDocumento > 0){
    $("#digi_tipo").trigger("change");
    $("#digi_tipo").removeClass('likeDisaled');
    $("#digi_tipo").addClass('likeDisaled');
    $("#subido").val(fileName);
    $("#subido" ).removeClass('required');
    $("#digi_descrip").trigger("change");
    
  }else{
    $("#digi_tipo").removeClass('likeDisaled');
    $("#subido" ).val("");
    $("#subido" ).removeClass('required');
    $("#subido" ).addClass('required');
    $("#pickfiles").attr("disabled", true);
  }

  //fileupload
  $("#filelist").html("");
}

function descargaDigital(url, tipo){
  let folder = '';
  switch (tipo) {
    case 1: folder = 'escritos'; break;
    case 2: folder = 'expedientes'; break;
    case 3: folder = 'audiencias'; break;
    case 4: folder = 'otros'; break;
    
  }
  let fileLink = '../upload/'+folder+'/'+url;
  var a = document.createElement('A');
  a.href = fileLink;
  a.download = fileLink.substr(fileLink.lastIndexOf('/') + 1);
  document.body.appendChild(a);
  a.click();
  document.body.removeChild(a);
}

function abrirDigital(url, tipo){
  let folder = '';
  switch (tipo) {
    case 1: folder = 'escritos'; break;
    case 2: folder = 'expedientes'; break;
    case 3: folder = 'audiencias'; break;
    case 4: folder = 'otros'; break;
    case 5: folder = 'audios'; break;
  }
  let fileLink = '../upload/'+folder+'/'+url;
  window.open(fileLink, '_blank');
}

function guardarCuenta(planPagos, totalJson){
  var validator = $("#formCuenta").validate({ });
  
  if($("#formCuenta").valid()){
    var htmlOriginal = showLoading('btnGuardarCuenta');
    var formElement = document.getElementById("formCuenta");
    var params = new FormData(formElement);
    var planPagosGet = params.get("planPagos");

    if(planPagos == 2 && planPagosGet ==1){//pasar de plan de pagos 2 a plan de pagos 1
      var urlDel = '../ajaxcall/ajaxFunctions.php?funct=cambiarTipo2a1';
      params.append("totalJson", totalJson);
      /*params.forEach(function(value, key) {
        console.log(key + ": " + value);
      });*/
      ajaxDataPost(urlDel, params, function(data){
        // ajaxData(params, function(data){
          console.log(data);
          hideLoading('btnGuardarCuenta', htmlOriginal);
  
          let c_id = ( accounting.unformat($("#c_id").val()) > 0)?"editado":"creado";
          if(data.success){
            alertify.success("Registro "+c_id+" correctamente.");
            setTimeout(function(){
              location.href="cuentasxcobrar.php?expedienteId="+data.casoId+"";
            }, 1500);
          }else{
            alertify.error("El registro no fue "+c_id+", intentar nuevamente.");
          }
        });
      
    }else if(planPagos == 2 && planPagosGet ==3){//pasar de plan de pagos 2 a plan de pagos 3
      var urlDel = '../ajaxcall/ajaxFunctions.php?funct=cambiarTipo2a3';
      console.log("entro")
      params.append("totalJson", totalJson);
      
      ajaxDataPost(urlDel, params, function(data){
        // ajaxData(params, function(data){
          console.log(data);
          hideLoading('btnGuardarCuenta', htmlOriginal);
  
          let c_id = ( accounting.unformat($("#c_id").val()) > 0)?"editado":"creado";
          if(data.success){
            alertify.success("Registro "+c_id+" correctamente.");
            setTimeout(function(){
              location.href="cuentasxcobrar.php?expedienteId="+data.casoId+"";
            }, 1500);
          }else{
            alertify.error("El registro no fue "+c_id+", intentar nuevamente.");
          }
        });
    } else if(planPagos == 2 && planPagosGet ==4){//pasar de plan de pagos 2 a plan de pagos 4
      var urlDel = '../ajaxcall/ajaxFunctions.php?funct=cambiarTipo2a4';
      console.log("entro")
      params.append("totalJson", totalJson);
      
      ajaxDataPost(urlDel, params, function(data){
        // ajaxData(params, function(data){
          console.log(data);
          hideLoading('btnGuardarCuenta', htmlOriginal);
  
          let c_id = ( accounting.unformat($("#c_id").val()) > 0)?"editado":"creado";
          if(data.success){
            alertify.success("Registro "+c_id+" correctamente.");
            setTimeout(function(){
              location.href="cuentasxcobrar.php?expedienteId="+data.casoId+"";
            }, 1500);
          }else{
            alertify.error("El registro no fue "+c_id+", intentar nuevamente.");
          }
        });
    } else if(planPagos == 3 && planPagosGet ==1){//Pasar de plan de pagos 3 al 1
      var urlDel = '../ajaxcall/ajaxFunctions.php?funct=cambiarTipo3a1';
      params.append("totalJson", totalJson);
  
      ajaxDataPost(urlDel, params, function(data){
          console.log(data);
          hideLoading('btnGuardarCuenta', htmlOriginal);
          let c_id = ( accounting.unformat($("#c_id").val()) > 0)?"editado":"creado";
          if(data.success){
            alertify.success("Registro "+c_id+" correctamente.");
            setTimeout(function(){
              location.href="cuentasxcobrar.php?expedienteId="+data.casoId+"";
            }, 1500);
          }else{
            alertify.error("El registro no fue "+c_id+", intentar nuevamente.");
          }
        });
    } else if(planPagos == 3 && planPagosGet ==2){//Pasar de plan de pagos 3 al 2
      var urlDel = '../ajaxcall/ajaxFunctions.php?funct=cambiarTipo3a2';
      params.append("totalJson", totalJson);
  
      ajaxDataPost(urlDel, params, function(data){
          console.log(data);
          hideLoading('btnGuardarCuenta', htmlOriginal);
          let c_id = ( accounting.unformat($("#c_id").val()) > 0)?"editado":"creado";
          if(data.success){
            alertify.success("Registro "+c_id+" correctamente.");
            setTimeout(function(){
              location.href="cuentasxcobrar.php?expedienteId="+data.casoId+"";
            }, 1500);
          }else{
            alertify.error("El registro no fue "+c_id+", intentar nuevamente.");
          }
        });
    }else if(planPagos == 3 && planPagosGet ==4){//Pasar de plan de pagos 3 al 4
      var urlDel = '../ajaxcall/ajaxFunctions.php?funct=cambiarTipo3a4';
      params.append("totalJson", totalJson);
  
      ajaxDataPost(urlDel, params, function(data){
          console.log(data);
          hideLoading('btnGuardarCuenta', htmlOriginal);
          let c_id = ( accounting.unformat($("#c_id").val()) > 0)?"editado":"creado";
          if(data.success){
            alertify.success("Registro "+c_id+" correctamente.");
            setTimeout(function(){
              location.href="cuentasxcobrar.php?expedienteId="+data.casoId+"";
            }, 1500);
          }else{
            alertify.error("El registro no fue "+c_id+", intentar nuevamente.");
          }
        });
    }else if(planPagos == 4 && planPagosGet ==1){//Pasar de plan de pagos 4 al 1
      var urlDel = '../ajaxcall/ajaxFunctions.php?funct=cambiarTipo4a1';
      params.append("totalJson", totalJson);
  
      ajaxDataPost(urlDel, params, function(data){
          console.log(data);
          hideLoading('btnGuardarCuenta', htmlOriginal);
          let c_id = ( accounting.unformat($("#c_id").val()) > 0)?"editado":"creado";
          if(data.success){
            alertify.success("Registro "+c_id+" correctamente.");
            setTimeout(function(){
              location.href="cuentasxcobrar.php?expedienteId="+data.casoId+"";
            }, 1500);
          }else{
            alertify.error("El registro no fue "+c_id+", intentar nuevamente.");
          }
        });
    }else if(planPagos == 4 && planPagosGet ==2){//Pasar de plan de pagos 4 al 2
      var urlDel = '../ajaxcall/ajaxFunctions.php?funct=cambiarTipo4a2';
      params.append("totalJson", totalJson);
  
      ajaxDataPost(urlDel, params, function(data){
          console.log(data);
          hideLoading('btnGuardarCuenta', htmlOriginal);
          let c_id = ( accounting.unformat($("#c_id").val()) > 0)?"editado":"creado";
          if(data.success){
            alertify.success("Registro "+c_id+" correctamente.");
            setTimeout(function(){
              location.href="cuentasxcobrar.php?expedienteId="+data.casoId+"";
            }, 1500);
          }else{
            alertify.error("El registro no fue "+c_id+", intentar nuevamente.");
          }
        });
    }else if(planPagos == 4 && planPagosGet ==3){//Pasar de plan de pagos 4 al 3
      var urlDel = '../ajaxcall/ajaxFunctions.php?funct=cambiarTipo4a3';
      params.append("totalJson", totalJson);
  
      ajaxDataPost(urlDel, params, function(data){
          console.log(data);
          hideLoading('btnGuardarCuenta', htmlOriginal);
          let c_id = ( accounting.unformat($("#c_id").val()) > 0)?"editado":"creado";
          if(data.success){
            alertify.success("Registro "+c_id+" correctamente.");
            setTimeout(function(){
              location.href="cuentasxcobrar.php?expedienteId="+data.casoId+"";
            }, 1500);
          }else{
            alertify.error("El registro no fue "+c_id+", intentar nuevamente.");
          }
        });
    }
    else{
      var urlDel = '../ajaxcall/ajaxFunctions.php?funct=guardarCuenta';
      ajaxDataPost(urlDel, params, function(data){
        console.log(data);
        hideLoading('btnGuardarCuenta', htmlOriginal);

        let c_id = ( accounting.unformat($("#c_id").val()) > 0)?"editado":"creado";
        if(data.success){
          alertify.success("Registro "+c_id+" correctamente.");
          setTimeout(function(){
            location.href="cuentasxcobrar.php?expedienteId="+data.casoId+"";
          }, 1500);
        }else{
          alertify.error("El registro no fue "+c_id+", intentar nuevamente.");
        }
      });
    }
  }else{
    validator.focusInvalid();
    return false;
  }
}

function guardarPago(){
  var validator = $("#formPago").validate({ });
  // btnCreaEditaAccion
  
  if($("#formPago").valid()){
    var htmlOriginal = showLoading('btnGuardarPago');

    // var datosForm = $("#formPago").serializeJSON();
    // console.log(datosForm);
    // params = paramsB64(datosForm);
    var formElement = document.getElementById("formPago");
    var params = new FormData(formElement);
    // params['funct'] = 'crearCaso';

    var file1 = $('input#reciboPago')[0].files[0];
    if(typeof file1 !== 'undefined'){
      params.append('reciboPago', file1);
    }

    var urlDel = '../ajaxcall/ajaxFunctions.php?funct=guardarPago';
    ajaxDataPost(urlDel, params, function(data){
    // ajaxData(params, function(data){
      console.log(data);
      hideLoading('btnGuardarPago', htmlOriginal);
      $('.modal').modal('hide');
      pagos.refresh();
      pagos.commit();

      if(data.success){
        $("#spanSaldo").html(data.saldo);
        alertify.success("Registro guardado correctamente.");
      }else{
        alertify.error("El registro no fue guardado, intentar nuevamente.");
      }

      setTimeout(function(){
        location.reload();
      }, 3000);
    });
  }else{
    validator.focusInvalid();
    return false;
  }
}

function agregarCobro(){
  let cont = $(".rowCobro").length + 1;
  if (cont == 0) {
    cont = cont+1;
  } 
  let restante = accounting.unformat($("#restante").val());
  let modoCobro   = $("#planPagos").val();

  let continuar = true;
  if(modoCobro == 1 || modoCobro == 4){//monto fijo pago unico, y monto fijo en pagos, solo pueden agregar si hay restante, en los otros dos casos pueden seguir agregando ya que no se sabe el total que se va a cobrar
    if(restante == 0){
      continuar = false;
    }
  }

  if(continuar){
    let html = '';
    let htmlCampo = ``;
    let htmlCampo2 = ``;
    if(modoCobro == 3){
      htmlCampo2 = `
      <input type="text" class="form-control text-right inputCobro avance" id="avance_${cont}" name="avance_${cont}" value="" placeholder="avance">
      `;
    }if(modoCobro == 2){nameColumn="Mes"}else{nameColumn="Cobro"}
    // else{
      htmlCampo = `<input type="text" class="form-control inputCobro inputfechaGral" id="fechacobro_${cont}" name="fechacobro_${cont}" value="" readonly="" style="width:87%;display:inline-block;">`;
    // }
    html = `
    <div class="row rowCobro" id="rowCobro_${cont}">
      <input type="hidden" name="modoCobro_${cont}" id="modoCobro_${cont}" value="${modoCobro}">
      <div class="col-md-2 text-right">
        <input type="text" readonly="" class="form-control" name="label_${cont}" id="label_${cont}" value="${nameColumn} ${cont}">
      </div>
      <div class="col-md-3">
        <input type="text" class="form-control inputCobro cobro text-right required" id="cobro_${cont}" name="cobro_${cont}" placeholder="Cobro ${cont}" value="" onchange="actualizaRestante(${cont}, '${modoCobro}')">
      </div>
      <div class="col-md-3">
        ${htmlCampo}
      </div>
      <div class="col-md-3">
        ${htmlCampo2}
      </div>
      <div class="col-md-1">
          <a onclick="eliminarCobro(${cont})" class="eliminarCobro cursorPointer" title="Eliminar cobro">
          <span class="material-icons" style="font-size:12px;">clear</span>
          </a>
      </div>
    </div>
    `;
  
    $("#divCobros").append(html);
    ActiveDatePicker();
  }else{
    alertify.warning("No hay monto restante.");
  }
}

function eliminarCobro(cont, idCuenta, planPagos){
  //$("#rowCobro_"+cont).remove();
  id = cont -1;
  alertify.confirm("<strong> Desea borrar este cobro?</strong>", function(){
    let params = {funct: 'eliminarCobro', id:id, idCuenta:idCuenta, planPagos:planPagos};
    ajaxData(params, function(data){
      if(data.success){
        alertify.success("Cobro eliminado.");
        setTimeout(() => {
          location.reload();
        }, 2000);
      }
    });
  },function(){
  }).set({labels:{ok:'Aceptar', cancel: 'Cancelar'}, padding: false});
}


function guardarCasosAsociados(){
  var htmlOriginal = showLoading('btnGuardarCasosAsociados');
  var formElement = document.getElementById("formCasosAsociadosCta");
  var params = new FormData(formElement);
  
  var urlDel = '../ajaxcall/ajaxFunctions.php?funct=guardarCasosAsociadosCta';
    ajaxDataPost(urlDel, params, function(data){

      console.log(data);
      hideLoading('btnGuardarCasosAsociados', htmlOriginal);
      
      $('.modal').modal('hide');

      if(data.success){
        alertify.success("Casos asociados correctamente.");
      }else{
        alertify.error("El registro no fue guardado, intentar nuevamente.");
      }

      setTimeout(function(){
        location.reload();
      }, 1000);
    });
}

function actualizaRestante(cont, planPagos){
  console.log(planPagos);
  if(planPagos != 2 && planPagos != 3){
    let valor = accounting.unformat($("#cobro_"+cont).val());
    let restante = accounting.unformat($("#restante").val());
    let montoIncial = accounting.unformat($("#montoAux").val());
    if(valor > restante ){
      $("#cobro_"+cont).val('');
      alertify.warning("El valor ingresado es mayor al restante.");
    }
    else{
      let total = 0;
      $(".cobro").each(function(index, el) {
        let valor = accounting.unformat($(this).val());
        total += valor;
        $(this).val(accounting.formatMoney(valor))
      });
      restante = accounting.unformat($("#monto").val()) - total-montoIncial;
    $("#restante").val(accounting.formatMoney(restante));
    }
  }
}

function guardarCobros(planPagos){
  let cont = $(".rowCobro").length;
  var validator = $("#formCobro").validate({ });
  if(cont == 0){
    alertify.warning("No hay cobros agregados.");
  }else{
    console.log("numero de cobros: "+cont);
    // return false;
      if($("#formCobro").valid()){
        var htmlOriginal = showLoading('btnGuardarCobros');

        // var datosForm = $("#formCobro").serializeJSON();
        // console.log(datosForm);
        // params = paramsB64(datosForm);
        var formElement = document.getElementById("formCobro");
        var params = new FormData(formElement);
        // params['funct'] = 'crearCaso';
        params.append('cuentaId', $("#idCuenta").val());
        params.append('cont', cont);
        params.append('planPagos', planPagos);
        // console.log(params);
        // return false;
        var urlDel = '../ajaxcall/ajaxFunctions.php?funct=guardarCobros';
        ajaxDataPost(urlDel, params, function(data){
        // ajaxData(params, function(data){
          console.log(data);
          hideLoading('btnGuardarCobros', htmlOriginal);

          if(data.success){
            alertify.success("Cobros guardados correctamente.");
          }else{
            alertify.error("No fue guardado, intentar nuevamente.");
          }

          setTimeout(function(){
            location.reload();
          }, 1000);
        });
    }else{

    }
  }
}

function preparaPlanPagos(){
  let modoCobro   = $("#planPagos").val();
  let montoInicial = accounting.unformat($("#monto").val());
  $(".rowNumCobros").removeClass('oculto');
  $(".rowNumCobros").addClass('oculto');
  let fechaHoy = $("#fechaHoy").val();

  if(modoCobro == 1){//una sola exhibicion
    $("#btnAgregarCobro").trigger("click");
    $("#btnAgregarCobro").addClass('fondoGris');
    $("#cobro_1").val(accounting.formatMoney(montoInicial));
    $("#fechacobro_1").val(fechaHoy);
    $("#label_1").val("Monto fijo");
  }else if(modoCobro == 2){//pago inicial y recurrentes
    $("#btnAgregarCobro").removeClass('fondoGris');
    $(".rowNumCobros").removeClass('oculto');
    
    let arrFechas = fechasCobros.split(",");
    let montoAux = accounting.unformat($("#montoAux").val());
    let cont = 1;
    for (let fechaItem of arrFechas) {
      $("#btnAgregarCobro").trigger("click");
      if(cont == 1){
        $("#cobro_"+cont).val(accounting.formatMoney(montoInicial));
        $("#label_"+cont).val("Monto inicial");
      }else{
        $("#cobro_"+cont).val(accounting.formatMoney(montoAux));
        $("#label_"+cont).val("Mes "+(cont-1));
      }
      $("#fechacobro_"+cont).val(fechaItem);
      cont++;
    }
  }
  else if(modoCobro == 3){//pagos sobre avances
    $("#btnAgregarCobro").trigger("click");
    $("#cobro_1").val(accounting.formatMoney(montoInicial));
    $("#fechacobro_1").val(fechaHoy);
    $("#label_1").val("Monto inicial");
    // $("#fechacobro_1").val($("#avance").val());
  }
  else if(modoCobro == 4){//plan de pagos
    let montoInicial = accounting.unformat($("#montoAux").val());
    // $("#btnAgregarCobro").trigger("click");
    // $("#cobro_1").val(accounting.formatMoney(montoInicial));
    // $("#cobro_1").trigger("change");
  }
}

function cambiaPlanPagos(modoCobro){
  // if(modoCobro == 3){
  //   $(".rowAvance").removeClass('oculto');
  // }else{
  //   $(".rowAvance").removeClass('oculto');
  //   $(".rowAvance").addClass('oculto');
  // }
  modoCobro = parseInt(modoCobro);

  let textoLabel = '';
  let textoLabelAux = '';

  switch (modoCobro) {
    case 1: textoLabel = 'Monto fijo'; break;
    case 2: textoLabel = 'Monto inicial'; break;
    case 3: textoLabel = 'Monto inicial'; break;
    case 4: textoLabel = 'Monto fijo'; break;
  }

  if(modoCobro == 3 || modoCobro == 1){
    textoLabelAux = 'Monto aux';
    $(".rowMontoAux").removeClass('oculto');
    $(".rowMontoAux").addClass('oculto');

    $("#numMeses").removeClass('required');
    $("#diaCobro").removeClass('required');
    
  }

  if(modoCobro == 2){
    textoLabelAux = 'Monto mensualidades';
    $(".rowMontoAux").removeClass('oculto');
    $("#numMeses").removeClass('required');
    $("#numMeses").addClass('required');
    $("#diaCobro").removeClass('required');
    $("#diaCobro").addClass('required');
  }

  if(modoCobro == 4){
    textoLabelAux = 'Monto inicial';
    $(".rowMontoAux").removeClass('oculto');
    $(".rowMontoAux").addClass('oculto');
    // $(".rowMontoAuxMain").removeClass('oculto');
    $("#numMeses").removeClass('required');
    $("#diaCobro").removeClass('required');
  }

  $("#spanLabelMonto").html(textoLabel);
  $("#spanLabelMontoAux").html(textoLabelAux);
}

function cambiaNumCobros(numCobros){
  let montoInicial = accounting.unformat($("#monto").val());
  numCobros = accounting.unformat(numCobros);
  $("#divCobros").html('');
  for (let index = 0; index < numCobros; index++) {
    $("#btnAgregarCobro").trigger("click");
  }
  $("#cobro_1").val(accounting.formatMoney(montoInicial));
  $("#btnAgregarCobro").addClass('fondoGris');
  $("#numcobros").attr('readonly', true);
}

function muestraEditarCliente(){
  var idCliente = $("#c_idcliente").val();
  reseteaFormulario("formCrearCliente");

  if(idCliente > 0){
    var params = {
      'funct': 'muestraEditarCliente',
      'idCliente' : idCliente
    };
    ajaxData(params, function(data){
      console.log(data);
      $("#popup_modalCrearCliente").modal('show');
      if(data.success){
        console.log(data.cliente);
        $("#pc_cliente").val(data.cliente.nombre);
        $("#pc_tel").val(data.cliente.telefono);
        $("#pc_email").val(data.cliente.email);
        $("#pc_dir").val(data.cliente.direccion);
        $("#pc_aka").val(data.cliente.aka);
        $("#clienteEditId").val(idCliente);
        
      }
    });
  }else{
    alertify.warning("No se ha seleccionado un cliente.");
  }
}

function asignaValorCampo(campo, valor){
  $("#"+campo).val(valor);
}

function cambiaMetodo(){
  //jquery attr data option selected value
  let requiere = $("#metodoId").find(':selected').attr('data-requierebanco');
console.log(requiere);
  if(requiere == 1){
    $("#bancoId").removeClass('required');
    $("#bancoId").addClass('required');
  }else{
    $("#bancoId").removeClass('required');
  }

}

function inicializaAgregarPAgo(tipo){
  $("#tipoPago").val(tipo);
  if(tipo == 1){
    $("#spanComentarios").html('Comentarios:');
  }else{
    $("#spanComentarios").html('Concepto:');
  }
}

//Función para resetear los filtros en
//pantalla de expedientes
async function resetFilt(btn){
  $(btn).addClass('spinicon');
  await new Promise(r => setTimeout(r, 200));
  var btnID = $(btn).attr("id");
  var idReset = btnID.replace('rs_', '');
  $('#'+idReset).selectpicker('deselectAll');
  $('#'+idReset).selectpicker('refresh');
  $(btn).removeClass('spinicon');
}


function btnCreaEditaAccionRelod() { //LDAH 16/08/2022 IMP Boton guardar y nuevo
  var validator = $("#formCrearAccion").validate({ignore: ':hidden:not(.required)', });
  console.log("si voy a editar");  
  tinyMCE.triggerSave();
  $('#pa_comentario').val(tinyMCE.get('pa_comentario').getContent());
  $('#pa_internos').val(tinyMCE.get('pa_internos').getContent());
  $('#pa_reporte').val(tinyMCE.get('pa_reporte').getContent());
  var valorAviso = $("#pa_comentario").val();
  var internos = $("#pa_internos").val();
  var reporte = $("#pa_reporte").val();
  // $("#valorAviso").val("");
  $("#pa_comentario_hd").val($.base64.encode(valorAviso));
  $("#pa_internos_hd").val($.base64.encode(internos));
  $("#pa_reporte_hd").val($.base64.encode(reporte));
  //Validar formulario
  if($("#formCrearAccion").valid()){
    var htmlOriginal = showLoading('btnCrearTipoRelod');
    $('#pa_comentario').val("");
    $('#pa_internos').val("");
    $('#pa_reporte').val("");
    var formElement = document.getElementById("formCrearAccion");
    var params = new FormData(formElement);
    var urlDel = '../ajaxcall/ajaxFunctions.php?funct=creaEditaAccion';
    console.log("Termine de validar voy a dar post");    
    ajaxDataPost(urlDel, params, function(data){
      console.log(data);
      $('.modal').modal('hide');
      if(data.success){
        if(data.opc == "crear"){
          console.log("crear");
          if(data.idaccion > 0){
            console.log(data.idaccion);
            alertify.success("Cambios guardados.");
            location.reload();
          }else{
          }
        }else{
          console.log("edit");
          if(data.resp > 0){
            console.log(data.resp);
            alertify.success("Cambios guardados.");
            location.reload();
          }
        }
      }else{
        alertify.error("El registro no fue creado, intentar nuevamente.");
      }
    });
  }else{
    validator.focusInvalid();
    return false;
  }
}
//LDAH IMP 23/08/2022 Filtros en historico
function filtrarExpedientesHis(){
  let responsables = ($("#fil_responsableId").val() != null)?$("#fil_responsableId").val():'';
  let clientes = ($("#fil_clienteId").val() != null)?$("#fil_clienteId").val():'';
  let estatus = ($("#fil_estatusId").val() != null)?$("#fil_estatusId").val():'';
  let juicios = ($("#fil_juicioId").val() != null)?$("#fil_juicioId").val():'';
  let juzgados = ($("#fil_juzgadoId").val() != null)?$("#fil_juzgadoId").val():'';
  let materias = ($("#fil_materiaId").val() != null)?$("#fil_materiaId").val():'';
  let camposIds = ($("#fil_campos").val() != null)?$("#fil_campos").val():'';
  let clientesno = ($("#fil_clientenoId").val() != null)?$("#fil_clientenoId").val():'';
  let representado = ($("#fil_representadoId").val() != null)?$("#fil_representadoId").val():'';
  let mostrar = ($("#fil_mostrar").val() != null)?$("#fil_mostrar").val():'';//Mostrar campos titular
  let camposGrid = ($("#fil_camposgrid").val() != null)?$("#fil_camposgrid").val():'';//Mostrar campos grid
  console.log(clientesno);
  let filtros = [];
  if(responsables != ''){
    filtros.push('responsables='+responsables);
  }
  if(clientes != ''){
    filtros.push('clientes='+clientes);
  }
  if(estatus != ''){
    filtros.push('estatus='+estatus);
  }
  if(materias != ''){
    filtros.push('materias='+materias);
  }
  if(juicios != ''){
    filtros.push('juicios='+juicios);
  }
  if(juzgados != ''){
    filtros.push('juzgados='+juzgados);
  }
  if(mostrar != ''){
    filtros.push('mostrarCamposTitular='+mostrar);
  }
  if(camposIds != ''){
    filtros.push('camposIds='+camposIds);
  }
  if(clientesno !=''){
    filtros.push('clientesno='+clientesno)
  }

 if(representado !=''){
    filtros.push('representado='+representado)
  }

  if(camposGrid != ''){
    filtros.push('mostrarCamposGrid='+camposGrid);
  }

  if(filtros.length > 0){
    location.href = "historico.php?"+filtros.join("&");
  }else{
    alertify.warning("No ha seleccionado ningun filtro");
  }
}

// Popup para crear cita simple
function btnCrearCitaSimple() {
  var validator = $("#formCrearCitaSimple").validate({ });
  let citaSimpleId = $("#citaSimpleEditId").val();
  //Validar formulario
  if($("#formCrearCitaSimple").valid()){

    var datosForm = $("#formCrearCitaSimple").serializeJSON();
    // console.log(datosForm);
    params = paramsB64(datosForm);
    params['funct'] = 'crearCitaSimple';
    console.log(params);
    // return false;
    ajaxData(params, function(data){
      console.log(data);

      $('.modal').modal('hide');
      if(data.success){
        if(citaSimpleId > 0){
          alertify.success("Registro editado correctamente.");
          clearForm("formCrearCitaSimple");
        }else{
          alertify.success("Registro creado correctamente.");
          clearForm("formCrearCitaSimple");
        }
        setTimeout(function(){
          location.reload();
        }, 1500);
        $("#citaSimpleEditId").val(data.idAccion);
      }else{
        alertify.error("El registro no fue creado, intentar nuevamente.");
      }
    });
  }else{
    validator.focusInvalid();
    return false;
  }
}

// Popup para crear cita Social
function btnCrearCitaSocial() {
  var validator = $("#formCrearCitaSocial").validate({ });
  let citaSimpleId = $("#citaSocialEditId").val();
  //Validar formulario
  if($("#formCrearCitaSocial").valid()){

    var datosForm = $("#formCrearCitaSocial").serializeJSON();
    // console.log(datosForm);
    params = paramsB64(datosForm);
    params['funct'] = 'crearCitaSocial';
    console.log(params);
    // return false;
    ajaxData(params, function(data){
      console.log(data);

      $('.modal').modal('hide');
      if(data.success){
        if(citaSimpleId > 0){
          alertify.success("Registro editado correctamente.");
          clearForm("formCrearCitaSocial");
        }else{
          alertify.success("Registro creado correctamente.");
          clearForm("formCrearCitaSocial");
        }
        setTimeout(function(){
          location.reload();
        }, 1500);
        $("#citaSocialEditId").val(data.idAccion);
      }else{
        alertify.error("El registro no fue creado, intentar nuevamente.");
      }
    });
  }else{
    validator.focusInvalid();
    return false;
  }
}

/**function eliminarCita(idAccion) {
  console.log(idAccion);
  var params = {
    funct: 'eliminarCitaSimple',
    idAccion:  idAccion,
  };
  ajaxData(params, function(data){
    console.log(data);
    if(data.success){
      alertify.success("Cita eliminada.");
      clearForm("formCrearCitaSimple");
      alertify.closeAll();
      setTimeout(function(){
        location.reload();
      }, 1500);
  }
  else{
    alertify.error("Error realizando esta accion","Error");
  }
  });
  
}*/
function eliminarCita(idAccion) {
  // Muestra una ventana de confirmación antes de continuar
  if (confirm("¿Estás seguro de que deseas eliminar esta cita?")) {
    var params = {
      funct: 'eliminarCitaSimple',
      idAccion:  idAccion,
    };
    ajaxData(params, function(data){
      console.log(data);
      if(data.success){
        alertify.success("Cita eliminada.");
        clearForm("formCrearCitaSimple");
        alertify.closeAll();
        setTimeout(function(){
          location.reload();
        }, 1500);
      } else {
        alertify.error("Error realizando esta accion","Error");
      }
    });
  }
}

function btnCrearPago() {
  var validator = $("#formCrearPago").validate({ });
  let citaSimpleId = $("#pagoEditId").val();
  //Validar formulario
  if($("#formCrearPago").valid()){

    var datosForm = $("#formCrearPago").serializeJSON();
    console.log(datosForm);
    params = paramsB64(datosForm);
    params['funct'] = 'crearPagoAgenda';
    console.log(params);
    //return false;
    ajaxData(params, function(data){
      console.log(data);

      $('.modal').modal('hide');
      if(data.success){
        if(citaSimpleId > 0){
          alertify.success("Registro editado correctamente.");
          clearForm("formCrearPago");
        }else{
          alertify.success("Registro creado correctamente.");
          clearForm("formCrearPago");
        }
        setTimeout(function(){
          location.reload();
        }, 1500);
        $("#pagoEditId").val(data.idAccion);
      }else{
        alertify.error("El registro no fue creado, intentar nuevamente.");
      }
    });
  }else{
    validator.focusInvalid();
    return false;
  }
}

function btnCrearCobro() {
  var validator = $("#formCrearCobro").validate({ });
  let citaSimpleId = $("#cobroEditId").val();
  //Validar formulario
  if($("#formCrearCobro").valid()){

    var datosForm = $("#formCrearCobro").serializeJSON();
    console.log(datosForm);
    params = paramsB64(datosForm);
    params['funct'] = 'crearCobroAgenda';
    console.log(params);
    //return false;
    ajaxData(params, function(data){
      console.log(data);

      $('.modal').modal('hide');
      if(data.success){
        if(citaSimpleId > 0){
          alertify.success("Registro editado correctamente.");
          clearForm("formCrearCobro");
        }else{
          alertify.success("Registro creado correctamente.");
          clearForm("formCrearCobro");
        }
        setTimeout(function(){
          location.reload();
        }, 1500);
        $("#cobroEditId").val(data.idAccion);
      }else{
        alertify.error("El registro no fue creado, intentar nuevamente.");
      }
    });
  }else{
    validator.focusInvalid();
    return false;
  }
}

function eliminarPago(idAccion) {
  // Muestra una ventana de confirmación antes de continuar
  if (confirm("¿Estás seguro de que deseas eliminar esta el pago?")) {
    var params = {
      funct: 'eliminarCitaSimple',
      idAccion:  idAccion,
    };
    ajaxData(params, function(data){
      console.log(data);
      if(data.success){
        alertify.success("Pago eliminado.");
        clearForm("formCrearCitaSimple");
        alertify.closeAll();
        setTimeout(function(){
          location.reload();
        }, 1500);
      } else {
        alertify.error("Error realizando esta accion","Error");
      }
    });
  }
}

function eliminarPagoSerie(idAccion) {
  // Muestra una ventana de confirmación antes de continuar
  if (confirm("¿Estás seguro de que deseas eliminar los pagos en serie?")) {
    var params = {
      funct: 'eliminarPagoSerie',
      idAccion:  idAccion,
    };
    ajaxData(params, function(data){
      console.log(data);
      if(data.success){
        alertify.success("Pagos eliminados.");
        clearForm("formCrearCitaSimple");
        alertify.closeAll();
        setTimeout(function(){
          location.reload();
        }, 1500);
      } else {
        alertify.error("Error realizando esta accion","Error");
      }
    });
  }
}
function deleteCliente(idCliente){
  if (confirm("¿Estás seguro de que deseas eliminar este el cliente?")) {
    var params = {
      funct: 'eliminarCliente',
      idCliente:  idCliente,
    };
    ajaxData(params, function(data){
      console.log(data.success);
      if(data.success){
        alertify.success("Cliente eliminado.");
        alertify.closeAll();
        setTimeout(function(){
          location.reload();
        }, 1500);
      } else if(data.success) {
        alertify.error("Error realizando esta accion","Error");
      }
    });
  }
}

//Setea los datos para editar una cita 
function editarCita(){
  alertify.closeAll();
  $('#popup_modalCrearCitaSimple').modal('show');
}

function editarCitaSocial(){
  alertify.closeAll();
  $('#popup_modalCrearCitaSocial').modal('show');
}

//pop up para ver proximos eventos de un expediente
function mostrarEventos(idCaso){
  console.log("idCaso:", idCaso);
  alertify.closeAll();
  $('#row-proxEventos').html("");
  $('#popup_modalVerEventos').modal('show');

  var params = {
    funct: 'obtProxEventosCaso',
    idCaso:  idCaso
  };

  ajaxData(params, function(data){

      if(data.success){
          $('#row-proxEventos').html(data.html);
      }
      else{
        alertify.error("Error realizando esta accion","Error");
      }
  });

}


function mostrarArbol(idCaso,idCasoClic){
  console.log("idCaso:", idCasoClic);
  alertify.closeAll();
  $('#treemain').html("");
  $('#popup_modalArbolCasos').modal('show');
  var params = {
    funct: 'obtArbolCaso',
    idCaso:  idCaso,
    idCasoClic: idCasoClic
  };
  ajaxData(params, function(data){
    console.log(data);
      if(data.success){
          $('#treemain').html(data.html);
          setTimeout(function(){
            console.log("espero el pop");
            IniciarArbol();
          }, 500);
      }
      else{
        alertify.error("Error realizando esta accion","Error");
      }
  });
}
function printModalContent() {
  var myButton = document.getElementById("print-modal-content");
  myButton.style.display = "none";

  setTimeout(() => {
    window.print();
    myButton.style.display = "block";
  }, 500);

}
//iniciaizador del arbol
function IniciarArbol(){
   // -- init -- //
    jsPlumb.ready(function() {

      // connection lines style
      var connectorPaintStyle = {
          lineWidth:3,
          strokeStyle:"#4F81BE",
          joinstyle:"round"
      };

      var pdef = {
          // disable dragging
          DragOptions: null,
          // the tree container
          Container : "treemain"
      };
      var plumb = jsPlumb.getInstance(pdef);

      // all sizes are in pixels
      var opts = {
          prefix: 'node_',
          // left margin of the root node
          baseLeft: 24,
          // top margin of the root node
          baseTop: 24,
          // node width
          nodeWidth: 100,
          // horizontal margin between nodes
          hSpace: 36,
          // vertical margin between nodes
          vSpace: 10,
          imgPlus: '../images/tree_expand.png',
          imgMinus: '../images/tree_collapse.png',
          // queste non sono tutte in pixel
          sourceAnchor: [ 1, 0.5, 1, 0, 10, 0 ],
          targetAnchor: "LeftMiddle",
          sourceEndpoint: {
              endpoint:["Image", {url: "../images/tree_collapse.png"}],
              cssClass:"collapser",
              isSource:true,
              connector:[ "Flowchart", { stub:[40, 60], gap:[10, 0], cornerRadius:5, alwaysRespectStubs:false } ],
              connectorStyle:connectorPaintStyle,
              enabled: false,
              maxConnections:-1,
              dragOptions:null
          },
          targetEndpoint: {
              endpoint:"Blank",
              maxConnections:-1,
              dropOptions:null,
              enabled: false,
              isTarget:true
          },
          connectFunc: function(tree, node) {
              var cid = node.data('id');
              console.log('Connecting node ' + cid);
          }
      };
      var tree = jQuery.jsPlumbTree(plumb, opts);
      tree.init();
      window.treemain = tree;
  });
}

function positioningBlockBug() {
  var oldNode = window.treemain.nodeById(2);
  //var newNode = $('#node_2_new');
  var newNode = $('    <div id="node_2" class="window hidden"\n' +
      '         data-id="2"\n' +
      '         data-parent="0"\n' +
      '         data-first-child="6"\n' +
      '         data-next-sibling="3">\n' +
      '        Node 2 NEW\n' +
      '    </div>\n');
  if (oldNode) {
      // butta il nodo nel container
      oldNode.replaceWith(newNode);
      // rimostra il nodo
      newNode.id = 'node_2';
      newNode.show();
      // aggiorna l'albero
      window.treemain.update();
  }

}

function validarPadre(idCaso){

  var idPadre = $("#idPadre").val();

  //Validamos sea un número
  if(isNaN(idPadre)){
    alertify.error("Debes ingresar solo un ID de expediente (númerico)","Error");
  }
  //imp. CMPB 10/03/2023
  /**if(idPadre>idCaso){
    alertify.error("El id que ingreso es incorrecto","Error");
    return
  }*/
  var htmlOriginal = showLoading("validar_padre");
  var params = {
    funct: 'reasignarPadreId',
    idCaso:  idCaso,
    idPadre: idPadre
  };

  ajaxData(params, function(data){
    console.log(data);
      if(data.success){
        $("#divCasoArbol").load();
         hideLoading('validar_padre', htmlOriginal);
         $("#idPadre").attr('readonly', true);
         $("#idPadre").val(idPadre);
         $("#validar_padre").hide();
         $("#editar_padre").show();
         $("#ver_arbol").show();
         $("#eliminarRelacionPadre").show();
         $("#cancelar_eliminar").hide();
         alertify.success("Id padre registrado corretamente."); 
      }
      else{
        alertify.error("Error realizando esta accion","Error");
      }
  });
}

//>>>>CMPB, 03/02/2023, cambios para eliminar nodos con hijos o sin hijos
function reasignarPadre(idCaso){
  var idPadre = $("#idPadre").val();
  //Validamos sea un n�mero
  if(isNaN(idPadre)){
    alertify.error("Debes ingresar solo un ID de expediente (n�merico)","Error");
  }
  if(idPadre>idCaso){
    alertify.error("El id que ingreso es incorrecto","Error");
    return
  }
  var htmlOriginal = showLoading("validar_padre");
  var params = {
    funct: 'salvarPadreId',
    idCaso:  idCaso,
    idPadre: idPadre
  };
  ajaxData(params, function(data){
      if(data.success){
        //window.setTimeout('location.reload()', 500);
         hideLoading('validar_padre', htmlOriginal);
         $("#idPadre").attr('readonly', true);
         $("#idPadre").val(idPadre);
         $("#validar_padre").hide();
         $("#reasignar_padre").hide();
         $("#eliminar_Padre").hide();
         $("#editar_padre").show();
         $("#ver_arbol").show();
         $("#cancelar_eliminar").hide();
         $("#eliminarRelacionPadre").show();
         alertify.success("Reasignacion Correcta."); 
      }
      else{
        alertify.error("Error realizando esta accion","Error");
      }
  });
}

function eliminarDelPadre(idCaso){
  var idPadre = $("#idPadre").val();
  console.log(idPadre)

  //Validamos sea un n�mero
  if(isNaN(idPadre)){
    alertify.error("Debes ingresar solo un ID de expediente (n�merico)","Error");
  }
  var htmlOriginal = showLoading("validar_padre");
  var params = {
    funct: 'eliminarDelPadreId',
    idCaso:  idCaso,
    idPadre: idPadre
  };

  ajaxData(params, function(data){
      if(data.success){
         $("#idPadre").attr('readonly', false);
         $("#idPadre").val('');
         $("#validar_padre").show();
         $("#editar_padre").hide();
         $("#ver_arbol").hide();
         $("#reasignar_padre").hide();
         $("#eliminar_Padre").hide();
         $("#cancelar_eliminar").hide();
         $(".loadImg").hide();
        
         alertify.success("Reasignacion Correcta."); 
      }
      else{
        alertify.error("Error realizando esta accion","Error");
      }
  });
}

//habilita los controles para editar un ID Padre
function editarPadre (){
  $("#idPadre").attr('readonly', false);
  $("#validar_padre").show();
  $("#editar_padre").hide();
  $("#ver_arbol").hide();
  $("#eliminarRelacionPadre").hide();
  $("#cancelar_eliminar").show();
}

function eliminarRelacion(){
  $("#idPadre").attr('readonly', false);
  $("#validar_padre").hide();
  $("#editar_padre").hide();
  $("#ver_arbol").hide();
  $("#eliminarRelacionPadre").hide();
  $("#reasignar_padre").show();
  $("#eliminar_Padre").show();
  $("#cancelar_eliminar").show();
}

//CMPB 09/02/2023 agregar boton para cancelar acciones
function cancelarEliminar(){
  $("#idPadre").attr('readonly', true);
  $("#validar_padre").hide();
  $("#editar_padre").show();
  $("#ver_arbol").show();
  $("#eliminarRelacionPadre").show();
  $("#reasignar_padre").hide();
  $("#eliminar_Padre").hide();
  $("#cancelar_eliminar").hide();
}

/*****Audio*****/
let recorder;
let audioBlob;
let contador = 0;
let intervalo;

const startRecordingButton = document.getElementById('startRecordingButton');
const stopRecordingButton = document.getElementById('stopRecordingButton');
const playRecordingButton = document.getElementById('playRecordingButton');
const redCircle = document.getElementById('redCircle');
//const restartRecordingButton = document.getElementById('restartRecordingButton');
const saveRecordingButton = document.getElementById('saveRecordingButton');
const audioPreview = document.getElementById('audioPreview');
const reproductor = document.getElementById('audioPreview');

startRecordingButton.addEventListener('click', () => {
  reproductor.style.display = 'none';
  const contadorElemento = document.getElementById("contador");
  contador = 0;
  contadorElemento.innerText = contador;
  intervalo = setInterval(() => {
    contador++;
    contadorElemento.innerText = contador;
  }, 1000); // Actualiza el contador cada segundo

  navigator.mediaDevices.getUserMedia({ audio: true })
    .then(stream => {
      recorder = new MediaRecorder(stream);
      recorder.start();

      const chunks = [];
      recorder.addEventListener('dataavailable', event => {
        chunks.push(event.data);
      });
      
      var audio = document.getElementById("audio");
       audio.onloadeddata = function() {
          alert(audio.duration);
      };
      
      recorder.addEventListener('stop', () => {
        audioBlob = new Blob(chunks, { type: 'audio/mp3' });
        audioPreview.src = URL.createObjectURL(audioBlob);
        playRecordingButton.disabled = false;
        //restartRecordingButton.disabled = false;
        saveRecordingButton.disabled = false;
      });
      document.getElementById("redCircle").style.display = "block";
      startRecordingButton.disabled = true;
      stopRecordingButton.disabled = false;
    });
});

stopRecordingButton.addEventListener('click', () => {
  recorder.stop();
  startRecordingButton.innerHTML = 'Grabar de nuevo';
  reproductor.style.display = 'block';
  clearInterval(intervalo);
  const contadorElemento = document.getElementById("contador");
  const contadorDetenido = contador;
  contadorElemento.innerText = contadorDetenido;
  startRecordingButton.disabled = false;
  stopRecordingButton.disabled = true;
  saveRecordingButton.disabled = false;
  document.getElementById("redCircle").style.display = "none";
});

playRecordingButton.addEventListener('click', () => {
  audioPreview.play();
  document.getElementById("redCircle").style.display = "none";
});


saveRecordingButton.addEventListener('click', async () => {
  let idCaso = $("#audio_casoid").val();
  let idUsario = $("#audio_usuarioId").val();
  let descripcion = $("#notaVozDescripcion").val();
  let mostrarTitulares = $("#mostrarTitulares").val();
  //console.log(mostrarTitulares);
  let params = new FormData();
  params.append("idCaso", idCaso);
  params.append("idUsario", idUsario);
  params.append("descripcion", descripcion);
  params.append("audienciaTipo", mostrarTitulares);
  params.append('audio', audioBlob, 'audio.mp3');
  var urlDel='../ajaxcall/ajaxFunctions.php?funct=guardarAudio';
  ajaxDataPost(urlDel, params, function (data) {
    console.log(data);
    if (data.success) {
      alertify.success("Registro guardado correctamente.");
    } else {
      alertify.error("El registro no fue guardado, intentar nuevamente.");
    }
    setTimeout(function () {
      location.reload();
    }, 3000);
  });
})

function verificarExistenciaCliente() {
  alertify.confirm('¿Ha verificado la existencia del cliente? Recuerda que si el cliente ya existe se duplicara en la base de datos.', function(){
    document.getElementById('popup_modalCrearCliente').style.display = 'block';
  }, function(){
    // Si el usuario hace clic en "Cancelar" en el cuadro de diálogo, se cierra el modal.
    $('#popup_modalCrearCliente').modal('hide');
  }).set('labels', {ok:'Si', cancel:'No'});
}

function deleteAudio(idNota, url){
  console.log(idNota);
  console.log(url);
  if (confirm("¿Estás seguro de que deseas eliminar esta nota?")) {
    var params = {
      funct: 'eliminarNota',
      idNota:  idNota,
      url: url
    };
    ajaxData(params, function(data){
      console.log(data.success);
      if(data.success){
        alertify.success("Nota Eliminada.");
        alertify.closeAll();
        setTimeout(function(){
          location.reload();
        }, 1500);
      } else if(data.success) {
        alertify.error("Error realizando esta accion","Error");
      }
    });
  }
}

/*/const myAudios = document.querySelectorAll(".myAudio");
const playFastButton = document.getElementById("playFastButton");

playFastButton.addEventListener("click", () => {
  myAudios.forEach(audio => {
    audio.playbackRate = 1.5; 
    audio.play();
  });
});*/

const playFastButtons = document.querySelectorAll("#playFastButton");
playFastButtons.forEach((playFastButton) => {
  playFastButton.addEventListener("click", (event) => {
    const targetId = playFastButton.getAttribute("data-target");
    const audio = document.getElementById(targetId);
    audio.playbackRate = 1.5;
    audio.play();
  });
});

const velocidadNormal = document.querySelectorAll("#velocidadNormal");
velocidadNormal.forEach((playFastButton) => {
  playFastButton.addEventListener("click", (event) => {
    const targetId = playFastButton.getAttribute("data-target");
    const audio = document.getElementById(targetId);
    audio.playbackRate = 1;
    audio.play();
  });
});

const velocidadX2 = document.querySelectorAll("#velocidadX2");
velocidadX2.forEach((playFastButton) => {
  playFastButton.addEventListener("click", (event) => {
    const targetId = playFastButton.getAttribute("data-target");
    const audio = document.getElementById(targetId);
    audio.playbackRate = 2;
    audio.play();
  });
});

function cantidadMoneda(cantidad){
  let moneda = parseFloat(cantidad)
  let opciones = { style: 'currency', currency: 'USD' };
  let cantidadConComas = moneda.toLocaleString('en-US', opciones);
  console.log(cantidad);
  return cantidadConComas
}

//encender/apagar el check de nota de voz solo titular
function stateSoloVozTitular(status){
  $("#mostrarTitulares").prop('checked', status);
}


