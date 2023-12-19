
async function savepropuesta(url){
    var ff = document.getElementById("formpoliza");

    const datos = new FormData(ff);

    objParametros.ultmod = getultmod();

    
    datos.append("parametros", JSON.stringify(objParametros));
    datos.append( "tomador", JSON.stringify(objTomador));
    datos.append( "coberturavigen", JSON.stringify(objcoberturavigencia));
    datos.append( "personasaseguradas", JSON.stringify(personasaseguradas));
    datos.append("barriosagregados",JSON.stringify(barriosagregados))

    

    const res = await fetch(url,{
        method: 'post',
        body: datos
    }).then( res => res.json())
      .then(data => {
            return data;
      })

    return res;
}

function getultmod(){
      
      const hoy = new Date();
      const secfec = new Date(hoy.setDate(hoy.getDate()));

      let mes = secfec.getMonth() + 1;
      mes = mes < 10 ? "0" + mes : mes;

      let dia = secfec.getDate();
      dia = dia < 10 ? "0" + dia : dia;

      let hora = secfec.getHours();
      hora = hora < 10 ? "0" + hora : hora;

      let min = secfec.getMinutes();
      min = min < 10 ? "0" + min : min;
      
      let seg = secfec.getSeconds();
      seg = seg < 10 ? "0" + seg : seg;

      return secfec.getFullYear() + "-" + mes + "-" + dia + " "+hora+":"+min+":"+seg;
}