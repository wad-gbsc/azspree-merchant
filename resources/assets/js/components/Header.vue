<style scoped lang="scss">
.avatar {
  vertical-align: middle;
  width: 40px;
  height: 40px;
  border-radius: 50%;
}
.dropdown-toggle::after {
    display: none;
   
}
.navbar .nav > li > .dropdown-menu:before, .navbar .nav > li > .dropdown-menu:after{
    border-bottom: none;
}
</style>
<template >
  <header class="app-header navbar">
    <button
      class="navbar-toggler mobile-sidebar-toggler d-lg-none"
      type="button"
      @click="mobileSidebarToggle"
    >&#9776;</button>
    <b-link class="navbar-brand" to="/dashboard">
      <img class="navbar-brand-full" src="images/azspreelogo.png" width="120%" height="110%" alt="a" />
      <!-- <img class="navbar-brand-minimized" src="uploads/logo/logo" width="30" height="30" alt="a"> -->
    </b-link>
    <button
      class="navbar-toggler sidebar-toggler d-md-down-none"
      type="button"
      @click="sidebarMinimize"
    >&#9776;</button>
    <!-- <b-navbar-nav class="d-md-down-none">
      <b-nav-item class="px-3">Dashboard</b-nav-item>
      <b-nav-item class="px-3">Users</b-nav-item>
      <b-nav-item class="px-3">Settings</b-nav-item>
    </b-navbar-nav>-->
    <b-navbar-nav is-nav-bar class="ml-auto">
      <!-- <b-nav-item class="d-md-down-none">
        <i class="icon-bell"></i><span class="badge badge-pill badge-danger">5</span>
      </b-nav-item>
      <b-nav-item class="d-md-down-none">
        <i class="icon-list"></i>
      </b-nav-item>
      <b-nav-item class="d-md-down-none">
        <i class="icon-location-pin"></i>
      </b-nav-item> -->

      <b-nav-item-dropdown class="dropdown-toggle" right aria-expanded="true" >
      
        
        <div class="dropdown-header bg-light py-2 "><strong>Account</strong></div>
        <template slot="button-content" >
          <!-- image -->
          
          <b-img :src="'/images/profile'+ '/' + $store.state.user.photo" class="avatar" width="30%" height="30%"></b-img>&nbsp;
          <!-- <span style="font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif; -->
          <!-- font-weight: 400; font-size: 16px;">{{$store.state.user.shop_name}}</span> -->
        </template>
        <b-dropdown-item @click="profile()">
         <i class="fa fa-user-o"></i>Profile
        </b-dropdown-item>
        <b-dropdown-item @click="logOut()">
          <i class="fa fa-sign-out" aria-hidden="true"></i>Logout
        </b-dropdown-item>
      </b-nav-item-dropdown>
      <span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
    </b-navbar-nav>
    <!-- <button class="navbar-toggler aside-menu-toggler d-md-down-none" type="button" @click="asideToggle">&#9776;</button> -->
  </header>
</template>
<script>
export default {
  name: "c-header",
  methods: {

    profile() {
      setTimeout(function(){
        this.$router.push({ name: 'Profile' })
        }.bind(this), 500)
      },
      //  getProfilePhoto(){
      //         let photo = this.$store.state.user.photo == "default.png" ? "/images/profile/default.png" : "/images/profile/" +  this.$store.state.user.photo;
      //         return photo;
      //       },
    logOut() {
      if (localStorage.token) {
        this.$http
          .get("api/auth/logout", {
            headers: {
              Authorization: "Bearer " + localStorage.getItem("token")
            }
          })
          .then(response => {
            localStorage.removeItem("token");
            this.$store.commit("logoutUser");
            this.$router.push({ name: "Login" });
            Toast.fire({
                      icon: 'success',
                      title: 'Success!',
                      text: 'You have successfully logged out.'
                    })
          })
          .catch(err => {
            console.log(err);
          });
      }
    },
    sidebarToggle(e) {
      e.preventDefault();
      document.body.classList.toggle("sidebar-hidden");
    },
    sidebarMinimize(e) {
      e.preventDefault();
      document.body.classList.toggle("sidebar-minimized");
    },
    mobileSidebarToggle(e) {
      e.preventDefault();
      document.body.classList.toggle("sidebar-mobile-show");
    },
    asideToggle(e) {
      e.preventDefault();
      document.body.classList.toggle("aside-menu-hidden");
    }
  }
};
</script>
