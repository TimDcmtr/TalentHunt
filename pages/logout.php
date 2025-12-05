<script>
function get_cookie(name){
    return document.cookie.split(';').some(c => {
        return c.trim().startsWith(name + '=');
    });
}
   

function delete_cookie() {
  if( get_cookie( 'authToken' ) ) {
    document.cookie = 'authToken' + "=" +
      (('/') ? ";path="+'/':"")+
      (('.lemecha.fr')?";domain="+'.lemecha.fr':"") +
      ";expires=Thu, 01 Jan 1970 00:00:01 GMT";
  }
}

delete_cookie()
window.location = "https://dev.lemecha.fr/"
</script>