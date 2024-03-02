const form = document.querySelector("form"),
statusTxt = form.querySelector(".button-submit span");

form.onsubmit = (e)=>{
    e.preventDefault(); //preventing form from submitting
    statusTxt.computedStyleMap.color = "#0D6EFD"
    statusTxt.computedStyleMap.display = "block";

    let xhr = new XMLHttpRequest(); //creating new xml object
    xhr.open("POST", "career.php", true); //sending post request to career.php file
    xhr.onload = ()=>{
        if(xhr.readyState == 4 && xhr.status == 200){
            let response = xhr.response;
            //if response is an error like enter valid email address then we'll change status color to red
            if(response.indexOf("Email field is required!") || response.indexOf("sorry fail to sent"))
            statusTxt.computedStyleMap.color = "red";
        }else{
            form.reset();
            setTimeout(()=>{
                statusTxt.computedStyleMap.display = "none";
            }, 3000); //hide the statusTxt after 3 second if the msg is sent
        }
        statusTxt,innerText = response;
        }
    }
    let formData = new FormData(form); //creating new FormData obj. This obj is used to send form data
    xhr.send(formData); //sending form data
