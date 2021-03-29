<template>
  <div class="sidebar">
    <nav class="sidebar-nav">
      <div slot="header"></div>
      <ul class="nav">
        <li class="nav-item" v-for="(item, index) in navItems">
          <template v-if="item.title">
            <SidebarNavTitle :name="item.name" />
          </template>
          <template v-else-if="item.divider">
            <li class="divider"></li>
          </template>
          <template v-else>
            <template v-if="item.children" >
              <SidebarNavDropdown v-if="item.type.includes($store.state.user.type)" :name="item.name" :url="item.url" :icon="item.icon">
                <template v-for="(child, index) in item.children" >
                  <template v-if="child.children" >
                    <SidebarNavDropdown :name="child.name" :url="child.url" :icon="child.icon" >
                      <li class="nav-item" v-for="(child, index) in item.children" >
                        <SidebarNavLink :name="child.name" :url="child.url" :icon="child.icon" :badge="child.badge" />
                      </li>
                    </SidebarNavDropdown>
                  </template>
                  <template v-else>
                    <li class="nav-item" style="margin-left:20px;" >
                      <SidebarNavLink v-if="child.type.includes($store.state.user.type)"  :name="child.name" :url="child.url" :icon="child.icon" :badge="child.badge"/>
                    </li>
        
                  </template>
                </template>
              </SidebarNavDropdown>
            </template>
            <template v-else>
              <SidebarNavLink :name="item.name" :url="item.url" :icon="item.icon" :badge="item.badge" />
            </template>
          
          </template>
        </li>
      </ul>
      <slot> 
        <!-- <br>
        <center>
      <b-img :src="getProfilePhoto()" class="avatar" style="width:50%;"/> <br>
           <br>
        <a style="font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif ;cursor: pointer;
          font-weight: 400; font-size: 16px;" @click="$router.push({name: 'Profile'})" ><i class="fa fa-user" aria-hidden="true"></i> <span>{{$store.state.user.shop_name}}</span></a>
          <br><br>
           <a style="font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif ; cursor: pointer;
          font-weight: 400; font-size: 16px;" @click="logOut()"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a>
          </center> -->
          </slot>
      <div slot="footer"><br><center><a href="#" @click="printTerms(data)">Terms & Condition</a></center></div>
    </nav>
  </div>
</template>
<script>
import SidebarNavDropdown from './SidebarNavDropdown'
import SidebarNavLink from './SidebarNavLink'
import SidebarNavTitle from './SidebarNavTitle'
export default {
  name: 'sidebar',
  data () {
      return {
        
      }
  },
  props: {
    navItems: {
      type: Array,
      required: true,
      default: () => []
    }
  },
  components: {
    SidebarNavDropdown,
    SidebarNavLink,
    SidebarNavTitle
  },
  methods: {
     printTerms(data) {
      window.open("api/terms");
      },
    handleClick (e) {
      e.preventDefault()
      e.target.parentElement.classList.toggle('open')
    },
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
    checkRight(type) {
      return type.includes(Number(this.user.type))
    }
  },
  computed: {
    user() {
      return this.$store.state.user
    }
  }
}
</script>
<style lang="css">
  .nav-link {
    cursor:pointer;
  }
</style>
