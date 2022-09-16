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

function validateEmail(email){
  var re = /\S+@\S+\.\S+/;
  return re.test(email);
}
function validateName(name){
  var re = /^([A-Za-z ])+$/g;
  return re.test(name);
}
document.getElementById("submit").addEventListener("click",function(e){
  var kE = email.value.trim(),kN = fname.value.trim();
  var x=validateEmail(kE),y=validateName(kN);
  if(!x || !y){
    if(!x) email.classList.add("danger-b");
    if(!y) fname.classList.add("danger-b");
    return;
  }
  email.classList.remove("danger-b");
  fname.classList.remove("danger-b");
  document.getElementById("submit").style.display = 'none';
  document.getElementsByClassName("loader")[0].style.display='block';
    postData('http://'+location.host+'/validate/', `email=${kE}&name=${kN}&submit=true` )
  .then((data) => {
    console.log(data);
    if(data['code']==200){
       error.style.display = 'none';
       document.getElementsByClassName("loader")[0].style.display='none';
       detblock.style.display = 'none';
       otpblock.style.display = 'block';
    }else if(data['code']==2){
      document.getElementsByClassName("loader")[0].style.display='none';
      error.style.display = 'block';
      var i = parseInt(data['msg']);
      var x = setInterval(()=>{error.innerText = `You can request new otp after ${i--} seconds`;if(i==0){
        document.getElementById("submit").style.display = 'block';error.style.display = 'none';
        clearInterval(x);
      }
      },1000);
    }else{
        error.style.display = 'block';
        error.innerText = data['msg'].toString();
        document.getElementById("submit").style.display = 'block';
    }
  });
})
document.getElementById("back").addEventListener("click",function(e){
  otpblock.style.display = "none";
  document.getElementById("submit").style.display = 'block';
  detblock.style.display = "block";
})
document.getElementById("verify").addEventListener("click",function(e){
  document.getElementById("errBlock").style.display = "none";
  postData('http://'+location.host+'/validate/', `otp=${otp.value}&verify=true` )
.then((data) => {
  console.log(data);
  if(data['code']==1){
    otpblock.innerHTML = data["msg"] + "<br> Random xkcd comics service will be started after 5 minutes.";
  }else{
    document.getElementById("errBlock").style.display = "block";
    document.getElementById("errBlock").innerText = data["msg"];
  }
});
})

