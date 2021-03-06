$(function(){
  $("#value").on("keyup",onChange(this));
});

function onChange(e){
  var old = v =$(e).find('#value').val();
  return function(){
    v = $(e).find('#value').val();
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
    $("#output").html(text.replace(/ /g,"&nbsp;"));
    setTweet(text);
  });
}

function setTweet(text){
  $('#tweet-area').empty();
  twttr.widgets.createShareButton(
    "",
    document.getElementById("tweet-area"),
    {
      size: "large",
      text: text,
      hashtags: "tategaki",
      url: "//lovesaemi.daemon.aisa/tategaki"
    }
  );
}