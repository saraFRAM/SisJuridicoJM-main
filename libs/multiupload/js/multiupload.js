function multiUploader(config){
  
	this.config = config;
	this.items = "";
	this.all = []
	var self = this;
	
	multiUploader.prototype._init = function(){
		if (window.File && 
			window.FileReader && 
			window.FileList && 
			window.Blob) {		
			 var inputId = $("#"+this.config.form).find("input[type='file']").eq(0).attr("id");
			 document.getElementById(inputId).addEventListener("change", this._read, false);
			 document.getElementById(this.config.dragArea).addEventListener("dragover", function(e){ e.stopPropagation(); e.preventDefault(); }, false);
			 document.getElementById(this.config.dragArea).addEventListener("drop", this._dropFiles, false);
			 document.getElementById(this.config.form).addEventListener("submit", this._submit, false);
		} else
			console.log("Browser supports failed");
	}
	
	multiUploader.prototype._submit = function(e){
		e.stopPropagation(); e.preventDefault();
		self._startUpload();

		//ocultar boton de subir archivos
		$("#submitHandler").hide();
		$("#loadingImg").show();

		/*//Validar antes de avanzar 
		if($("#anio").val()!='' && $("#mes").val()!='' && $("#empresa").val()!=''){
			e.stopPropagation(); e.preventDefault();
			self._startUpload();

			//ocultar boton de subir archivos
			$("#submitHandler").hide();
			$("#loadingImg").show();
		}*/
	}
	
	multiUploader.prototype._preview = function(data){
		this.items = data;
		if(this.items.length > 0){
			var html = "";		
			var uId = "";
                        var fileNotValid = [];
                        var msgNotValid = "";
                        
 			for(var i = 0; i<this.items.length; i++){
				uId = this.items[i].name._unique();
				arrUnique.push(uId);
				arrFileNameOriginal.push(this.items[i].name);

				var sampleIcon = '<img src="../images/image.png" />';
				var errorClass = "";
                                
				if(typeof this.items[i] != undefined){
					if(self._validate(this.items[i].type) <= 0) {
						var errorClassfile = 'style="background:red; color:#fff;"';
						sampleIcon = '<img src="../images/unknown.png" />';
						html += '<div class="dfiles" '+errorClassfile+' rel="'+uId+'"><h5>'+sampleIcon+this.items[i].name+'</h5><div id="'+uId+'" class="progress" style="display:none;"><img src="../images/ajax-loader.gif" /></div></div>';
                                                
                        fileNotValid.push('error');
					}else{
						var nombre = this.items[i].name;
						var nombreSeparado = nombre.split("_");
						var tituloPago = nombreSeparado[1] + "_" + nombreSeparado[2] + "_" + nombreSeparado[3];
						tituloPago = tituloPago.toLowerCase();

						if (nombreSeparado.length != 7 || tituloPago.indexOf("pago_de_n") != 0)
						{
							var errorClassfile = 'style="background:red; color:#fff;"';
							sampleIcon = '<img src="../images/unknown.png" />';
							html += '<div class="dfiles" ' + errorClassfile + ' rel="' + uId + '"><h5>' + sampleIcon + this.items[i].name + '</h5><div id="' + uId + '" class="progress" style="display:none;"><img src="../images/ajax-loader.gif" /></div></div>';

							fileNotValid.push('error');
						} else {
							html += '<div class="dfiles'+errorClass+'" rel="'+uId+'"><h5>'+sampleIcon+this.items[i].name+'</h5><div id="'+uId+'" class="progress" style="display:none;"><img src="../images/ajax-loader.gif" /></div></div>';
						}
					}
				}				
			}
			$("#dragAndDropFiles").append(html);
                        
                        //Mostrar mensaje si existen archivos incorrecto
                        if(fileNotValid.length>0){
                            msgNotValid += "<div><strong>Nota</strong>: Por favor verificar los archivos que no cumplen con los requisitos, revisar la lista están marcados en rojo, estos no se subirán al servidor:</div>";
                            msgNotValid += "<div><ul>";
                                msgNotValid += "<li>Solo se permiten archivos con extensión (xml,pdf).</li>";
                                msgNotValid += "<li>Deben cumplir con la estructura de nombre adecuado ejemplo (XXXXX_Pago_de_Nómina_fecha_X_RFC).</li>";
                            msgNotValid += "</ul></div>";                            
                            $(".msgNotValid").html(msgNotValid);
                        }
		}
	}

	multiUploader.prototype._read = function(evt){
		if(evt.target.files){
			self._preview(evt.target.files);
			self.all.push(evt.target.files);
		} else 
			console.log("Failed file reading");
	}
	
	multiUploader.prototype._validate = function(format){
		var arr = this.config.support.split(",");                
		return arr.indexOf(format);
	}
	
	multiUploader.prototype._dropFiles = function(e){
		e.stopPropagation(); e.preventDefault();
		self._preview(e.dataTransfer.files);
                self.all.push(e.dataTransfer.files);                                                 
	}
	
	multiUploader.prototype._uploader = function(file,f){ 		
		arrFilesByUpload.push(f);                
                
                //Validar que el tamanio del archivo sea correcto y contenga la palabra pago nomina
                var nombre = file[f].name;
                var nombreSeparado = nombre.split("_");
                var tituloPago = "false";
                var fileCorrect = false;
                
                if(nombreSeparado.length==7){
                    if(typeof nombreSeparado[1] != undefined && typeof nombreSeparado[2] != undefined && typeof nombreSeparado[3] != undefined){
                        var tituloPago = nombreSeparado[1] + "_" + nombreSeparado[2] + "_" + "n";
                    }	
                }
                tituloPago = tituloPago.toLowerCase();        
                if(tituloPago.indexOf("pago_de_n") == 0){
                    fileCorrect = true;
                }
                
		if(self._validate(file[f].type)!=-1 && fileCorrect==true){		
                    contFiles ++;                    
                    
                        //if(typeof file[f] != undefined && self._validate(file[f].type) > 0){
			var data = new FormData();
			var ids = file[f].name._unique();
			data.append('file',file[f]);
			data.append('index',ids);
                        data.append('anio',$("#anio").val());
                        data.append('mes',$("#mes").val());
			data.append('empresa',$("#empresa").val());
                        
                        //console.log(file[f]);
			$(".dfiles[rel='"+ids+"']").find(".progress").show();
			$.ajax({
				type:"POST",
				url:this.config.uploadUrl,
				data:data,
				cache: false,
				contentType: false,
				processData: false,                                
				success:function(rponse){
					$("#"+ids).hide();
					var obj = $(".dfiles").get();
					$.each(obj,function(k,fle){
						if($(fle).attr("rel") == rponse){
							$(fle).slideUp("normal", function(){ $(this).remove(); });
						}
					});

					var posUplaoded = arrUnique.indexOf(rponse);
					if(posUplaoded!=-1){
						arrFilesUploaded.push(arrFileNameOriginal[posUplaoded]);						
					}else{
						arrFilesNotUploaded.push(arrFileNameOriginal[posUplaoded]);
					}
					
					if (f+1 < file.length) {						
						self._uploader(file,f+1);						
					}else{
                                            setTimeout(function(){ 
                                                //window.location.href = "altarecibos.php"; 
                                                //asignando resultados

                                                $("#anio_span").html($("#anio").val());                                                
                                                $("#mes_span").html($("#mes option:selected").text());
                                                $("#empresa_span").html($("#empresa option:selected").text());

                                                $("#porsubir_span").html(arrFilesByUpload.length);	
                                                $("#subidos_span").html(arrFilesUploaded.length);
                                                $("#nosubidos_span").html(arrFilesNotUploaded.length);
                                                //mostrar nombre de archivos no subidos 
                                                if(arrFilesNotUploaded.length>0){
	                                                contNotUp = 1;
	                                                html = "";
	                                                $.each(arrFilesNotUploaded,function(index,val){                                                	
	                                                	html += "<div>"+contNotUp+". " + val +"</div>";
	                                                	contNotUp++;
	                                                });
	                                                $("#archivosNosubidos").html(html);
                                                }
                                                $(".resumenArchivos").show(); //mostrar los resultados

                                                //parar loading
                                                $("#loadingImg").hide();
                                                //salvar en db los resultados
                                                // saveResumenUpload($("#anio").val(), $("#mes").val(), $("#empresa").val(), arrFilesByUpload.length, arrFilesUploaded, arrFilesNotUploaded);

                                                $(".cont_gral_recibos").hide();
	                                    	}, 400);
                                            //}, 1000);
                                        }                                                                                
				}
			});		
		}else{
                    contFiles ++;
                    
                    arrFilesNotUploaded.push(file[f].name); //agregar nombre de archivo que no tienen la extension correcta
                    //quita de la vista los archivos que no se subieron
                    var obj = $(".dfiles").get();
                    $.each(obj,function(k,fle){				
                            $(fle).slideUp("normal", function(){ $(this).remove(); });				
                    });
                    
                    if(contFiles==arrFileNameOriginal.length){                        
                        $("#anio_span").html($("#anio").val());                                                
                        $("#mes_span").html($("#mes option:selected").text());
                        $("#empresa_span").html($("#empresa option:selected").text());
                                  
                        $("#porsubir_span").html(arrFileNameOriginal.length);
                        $("#subidos_span").html(arrFilesUploaded.length);
                        $("#nosubidos_span").html(arrFilesNotUploaded.length);
                        
                        //mostrar nombre de archivos no subidos 
                        if(arrFilesNotUploaded.length>0){
                                contNotUp = 1;
                                html = "";
                                $.each(arrFilesNotUploaded,function(index,val){                                                	
                                        html += "<div>"+contNotUp+". " + val +"</div>";
                                        contNotUp++;
                                });
                                $("#archivosNosubidos").html(html);
                        }
                        //setTimeout(function(){
                            $(".resumenArchivos").show(); //mostrar los resultados

                            //parar loading
                            $("#loadingImg").hide();
                            //salvar en db los resultados
                            // saveResumenUpload($("#anio").val(), $("#mes").val(), $("#empresa").val(), arrFilesByUpload.length, arrFilesUploaded, arrFilesNotUploaded);

                            $(".cont_gral_recibos").hide();
                        //}, 400);
                    }else{
                        self._uploader(file,f+1);
                    }                                        
		}                
	}
	
	multiUploader.prototype._startUpload = function(){
		if(this.all.length > 0){
			for(var k=0; k<this.all.length; k++){
				var file = this.all[k];
				this._uploader(file,0);
			}
		}
	}
	
	String.prototype._unique = function(){
		return this.replace(/[a-zA-Z]/g, function(c){
     	   return String.fromCharCode((c <= "Z" ? 90 : 122) >= (c = c.charCodeAt(0) + 13) ? c : c - 26);
    	});
	}

	this._init();
}

