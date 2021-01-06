<style lang=scss scoped>
input[type="number"]::-webkit-inner-spin-button,
input[type="number"]::-webkit-outer-spin-button {
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
  margin: 0;
}
.badge {
     border-radius: 0;
     font-size: 12px;
     line-height: 1;
     padding: .375rem .5625rem;
     font-weight: bold;
     margin-top: 4px ;

 }

 .badge.badge-pill {
     border-radius: 15rem
 }

.img {
  float: left;
  display: flex;
  border: 2px solid #ddd;
  border-radius: 4px;
  padding: 5px;
  width: 220px;
  height: 322px;
  margin: 5px;
}

.img:hover {
  box-shadow: 0 0 2px 1px rgba(0, 140, 186, 0.5);
}
@media (min-width: 768px) {
  .modal-xl {
    width: 90%;
   max-width:1200px;
  }
}
.uploader {
    width: 100%;
    padding: 40px 15px;
    border-radius: 10px;
    border: 3px dashed #fff;
    position: relative;
    
    i {
        font-size: 85px;
    }
   
    .images-preview {
        display: flex;
        flex-wrap: wrap;
        margin-top: 20px;
        .img-wrapper {
            width: 213px;
            height: 322px;
            display: flex;
            flex-direction: column;
            img {
                max-height: 310px;
            }
        }
        
    }
    .upload-control {
        position: absolute;
        width: 100%;
        background: #fff;
        top: 0;
        left: 0;
        border-top-left-radius: 7px;
        border-top-right-radius: 7px;
        padding: 10px;
        padding-bottom: 4px;
        button, label {
            background: #2196F3;
            border: 2px solid #03A9F4;
            border-radius: 3px;
            color: #fff;
            font-size: 15px;
            cursor: pointer;
        }
        label {
            padding: 2px 5px;
            margin-right: 10px;
            font-size: 14px;
        }
    }
}

