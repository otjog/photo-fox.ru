var allWorks = document.getElementsByClassName('fancy-fox');
var allTagsContainer = document.getElementsByClassName('tags-container');
var standartTagClass = 'work-tags badge';
var activeTagClass = 'badge-primary';

allTagsContainer[0].addEventListener('click', function (e) {
    if(changeTagsButtons(e)){
        filterWorks(e);
    }
});

function filterWorks(e){
    var tag = e.target.dataset.filter;
    for(var i = 0; i < allWorks.length ; i++){
        allWorks[i].style.display = 'none';
    }

    for(var i = 0; i < allWorks.length ; i++){
        if(tag === ''){
            allWorks[i].style.display = 'block';
        }else{
            if(allWorks[i].dataset.filter === tag){
                allWorks[i].style.display = 'block';
            }
        }
    }
}

function changeTagsButtons(e){
    var currentTag = document.getElementsByClassName(activeTagClass);
    var currentTagName = currentTag[0].dataset.filter;
    var clickedTag = e.target.dataset.filter;
    if(currentTag === clickedTag){
        return false;
    }else{
        currentTag[0].className = standartTagClass;
        e.target.className = standartTagClass + ' ' + activeTagClass;
        return true;
    }

}