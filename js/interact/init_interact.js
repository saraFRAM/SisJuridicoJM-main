/************************Inicializar plugin***************************************/	  
// target elements with the "draggable" class
interact('.draggable')
  .draggable({
	// enable inertial throwing
	inertia: true,
	// keep the element within the area of it's parent
	restrict: {
	  restriction: "parent",
	  endOnly: true,
	  elementRect: { top: 0, left: 0, bottom: 1, right: 1 }
	},
	// enable autoScroll
	autoScroll: true,

	// call this function on every dragmove event
	onmove: dragMoveListener,
	// call this function on every dragend event
	onend: function (event) {
	  //console.log(event.pageX+','+event.pageY);
	  //console.log("Abrir upload para subir las imagenes");		  		
	  /*
	  var textEl = event.target.querySelector('p');		  		 
	  textEl && (textEl.textContent =
		'moved a distance of '
		+ (Math.sqrt(Math.pow(event.pageX - event.x0, 2) +
					 Math.pow(event.pageY - event.y0, 2) | 0))
			.toFixed(2) + 'px');
			*/
	}
  });

  function dragMoveListener (event) {
	//console.log(event.target);		
	var target = event.target,
		// keep the dragged position in the data-x/data-y attributes
		x = (parseFloat(target.getAttribute('data-x')) || 0) + event.dx,
		y = (parseFloat(target.getAttribute('data-y')) || 0) + event.dy;

	// translate the element
	target.style.webkitTransform =
	target.style.transform =
	  'translate(' + x + 'px, ' + y + 'px)';

	// update the posiion attributes
	target.setAttribute('data-x', x);
	target.setAttribute('data-y', y);			
  }
	 
  // this is used later in the resizing and gesture demos
  window.dragMoveListener = dragMoveListener;	 
	  

/***********************Modificar init****************************************/
$( document ).ready(function() {
	$('#cont_hostpot').dblclick(function (e) {
		ctr_hostpot = parseInt($("#ctr_hostpot").val())+1;
		xHostpost = e.pageX-20;
		yHostpost = e.pageY-20;
		hostpot = '';
		hostpot = '<div class="draggable hostpot" onclick="clickHostpot('+ctr_hostpot+');" data-x="'+xHostpost+'" data-y="'+yHostpost+'" style="transform: translate('+xHostpost+'px, '+yHostpost+'px);"><p></p></div>';			
		$("#cont_hostpot").append(hostpot);	
		clickHostpot(ctr_hostpot);
		$("#ctr_hostpot").val(ctr_hostpot);
	});
});
//ejecuta al presionar sobre un hostpot
function clickHostpot(ctr_hostpot){		
	console.log("Abrir upload para subir las imagenes "+ctr_hostpot);
}	