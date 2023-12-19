
        
        
        actividades.forEach(element => {
            opcionesactividades += `<option value='${element.reg}'>${element.nombre}</option> `;
        })

        gruposbarriosnombres.forEach(element => {
            opcionesgruposbarrios += `<option value='${element.id}'>${element.nombre}</option> `;
        })


        function cerrarmodalfeedback(){
            document.getElementById("modalfeedback").style.display = "none";
        }
        
        function revisarpolizarepetida(){
            const filastabla = document.getElementById("tablepolizas").rows;
            let conca = "";
            for (j = 0; j < filastabla.length; j++) {
                const input = filastabla[j].getElementsByTagName('input');
                if(objTomador.documento ==  input[0].value){
                    return false;
                }
            }
            return true;
        }

        function agregartomador(av = 0){

            objTomador.tipodocumento = document.getElementById("tipodocuemntotomador").value;
            objTomador.sexo = document.getElementById("sexotomador").value;
            objTomador.situacionimpositiva = document.getElementById("situaciontomador").value;

            const err = validartomador();

            if( err == ""){
                if(objTomador.documento != "" && objTomador.documento != null 
                && objTomador.tipodocumento != "" && objTomador.tipodocumento != null 
                ){

                    if(av == 1){
                        next();
                    }else{
                        if(revisarpolizarepetida(objTomador.documento)){
                            agregarfilas();
                            ingresarlinea(filasgenerales,{
                                "documento":objTomador.documento,
                                "nombres":objTomador.nombres,
                                "apellidos":objTomador.apellidos,
                                "fechanacimiento":objTomador.fechanacimiento,
                                "tipodocumento":objTomador.tipodocumento
                        });
                        }else{
                            contentfeedback("El tomador ya ha sido agregado");
                        }
                    }
                }
            }else{
                contentfeedback("Hay errores de validación en el tomador : <br> <ul>"+err+"</ul>");
            }
            
        }

        function validartomador(){

            let errores = "";

            for (const val in objTomador) {
                if( objTomador[val] == "" || objTomador[val] == null ){
                    errores += "<li>"+val.toUpperCase()+" No puede estar vacío</li>";
                }
            }

            if(isNaN(objTomador.documento) != false){
                errores += "<li>El documento debe tener sólo números, sin esapcios ni puntos</li>";
            }

            if(isNaN(objTomador.telefono) != false){
                errores += "<li>El teléfono debe tener sólo números</li>";
            }

            if(isNaN(objTomador.codpostal) != false){
                errores += "<li>El código postal debe tener sólo números</li>";
            }

            expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            if ( !expr.test(objTomador.email) )
                errores += "<li>El correo electrónico está mal escrito</li>";

            

            return errores;
            
        }

        function validarcoberturavigencia(){

            let errores = "";

            
            for (const val in objcoberturavigencia) {
                
                if(val != 'promociones'){
                    if( objcoberturavigencia[val] == "" || objcoberturavigencia[val] == null){
                        errores += "<li>"+val.toUpperCase()+" No puede estar vacío</li>";
                    }
                }
            }

            return errores;
        }


        function ingresarlinea(linea, polizalinea){
            const filastabla = document.getElementById("tablepolizas").rows;

            const input = filastabla[linea - 1].getElementsByTagName('input');
            const select = filastabla[linea - 1].getElementsByTagName('select');

            select[0].value  = polizalinea.tipodocumento;
            input[0].value  = polizalinea.documento;
            input[1].value  = polizalinea.apellidos;
            input[2].value  = polizalinea.nombres;
            input[3].value  = polizalinea.fechanacimiento;
            
        }

        function validardocumento(input){
            
            input.className = "form-control";    

            if(isNaN(input.value)){
                input.className += " bg-danger text-white";    
                input.value = "";
                input.focus();
            }
                
        }

        function getEdad(dateString) {
            let hoy = new Date()
            let fechaNacimiento = new Date(dateString)
            let edad = hoy.getFullYear() - fechaNacimiento.getFullYear()
            let diferenciaMeses = hoy.getMonth() - fechaNacimiento.getMonth()
            if (
                diferenciaMeses < 0 ||
                (diferenciaMeses === 0 && hoy.getDate() < fechaNacimiento.getDate())
            ) {
                edad--;
            }

            return edad
        }

        function validarfechanacimineto(input){
            
            input.className = "form-control";    
            input.parentNode.parentNode.className = "";
            
            
            if( getEdad(input.value) < 90 ){
                
                if(getEdad(input.value) < 18){
                    input.className += " bg-danger text-white";    
                    input.value = "";
                    input.focus();
                }

                if(getEdad(input.value) > 60){
                    input.parentNode.parentNode.className += " bg-warning p-2";
                    
                }
                datospersonaspolizas();
                validardatospolizas();  
            }


                
        }


        const filasasegurados = (fil) => `
                <td id='filpc${fil}'><span style="float:right;font-size:20px;" >#${fil}</span> <button  onclick="deleteRow(this)" class="btn btn-danger btncerrar-i">x</button></td>
                <td>
                <input type="text" class="form-control" onchange="validardocumento(this)" placeholder="Documento">
                </td>
                <td>
                <select name="tipodocumento"  class="form-control">
                    <option value="">Tipo</option>
                    <option value="DNI">DNI</option>
                    <option value="LE">LE</option>
                    <option value="LC">LC</option>
                    <option value="CUIT">CUIT</option>
                    <option value="CI">CI</option>
                </select>
                </td>
                <td>
                <input type="text" class="form-control" placeholder="Apellidos">
                </td>
                <td>
                <input type="text" class="form-control"  placeholder="Nombres">
                </td>
                <td>
                <input onfocus="(this.type='date')" type="text" class="form-control" placeholder="Fecha Nacimiento(dd/mm/aaaa)" onchange="validarfechanacimineto(this)">
                </td>
                <td>
                <select name="tipodocumento" class="form-control" onchange="asignacionactividad(this)">
                    <option value="">Seleccionar Actividad</option>
                    ${opcionesactividades}
                </select>
                </td>
                <td>
                <select name="tipodocumento" id="" class="form-control">
                    <option value="">Seleccionar Clasificación</option>
                </select>
                </td>
                <td class="btncerrar-d"><button  onclick="deleteRow(this)" class="btn btn-danger text-right">x</button></td>
                <td class="btncerrar-i text-center">------------------------------------------------</td>
    `;


        function asignacionactividad(row) {

            document.getElementById("tablepolizas").rows[row.parentNode.parentNode.rowIndex].cells[7].innerHTML =
                `
        <select class='form-control'>
            <option value=''>Seleccionar Clasificación</value>
            ${opcionesclasificaciones(row.value)}
        </select>
        `;

        }

        function opcionesclasificaciones(cod) {

            let concat = "";
            clasificaciones.map((cla) => {
                if (cla.id_actividad == cod) {
                    concat += `
                <option value = "${cla.reg}">${cla.nombre}</option>
            `;
                }
            });

            return concat;
        }


        function numerarspan() {
            const filtabla = document.getElementById("tablepolizas").rows;

            for (let i = 0; i < filtabla.length; i++) {
                filtabla[i].getElementsByTagName("span")[0].textContent = "#"+ (i + 1);
            }

        }


        function deleteRow(btn) {
            var row = btn.parentNode.parentNode;
            row.parentNode.removeChild(row);
            numerarspan();
            filasgenerales--;

            datospersonaspolizas();
            validardatospolizas();  

            totalizar();
        }

        function totalizar() {

            let total = 0;

            //console.log("FILAS GENE "+filasgenerales + " PREMIO "+parseFloat(document.getElementById("premio").value)+ " MAYORES "+(parseFloat(document.getElementById("premio").value) * mayoresde60) + " MESES "+parseInt(document.getElementById("meses").value));
            const mesesasignados = parseInt(document.getElementById("meses").value);
            let subtotal = parseFloat(document.getElementById("premio").value) * mesesasignados;
            let promo = "";
            if(valcobertura){

                if(mesesasignados == 2 && valcobertura[0].x21 > 0){
                    subtotal = valcobertura[0].x21;
                    promo = "2x1 "+ valcobertura[0].x21;
                }
                if(mesesasignados == 3 && valcobertura[0].x32 > 0){
                    subtotal = valcobertura[0].x32;
                    promo = "3x2 "+valcobertura[0].x32;
                }
                if(mesesasignados == 6 && valcobertura[0].x64 > 0){
                    subtotal = valcobertura[0].x64;
                    promo = "6x4 "+valcobertura[0].x64;
                }
            }


            if(promo != "")
                objcoberturavigencia.promociones = promo;

            total = (filasgenerales * subtotal) + (subtotal *  mayoresde60);

            document.getElementById("premiototal").value = total;
            document.getElementById("restotal").innerText = "TOTAL : " + total;
            
            objcoberturavigencia.premiototal = total;

        }

        function postal(val, op = 0) {

            const vecpostal = provincias.filter(pro => pro.codpostal.trim() == val.trim());

            if (vecpostal.length && op == 0) {
                document.getElementById("localidad").value = vecpostal[0].provincia;
                document.getElementById("ciudad").value = vecpostal[0].ciudad;
            }

            const vecciudad = provincias.filter(pro => pro.ciudad.trim() == val.trim());

            if (vecciudad.length && op == 1) {
                document.getElementById("localidad").value = vecpostal[0].provincia;
                document.getElementById("ciudad").value = vecpostal[0].ciudad;
            }

            objTomador.ciudad =  document.getElementById("ciudad").value;
            objTomador.localidad =  document.getElementById("localidad").value;

        }


        function agregarfilas() {

            

            filaspc++;
            document.getElementById("botagregafila").href = "#filpc"+filaspc;
            filasgenerales++;

            const tr = document.createElement("tr");
            tr.innerHTML = filasasegurados(filaspc)
            document.getElementById("fila").appendChild(tr);

            numerarspan();
            totalizar();
            datospersonaspolizas();
            
        }


        function next() {


            if (vecesnext == 1) {

                
                
                datospersonaspolizas();

                let err = validarcoberturavigencia();
                let err1 = validardatospolizas();

                if( err == "" && err1 == ""){

                    mostrarresumendecompra();
                    
                    document.getElementById("datostomador").style.display = "none";
                    document.getElementById("datospoliza").style.display = "none";
                    document.getElementById("datosdepago").style.display = "block";
                    document.getElementById("next").style.display = "none";
                    vecesnext++;
                    contentfeedback("Esta cobertura solo aplica para dentro de barrios privados.");

                }else{
                    if(err != "")
                        err = "<ul>"+err+"</ul>";
                    if(err1 != "")
                        err1 = "<ul>"+err1+"</ul>";

                    contentfeedback("Error de validación "+err+err1);
                }

            }

            if (vecesnext == 0) {
                document.getElementById("datostomador").style.display = "none";
                document.getElementById("datospoliza").style.display = "block";
                document.getElementById("datosdepago").style.display = "none";
                document.getElementById("preview").style.display = "block";
                vecesnext++;

            }

            document.getElementById("paso").innerText = "PASO " + (vecesnext + 1) + "/3";
        }

        function preview() {

            if (vecesnext == 1) {
                document.getElementById("datostomador").style.display = "block";
                document.getElementById("datospoliza").style.display = "none";
                document.getElementById("preview").style.display = "none";
                document.getElementById("datosdepago").style.display = "none";
                vecesnext--;
            }


            if (vecesnext == 2) {
                document.getElementById("datostomador").style.display = "none";
                document.getElementById("datospoliza").style.display = "block";
                document.getElementById("datosdepago").style.display = "none";
                document.getElementById("next").style.display = "block";
                vecesnext--;
            }

            

            document.getElementById("paso").innerText = "PASO " + (vecesnext + 1) + "/3";
        }


        function filapoliza() {
            const fila = document.getElementById('filap').parentNode;

        }


        function sumarmes(value) {

            const fec = document.getElementById("vigenciadesde").value;
            const hoy = new Date(fec.substr(5, 2) + "/" + fec.substr(8, 2) + "/" + fec.substr(0, 4));
            const secfec = new Date(hoy.setDate(hoy.getDate() + (parseInt(value) * 30)));


            let mes = secfec.getMonth() + 1;
            mes = mes < 10 ? "0" + mes : mes;

            let dia = secfec.getDate();
            dia = dia < 10 ? "0" + dia : dia;


            const fecha = secfec.getFullYear() + "-" + mes + "-" + dia;

            document.getElementById('vigenciahasta').value = fecha;

            objcoberturavigencia.meses = value;
            objcoberturavigencia.vigenciahasta = fecha;

            totalizar();

        }

        function revisarcobertura(){
            if( document.getElementById("cobertura").value == "" ) {
                contentfeedback("Por favor seleccione la cobertura primero");
                document.getElementById("cobertura").focus();
                return false;
            }

            return true;
        }

        function seleccinarbarrio(c = ""){

            document.getElementById("mensajebarrio").innerHTML = "";
            let cuitbarrio = document.getElementById("barrio").value;
            if(c != "")
                cuitbarrio = c;
            

            
                const vecbarrios = barriosagregados.filter(bar => bar.cuit == cuitbarrio);
            
                if(vecbarrios.length < 1){
                        const barr = barrios.filter(bar => bar.id == cuitbarrio);
                        if(barr.length < 1){
                            contentfeedback(
                                `<p>No hay coincidencias con el CUIT escrito</p><p>Caso no encuentres el Cuit Deseado, por favor contactarte por<a href="https://api.whatsapp.com/send/?phone=5491155841038&app_absent=0" target="_blank">  WhatsApp al numero +54 9 11 5584 1038 </a>y analizaremos la inclusión del grupo para tu mayor facilidad y/o también te ayudaremos a emitir la póliza</p>`
                                );
                        }
                        else{
                            
                            if(barr[0].suma_muerte != "" && valcobertura[0].suma != ""){
                                document.getElementById("avisosa").innerHTML= "";
                                if( parseFloat( barr[0].suma_muerte ) > parseFloat( valcobertura[0].suma )){
            
                                    const mensajemuerte = `<p>La cobertura exigida Por Muerte por el barrio para ingresar es mayor suma asegurada que la cobertura seleccionada <b>(${new Intl.NumberFormat('de-DE').format(valcobertura[0].suma)}).</b></p> 
            
                                    <br>Nombre:<b> ${barr[0].nombre}</b>
                                    <br>Suma: <b>${new Intl.NumberFormat('de-DE').format(barr[0].suma_muerte)}</b>
                                    <p>
                                    <br>
                                    Podrás avanzar con la emisión y el seguro te cubrirá dentro de barrios privados hasta la suma asegurada contratada, pero ten en cuenta, es posible que tengas problemas para entrar al barrio a trabajar y te sugerimos que le des cerrar y cambies la cobertura dentro de la sección cobertura para que puedas avanzar con éxito. 
                                    </p>
                                    <p>A su vez te sugerimos que revises el valor de cobertura de gastos médicos(GM) para que cumplas con las exigencias del barrio.</p>
                                    `;
            
                                    contentfeedback(
                                        mensajemuerte, 
                                        "Error de validación",
                                        "bg-danger text-light"
                                        );
            
                                    document.getElementById("avisosa").innerHTML=  `<div class="alert alert-danger" role="alert">
                                    Dentro de los barrios elegidos tienes sumas aseguradas mayores a la escogida en la cobertura, Te sugiero cambiar la cobertura por una con mayor SA Te ofrcemos varias alternativas. Si aún deseas continuar, podrás avanzar pero evita problemas al ingresar al barrio y recuerda que solo tendrás cobertura dentro de barrios privados y hasta la suma asegurada contratada.
                                </div>`;
            
                                    //return false;
                                }
                                
                                barriosagregados.push({
                                    "cuit" : barr[0].id,
                                    "reg" : barr[0].reg,
                                    "nombre" : barr[0].nombre,
                                    "suma_muerte" : barr[0].suma_muerte,
                                    "suma_gm" : barr[0].suma_gm,
                                    "suma_rc" : barr[0].suma_rc
                                });
            
                                document.getElementById("barrio").value = "";
                                listabarrios();
                            }
                        }
                    
                }else{
                    document.getElementById("mensajebarrio").innerHTML = "El CUIT Ya ha sido agregado";
                }
    
    
            
        }

        const agregargrupo = () =>{
            return `
                <div>
                    <p>Aquí encontraras todos aquellos barrios que por su característica forman un grupo de Cláusulas de no repetición para facilitar su inclusión. </p>
                    <select id="grupoelegido" class="form-select" aria-label="Default select example">
                        <option selected>Seleccione un grupo</option>
                        ${opcionesgruposbarrios}
                    </select>
                    
                    <button onclick="barriosgrupos()"  class="btn btn-primary">Seleccionar Grupo</button>
                    <br>
                    <br>

                    <p>Caso no encuentres el grupo por favor contactarte por<a href="https://api.whatsapp.com/send/?phone=5491155841038&app_absent=0" target="_blank"> WhatsApp al numero +54 9 11 5584 1038 </a> y analizaremos la inclusión del grupo para tu mayor facilidad y/o también te ayudaremos a emitir la póliza.</p>
                </div>
            `; 
        }

        function barriosgrupos(){
            const grupoelegido = document.getElementById("grupoelegido").value;

            gruposbarrios.forEach(element =>  {
                if(element.id == grupoelegido){
                    seleccinarbarrio(element.idbarrio);
                }
                
            })
            
        }

        const filabarrio = (conse,barriprivado) => `
                            
                <tr>
                    <td style="text-align:right;">
                        ${conse}
                    </td>
                    <td>
                        <input type="text" class="form-control" value="CUIT: ${barriprivado["cuit"]}"  placeholder="First name" readonly>
                    </td>
                    <td>
                        <input type="text" size="250px" class="form-control" value="BARRIO: ${barriprivado["nombre"]}" placeholder="Last name" readonly>
                    </td>
                    <td>
                        <input type="text" class="form-control" value="SA: ${barriprivado["suma_muerte"]}" placeholder="Last name" readonly>
                    </td>
                    <td>
                        <button class="btn btn-danger" onclick="borrarbarrio(${barriprivado["cuit"]})">x</buttton>
                    </td>
                </tr>

                            
        `;


        function borrarbarrio(cuit){
            barriosagregados = barriosagregados.filter(bar => bar.cuit != cuit);
            listabarrios();

            if(barriosagregados.length < 1)
                document.getElementById("avisosa").innerHTML= "";
        }


        function listabarrios(){
            let concatbarrio = "<ul>";

            document.getElementById("tablabarrios").innerHTML = "";
            
            for(let i = 0 ; i < barriosagregados.length ; i++){
                concatbarrio += "<li>CUIT : "+barriosagregados[i]["cuit"]+" - " + barriosagregados[i]["nombre"] + "<br>SUMA : " +  new Intl.NumberFormat('de-DE').format(barriosagregados[i]["suma_muerte"]) +"<a onclick='-' class='btn btn-danger'>-</a></li>";
                

                document.getElementById("tablabarrios").innerHTML += filabarrio((i+1),barriosagregados[i]);
            }

            

            

            concatbarrio = concatbarrio + "</ul>";

            if(barriosagregados.length < 1){
                concatbarrio = "NO HAY BARRIOS AGREGADOS";
            }else{
                document.getElementById("tablabarrios").innerHTML = '<tr><td colspan="4">BARRIOS: </td></tr>'+ document.getElementById("tablabarrios").innerHTML ;
            }

            //contentfeedback(concatbarrio, "Barrios Agregados");
        }


        function seleccioncobertura(dato) {
            valcobertura = coberturas.filter(cob => cob.nombre == dato);

            let concapromo = "";
            

            if(valcobertura[0].x21 > 0 && objcoberturavigencia.meses <= 2){
                concapromo += "<li>Selecciona 2 Meses paga 1</li>";
                
            }
                
            if(valcobertura[0].x32 > 0  && objcoberturavigencia.meses <= 3){
                concapromo += "<li>Selecciona 3 Meses paga 2</li>";
                
            }
                
            if(valcobertura[0].x64 > 0  && objcoberturavigencia.meses <= 6){
                concapromo += "<li>Selecciona 6 Meses paga 4</li>";
                
            }
                
            //document.getElementById("promociones").innerHTML = concapromo;
            if(concapromo != ""){
                concapromo = "<ul>"+concapromo+"</ul>";
                contentfeedback("La cobertura seleccionada tiene las siguientes PROMOS : </br>"+concapromo+"<br><h5>¡Solo cambia el mes y listo! será aplicada la promoción</h5>","Aprovecha las promociones","bg-warning");
                
            }
                
            document.getElementById("premio").value = valcobertura[0].vrMensual;

            objcoberturavigencia.cobertura = valcobertura[0];
            objcoberturavigencia.premio = document.getElementById("premio").value;

            totalizar();

            revisarsumacoberturabarrio(valcobertura[0].suma);

        }

        function revisarsumacoberturabarrio(sm){
            var confirm = 0;

            barriosagregados.forEach(element => {
                if( parseFloat( element.suma_muerte )> parseFloat(sm))
                    confirm++;
            });

              

            

            if(confirm == 0)
                document.getElementById("avisosa").innerHTML = "";
            else
                document.getElementById("avisosa").innerHTML=  `<div class="alert alert-danger" role="alert">
            Dentro de los barrios elegidos tienes sumas aseguradas mayores a la escogida en la cobertura, Te sugiero cambiar la cobertura por una con mayor SA Te ofrcemos varias alternativas. Si aún deseas continuar, podrás avanzar pero evita problemas al ingresar al barrio y recuerda que solo tendrás cobertura dentro de barrios privados y hasta la suma asegurada contratada.
        </div>`;

            
        }

        function contentfeedback(content,title = "Mensaje", barra = "bg-primary text-white"){

            document.getElementById("botfeedback").innerText = "Cerrar";

            document.getElementById("titlecontent").innerHTML = title;
            document.getElementById("contentfeedback").innerHTML = content;

            if(content == "Esta cobertura solo aplica para dentro de barrios privados."){
                
                document.getElementById("botfeedback").innerText = "Avanzar";
            }

            

            const classbarra = "modal-header "+barra;
            document.getElementById("barratitle").className = classbarra;
            document.getElementById("modalfeedback").style.display = "block";
        }


        function validardatospolizas() {

            let errores = "";
            let aux = "";

            mayoresde60 = 0;

            if(personasaseguradas.length < 1){
                errores += "<li>No se ha agregado ninguna persona para asegurar</li>";
            }

            for(let i = 0 ; i< personasaseguradas.length ; i++){

                if( personasaseguradas[i]["documento"] == "" || personasaseguradas[i]["documento"] == null )
                    aux += "<li>Campo documento vacío</li>";
                if( personasaseguradas[i]["tipodocumento"] == "" || personasaseguradas[i]["tipodocumento"] == null )
                    aux += "<li>Campo tipo documento vacío</li>";
                if( personasaseguradas[i]["apellidos"] == "" || personasaseguradas[i]["apellidos"] == null )
                    aux += "<li>Campo apellidos vacío</li>";
                if( personasaseguradas[i]["nombres"] == "" || personasaseguradas[i]["nombres"] == null )
                    aux += "<li>Campo nombres vacío</li>";
                if( personasaseguradas[i]["fechanacimiento"] == "" || personasaseguradas[i]["fechanacimiento"] == null )
                    aux += "<li>Campo fecha nacimiento vacío 1</li>";
                
                

                if( personasaseguradas[i]["fechanacimiento"] != "" ){

                    
                    if(getEdad(personasaseguradas[i]["fechanacimiento"]) > 60){
                        mayoresde60++;
                    }
                    
                    totalizar();

                }
                if( personasaseguradas[i]["actividad"] == "" || personasaseguradas[i]["actividad"] == null )
                    aux += "<li>Campo actividad vacío</li>";
                if( personasaseguradas[i]["clasificacion"] == "" || personasaseguradas[i]["clasificacion"] == null )
                    aux += "<li>Campo clasificacion vacío</li>";
                
                if(aux != "")
                    errores += "<li>Hay campos vacíos en la póliza #"+(i+1)+". </li>";
                aux = "";
            }

            if(errores != ""){
                errores += "<br>Por favor complete los campos vacíos o presione la <span class='btn btn-danger'>X</span> al lado derecho de cada línea para borrarla  y de click en siguiente paso para avanzar</li>"
            }
            
            document.getElementById("mayores60").innerHTML = "";

            if(mayoresde60 > 0){
                document.getElementById("mayores60").innerHTML = "<p class='text-warning'>Se ha agregado "+mayoresde60+" persona(s) mayor(es) de 60 años</p>";
            }

            return errores;
        }

        function datospersonaspolizas() {

            const filastabla = document.getElementById("tablepolizas").rows;

            personasaseguradas = [];

            for (j = 0; j < filastabla.length; j++) {

                const input = filastabla[j].getElementsByTagName('input');
                const select = filastabla[j].getElementsByTagName('select');

                personasaseguradas.push({
                    "tipodocumento" : select[0].value,
                    "documento" : input[0].value,
                    "apellidos" : input[1].value.toUpperCase(),
                    "nombres" : input[2].value.toUpperCase(),
                    "fechanacimiento" : input[3].value,
                    "actividad" : select[1].value,
                    "clasificacion" : select[2].value,
                    "nomactividad" : select[1].options[select[1].selectedIndex].text,
                    "nomclasificacion" : select[2].options[select[2].selectedIndex].text
                });

            }
            
        }


        function traerdatostabla() {

            const filastabla = document.getElementById("tablepolizas").rows;
            let conca = "";

            for (j = 0; j < filastabla.length; j++) {
                const input = filastabla[j].getElementsByTagName('input');
                conca += "<li>"+ input[1].value.toUpperCase()+ " "+ input[2].value.toUpperCase()+"</li>";
            }
            return conca;
        }


        function traerdatosbarrios() {

            let conca = "";
            barriosagregados.forEach(element => {
                
                conca += "<li>"+element.nombre+"</li>";
            });

            
            
            return conca;
        }


        function mostrarresumendecompra() {
            
            const resumen = `
            <div id="resumen">
                <h5>Resumen de la compra: </h5>
                <h5>Tomador : ${ document.getElementById("nombretomador").value.toUpperCase()+ " " +document.getElementById("apellidotomador").value.toUpperCase()}</h5>
                <div style="background-color:#00ffdc;border:1px solid #fff; padding:10px;border-radius:5px;">
                <h5>Personas Aseguradas (${document.getElementById("tablepolizas").rows.length}): </h5>
                <ol>
                    ${traerdatostabla()}
                </ol>
                </div>
                <div style="background-color:#00ffdc;border:1px solid #fff; padding:10px;border-radius:5px;">
                <h5>Barrios sleccionados (${barriosagregados.length}): </h5>
                    <div style="max-height:300px;overflow: auto;">
                        <ol>
                            ${traerdatosbarrios()}
                        </ol>
                    </div>
                </div>
                <h5>TOTAL A PAGAR $ ${document.getElementById("premiototal").value} </h5>
                <br>
                <b class="bg-danger text-light">Cobertura sólo para dentro de barrios privados</b>
                <br>
                <button onclick="savep()" class='btn btn-primary'>PAGA AQUÍ Y OBTÉN TU SEGURO</button> 
            </div>
            `;
            document.getElementById("pasarela").innerHTML = resumen;
        }

    