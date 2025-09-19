var script = document.createElement('script');


script.src = "https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js";
script.type = "text/javascript";

const arr = [];

function avg(arr){

	let sum = 0;

	for(let i=0; i<arr.length; ++i) {
		
		sum += arr[i];
	}

	sum /= arr.length;

	return sum;

};

script.onload = function(){
	$(document).ready(function(){

		//$('add').onclick()
		$('#add').click(function(){

			alert("Added");

			if(!isNaN(Number($('#val').val()))){
				arr.push(Number($('#val').val()));
				console.log("New length: " + arr.length);
				$('#val').val(avg(arr));	
			}
		});

		$('#sub').click(function(){

			alert("Subbed");
			arr.pop();
			console.log("New length: " + arr.length);

		});

		console.log("Hi there");

		alert("Hello");

	});

};

document.head.appendChild(script);
