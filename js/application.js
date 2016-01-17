$(function(){
      $("#value").on("keyup",onChange(this));
});

function onChange(e){
    var old = v=$(e).find('#value').val();
    return function(){
        v=$(e).find('#value').val();
        if(old != v){
            old = v;
            change();
        }
    };
}

function change(){
    var value = $("#value").val();
    $.ajax({
               url : "api.php",
               data : {
                 value : value  
               },
               dataType : "text"
           }).done(function(text){
                       $("#output").html(text);
           });
}