</style>
<template>
    <div  class="animated fadeIn">
        <!-- main container -->
        <div v-if="$store.state.user.type == 1">
        <not-found></not-found>
        </div>
        <notifications group="notification" />
        <div v-show="$store.state.user.type != 1">
        <div v-show="!showEntry" class="animated fadeIn">
        <b-row>
        <!-- main row -->
        <b-col sm="12">
          <b-card>
            <!-- main card -->
            <h5 slot="header">
              <!-- table header -->
              <span class="text-primary">
                <i class="fa fa-bars"></i>
                Product List
              </span>
            </h5>
            <b-row class="mb-2">
              <!-- row button and search input -->
              <b-col sm="4">
                <b-form-group v-show="$store.state.user.type == 0">
                <b-button
                  variant="primary"
                  @click="showEntry = true, entryMode='Add', clearFields('products'), focusElement('product_name', true)"
                >
                  <i class="fa fa-plus-circle"></i> Add New Product
                </b-button>
                </b-form-group>
                <b-form-group v-show="$store.state.user.type == 2">
                <select2
                v-model="forms.products.fields.getmerchantproducts"
                :allowClear="true"
                :placeholder="'Select Merchant'">
                        <option v-for="right in options.sumr.items"
                        :key="right.sumr_hash" 
                        :value="right.sumr_hash">
                        {{right.seller_name}}
                        </option>
                </select2>
            </b-form-group>
              </b-col>
              <b-col sm="4">
                <span></span>
              </b-col>

              <b-col sm="4">
                <b-form-input
                 v-model="filters.products.criteria"
                  type="text"
                  placeholder="Search"
                ></b-form-input>
              </b-col>
            </b-row>
            
            <!-- row button and search input -->
            
            <b-row>
              <!-- row table -->
              <b-col sm="12">
                <b-table
                  responsive
                  striped
                  hover
                  small
                  bordered
                  show-empty
                  :fields="tables.products.fields"
                  :items="getMerchantProducts"
                  :filter="filters.products.criteria"
                  :current-page="paginations.products.currentPage"
                  :per-page="paginations.products.perPage"
                >
                <template v-slot:cell(status)="data">
                    <b-badge v-if="data.item.is_verified == 1" pill variant="success">{{"Verified"}}</b-badge>
                    <b-badge v-else-if="data.item.is_verified == 2" pill variant="warning" style="color: white;">{{"Pending"}}</b-badge>
                    <b-badge v-else-if="data.item.is_verified == 3" pill variant="danger" style="color: white;">{{"Disapproved"}}</b-badge>
                    <b-badge v-else pill variant="dark" style="color: white;">{{"Banned"}}</b-badge>
                </template>
                <template v-slot:cell(action)="data">
                    
                    <div v-if="$store.state.user.type == 0">
                    <b-btn :size="'sm'" variant="primary" @click="setUpdate(data)">
                      <i class="fa fa-edit"></i>
                    </b-btn>
                    <b-btn
                      :size="'sm'"
                      :disabled="forms.products.isDeleting"
                      variant="danger"
                      @click="setDelete(data)"
                    >
                      <icon v-if="forms.products.isDeleting" name="sync" spin></icon>
                      <i v-else class="fa fa-trash"></i>
                    </b-btn>
                    </div>



                    <div v-else>
                      <b-btn
                      v-show="data.item.is_verified == 2 || data.item.is_verified == 3"
                      :size="'sm'"
                      variant="success"
                      @click="ApproveProduct(data)"
                    >Approve
                      <i class="fa fa-check"></i>
                    </b-btn>
                      <b-btn
                      v-show="data.item.is_verified == 1 || data.item.is_verified == 2 "
                      :size="'sm'"
                      variant="danger"
                      @click="DisapproveProduct(data)"
                      >Disapprove
                      <i class="fa fa-thumbs-down"></i>
                      </b-btn>

                      <b-btn
                      v-show="data.item.is_verified == 3 || data.item.is_verified == 1"
                      :size="'sm'"
                      variant="dark"
                      @click="BannedProduct(data)"
                    >Banned
                    <i class="fa fa-ban"></i>
                    </b-btn>
                    
                    </div>
                  </template>
                </b-table>
                <b-row>
              <!-- Pagination -->
              <b-col sm="12" class="my-1">
                <b-pagination
                  size="sm"
                  align="right"
                  :total-rows="paginations.products.totalRows"
                  :per-page="paginations.products.perPage"
                  v-model="paginations.products.currentPage"
                  class="my-0"
                />
              </b-col>
            </b-row>
                <b-modal 
                v-model="showModalDelete"
                :noCloseOnEsc="true"
                :noCloseOnBackdrop="true">
                <div slot="modal-title">
                    Delete Product
                </div>
                <b-col lg=12>
                    Are you sure you want to delete this Product?
                </b-col>
                <div slot="modal-footer">
                    <b-button :disabled="forms.products.isSaving" variant="primary" @click="onProductsDelete">
                        <icon v-if="forms.products.isSaving" name="sync" spin></icon>
                        <i class="fa fa-check"></i>
                        OK
                    </b-button>
                    <b-button variant="secondary" @click="showModalDelete=false">Close</b-button>            
                </div>
            </b-modal>
                <!-- table -->
              </b-col>
              </b-row>
              </b-card>
            </b-col>
          </b-row>
        </div>
        
      <div v-show="showEntry" class="animated fadeIn"  @shown="focusElement('product_name')">
      <!-- sec div -->
      <b-row>
        <!-- sec row -->
        <b-col sm="12">
          <b-card>
            <!-- sec card -->
            <h5 slot="header">
              <!-- table header -->
              <span class="text-primary">{{entryMode}} Product</span>
            </h5> 
      <b-col lg="12">
        <b-tabs v-model="tabIndex" >
        <b-form @keydown="resetFieldStates('products')" autocomplete="off" @shown="focusElement('product_name')">
          <b-tab title="Product Information">
            <b-row>
            <b-col lg="6">
            <b-form-group
            tab="0"
            id="fieldset-1"
            label-for="input-1"
            :invalid-feedback="invalidFeedbackName"
            :valid-feedback="ValidFeedbackName"
            :state="nameState">
                <label for="product_name"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> Product Name</label>
                        <b-form-input
                        id="product_name"
                        ref="product_name"
                        minlength=20
                        maxlength=100
                        placeholder="Enter your Product Name"
                        v-model="forms.products.fields.product_name"
                      ></b-form-input>
                    </b-form-group>
                      <b-form-group>
                                <label><i class="icon-required fa fa-exclamation-circle"></i> Category</label>
                                <select2
                                id="inct_hash"
                                ref="inct_hash"
                                :allowClear="false"
                                :placeholder="'Select Category'"
                                v-model="forms.products.fields.inct_hash">
                                        <option v-for="right in options.category.items"
                                        :key="right.inct_hash" 
                                        :value="right.inct_hash">
                                        {{right.cat_name}}
                                        </option>
                                </select2>
                            </b-form-group>
                          <b-form-group
                          tab="0"
                          :invalid-feedback="invalidFeedbackDetail"
                          :valid-feedback="ValidFeedbackDetail"
                          :state="DetailState">
                        <label for="product_details"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> Product Details</label>
                       <b-form-textarea
                        tab="0"
                        rows="3"
                        minlength=50
                        maxlength=1500
                        placeholder="Enter your Product Details"
                        id="product_details"
                        ref="product_details"
                        v-model="forms.products.fields.product_details"
                      ></b-form-textarea>
                    </b-form-group>
                    
                    </b-col>
                    <b-col lg="6">
                      <b-form-group>
                        <label for="onhand_qty"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> On hand Quantity.</label><span>  The number you have physically available.</span>
                      <b-form-input
                            tab="0"
                            ref="onhand_qty"
                            id="onhand_qty"
                            v-model="forms.products.fields.onhand_qty"
                            type="number"
                            placeholder="On Hand Quantity">
                        </b-form-input>
                    </b-form-group>
                           <b-form-group>
                        <label for="available_qty"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> Available Quantity.</label><span>  The quantity of an item that is currently available for sale.</span>
                     <b-form-input
                            tab="0"
                            ref="available_qty"
                            id="available_qty"
                            v-model="forms.products.fields.available_qty"
                            type="number"
                            placeholder="Available Quantity">
                        </b-form-input>
                      </b-form-group>
                      <b-form-group>
                    <label><i class="icon-required fa fa-exclamation-circle"></i> Price</label>
                    <vue-autonumeric
                        tab="0"
                        ref="cost_amt"
                        id="cost_amt"
                        v-model="forms.products.fields.cost_amt"
                        class='form-control text-right'
                        placeholder="0.00"
                    :options="{
                            minimumValue: '0',
                            decimalCharacter: '.',}"
                ></vue-autonumeric>
                  </b-form-group>
                  </b-col>
                  </b-row>
                  </b-tab>
                  <b-tab title="Shipping Fee">
                  <b-col lg="6">
                  <b-form-group>
                  <label>Weight (kg) :</label>
                  <vue-autonumeric
                    tab="1"
                    ref="weight"
                    id="weight"
                    v-model="forms.products.fields.weight"
                    class='form-control'
                    placeholder="Kg"
                :options="{
                        minimumValue: '0',
                        decimalPlaces: 3,
                        decimalCharacter: '.'}"
              ></vue-autonumeric>
                  </b-form-group>
                  <b-form-group>
                  <b-form-checkbox
                  tab="1"
                  ref="is_measurable"
                  id="is_measurable"
                  v-model="forms.products.fields.is_measurable"
                  value=1
                  unchecked-value=0
                  @input="forms.products.fields.is_measurable==0? (forms.products.fields.lengthsize = 0 ,  forms.products.fields.width = 0 , forms.products.fields.height = 0): ''"
                  >
                  Is Measurable?
                  </b-form-checkbox>
                  </b-form-group>
                  <b-form-group>
                  <label>Length (cm) :</label>
                  <vue-autonumeric
                      tab="1"
                      ref="lengthsize"
                      id="lengthsize"
                      placeholder="cm"
                      :disabled="forms.products.fields.is_measurable == 0" 
                      v-model="forms.products.fields.lengthsize"
                      class='form-control'
              ></vue-autonumeric>
                  </b-form-group>
                  <b-form-group>
                  <label>Width (cm) :</label>
                  <vue-autonumeric
                      tab="1"
                      ref="width"
                      id="width"
                      placeholder="cm"
                      :disabled="forms.products.fields.is_measurable == 0"
                      v-model="forms.products.fields.width"
                      class='form-control'
              ></vue-autonumeric>
                  </b-form-group>
                  <b-form-group>
                  <label>Height (cm) :</label>
                  <vue-autonumeric
                      tab="1"
                      ref="height"
                      id="height"
                      :disabled="forms.products.fields.is_measurable == 0"
                      v-model="forms.products.fields.height"
                      class='form-control'
                      placeholder="cm"
              ></vue-autonumeric>
                  </b-form-group>
                  <b-form-group>
                  <label>Dimension (kg):</label>
                  <vue-autonumeric
                      tab="1"
                      placeholder="kg"
                      disabled
                      ref="dimension"
                      id="dimension"
                      v-model="this.Getdimension"
                      class='form-control'
                    ></vue-autonumeric>
                  </b-form-group>
                  </b-col>
                  </b-tab>
                  
        <b-tab title="Images">
        <div class="uploader">
        <div v-show="images.length">
            <!-- <b-button @click="upload" variant="primary">Upload</b-button> -->
            <b-button @click ="files = [], images = []" variant="danger" >Remove</b-button>
        </div>
        <div v-show="!images.length">
            <div class="file-input">
                <label for="file">Select a file</label>
                <b-form-file multiple id="file" ref="file" name="images" type="file" v-model="files" @change="onInputChange" tab="2"> 
              </b-form-file>
            </div>
        </div>

        <div class="images-preview" v-show="images.length">
            <div class="img-wrapper img" v-for="(image, index) in images" :key="index">
                <img :src="image" :alt="`Image Uplaoder ${index}`">
            </div>
        </div>
    </div>
          </b-tab>
          <b-tab title="Variants" disabled>
          </b-tab>
           </b-form>
              </b-tabs>
               <hr />
            <b-row class="pull-right mt-2">
              <b-col sm="12">
                <b-button :disabled="forms.products.isSaving" variant="primary" @click="onProductEntry">
                    <icon v-if="forms.products.isSaving" name="sync" spin></icon>
                    <i class="fa fa-check"></i>
                    Save
                </b-button>
                <b-button variant="secondary" @click=" files = [], images = [], showEntry=false">Close</b-button>
              </b-col>
              </b-row>
            </b-col>
            </b-card>
          </b-col>
        </b-row>
        </div>
      </div>
    </div>
