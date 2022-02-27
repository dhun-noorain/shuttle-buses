$(document).ready(function(){
  $.get('seats.php', function(data){
    var seats = data.split(',');

    if(jQuery.trim(seats) != "")
    {
      for(let j = 0; j <= seats.length; j++)
      {
        $("#seats :button").each(function(){
          if($(this).val() == seats[j])
          {
            $(this).removeClass("text-success").addClass("text-danger");
          }
        });
      }
    }
  });
});