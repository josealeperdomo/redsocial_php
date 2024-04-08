mostrarR = ()=>{
    let registro = document.getElementById("registro")
    let login = document.getElementById("login")
    if (registro.classList.contains("hidden")) {
        registro.classList.replace("hidden", "flex")
        login.classList.replace("flex", "hidden")
    } else {            
    }
}

mostrarL = ()=>{
    let registro = document.getElementById("registro")
    let login = document.getElementById("login")
    if (login.classList.contains("hidden")) {
        login.classList.replace("hidden", "flex")
        registro.classList.replace("flex", "hidden")
    } else {            
    }
}