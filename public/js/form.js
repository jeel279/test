var email = document.getElementById("email");
var fname = document.getElementById("name");
var detblock = document.getElementById("detBlock");
var otpblock = document.getElementById("otpBlock");
var error = document.getElementById("errBlock");
var otp = document.getElementById("otp");
var k;
async function postData(url = '', data = {}) {
    // Default options are marked with *
    const response = await fetch(url, {
      method: 'POST', // *GET, POST, PUT, DELETE, etc.
      mode: 'cors', // no-cors, *cors, same-origin
      cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
      credentials: 'same-origin', // include, *same-origin, omit
      headers: {
        // 'Content-Type': 'application/json'
        'Content-Type': 'application/x-www-form-urlencoded',
      },
      redirect: 'follow', // manual, *follow, error
      referrerPolicy: 'no-referrer', // no-referrer, *no-referrer-when-downgrade, origin, origin-when-cross-origin, same-origin, strict-origin, strict-origin-when-cross-origin, unsafe-url
      body: data // body data type must match "Content-Type" header
    });
    return response.json(); // parses JSON response into native JavaScript objects
  }  


document.getElementById("submit").addEventListener("click",function(e){
    postData('https://'+location.host+'/validate/', `email=${email.value}&name=${fname.value}&submit=true` )
  .then((data) => {
    console.log(data);
    if(data['code']==200){
       error.style.display = 'none';
       detblock.style.display = 'none';
       otpblock.style.display = 'block';
    }else if(data['code']==2){
      error.style.display = 'block';
      var i = parseInt(data['msg']);
      var x = setInterval(()=>{error.innerText = `You can request new otp after ${i--} seconds`;if(i==0)clearInterval(x)},1000);
    }else{
        error.style.display = 'block';
        error.innerText = data['msg'].toString();
    }
  });
})

document.getElementById("verify").addEventListener("click",function(e){
  postData('https://'+location.host+'/validate/', `otp=${otp.value}&verify=true` )
.then((data) => {
  console.log(data);
  if(data['code']==1){
    document.body.innerHTML = data["msg"];
  }
});
})

