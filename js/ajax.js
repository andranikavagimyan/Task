$(document).ready(function(){

    $("#but_upload").click(function(){

        var fd = new FormData();
        var files = $('#my-file')[0].files;
        
        if(files.length > 0 ){
           fd.append('file',files[0]);

           $.ajax({
              url: 'php/matching.php',
              type: 'post',
              data: fd,
              contentType: false,
              processData: false,
              success: function(response){
                var data=$.parseJSON(response);
                $('table').show();
                $('table tbody').html('');
                $.each( data.employees, function( key, value ) {
                    $('table tbody').append("<tr><td>"+value.Name+"</td><td>"+value.Email+"</td><td>"+value.Division+"</td><td>"+value.Age+"</td>><td>"+value.Timezone+"</td></tr>");
                  });
                  $(".matching").text('The highest average match score is '+ data.matching+'%');
              },
           });
        }else{
           alert("Please select a file.");
        }
    });
});