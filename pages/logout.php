<script>
function get_cookie(name){
    return document.cookie.split(';').some(c => {
        return c.trim().startsWith(name + '=');
    });
}
   

function delete_cookie() {
  if( get_cookie( 'authToken' ) ) {
    document.cookie = name + "=" +
      (('/') ? ";path="+'/':"")+
      (('.dev.lemecha.fr')?";domain="+'.dev.lemecha.fr':"") +
      ";expires=Thu, 01 Jan 1970 00:00:01 GMT";
  }
}

delete_cookie()
window.location.replace('https://dev.lemecha.fr/')
</script>