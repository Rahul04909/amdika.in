<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Courier Tracking</title>

<style>
body{
    font-family:Arial;
    background:#f4f4f4;
}

.container{
    width:500px;
    margin:50px auto;
    background:#fff;
    padding:20px;
    border-radius:8px;
    box-shadow:0 0 10px #ccc;
}

input{
    width:100%;
    padding:12px;
    font-size:18px;
}

button{
    width:100%;
    margin-top:10px;
    padding:12px;
    background:#ff6600;
    color:#fff;
    border:none;
    cursor:pointer;
}

.card{
    margin-top:20px;
    padding:15px;
    border:1px solid #ddd;
    border-radius:6px;
}

.timeline{
    border-left:3px solid #ff6600;
    margin-top:20px;
    padding-left:15px;
}

.timeline div{
    margin-bottom:20px;
}
</style>

</head>

<body>

<div class="container">

<h2>Track Shipment</h2>

<input
id="awb"
placeholder="Enter AWB Number"
value="26040200188266">

<button onclick="trackShipment()">

Track

</button>

<div id="result"></div>

</div>

<script>

async function trackShipment(){

let awb=document.getElementById("awb").value.trim();

if(!awb){
alert("Enter AWB");
return;
}

let url="https://apis-hubops.innofulfill.com/tracking/v2/"+awb;

try{

let res=await fetch(url);

let data=await res.json();

console.log(data);

showResult(data);

}catch(e){

document.getElementById("result").innerHTML=e;

}

}

function showResult(data){

let html="";

if(data.orderInformation){

html+=`
<div class="card">

<h3>Shipment Details</h3>

<b>Tracking :</b> ${data.orderInformation.trackingId}<br>

<b>Status :</b> ${data.orderInformation.currentStatus}<br>

<b>Origin :</b> ${data.orderInformation.origin}<br>

<b>Destination :</b> ${data.orderInformation.destination}<br>

</div>
`;

}

if(data.statuses){

html+="<div class='timeline'>";

data.statuses.forEach(function(i){

html+=`
<div>

<h4>${i.status}</h4>

<p>${i.location}</p>

<small>${i.date}</small>

</div>
`;

});

html+="</div>";

}

document.getElementById("result").innerHTML=html;

}

</script>

</body>
</html>