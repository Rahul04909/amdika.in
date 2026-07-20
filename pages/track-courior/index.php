<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Track Shipment</title>
<style>
body{
    font-family:Arial;
    background:#f5f5f5;
}
.box{
    width:450px;
    margin:80px auto;
    background:#fff;
    padding:25px;
    border-radius:8px;
    box-shadow:0 0 10px rgba(0,0,0,.15);
}
input{
    width:100%;
    padding:12px;
    font-size:16px;
    margin-bottom:15px;
}
button{
    width:100%;
    padding:12px;
    background:#ff6b00;
    color:#fff;
    border:none;
    cursor:pointer;
}
#result{
    margin-top:20px;
    white-space:pre-wrap;
}
</style>
</head>

<body>

<div class="box">
<h2>Track Shipment</h2>

<input id="awb" placeholder="Enter AWB Number">

<button onclick="trackShipment()">
Track
</button>

<div id="result"></div>

</div>

<script>

async function trackShipment(){

let awb=document.getElementById("awb").value;

let url="https://shreemaruti.com/wp-json/oembed/1.0/embed?url=https://shreemaruti.com/track-shipment/";

try{

let res=await fetch(url);

let data=await res.json();

document.getElementById("result").innerHTML=
JSON.stringify(data,null,4);

}catch(e){

document.getElementById("result").innerHTML=e;

}

}

</script>

</body>
</html>