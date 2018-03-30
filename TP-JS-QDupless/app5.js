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
var sub = document.getElementsByClassName('btn btn-primary btn-sm float-right')
console.log(li);
sub[0].addEventListener('click', function(e){
    console.log(sub[0]);
    li[0].style.display = "none";
})

sub[1].addEventListener('click', function(e){
    console.log(sub[0]);
    li[1].style.display = "none";
})

sub[2].addEventListener('click', function(e){
    console.log(sub[0]);
    li[2].style.display = "none";
})

sub[3].addEventListener('click', function(e){
    console.log(sub[0]);
    li[3].style.display = "none";
})

sub[4].addEventListener('click', function(e){
    console.log(sub[0]);
    li[4].style.display = "none";
})

sub[5].addEventListener('click', function(e){
    console.log(sub[0]);
    li[5].style.display = "none";
})