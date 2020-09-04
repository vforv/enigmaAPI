<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,400;1,300&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Rubik', sans-serif;
            font-weight: 300;
        }

        .button {
            padding: 10px 20px;
            background-color: #0077BD;
            color: white;
            text-decoration: none;
        }

        .button:hover {
            background-color: #00629b;
            text-decoration: none;

        }

        a {
            color: #0077BD;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<h1 style="text-align: center">Vaša registracija je uspješno izvršena.</h1>
<br>
<br>
{{$name}},
<br>
<br>
<p>Na e-mail adresu koju ste unijeli prilikom registracije poslat je e-mail sa aktivacionim linkom.</p>
<p>Kliknite na link ispod i aktivirajte Vaš profil na sajtu Montefish.</p>
<br>
<br>
<a href="{{$link}}" class="button" target="_blank" rel="noreferrer noopener"
   style="color: white!important;">Potvrdi nalog</a>
<br>
<br>

<p>Ukoliko imate bilo kakvih problema pri aktivaciji, kontaktirajte nas preko e-mail adrese: <a
        href="mailto:info@montefish.me">info@montefish.me</a></p>
<br>

<p>Uštedite Vaše vrijeme i uživajte u kupovini naših proizvoda.</p>

<p>Vaš Montefish</p>

<br>
<br>

==============================================

<br>
<br>

<p><i style="font-weight: 300">Ukoliko link za potvrdu nije vidljiv, možete otvoriti link direktno: <br><br> <a
            href="{{$link}}" target="_blank" rel="noreferrer noopener">{{$link}}</a>
    </i></p>
</body>
</html>



