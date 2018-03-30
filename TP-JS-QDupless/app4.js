var square = document.getElementsByClassName('square');

square[0].addEventListener('click', function(e){
	square[0].className = "square selected";
	square[1].className = "square";
	square[2].className = "square";
	square[3].className = "square";
})

square[1].addEventListener('click', function(e){
	square[1].className = "square selected";
	square[0].className = "square";
	square[2].className = "square";
	square[3].className = "square";
})

square[2].addEventListener('click', function(e){
	square[2].className = "square selected";
	square[1].className = "square";
	square[0].className = "square";
	square[3].className = "square";
})

square[3].addEventListener('click', function(e){
	square[3].className = "square selected";
	square[1].className = "square";
	square[2].className = "square";
	square[0].className = "square";
})