function readImage(input) {
    if (input.files && input.files[0]) {
      var imageReader = new FileReader();
      imageReader.onload = function (e) {$('#' + input.id + '_preview').attr('src', e.target.result) };
      imageReader.readAsDataURL(input.files[0]);
      document.getElementById(input.id + '_remove').style.display = 'flex';
      document.getElementById(input.id + '_label').innerHTML = input.files[0].name;
    }
  }
  

function clearInput(inputID) {
    const element = document.getElementById(inputID);
    const newInput = document.createElement('input');

    newInput.setAttribute('id', inputID);
    newInput.setAttribute('name', inputID);
    newInput.setAttribute('type', 'file');
    newInput.setAttribute('class', 'custom-file-input');
    newInput.setAttribute('accept', element.accept);
    
    element.parentNode.replaceChild(newInput, element);
    document.getElementById(inputID + '_label').innerHTML = 'Choose file';

    if(element.accept == '.png') {
      newInput.setAttribute('onchange', 'readImage(this)');
      document.getElementById(inputID + '_remove').style.display = 'none';
      loadNoImagePreview(inputID);
    }
    else if(element.accept == '.pdf') {
      initiallizePdfPreview(inputID);
      document.getElementById(inputID + '_preview').style.display = 'none';
      document.getElementById(inputID + '_preview_default').style.display = 'inline-block';
      document.getElementById(inputID + '_remove').style.display = 'none';
    }
}