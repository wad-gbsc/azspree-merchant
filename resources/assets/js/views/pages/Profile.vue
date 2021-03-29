<style>
.widget-user-header{
    background-position: center center;
    background-size: cover;
    height: 250px !important;
}
.widget-user .card-footer{
    padding: 0;
}
.plus-icon {
	position: relative;
	display: inline;
}

.plus-icon:hover .edit {
   
	display: inline;
}

.edit {
     margin-top: 30%;
    padding-top: 7px;	
    padding-right: 30px;
    position: absolute;
    right: 0;
    top: 0;
    display: none;
}
.widget-user-header{
    background-position: center center;
    background-size: cover;
    height: 250px !important;
}
.widget-user .card-footer{
    padding: 0;
}


</style>


<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12 mt-3">
                <div class="card card-widget widget-user">
                <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="widget-user-header text-white" id="header-image" v-bind:style="{ backgroundImage: 'url(' + getCoverPhoto() +')' }">
                <b-row>
                    <b-col sm="4">
                    <!-- <h3 class="widget-user-username">{{$store.state.user.seller_name}}</h3>
                    <h5 class="widget-user-desc">{{$store.state.user.shop_name}}</h5> -->
                    </b-col>
                    <b-col sm="4">
                <div class="widget-user-image">
                <center>
                <div class="plus-icon">
                    <br>
                    <!-- <img class="img-circle plus-icon" width="60%" height="60%" style="border: 5px solid #555; border-radius: 50%" :scr="getProfilePhoto()"> -->

                    <img v-if="$store.state.user.photo != 1" class="img-circle" :src="getProfilePhoto()" width="60%" height="60%" style="border: 5px solid #555; border-radius: 50%" alt="User Avatar">
                     <a class="edit" @click="clickfile()" style="cursor: pointer;"><i class="fa fa-camera" style="color:black; font-size:26px; "></i></a>
                </div>
                </center>
                </div>
                </b-col>
                <b-col sm="4">
                   <button @click="cover()" class="float-right"><i class="fa fa-bars" aria-hidden="true"></i> Edit Cover</button>
                </b-col>
                    </b-row>
                
                </div>
                </div>
            </div>

            <!-- tab -->

            <div class="col-md-12">
                <div class="card">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills">
                        <li class="nav-item"><a class="nav-link active show" href="#activity" data-toggle="tab">Activity</a></li>
                        <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">Settings</a></li>
                        </ul>
                    </div><!-- /.card-header -->
                    <div class="card-body">
                        <div class="tab-content">
                            <!-- Activity Tab -->
                            <div class="tab-pane active show" id="activity">
                                <h3 class="text-center">Display User Activity</h3>

                                        <!-- <img :src="GetItems()" style="max-width: 50%"> -->
                                        
                                </div><br>
                                
                            <!-- Setting Tab -->
                            <div class="tab-pane" id="settings">
                                <form class="form-horizontal">
                                    <div class="form-group">
                                    <label for="shop_name" class="col-sm-2 control-label">Shop Name</label>

                                    <div class="col-sm-12">
                                    <input type="text"  ref="shop_name" v-model="forms.profile.fields.shop_name"  class="form-control" id="shop_name" placeholder="Shop Name" >
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="seller_name" class="col-sm-2 control-label">Name</label>

                                    <div class="col-sm-12">
                                    <input type="text" ref="seller_name" v-model="forms.profile.fields.seller_name" class="form-control" id="seller_name" placeholder="Seller Name" >
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="email" class="col-sm-2 control-label">Email</label>

                                    <div class="col-sm-12">
                                    <input type="email" ref="email" v-model="forms.profile.fields.email" class="form-control" id="email" placeholder="Email" >
                                    </div>
                                </div>

                                <!-- <div class="form-group">
                                    <label for="password" class="col-sm-2 control-label">Password</label>

                                    <div class="col-sm-12">
                                    <input type="password" v-model="forms.profile.fields.password" class="form-control" id="password" placeholder="Password" >
                                    </div>
                                </div> -->
                                <div class="form-group">
                                    <label for="contact" class="col-sm-2 control-label">Contact No.</label>
                                    <div class="col-sm-12">
                                    <input type="text" ref="contact" v-model="forms.profile.fields.contact" class="form-control" id="contact" placeholder="Contact No." >
                                    </div>
                                </div>

                                 
                                    
                                <div class="form-group">
                                    <label for="province" class="col-sm-2 control-label">Province</label>
                                    <div class="col-sm-12">
                                    <!-- <input type="text" v-model="forms.profile.fields.province" class="form-control" id="province" placeholder="Province" > -->
                                    <!-- <select2
                                    ref="prov_hash"
                                    id="prov_hash"
                                    :allowClear="false"
                                    v-model="forms.profile.fields.province">
                                            <option v-for="right in options.prov.items"
                                            :key="right.prov_hash" 
                                            :value="right.prov_hash">
                                            {{right.province}}
                                            </option>
                                    </select2> -->
                                    <b-form-select
                                        @input="getProvince"
                                        class="form-control"
                                        id="prov_hash"
                                        ref="prov_hash"
                                        :allowClear="false"
                                        :placeholder="'Select Province'"
                                        v-model="forms.profile.fields.prov_hash">
                                                <option v-for="right in options.prov.items"
                                                :key="right.prov_hash" 
                                                :value="right.prov_hash">
                                                {{right.province}}
                                                </option>
                                        </b-form-select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="city" class="col-sm-2 control-label">City</label>
                                    <div class="col-sm-12">
                                        <b-form-select
                                        @input="getCity"
                                        class="form-control"
                                        id="city_hash"
                                        ref="city_hash"
                                        :allowClear="false"
                                        :placeholder="'Select City'"
                                        v-model="forms.profile.fields.city_hash">
                                                <option v-for="c in options.filteredCity.items"
                                                :key="c.city_hash" 
                                                :value="c.city_hash">
                                                {{c.m_city}}
                                                </option>
                                        </b-form-select>
                                    <!-- <input type="text" v-model="forms.profile.fields.m_city" class="form-control" id="m_city" placeholder="City" > -->
                                    </div>
                                </div>

                                 <div class="form-group">
                                    <label for="barangay" class="col-sm-2 control-label">Barangay</label>
                                    <div class="col-sm-12">
                                         <b-form-select
                                        class="form-control"
                                        id="brgy"
                                        ref="brgy"
                                        :allowClear="false"
                                        :placeholder="'Select Barangay'"
                                        v-model="forms.profile.fields.brgy">
                                                <option v-for="c in options.filteredBrgy.items"
                                                :key="c.brgy_hash" 
                                                :value="c.brgy_hash">
                                                {{c.barangay}}
                                                </option>
                                        </b-form-select>
                                    <!-- <input type="text" v-model="forms.profile.fields.barangay" class="form-control" id="barangay" placeholder="Barangay" > -->
                                    </div>
                                </div>

                                 <div class="form-group">
                                    <label for="sumr_address" autocomplete="off" class="col-sm-1 control-label">Address</label><span>House# / Bldg# / Subdivision </span>
                                    <div class="col-sm-12">
                                    <input type="text" v-model="forms.profile.fields.sumr_address" class="form-control" id="sumr_address" placeholder="House# / Bldg# / Subdivision" >
                                    </div>
                                </div>

                                <!-- <div class="form-group">
                                    <label for="inputExperience" class="col-sm-2 control-label">Experience</label>

                                    <div class="col-sm-12">
                                    <textarea   class="form-control" id="inputExperience" placeholder="Experience" ></textarea>
                                    </div>
                                </div> -->
                                <div class="form-group">
                                    <label for="photo" class="col-sm-2 control-label">Profile Photo</label>
                                    <div class="col-sm-12">
                                        <b-form-file  id="photo" ref="photo" name="photo" type="file" @change="updateProfile">  </b-form-file>
                                    </div>

                                </div>

                                <div class="form-group">
                                    <label for="cover" class="col-sm-2 control-label">Cover Photo</label>
                                    <div class="col-sm-12">
                                        <b-form-file  id="cover" ref="cover" name="cover" type="file" @change="updateCover">  </b-form-file>
                                        <!-- <input type="file" @change="updateProfile" name="photo" id="photo" ref="photo" class="form-input"> -->
                                    </div>

                                </div>


                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-12">
                                     <button  :disabled="forms.profile.isSaving" @click="updateInfo()" type="submit" class="btn btn-success">
                                         <icon v-if="forms.profile.isSaving" name="sync" spin></icon>Update</button>
                                    </div>
                                </div>
                                </form>
                            </div>
                        <!-- /.tab-pane -->
                        </div>
                        <!-- /.tab-content -->
                    </div><!-- /.card-body -->
                </div>
                <!-- /.nav-tabs-custom -->
          </div>
          <!-- end tabs -->
        </div>
    </div>
