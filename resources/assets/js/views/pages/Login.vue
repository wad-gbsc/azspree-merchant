<style scoped>
.body {
  margin: 0%;
  overflow: hidden;
}
.sec {
  height: 400%;
  color: white;
  text-align: justify;
  background-attachment: fixed;
  background-size: cover;
  background-repeat: no-repeat;
  background-position: center;
}
.titlelabeltop {
  font-size: 15px;
}

.titlelabelbot {
  font-size: 12px;
}

@media screen and (max-width: 992px) {
  div.back {
    width: 80%;
  }
}
@media screen and (max-width: 320px) {
  div.back {
    width: 80%;
  }
}
</style>
<template>
<div>
<body class="body"  style="background-image: url('images/BACKGROUNDLOGIN.jpg'); background-repeat: no-repeat;
  background-attachment: fixed;
  background-size: 100% 100%;" >
  <notifications group="notification" />
  <section class="sec">
    <div class="app flex-row align-items-center">
      <div class="container">
        <div>
         <center>
          <div
            class="row back "
            style="background: rgb(255,255, 255);
             background: rgba(255, 255, 255, 0.7);
             color: #f1f1f1;
             padding: 20px;
             height: auto;
             border-radius: 5px;
             width: 320px;"
             
          >
            <div class="col-12">
              <h1 style="color: #17a2b8">Login</h1>
              <hr style="width:99%;height:10px;text-align:left;margin-left:0">
              <b-col lg="8">
                
              </b-col>
              <b-form @submit.prevent="authLogin()" @input="login.success = null">
                <div class="input-group mb-3">
                  <span class="input-group-addon">
                    <i class="icon-user"></i>
                  </span>
                  <b-form-input
                    ref="email"
                    v-model="login.email"
                    type="text"
                    placeholder="Email"
                  ></b-form-input>
                </div>
                <div class="input-group mb-4">
                  <b-form-invalid-feedback>
                    <i class="fa fa-exclamation-triangle text-danger"></i>
                    <span v-if="login.success==false">Incorrect email or password.</span>
                  </b-form-invalid-feedback>

                  <span class="input-group-addon">
                    <i class="icon-lock"></i>
                  </span>
                  <b-form-input v-model="login.password" type="password" placeholder="Password"></b-form-input>
                </div>
                <div class="row">
                  <div class="col-12">
                    <b-btn :disabled="login.is_saving" type="submit" variant="primary" block px-4>Login</b-btn>
                  </div>
                </div>
             <hr style="width:99%;height:10px;text-align:left;margin-left:0">
              </b-form>
            </div>
              <!-- <div class="card-body text-center">
               <img src="images/azspreelogo.png" >
              </div> -->

              <b-modal @shown="focusElement('new_password')" size="sm" v-model="showModalChangePassword" :noCloseOnEsc="true" :noCloseOnBackdrop="true" 
              header-bg-variant="primary"
              header-text-variant="light">
              <template #modal-header="{ }">
                <!-- Emulate built in modal header close button action -->
              <h5>Change Password</h5>
              </template>
              <label for="new_password"> New Password</label>
              <b-form-input 
              class="form-control"
              name="new_password"
              id="new_password"
              ref="new_password"
              type="password"
              v-model="login.new_password"
              @keyup.enter="ChangePassword">
                </b-form-input>
              <label for="confirm_password"> Confirm Password</label>
              <b-form-input 
              class="form-control" 
              name="confirm_password" 
              type="password"
              id="confirm_password"
              ref="confirm_password"
              v-model="login.confirm_password"
               @keyup.enter="ChangePassword">
              </b-form-input>
               <div slot="modal-footer">
                  <b-button
                    class="button"
                    :disabled="login.is_Saving"
                    variant="primary"
                    @click="ChangePassword"
                   
                  >
                    <icon v-if="login.is_Saving" name="sync" spin></icon>
                    <i class="fa fa-check"></i>
                    Sumbit
                  </b-button>
                </div>
              </b-modal>
            </div>
            </center>
          </div>
          <div>
          </div>
        </div>
      </div>
  </section>
</body> 
</div>
</template>

<script>
export default {
  name: "Login",
  data() {
    return {
      showModalChangePassword: false,
      login: {
        email: null,
        password: null,
        is_saving: false,
        new_password: null,
        confirm_password: null,

      }
    };
  },
  methods: {

  
    ChangePassword() {
      if (this.login.new_password == null || this.login.new_password == "") {
          this.focusElement('new_password')
          this.$notify({
          type: 'error',
          group: 'notification',
          title: 'Error!',
          text: 'Please enter New password'
        })
      }else if (this.login.confirm_password == null || this.login.confirm_password == "") {
         this.focusElement('confirm_password')
          this.$notify({
          type: 'error',
          group: 'notification',
          title: 'Error!',
          text: 'Please enter Confirm password'
        })
      }else if (this.login.new_password != this.login.confirm_password) {
          this.$notify({
          type: 'error',
          group: 'notification',
          title: 'Error!',
          text: 'Password do not match'
        })
        }else{
      
      this.login.is_saving = true;
      this.$http
        .put(
          "api/changepassword/" + this.$store.state.user.sumr_hash,
          this.login,
          {
            headers: {
              Authorization: "Bearer " + localStorage.getItem("token")
            }
          }
        )
        .then(response => {
          this.login.is_saving = false;
          this.$notify({
            type: "success",
            group: "notification",
            title: "Success!",
            text: "Change Password Successful."
          });

            this.showModalChangePassword = false;
            this.focusElement("password");
            this.login.password = "";
            
        })
        .catch(error => {
          this.login.is_saving = false;
          if (!error.response) return;
          const errors = error.response.data.errors;
          var a = 0;
          for (var key in errors) {
            // this.forms[entity].states[key] = false
            // this.forms[entity].errors[key] =  errors[key]
            if (a == 0) {
              this.focusElement('new_password', false);
              this.$notify({
                type: "error",
                group: "notification",
                title: "Error!",
                text: errors[key][0]
              });
            }

            a++;
          }
        });
    }
    },
      authLogin(){
      this.login.is_saving = true
      this.$http.post('api/auth/login', { 
                    email: this.login.email,
                    password: this.login.password
                }).then(response => {
                    this.$store.commit('loginUser')
                    this.$store.commit('user', response.data.user)
                    localStorage.setItem('token', response.data.access_token)
                    if (this.$store.state.user.is_first == 0) {
                      this.fillEntityForm('sumr',this.$store.state.user.sumr_hash)
                      this.showModalChangePassword = true
                    }else{
                    this.$notify({
                      type: 'success',
                      group: 'notification',
                      title: 'Success!',
                      text: 'User Authenticated successfully.'
                    })
                    setTimeout(function(){
                    this.$router.push({ name: 'Dashboard' })
                    }.bind(this), 1000)
                    this.login.is_saving = false
                    }
                }).catch(err => {
                      this.$notify({
                        type: 'error',
                        group: 'notification',
                        title: 'Error!',
                        text: 'Incorrect username or password.'
                      })
                      this.login.is_saving = false
      });
    }
  },
  mounted() {
    this.focusElement("email");
  }
};
</script>
