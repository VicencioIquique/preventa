function fncSumar(){
caja=document.forms["sumar"].elements;
var numero1 = parseFloat(caja["numero1"].value);
var numero2 = parseFloat(caja["numero2"].value);
var resultado=numero1+numero2;
if(!isNaN(resultado)){
var res = numero1-((numero2*numero1)/100);
var numformateado = res.toString().replace(/\./g,','); 
var original = parseFloat(numformateado);
var redondeado = Math.round(original*100)/100;
caja["resultado"].value=redondeado;
}
}