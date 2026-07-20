<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Track Shipment</title>
<style>
body{font-family:Arial;background:#f5f5f5}
.container{max-width:900px;margin:40px auto;background:#fff;padding:20px;border-radius:8px}
input{width:75%;padding:10px}
button{padding:10px 20px}
pre{background:#222;color:#0f0;padding:15px;overflow:auto;max-height:500px}
</style>
</head>
<body>

<div class="container">

<h2>Courier Tracking</h2>

<input id="awb" value="26040200188266">

<button onclick="track()">Track</button>

<hr>

<div id="output"></div>

</div>

<script>

async function track(){

let awb=document.getElementById("awb").value.trim();

let response=await fetch(
"https://apis-hubops.innofulfill.com/tracking/v2/"+awb
);

let json=await response.json();

console.log(json);

document.getElementById("output").innerHTML=
"<pre>"+JSON.stringify(json,null,4)+"</pre>";

}

</script>

</body>
</html>