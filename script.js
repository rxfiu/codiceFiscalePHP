function script() {
    alert("a");
}
window.onload = script;
function loadCodice() {
    alert(window.onload);
    document.getElementById("div_result").innerHTML = "ciao";
    var className  = "class_codiceFiscale";
    var elements = document.getElementsByClassName(className);
    var formData = new FormData();
    for(var i = 0; i < elements.length; i++) {
        formData.append(elements[i].name, elements[i].value);
    }
    formData.set("gender", document.getElementById("radio_gender_male").checked ? "1" : "0");
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("div_result").innerHTML = this.responseText;
        }
        else {
            document.getElementById("div_result").innerHTML = "Loading...";
        }
    };
    xhttp.open("post", "codiceFiscale.php", true);
    xhttp.send(formData);
}
