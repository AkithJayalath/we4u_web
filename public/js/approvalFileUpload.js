document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('documents');
    const fileList = document.getElementById('file-list');
  
    fileInput.addEventListener('change', function() {
      fileList.innerHTML = ''; // Clear any previous file list
  
      // Display names of uploaded files
      Array.from(this.files).forEach(file => {
        const listItem = document.createElement('p');
        listItem.textContent = file.name;
        fileList.appendChild(listItem);
      });
    });
  });
  