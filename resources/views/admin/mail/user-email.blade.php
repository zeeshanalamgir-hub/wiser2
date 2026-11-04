<!DOCTYPE html>
<html>

<head>
<meta charset="UTF-8">
<title>{{ $subject }}</title>

<style>
body{
    margin:0;
    background:#F7F7F7;
    font-family:sans-serif, Arial, Helvetica;
}

table{
    border-spacing:0;
}

td{
    padding:0;
}

img{
    border:0;
}

.wraper{
    width:100%;
    table-layout:fixed;
    background:#F7F7F7;
    padding:20px 0;
}

.main{
    background:#ffffff;
    max-width:626px;
    width:100%;
    margin:0 auto;
    color:#171a1b;
}

.two-columns{
    text-align:center;
    font-size:0;
}

.two-columns .column{
    text-align:center;
    width:100%;
    max-width:300px;
    display:inline-block;
    vertical-align:top;
}

.header{
    background:#003F45;
    padding:10px;
}

.content{
    padding:35px;
    color:#171a1b;
    font-size:15px;
    line-height:1.5;
}

.footer{
    background:#003F45;
    height:8px;
}

.social-links{
    font-size:20px;
}

.social-links a{
    color:#ffffff;
    text-decoration:none;
    margin:0 5px;
    display:inline-block;
}

.social-links a:hover{
    opacity:0.8;
}

/* For better email client compatibility */
.pt-1{
    padding-top:5px;
}

.fs-5{
    font-size:18px;
}

.me-2{
    margin-right:8px;
}
</style>

<!-- Font Awesome for icons (works in most modern email clients) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

</head>

<body>

<center class="wraper">

<table class="main" width="100%" cellpadding="0" cellspacing="0" border="0">

<tr>
<td class="header">

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
<td class="two-columns">

<!-- Logo Column -->
<table class="column" cellpadding="0" cellspacing="0" border="0">
<tr>
<td width="300" align="left">
<a href="https://titanlance.com/" target="_blank">
<img src="https://titanlance.com/assets/images/logo.svg" width="170" alt="Wiserlance" style="display:block;">
</a>
</td>
</tr>
</table>

<!-- Social Icons Column -->
<table class="column" cellpadding="0" cellspacing="0" border="0">
<tr>
<td width="300" align="right" class="social-links fs-5 pt-1">
<a href="https://facebook.com/wiserlance" class="me-2"><i class="fab fa-facebook-square"></i></a>
<a href="https://linkedin.com/company/wiserlance" class="me-2"><i class="fab fa-linkedin"></i></a>
<a href="https://twitter.com/wiserlance" class="me-2"><i class="fab fa-twitter-square"></i></a>
<a href="https://instagram.com/wiserlance" class="me-2"><i class="fab fa-instagram"></i></a>
</td>
</tr>
</table>

</td>
</tr>
</table>

</td>
</tr>


<tr>
<td class="content">

<p>Greetings&nbsp; ({{$username}}),</p>
{!! $body !!}

</td>
</tr>


<tr>
<td class="footer"></td>
</tr>

</table>

</center>

</body>

</html>