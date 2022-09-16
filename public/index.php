<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="theme-color" content="#df6303">

<link href="css/style.css" rel="stylesheet">
<!Mail verification and sender engines are different>
</head>
<body>


    <div id='sBlock'>
    <div class="loader">
  <div class="line"></div>
  <div class="subline inc"></div>
  <div class="subline dec"></div>
</div>
    <div id='kBlock'>
    <div id="detBlock">
        <span id="title">Random xkcd Comics</span>

        <label for="email">Email</label> <input id="email">
        <label for="name">Name</label>  <input id="name"><br>
        <button id="submit">Submit</button>
    </div>
    <div id="otpBlock">
        <span id="back">< Back</span>
        <br>
        <label for="otp">OTP</label><input id="otp" type="number" max="999999" min="100000">
        <button id="verify">Verify</button>
    </div>
    <span id='errBlock'></span>
</div>
</div>
    <script src="js/form.js"></script>
</body>