<template>
  <div>
    <button class="fb-button" @click="logInWithFacebook"> Login with Facebook</button>
  </div>
</template>
<script>
export default {
    name:"facebookLogin",
    methods: {
        async logInWithFacebook() {
        //   await this.loadFacebookSDK(document, "script", "facebook-jssdk");
        //   await this.initFacebook();
        //       window.FB.login(function(response) {
        //     if (response.authResponse) {
        //       alert("You are logged in &amp; cookie set!");
        //       // Now you can redirect the user or do an AJAX request to
        //       // a PHP script that grabs the signed request from the cookie.
        //     } else {
        //       alert("User cancelled login or did not fully authorize.");
        //     }
        //   });
        this.loadFacebookSDK(document, "script", "facebook-jssdk").then(() => {
            this.initFacebook().then(() => {
                console.log(window.FB);
                window.FB.login(function(response) {
                    if (response.authResponse) {
                    alert("You are logged in &amp; cookie set!");
                    // Now you can redirect the user or do an AJAX request to
                    // a PHP script that grabs the signed request from the cookie.
                    } else {
                    alert("User cancelled login or did not fully authorize.");
                    }
                });
            });
        });
      

      return false;
    },
    async initFacebook() {
      window.fbAsyncInit = function() {
        window.FB.init({
          appId: "120385808817721", //You will need to change this
          cookie: true, // This is important, it's not enabled by default
          version: "v13.0"
        });
      };
    },
    async loadFacebookSDK(d, s, id) {
      var js,
        fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) {
        return;
      }
      js = d.createElement(s);
      js.id = id;
      js.src = "https://connect.facebook.net/en_US/sdk.js";
      fjs.parentNode.insertBefore(js, fjs);
    }
  }
};
</script>
<style>
.fb-button{
    font-family: Lucida Grande, Helvetica Neue, Helvetica, Arial, sans-serif;
    display: inline-block;
    font-size: 14px;
    padding: 13px 30px 15px 44px;
    background: #3A5A97;
    color: #fff;
    text-shadow: 0 -1px 0 rgba(0,0,20,.4);
    text-decoration: none;
    line-height: 1;
    position: relative;
    border-radius: 5px;
}
</style>