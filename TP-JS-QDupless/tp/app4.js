var squares = document.querySelectorAll('.square');
squares.forEach(function(e){
	e.addEventListener('click', function(e){
		var select = document.querySelectorAll('.selected');
		if (select[0])
			select[0].className = "square";
		e.originalTarget.className = "square selected";
	})
})