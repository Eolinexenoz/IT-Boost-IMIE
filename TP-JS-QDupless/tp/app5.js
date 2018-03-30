var structure = [
    {
        'tag': 'li',
        'content': 'task 1'
    },
    {
        'tag': 'li',
        'content': 'task 2'
    },
    {
        'tag': 'li',
        'content': 'task 3'
    },
    {
        'tag': 'li',
        'content': 'task 4'
    },{
        'tag': 'li',
        'content': 'task 5'
    }
]
var li = document.getElementsByClassName('list-group-item');
structure.forEach(function(item)
{
    var ul = document.getElementsByClassName('list-group');
    var a = document.createElement('a');
    var childTag = document.createElement(item.tag);
    var childText = document.createTextNode(item.content);
    childTag.className = "list-group-item"
    ul[0].appendChild(childTag);
    childTag.appendChild(childText);
    childTag.appendChild(a);
    a.className = "btn btn-primary btn-sm float-right";
    a.href = "#";
    a.innerHTML = "delete";
})

var sub2 = document.querySelectorAll('li');
sub2.forEach(function(e){
    e.addEventListener('click', function(a){
        e.style.display = "none";
    })
})
