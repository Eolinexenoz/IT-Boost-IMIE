var block = document.getElementsByTagName('div');
console.log(block);

var ft_fade = function(e)
{
	block[0].className = "square fade";
}

block[0].addEventListener('click', ft_fade);