<style>
input[type="number"]::-webkit-inner-spin-button,
input[type="number"]::-webkit-outer-spin-button {
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
  margin: 0;
}
.body {
  margin: 0%;
  overflow: hidden;
}
.sec {
  height: 400%;
  color: #ffffff;
  text-align: justify;
  background-attachment: fixed;
  background-size: cover;
  background-repeat: no-repeat;
  background-position: center;
}

.span {
  border: 0px solid red;
  border-radius: 5px;
  background: rgba(255, 255, 255, 0.3);
  box-sizing: border-box;
  color: white;
  display: inline-block;
  padding: 0 30px 0 10px;
  width: 40%;
  margin: 3%;
  height: 30px;
  justify-content: center;
  font-family: Sans-serif;
}
</style>
<template>
  <div class="body" entryMode="Add">
    <notifications group="notification" />
    <section class="sec" style="background-image: url(images/login/login.jpg);">
      <div class="app flex-row align-items-center">
        <div class="container">
          <div class="row justify-content-center">
            <div
              class="row"
              style="background: rgb(0, 0, 0); background: rgba(0, 0, 0, 0.3);color: #f1f1f1;padding: 20px; width: 50%; "
            >
              <div class="row">
                <div class="col">
                  <h3>Registration for Staff</h3>
                  <label>It’s quick and easy. Enjoy!</label>
                </div>
              </div>
              <div class="row">
                <div class="col-6">
                  <div class="input-group mb-3">
                    <span class="input-group-addon" placeholder="ID NO.">
                      <i class="icon-user-follow"></i>
                    </span>
                    <b-form-input
                      disabled
                      class="form-control"
                      placeholder="OLFU - XXXX-XXXX-XXXX"
                      hover
                      title="Auto Generate"
                    />
                  </div>
                  <div class="input-group mb-3">
                    <span class="input-group-addon">
                      <i class="fa fa-hashtag"></i>
                    </span>

                    <b-form-input
                      type="number"
                      class="form-control"
                      placeholder="ID NO."
                      v-model="forms.registerstaff.fields.id_number"
                    />
                  </div>
                  <div class="input-group mb-3">
                    <span class="input-group-addon">
                      <i class="icon-user"></i>
                    </span>
                    <b-form-input
                      type="text"
                      class="form-control"
                      placeholder="Full Name"
                      v-model="forms.registerstaff.fields.staff_name"
                    />
                  </div>

                  <div class="input-group mb-3">
                    <span class="input-group-addon">
                      <i class="icon-phone"></i>
                    </span>
                    <b-form-input
                      type="number"
                      class="form-control"
                      placeholder="Contact No."
                      v-model="forms.registerstaff.fields.contact_number"
                    />
                  </div>
                </div>

                <div class="col-6">
                  <div class="input-group mb-3">
                    <span class="input-group-addon">
                      <i class="icon-home"></i>
                    </span>

                    <select2
                      ref="department_id"
                      :allowClear="false"
                      :placeholder="'Select Department'"
                      v-model="forms.registerstaff.fields.department"
                    >
                      <option value>A</option>
                      <option value>B</option>
                      <option value>C</option>
                    </select2>
                  </div>

                  <div class="input-group mb-3">
                    <span class="input-group-addon">
                      <i class="icon-user"></i>
                    </span>
                    <b-form-input
                      type="text"
                      class="form-control"
                      placeholder="Username"
                      v-model="forms.registerstaff.fields.username"
                    />
                  </div>

                  <div class="input-group mb-3">
                    <span class="input-group-addon">
                      <i class="icon-lock"></i>
                    </span>
                    <b-form-input
                      type="password"
                      class="form-control"
                      placeholder="Password"
                      v-model="forms.registerstaff.fields.password"
                    />
                  </div>

                  <div class="input-group mb-3">
                    <span class="input-group-addon">
                      <i class="icon-lock"></i>
                    </span>
                    <b-form-input
                      type="password"
                      class="form-control"
                      placeholder="Repeat password"
                      v-model="forms.registerstaff.fields.password_confirmation"
                    />
                  </div>
                  <input />
                </div>

                <button
                  type="button"
                  class="btn btn-block btn-success"
                  @click="register()"
                >Create Account</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
export default {
  // name: "registerstaff",
  // data() {
  //   return {
  //     entryMode: "Add",

  //     forms: {
  //       registerstaff: {
  //         fields: {
  //           username: null,
  //           department: null,
  //           contact_number: null,
  //           staff_name: null,
  //           password: null,
  //           password_confirmation: null,
  //           success: null
  //         }
  //       }
  //     }
  //   };
  name: "registerstaff",
  data() {
    return {
      entryMode: "Add",
      forms: {
        registerstaff: {
          success: null,
          fields: {
            username: null,
            department: null,
            contact_number: null,
            staff_name: null,
            password: null,
            password_confirmation: null,
            id_number: null
          }
        }
      }
    };
  },
  methods: {
    register() {
      if (this.entryMode == "Add") {
        //name of form
        //if from a modal?
        //name of table to be inserted
        this.createEntity("registerstaff", false, "registerstaff");
      }
    }
  }
};
</script>