</template>



<script>
    export default {
        data(){
            return {
               
                forms : {
                    profile: {
                        isSaving: false,
                        fields: {
                            shop_name:'',
                            seller_name : '',
                            email: '',
                            password: '',
                            prov_hash: null,
                            city_hash: null,
                            photo: '',
                            path: '',
                            cover: '',
                        }
                    }
                  
                    },
                    options: {
                    prov: {
                        items: []
                    },
                    filteredCity: {
                        items: []
                    },
                    filteredBrgy: {
                        items: []
                    },
                    m_city: {
                        items: []
                    },
                     brgy: {
                        items: []
                    }
                    },
            }
        },
        methods:{
            
            getProvince: function(value, data) {
                if (this.forms.profile.fields.prov_hash != null) {
                    this.options.filteredCity.items = this.options.m_city.items.filter(
                    p => p.province_hash == value
                    );
                }
                },
                getCity: function(value, data) {
                if (this.forms.profile.fields.city_hash != null) {
                    this.options.filteredBrgy.items = this.options.brgy.items.filter(
                    p => p.city_hash == value
                    );
                }
                },

            getProfilePhoto(){

                let photo = (this.forms.profile.fields.photo.length < 200) ? this.forms.profile.fields.photo : "/images/profile/" + this.forms.profile.fields.photo;
                return photo;
            },
            getCoverPhoto(){
                    let cover = (this.forms.profile.fields.cover.length > 200) ? this.forms.profile.fields.cover : "/images/cover/" + this.forms.profile.fields.cover;
                    return cover;
            },
            updateProfile(e){
                    
                let file = e.target.files[0];
                let reader = new FileReader();

                let limit = 2111775;
                if(file['size'] > limit){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'You are uploading a large file',
                    })
                    return false;
                }
                reader.onload = (file) => {
                    this.forms.profile.fields.photo = reader.result;
                }
                reader.readAsDataURL(file);
            },

            updateCover(e){
                    
                let file = e.target.files[0];
                let reader = new FileReader();

                let limit = 2111775;
                if(file['size'] > limit){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'You are uploading a large file',
                    })
                    return false;
                }
                reader.onload = (file) => {
                    this.forms.profile.fields.cover = reader.result;
                }
                reader.readAsDataURL(file);
            },
            updateInfo(){
            this.forms.profile.isSaving = true;
            this.$http
                .put(
                "api/profile/" + this.$store.state.user.sumr_hash,
                this.forms.profile.fields,
                {
                    headers: {
                    Authorization: "Bearer " + localStorage.getItem("token")
                    }
                }
                )
                .then(response => {
                    Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Update successfuly.',
                    showConfirmButton: false,
                    timer: 2000
                    })
                    setTimeout(function () {
                            window.location.reload();
                    }, 1000);
                    this.forms.profile.isSaving = false;
                }) 
                .catch((error) => {
                    this.forms.profile.isSaving = false;
                    if (!error.response) return;
                    const errors = error.response.data.errors;
                    var a = 0;
                    for (var key in errors) {
                        if (a == 0) {
                                this.focusElement(key, false);
                                Toast.fire({
                                icon: 'error',
                                title: 'Error!',
                                showConfirmButton: false,
                                timer: 2000,
                                text: errors[key][0]
                                })
                                }
                                a++
                    }
                });
                    
                },
            clickfile() {
                $('#photo').trigger('click');
                
            },
            cover() {
                $('#cover').trigger('click');
                
            },
           
        },
        created() {
            this.$http
            .get("api/profile", {
                headers: {
                Authorization: "Bearer " + localStorage.getItem("token")
                }
            })
             .then(response => {
                this.forms.profile.fields = response.data.sumr;
                this.options.prov.items = response.data.prov;
                this.options.m_city.items = response.data.m_city;
                this.options.brgy.items = response.data.brgy;
                })
            .catch(error => {
                console.log(error);
            });

      
          
        },
        
        
    }
     
</script>
