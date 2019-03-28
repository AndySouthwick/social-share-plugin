jQuery(document).ready( function($) {
	
	let getUrlParameter = function getUrlParameter(sParam) {
    let sPageURL = window.location.search.substring(1),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
        }
    }
};
	
	function checkForVarInUrl(){
		return localStorage.setItem("id", getUrlParameter('id'))
	}

function usanaShareLinks(){

  var loc = window.location;
  $("#popup-share-links #fb").attr("href", "https://www.facebook.com/sharer/sharer.php?u=" + loc);
  $("#popup-share-links #tw").attr("href", "https://www.twitter.com/share?url=" + loc);
  $("#popup-share-links #gl").attr("href", "https://plus.google.com/share?url=" + loc);

}
function updateShare(){
	console.log('updateShareRan')
   var loc = window.location;
   var variabelstring = "?id=";
   var datafixstring = Cookies.get('my_associates_id');

   if(datafixstring){
     datafixstring = datafixstring.toString();
     
     $("#myBtn #fb,#popup-share-links #fb").attr("href", "https://www.facebook.com/sharer/sharer.php?u=" + loc + variabelstring + datafixstring + '&source=facebook');
     $("#myBtn #tw,#popup-share-links #tw").attr("href", "https://www.twitter.com/share?url=" + loc + variabelstring + datafixstring + '&source=twitter')
     .attr("data-link",  loc + variabelstring + datafixstring + '&source=twitter');
     $(".copyTheLink").attr('onClick', 'Clipboard.copy("' + loc + variabelstring + datafixstring  + '&source=copiedlink' + '")');
     $('meta[property="og:url"]').attr("content", loc + variabelstring + datafixstring + '&source=og');
   }else{

     $("#myBtn #fb,#popup-share-links #fb").attr("href", "");
     $("#myBtn #tw,#popup-share-links #tw").attr("href", "")
     .attr("data-link",  "");
     $("#myBtn #gl,#popup-share-links #gl").attr("href", "");
   }
}
function askSharePopup(){

  $('.usana-share-form').on('submit', function(e){
    e.preventDefault();
    var $this = $(this);
    var associate_id = $this.find('#associate_id').val();
    //set cookie with this value

    if(associate_id){

      Cookies.set('my_associates_id', associate_id);
      $this.hide();
      //show social share icon after form
      updateShare();
    }

  });

}

function askSharePoupClose(){

  $('.modal-content .close').on('click', function(e){
    e.preventDefault()
    //hide model
    $('#myModal').hide();
    //relaod page
  });
}

function social_share_click(){

  var model_check = false;

  $("#myBtn").click(function(e){

    if (model_check) {
        model_check = false; // reset flag
        return; // let the event bubble away
    }

    if(!Cookies.get('my_associates_id')){
      e.preventDefault();
      $('#myModal').show();
      //update share buttons
      usanaShareLinks();
    }
    model_check = true; // set flag
    $(this).trigger('click');
  });

}

function shop_shortcode(){

  if(localStorage.getItem('id') !== "undefined"){

    $('a.shop-link').each(function() {
        var $this = $(this);
        $this.show();
//         var link = $this.attr('href').toString().replace('my_associates_id', Cookies.get('my_associates_id'));
       
                var link = $this.attr('href').toString().replace('my_associates_id', localStorage.getItem('id'));
        console.log(link);
        $this.attr('href',link);
    });
  } else {
	  $('a.shop-link').each(function() {
        var $this = $(this);
        $this.hide();})
  }

}

window.Clipboard = (function(window, document, navigator) {
  var textArea,
      copy;

  function isOS() {
      return navigator.userAgent.match(/ipad|iphone/i);
  }

  function createTextArea(text) {
      textArea = document.createElement('textArea');
      textArea.value = text;
      document.body.appendChild(textArea);
  }

  function selectText() {
      var range,
          selection;

      if (isOS()) {
          range = document.createRange();
          range.selectNodeContents(textArea);
          selection = window.getSelection();
          selection.removeAllRanges();
          selection.addRange(range);
          textArea.setSelectionRange(0, 999999);
      } else {
          textArea.select();
      }
  }

  function copyToClipboard() {        
      document.execCommand('copy');
      document.body.removeChild(textArea);
  }

  function toast() {
    const toast = document.createElement('div')
    const node = document.createTextNode('Your link has been copied!')
    toast.appendChild(node)
    const element = document.getElementById("myBtn");
    element.appendChild(toast)
    toast.setAttribute('id', 'alertSuccess')
    toast.setAttribute('class', 'alertFadeOut')
    setTimeout(() => {
     element.removeChild(toast)
    }, 4000)
  }

  copy = function(text) {
      createTextArea(text);
      selectText();
      copyToClipboard();
      toast();
  };

  return {
      copy: copy
  };
})(window, document, navigator);
askSharePopup();

updateShare();

social_share_click();
checkForVarInUrl();
shop_shortcode();

askSharePoupClose();


})
