


/*async function descargapdf() {
    document.getElementById('errores').innerHTML = "";
    document.getElementById('lista').innerHTML = "";
    //const ns = document.getElementById('numseguridad').value;
    const doc = document.getElementById('documento').value;
    alert( document.getElementsByName('_token').value );
    
    let errores = "";
    
    if (doc == "") {
      errores += "Debe ingresar el número de documento<br>";
    }

    //if (ns == "") {
      //errores += "Debe ingresar el código de seguridad";
    //}

    if (errores != "") {
      document.getElementById('errores').innerHTML = ' <div  class="alert alert-danger mb-1" role="alert">'+ errores+'</div>';
      return false;
    }

    const url = 'http://127.0.0.1:8000/consultapoliza?documento='+doc;

    var listado = "";
    await fetch(url,  { method: 'POST'} )
      .then(res => res.json())
      .then(data => {
        data.forEach(element => {
            listado += "<li>"+element.prefijo+element.reg+" |<b> Vigencia</b> : "+element.fechaDesde+"<b> a</b> "+element.fechaHasta+"</li>";
        });
    });


    if(listado != ""){
      listado = "<h3>Póliza(s) a descargar</h3><ol>"+listado+"</ol>";
      document.getElementById("lista").innerHTML = listado;
    }
      


    /*await fetch(url)
      .then(res => {
        if (res.status == 200) {
          console.log(res);
          //window.open(url, '_blank');
        } else {
          alert("Los datos ingresados no tienen documentos asociados o no se han subido");
        }
      })



  }*/