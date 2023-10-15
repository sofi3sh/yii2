$(document).ready(function(){
    $('#basic-modules').change(function(){
        const files = $('#basic-modules').prop("files");
        let filesList = $('#files-list');
        filesList.html(' ');
        $.map(files, function(file) {
            const fileName = document.createElement('li');
            fileName.innerHTML = file.name;
            filesList.append(fileName);
        });
    })
});