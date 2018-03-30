var sub = document.getElementsByTagName('button');
var x = 0;
var ft_add = function(e)
{
	var ul = document.getElementsByTagName('ul');
	var li = document.createElement('li');
	var text = document.createTextNode('content ' + x);
	ul[0].appendChild(li);
	li.appendChild(text);
	x++;
}

sub[0].addEventListener('click', ft_add);
