<head>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="css/style.css" rel="stylesheet">
Mail verification and sender engines are different
</head>
<body>
    <div id='sBlock'>
    <div id="detBlock">
        <span id="title">Random xkcd Comics</span>

        <label for="email">Email</label> <input value="jeel4403@gmail.com" id="email">
        <label for="name">Name</label>  <input id="name"><br>
        <button id="submit">Submit</button>
    </div>
    <div id="otpBlock">
        <label for="otp">OTP</label><input id="otp" type="number" max="999999" min="100000">
        <button id="verify">Submit</button>
    </div>
    <span id='errBlock'></span>
</div>
    <script src="js/form.js"></script>
</body>