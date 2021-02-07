var formClassName  = "class_codiceFiscale";
var formElements = document.getElementsByClassName(formClassName);
function script() {
    loadCodice();
    for(var i = 0; i < formElements.length; i++) {
        formElements[i].onchange = loadCodice;
        formElements[i].oninput = loadCodice;
    }
}
window.onload = script;

function loadCodice() {
    var formData = new FormData();
    for(var i = 0; i < formElements.length; i++) {
        formData.append(formElements[i].name, formElements[i].value);
    }
    formData.set("gender", document.getElementById("radio_gender_male").checked ? "1" : "0");
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("div_result").innerHTML = this.responseText;
        }
    };
    xhttp.open("post", "codiceFiscale.php", true);
    xhttp.send(formData);
}