</template>

<script>
  export default {
    data() {
      return {
        files: [],
        images: [],
        entryMode: "Add",
        showModalProducts: false, 
        showModalDelete: false,
        showEntry: false,
        tabIndex: 0,
        image: '',
        forms: { 
            products: {
            isSaving: false,
            isDeleting: false,
            fields: {
              getmerchantproducts: null,
              image: '',
              imagePreview: null,
              showPreview: false,
              dimension: 0,
              is_measurable: 0,
              product_name: null,
              product_details: '' ,
              onhand_qty: '',
              available_qty: '',
              cost_amt: '',
              weight: null,
              lengthsize: 0,
              width: 0,
              height: 0,
              inct_hash: null,
            },
          },
        },
        tables: {
          products: {
            fields: [
              {
                key: "product_name",
                label: "Product Name",
                tdClass: "align-middle",
                thStyle: { width: "130px"},
                sortable: true,
              },
              {
                key: "onhand_qty",
                label: "On hand Qty.",
                tdClass: "align-middle text-center",
                thClass: "text-center",
                thStyle: { width: "70px" },
                sortable: true
              },
              {
                key: "available_qty",
                label: "Available Qty.",
                tdClass: "align-middle text-center",
                thClass: "text-center",
                thStyle: { width: "70px" },
                sortable: true
              },
                {
                key: "sales",
                label: "Sales",
                tdClass: "align-middle text-center",
                thClass: "text-center",
                thStyle: { width: "80px"},
                sortable: true
              },
                {
                key: "cost_amt",
                label: "Price",           
                thClass: "text-right",
                tdClass: "align-middle text-right",
                thStyle: { width: "120px" },
                sortable: true,
                formatter: (value) => {
                        return this.formatNumber(value)
                    }
              },
              {
                key: "status",
                thClass: "text-center",
                label: "Status",
                thStyle: { width: "80px" },
                tdClass: "text-center"
              },
              {
                key: "action",
                label: "",
                thStyle: { width: "80px" },
                tdClass: "text-center"
              }
            ],
            items: []
          }
        },
        filters: {
          products: {
            criteria: null
          }
        },
        paginations: {
          products: {
            totalRows: 0,
            currentPage: 1,
            perPage: 10
          }
        },
        options: {
          category: {
            items: []
          },
          sumr: {
            items: []
          }
        },

        inmr_hash: null,
        row: []
      };
    },
     methods: {
       BannedProduct(data) {
          this.inmr_hash = data.item.inmr_hash;
          swal.fire({
                    title: 'Are you sure?',
                    // text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Banned it!',
                    text: "You won't be able to revert this!",
                    }).then((result) => {

                        // Send request to the server
                         if (result.value) {
                                this.$http
                          .put(
                            "api/products/banned/" + this.inmr_hash,
                            this.forms.products.fields,
                            {
                              headers: {
                                Authorization: "Bearer " + localStorage.getItem("token")
                              }
                            }
                          ).then(()=>{
                                    swal.fire(
                                    'Banned!',
                                    'The Product has been Banned.',
                                    'success'
                                    )
                                    this.loadProducts();
                            }).catch(()=> {
                                    swal.fire("Failed!", "There was something wronge.", "warning");
                            });
                         }
                    })
       },
       DisapproveProduct(data) {
          this.inmr_hash = data.item.inmr_hash;
          swal.fire({
                    title: 'Are you sure?',
                    // text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Disapprove it!'
                    }).then((result) => {

                        // Send request to the server
                         if (result.value) {
                                this.$http
                          .put(
                            "api/products/disapprove/" + this.inmr_hash,
                            this.forms.products.fields,
                            {
                              headers: {
                                Authorization: "Bearer " + localStorage.getItem("token")
                              }
                            }
                          ).then(()=>{
                                    swal.fire(
                                    'Disapproved!',
                                    'The Product has been Disapproved.',
                                    'success'
                                    )
                                    this.loadProducts();
                            }).catch(()=> {
                                    swal.fire("Failed!", "There was something wronge.", "warning");
                            });
                         }
                    })
       },
       ApproveProduct(data) {
           this.inmr_hash = data.item.inmr_hash;
           swal.fire({
                    title: 'Are you sure?',
                    // text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, approve it!'
                    }).then((result) => {

                        // Send request to the server
                         if (result.value) {
                                this.$http
                          .put(
                            "api/products/approve/" + this.inmr_hash,
                            this.forms.products.fields,
                            {
                              headers: {
                                Authorization: "Bearer " + localStorage.getItem("token")
                              }
                            }
                          ).then(()=>{
                                    swal.fire(
                                    'Approved!',
                                    'The Product has been Approved.',
                                    'success'
                                    )
                                    this.loadProducts();
                            }).catch(()=> {
                                    swal.fire("Failed!", "There was something wronge.", "warning");
                            });
                         }
                    })
        },
       
      
          onInputChange(e) {
            const files = e.target.files;
            Array.from(files).forEach(file => this.addImage(file));
        },  
       
        addImage(file) {
            if (!file.type.match('image.*')) {
                this.$notify({
                  type: "error",
                  group: "notification",
                  title: "Error!",
                  text: "`${file.name} is not an image`"
                });
                return;
            }
           
            this.files.push(file);
            const img = new Image(),
                reader = new FileReader();
            reader.onload = (e) => this.images.push(e.target.result);
            reader.readAsDataURL(file);
        },
       
         upload() {
            const formData = new FormData();
            this.files.forEach(file => {
                formData.append('images[]', file, file.name);
            });
             this.$http.post('/api/upload', formData, {
                headers: {
                      Authorization: 'Bearer ' + localStorage.getItem('token'),
                      'Content-Type' : 'multipart/form-data'
                  }
                })
                .then(response => {
                  this.$notify({
                  type: "success",
                  group: "notification",
                  title: "Success!",
                  text: "Image Uploaded"
                });
                  this.images = [];
                  this.files = [];
                })
                .catch(error => {
                  if (!error.response) return
                  console.log(error)
                })
        },
      // filterProduct(sumr_hash) {
      // return this.tables.products.items.filter(r => r.sumr_hash == sumr_hash);
      // },
      onProductEntry() {
        if (this.forms.products.fields.product_name !== null && this.forms.products.fields.product_name.length == 0) {
          this.focusElement('product_name', true)
          this.$notify({
          type: 'error',
          group: 'notification',
          title: 'Error!',
          text: 'Please Enter Product Name'
        })
        }else if (this.forms.products.fields.product_name !== null && this.forms.products.fields.product_name.length <= 19) {
          this.focusElement('product_name', true)
          this.$notify({
          type: 'error',
          group: 'notification',
          title: 'Error!',
          text: 'Please Enter Product Name at least 20 Characters'
        })
        }else if (this.forms.products.fields.inct_hash == null) {
          this.focusElement('inct_hash', true)
          this.$notify({
          type: 'error',
          group: 'notification',
          title: 'Error!',
          text: 'Please Select Category'
        })
        }else if (this.forms.products.fields.product_details !== null && this.forms.products.fields.product_details.length == 0) {
          this.focusElement('product_details', true)
          this.$notify({
          type: 'error',
          group: 'notification',
          title: 'Error!',
          text: 'Please Enter Product Details'
        })
        }else if (this.forms.products.fields.product_details !== null && this.forms.products.fields.product_details.length < 50) {
          this.focusElement('product_details', true)
          this.$notify({
          type: 'error',
          group: 'notification',
          title: 'Error!',
          text: 'Please Enter Product Details at least 50 Characters'
        })
        }else if (this.forms.products.fields.onhand_qty !== null && this.forms.products.fields.onhand_qty.length == 0) {
          this.focusElement('onhand_qty', true)
          this.$notify({
          type: 'error',
          group: 'notification',
          title: 'Error!',
          text: 'Please Enter On hand Quantity'
        })
        }else if (this.forms.products.fields.available_qty !== null && this.forms.products.fields.available_qty.length == 0) {
          this.focusElement('available_qty', true)
          this.$notify({
          type: 'error',
          group: 'notification',
          title: 'Error!',
          text: 'Please Enter Available Quantity'
        })
        }else if (Number(this.forms.products.fields.onhand_qty) < Number(this.forms.products.fields.available_qty)) {
          this.focusElement('onhand_qty', true)
          this.$notify({
          type: 'error',
          group: 'notification',
          title: 'Error!',
          text: 'Your On Hand Stock should be greater than Your Available Stock'
        })
          }else if (this.forms.products.fields.cost_amt !== null && this.forms.products.fields.cost_amt.length == 0) {
          this.focusElement('cost_amt', true)
          this.$notify({
          type: 'error',
          group: 'notification',
          title: 'Error!',
          text: 'Please Enter Price'
        })
        }else if (this.forms.products.fields.weight !== null && this.forms.products.fields.weight.length == 0) {
          this.focusElement('weight', true)
          this.$notify({
          type: 'error',
          group: 'notification',
          title: 'Error!',
          text: 'Please Enter Weight'
        })
        }else if (this.images !== null && this.images.length == 0) {
          this.focusElement('file', true)
          this.$notify({
          type: 'error',
          group: 'notification',
          title: 'Error!',
          text: 'Please Enter Image'
        })
        }else{
          if (this.entryMode == "Add") {
            //name of form
            //if from a modal?
            //name of table to be inserted
            //is from tab
          //  this.createEntity("products", false, "products", true);
            
           
            // const formData = new FormData();
            // this.files.forEach(file => {
            //     formData.append('images[]', file, file.name);
            // });
            this.forms.products.isSaving = true;
            this.resetFieldStates('products');

            this.$http
              .post("api/" + 'products', this.forms.products.fields, {
                headers: {
                  Authorization: "Bearer " + localStorage.getItem("token")
                }
              })
        .then(response => {
          this.forms.products.isSaving = false;
          this.clearFields('products');
          this.$notify({
            type: "success",
            group: "notification",
            title: "Success!",
            text: "The record has been successfully created."
          });
          this.upload();
          this.tables.products.items.unshift(response.data.data);
          this.paginations.products.totalRows++;
          this.images = [];
          this.files = [];
          this.showEntry = false;
        
        })
        .catch(error => {
          this.forms.products.isSaving = false;
          if (!error.response) return;
          const errors = error.response.data.errors;
          var a = 0;
          for (var key in errors) {
            // this.forms[entity].states[key] = false
            // this.forms[entity].errors[key] =  errors[key]
            if (a == 0) {
              this.focusElement(key, true);
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
          } else {
            //name of form
            //name of primary key
            //if from a modal?
            //row to be edited
            //is from tab
            this.updateEntity("products", "inmr_hash", false, this.row, true);
          }
        }
      },
        
     onProductsDelete() {
        this.deleteEntity(
          "products",
          this.inmr_hash,
          true,
          "products",
          "inmr_hash"
        );
      },
      async setDelete(data) {
        this.inmr_hash = data.item.inmr_hash;
        this.showModalDelete = true;
      },
     
      setUpdate(data) {
        this.row = data.item;
        this.resetFieldStates("products");
        this.fillEntityForm("products", data.item.inmr_hash);
        this.showEntry = true;
        this.entryMode = "Edit";
      // this.row = data.item;
      // this.$http
      //   .get("api/products/" + data.item.inmr_hash, {
      //     headers: {
      //       Authorization: "Bearer " + localStorage.getItem("token")
      //     }
      //   })
      //   .then(response => {
      //     this.forms.purchasemain.fields = response.data.purchasemain;
      //     this.tables.orderlists.items = response.data.po_items;
      //     this.showEntry = true;
      //     this.entryMode = "Edit";
      //   })
      //   .catch(err => {
      //     console.log(err);
      //   });
      },
      loadProducts() {
         this.$http
      .get("api/products", {
        headers: {
          Authorization: "Bearer " + localStorage.getItem("token")
        }
      })
      .then(response => {
        this.tables.products.items = response.data.products;
        this.options.category.items = response.data.category;
        this.options.sumr.items = response.data.sumr;
        this.paginations.products.totalRows = response.data.products.length;
      })
      .catch(error => {
        console.log(error);
      });
      },
      
      
     },
  computed: {
    getMerchantProducts() {
      if (this.forms.products.fields.getmerchantproducts != null) {
        return this.tables.products.items.filter(p => p.sumr_hash == this.forms.products.fields.getmerchantproducts);
      }else{
        return this.tables.products.items;
      }
    },
    invalidFeedbackName() {
      if (this.forms.products.fields.product_name !== null && this.forms.products.fields.product_name.length == 0) {
        return 'Please enter something.'
        } if (this.forms.products.fields.product_name !== null && this.forms.products.fields.product_name.length <= 19) {
          return 'Enter at least 20 characters.'
          }else{
            return
        }
      },
      ValidFeedbackName() {
        if (this.forms.products.fields.product_name !== null && this.forms.products.fields.product_name.length >= 20) {
          return 'Maximum of 100 characters.!'
        }
      },
      invalidFeedbackDetail() {
      if (this.forms.products.fields.product_details !== null && this.forms.products.fields.product_details.length == 0) {
        return 'Please enter something.'
        } if (this.forms.products.fields.product_details !== null && this.forms.products.fields.product_details.length <= 49) {
          return 'Enter at least 50 characters.'
          }else{
            return
        }
      },
      ValidFeedbackDetail() {
       if (this.forms.products.fields.product_details !== null && this.forms.products.fields.product_details.length >= 50) {
          return 'Maximum of 1500 characters.'
        }
      },
      nameState() {
        return this.forms.products.fields.product_name !== null && this.forms.products.fields.product_name.length >= 20
      },
      DetailState() {
        return this.forms.products.fields.product_details !== null && this.forms.products.fields.product_details.length >= 50
      },
        Getdimension() {
        this.forms.products.fields.dimension = 0;
        this.forms.products.fields.dimension += 
        ( Number(this.forms.products.fields.lengthsize) * Number(this.forms.products.fields.width) * Number(this.forms.products.fields.height) / 3500 );
          return this.forms.products.fields.dimension;
        } 
    },
 created () {

      this.loadProducts()
  },
  }
</script>