function initMultiUploader(){
	new multiUploader(config);
}

var config = {    
    // support : "bmp,text/xml,application/pdf", 
    support : "application/msword, application/pdf, application/vnd.openxmlformats-officedocument.wordprocessingml.document", 
    form: "demoFiler",					// Form ID
    dragArea: "dragAndDropFiles",		// Upload Area ID
    uploadUrl: "../upload.php"				// Server side upload url
};
        
$(document).ready(function(){
    initMultiUploader(config);
});
var arrFilesByUpload = [];
var arrFilesUploaded = [];
var arrUnique = [];
var arrFileNameOriginal = [];
var arrFilesNotUploaded = [];
var contFiles = 0; 
var contFilesUploaded = 0;


/*function saveResumenUpload($anio, $mes, $empresa, $totalPorSubir, $archivosSubidos, $archivosNoSubidos){
	//arrFilesUploaded.join(), arrFilesNotUploaded.join()
	$subidos = $archivosSubidos.length;
	$noSubidos = $archivosNoSubidos.length;
	$textSubidos = "";//$archivosSubidos;
	$textNoSubidos = $archivosNoSubidos;

	$.ajax({
	     type: 'GET',
	     dataType: 'jsonp',             
	     data: {funct: 'saveResumenUpload', anio:$anio, mes:$mes, empresa:$empresa, totalPorSubir:$totalPorSubir, subidos:$subidos, noSubidos:$noSubidos, textSubidos:$textSubidos, textNoSubidos:$textNoSubidos},
	     jsonp: 'callback',
	     url: '../ajaxcall/ajaxFunctions.php',
	     beforeSend: function () {
	     },
	     complete: function () {
	     },
	     success: function (data) {
	         console.log(data);

	         if(data.result=='ok')
	         {
	         }
	         else{
	         }
	     },
	     error: function () {
	     }
	});
}*/