var infoTags = document.getElementsByClassName('info');

for(index = 0; index < infoTags.length; index++) {
    const info = infoTags[index].getElementsByTagName('a')[0].innerText;
    infoTags[index].getElementsByTagName('a')[0].innerText = info.replace(/\s+/g, '-').toLowerCase();
}