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
    margin-top: 20px ;
    border-radius: 10px;
    border: 3px dashed #fff;
    position: relative;
   
    .images-preview {
        display: flex;
        flex-wrap: wrap;
        margin-top: 20px;
        .img-wrapper {
            width: 180px;
            height: 250px;
            flex-direction: column;
            img {
                max-height: 237px;
            }
        }
        
    }
}

</style>
<template>
    <div  class="animated fadeIn">
      <notifications group="notification" />
        <!-- main container -->
        <div v-if="$store.state.user.type == 1">
        <not-found></not-found>
        </div>
        
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
                  @click="showEntry = true, entryMode='Add', clearFields('products'), focusElement('product_name', true) , images = []"
                >
                  <i class="fa fa-plus-circle"></i> Add New Product
                </b-button>
                </b-form-group>
                <b-form-group v-show="$store.state.user.type == 2">
                <select2
                v-model="forms.products.fields.getmerchantproducts"
                :allowClear="false"
                :placeholder="'Select Merchant'">
                        <option v-for="right in options.sumr.items"
                        :key="right.sumr_hash" 
                        :value="right.sumr_hash">
                        {{right.shop_name}}
                        </option>
                </select2>
              </b-form-group>
              </b-col>
              <b-col sm="2">
                <b-form-group v-show="$store.state.user.type == 2">
                <select2
                v-model="forms.products.fields.getStatus"
                :allowClear="false"
                :placeholder="'Select Status'">
                <option value="1">Verified</option>
                <option value="2">Pending</option>
                <option value="3">Disapproved</option>
                <option value="4">Banned</option>
                </select2>
              </b-form-group>
              </b-col>
              <b-col lg="2">
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
                    <b-badge v-else-if="data.item.is_verified == 4" pill variant="dark" style="color: white;">{{"Banned"}}</b-badge>
                </template>
                <template v-slot:cell(action)="data">
                    
                    <div v-if="$store.state.user.type == 0">
                    <div v-show="data.item.is_verified != 4">
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
                    </div>
                    <div v-else>
                      <b-btn
                      v-show="data.item.is_verified == 2 || data.item.is_verified == 3"
                      :size="'sm'"
                      variant="success"
                      @click="ApproveProduct(data)"
                    >
                      <i class="fa fa-check"></i>
                    </b-btn>
                      <b-btn
                      v-show="data.item.is_verified == 1 || data.item.is_verified == 2 "
                      :size="'sm'"
                      variant="danger"
                      @click="DisapproveProduct(data)"
                      >
                      <i class="fa fa-thumbs-down"></i>
                      </b-btn>
<!-- 
                      <b-btn
                      v-show="data.item.is_verified == 3 || data.item.is_verified == 1"
                      :size="'sm'"
                      variant="dark"
                      @click="BannedProduct(data)"
                    > 
                    <i class="fa fa-ban"></i>
                    </b-btn>
                    -->
                    </div>
                  </template>
                   <template v-slot:cell(show_details)="data">
                    <b-button
                      class="button"
                      variant="primary"
                      size="sm"
                      @click="data.toggleDetails()"
                    >
                      <i class="fa fa-eye"></i>
                      {{ row.detailsShowing ? 'Hide' : 'Show'}} Details 
                    </b-button>
                  </template>
                  <template v-slot:row-details="data"> 
                    <b-card>
                      <b-row class="mb-2">
                      <b-col lg="6">
                        <br>
                          <h3>{{data.item.shop_name}}</h3>
                      <b-row class="mb-2">
                        <b-col>
                          <b>Merchant Name :</b>
                          <label>&emsp;{{data.item.seller_name}}</label>
                        </b-col>
                      </b-row>
                      
                      <b-row class="mb-2">
                        <b-col>
                          <b>Product Name :</b>
                          <label>&emsp;{{data.item.product_name}}</label>
                        </b-col>
                      </b-row>

                      <b-row class="mb-2">
                        <b-col>
                          <b>Category :</b>
                          <label>&emsp;{{data.item.cat_name}}</label>
                        </b-col>
                      </b-row>

                      <b-row class="mb-2">
                        <b-col>
                          <b>Product Details :</b>
                          <label style="text-overflow: ellipsis;
                        white-space: nowrap;
                        overflow: hidden;">&emsp;{{data.item.product_details}}</label>
                        </b-col>
                      </b-row>

                      <b-row class="mb-2">
                        <b-col>
                          <b>On hand Quantity :</b>
                          <label>&emsp;{{data.item.onhand_qty}}</label>
                        </b-col>
                      </b-row>

                       <b-row class="mb-2">
                        <b-col>
                          <b>Available Quantity :</b>
                          <label>&emsp;{{data.item.available_qty}}</label>
                        </b-col>
                      </b-row>

                      <b-row class="mb-2">
                        <b-col>
                          <b>Sales :</b>
                          <label>&emsp;{{data.item.sales}}</label>
                        </b-col>
                      </b-row>
                      
                       <b-row class="mb-2">
                        <b-col>
                          <b>Sales :</b>
                          <label>&emsp;{{data.item.cost_amt}}</label>
                        </b-col>
                      </b-row>

                      <b-row class="mb-2">
                        <b-col>
                          <b>Weight :</b>
                          <label>&emsp;{{data.item.weight}}</label>
                        </b-col>
                      </b-row>

                      <b-row class="mb-2">
                        <b-col>
                          <b>Dimension :</b>
                          <label>&emsp;{{data.item.dimension}}</label>
                        </b-col>
                      </b-row>
                      

                      </b-col>
                       </b-row>
                       </b-card>
                  </template>
                </b-table>
                <b-row>
              <!-- Pagination -->
              <b-col sm="12" class="my-1">
                <b-pagination size="sm" align="right" :total-rows="paginations.products.totalRows" :per-page="paginations.products.perPage" v-model="paginations.products.currentPage" />
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
        <b-form @keydown="resetFieldStates('products')" autocomplete="off" @shown="focusElement('product_name')">
            <b-row>
            <b-col lg="4">
            <b-form-group
            id="fieldset-1"
            label-for="input-1"
            :invalid-feedback="invalidFeedbackName"
            :valid-feedback="ValidFeedbackName"
            :state="nameState">
            <input type="hidden" v-model="forms.products.fields.inmr_hash" />
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
                          :invalid-feedback="invalidFeedbackDetail"
                          :valid-feedback="ValidFeedbackDetail"
                          :state="DetailState">
                        <label for="product_details"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> Product Details</label>
                       <b-form-textarea
                        rows="3"
                        minlength=50
                        maxlength=1500
                        placeholder="Enter your Product Details"
                        id="product_details"
                        ref="product_details"
                        v-model="forms.products.fields.product_details"
                      ></b-form-textarea>
                    </b-form-group>
                    
                      <b-form-group>
                        <label for="onhand_qty"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> On hand Quantity.</label><span>  The number you have physically available.</span>
                      <b-form-input
                            min="0"
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
                            min="0"
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
                  <b-col lg="2">
                  <b-form-group>
                  <label><i class="icon-required fa fa-exclamation-circle"></i> Weight (kg) :</label>
                  <vue-autonumeric
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
                      placeholder="kg"
                      disabled
                      ref="dimension"
                      id="dimension"
                      v-model="this.Getdimension"
                      class='form-control'
                    ></vue-autonumeric>
                  </b-form-group>
                  </b-col>
               
        <b-col lg="6">
                <!-- <label>{{ShowImages[0] != undefined ? ShowImages[0].path:'N/A'}}</label> -->
                
        <div class="uploader">
      
        <div v-show="images.length < 1 ">
                <b-form-file multiple id="file" ref="file" name="images" type="file" v-model="files" @change="onInputChange"> 
              </b-form-file>
        </div>

          
        <div class="images-preview" v-show="images.length > 0 || ShowImages.length > 0 ">
          <div class="img-wrapper img" v-bind:style="{display: ShowImages[0] != undefined ? 'flex' : 'none' }">
                <img :src=" ShowImages[0] != undefined ? '/storage/products/' + $store.state.user.sumr_hash + '/' + forms.products.fields.inmr_hash + '/' + ShowImages[0].path : ''">
          </div>
           <div class="img-wrapper img" v-bind:style="{display: ShowImages[1] != undefined ? 'flex' : 'none' }">
                <img :src=" ShowImages[1] != undefined ? '/storage/products/' + $store.state.user.sumr_hash + '/' + forms.products.fields.inmr_hash + '/' + ShowImages[1].path : ''">
          </div>  
          <div class="img-wrapper img" v-bind:style="{display: ShowImages[2] != undefined ? 'flex' : 'none' }">
                <img :src=" ShowImages[2] != undefined ? '/storage/products/' + $store.state.user.sumr_hash + '/' + forms.products.fields.inmr_hash + '/' + ShowImages[2].path : ''">
          </div>
          <div class="img-wrapper img" v-bind:style="{display: ShowImages[3] != undefined ? 'flex' : 'none' }">
                <img :src="ShowImages[3] != undefined ? '/storage/products/' + $store.state.user.sumr_hash + '/' + forms.products.fields.inmr_hash + '/' + ShowImages[3].path : ''">
          </div>     
          <div class="img-wrapper img" v-bind:style="{display: ShowImages[4] != undefined ? 'flex' : 'none' }">
                <img :src=" ShowImages[4] != undefined ? '/storage/products/' + $store.state.user.sumr_hash + '/' + forms.products.fields.inmr_hash + '/' + ShowImages[4].path : ''" >
          </div>
          <div class="img-wrapper img" v-bind:style="{display: ShowImages[5] != undefined ? 'flex' : 'none' }">
                <img :src=" ShowImages[5] != undefined ? '/storage/products/' + $store.state.user.sumr_hash + '/' + forms.products.fields.inmr_hash + '/' + ShowImages[5].path : ''" >
          </div> 

          <div class="img-wrapper img" v-bind:style="{display: ShowImages[6] != undefined ? 'flex' : 'none' }">
                <img :src=" ShowImages[6] != undefined ? '/storage/products/' + $store.state.user.sumr_hash + '/' + forms.products.fields.inmr_hash + '/' + ShowImages[6].path : ''" >
          </div> 
          <div class="img-wrapper img" v-bind:style="{display: ShowImages[7] != undefined ? 'flex' : 'none' }">
                <img :src=" ShowImages[7] != undefined ? '/storage/products/' + $store.state.user.sumr_hash + '/' + forms.products.fields.inmr_hash + '/' + ShowImages[7].path : ''" >
          </div> 
          <div class="img-wrapper img" v-bind:style="{display: ShowImages[8] != undefined ? 'flex' : 'none' }">
                <img :src=" ShowImages[8] != undefined ? '/storage/products/' + $store.state.user.sumr_hash + '/' + forms.products.fields.inmr_hash + '/' + ShowImages[8].path : ''" >
          </div> 
          <div class="img-wrapper img" v-bind:style="{display: ShowImages[9] != undefined ? 'flex' : 'none' }">
                <img :src=" ShowImages[9] != undefined ? '/storage/products/' + $store.state.user.sumr_hash + '/' + forms.products.fields.inmr_hash + '/' + ShowImages[9].path : ''" >
          </div> 
            <div class="img-wrapper img" v-for="(image, index) in images" :key="index">
                <img :src="image" :alt="`Image Uplaoder ${index}`">
            </div>
        </div>
          <div v-show="images.length > 0  || ShowImages.length > 0">
            <b-button @click ="ShowImages.length > 0 ? removeImages() : files = [], images = []" variant="danger">Remove</b-button>
          </div>
      </div>
    </b-col>
    </b-row>   
           </b-form>
            <hr />
            <b-row class="pull-right">
              <b-col sm="12">
                <b-button :disabled="forms.products.isSaving" variant="primary" @click="onProductEntry">
                    <icon v-if="forms.products.isSaving" name="sync" spin></icon>
                    <i class="fa fa-check"></i>
                    Save
                </b-button>
                <b-button variant="secondary" @click=" files = [], images = [], showEntry=false , ShowImages = [] , clearFields('products')">Close</b-button>
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
        ShowImages: [],
        files: [],
        images: [],
        entryMode: "Add",
        showModalProducts: false, 
        showModalDelete: false,
        showEntry: false,
        forms: { 
            products: {
            isSaving: false,
            isDeleting: false,
            fields: {
              inmr_hash: null,
              product_name: null,
              product_details: null ,
              getStatus: null,
              getmerchantproducts: null,
              dimension: 0,
              is_measurable: 0,
              onhand_qty: 0,
              available_qty: 0,
              cost_amt: 0,
              weight: 0,
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
                label: "Action",
                thClass: "text-center",
                thStyle: { width: "80px" },
                tdClass: "text-center"
              },
              {
                key: "show_details",
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
      removeImages() {
        this.inmr_hash = this.forms.products.fields.inmr_hash;
               Swal.fire({
                    title: 'Are you sure?',
                    // text: "You won't be able to revert this!",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                    }).then((result) => {

                        // Send request to the server
                         if (result.value) {
                          //  console.log('Show')
                          this.$http
                          .put(
                            "api/products/remove/" + this.inmr_hash, this.forms.products.fields,
                            {
                              headers: {
                                Authorization: "Bearer " + localStorage.getItem("token")
                              }
                            }
                          )
                          .then(()=>{
                                     Swal.fire({
                                        title: 'Deleted!',
                                        icon: 'success',
                                        text: 'The Product has been Deleted.',
                                        showConfirmButton: false,
                                        timer: 2000
                                    }),
                                    this.$http
                                    .get("api/products/" + this.forms.products.fields.inmr_hash, {
                                      headers: {
                                        Authorization: "Bearer " + localStorage.getItem("token")
                                      }
                                    })
                                    .then(response => {
                                      this.forms.products.fields = response.data.products;
                                      this.ShowImages = response.data.images;
                                      // console.log(this.ShowImages.length > 0)
                                      this.showEntry = true;
                                      this.entryMode = "Edit";
                                    })
                                    .catch(err => {
                                      console.log(err);
                                    });
                          }).catch(()=> {
                                  Swal.fire("Failed!", "There was something wronge.", "warning");
                          });
                         }
                    })
      },
     
       DisapproveProduct(data) {
          this.inmr_hash = data.item.inmr_hash;
          Swal.fire({
                    title: 'Are you sure?',
                    // text: "You won't be able to revert this!",
                    icon: 'question',
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
                                    Swal.fire({
                                        title: 'Disapproved!',
                                        icon: 'success',
                                        text: 'The Product has been Disapproved.',
                                        showConfirmButton: false,
                                        timer: 2000
                                    })
                                    this.loadProducts();
                            }).catch(()=> {
                                    Swal.fire("Failed!", "There was something wronge.", "warning");
                            });
                         }
                    })
       },
       ApproveProduct(data) {
           this.inmr_hash = data.item.inmr_hash;
           Swal.fire({
                    title: 'Are you sure?',
                    // text: "You won't be able to revert this!",
                    icon: 'question',
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
                                    Swal.fire({
                                        title: 'Approved!',
                                        icon: 'success',
                                        text: 'The Product has been Approved.',
                                        showConfirmButton: false,
                                        timer: 2000
                                    })
                                    this.loadProducts();
                            }).catch(()=> {
                                    Swal.fire("Failed!", "There was something wronge.", "warning");
                            });
                         }
                    })
        },
       
      
        onInputChange(e) {
            const files = e.target.files;
            Array.from(files).forEach(file => this.addImage(file));
        },  
       
        addImage(file) {
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
                  this.images = [];
                  this.files = [];
                })
                .catch(error => {
                  if (!error.response) return
                  console.log(error)
                })
        },
      onProductEntry() {
        if (this.forms.products.fields.product_name == null || this.forms.products.fields.product_name == "") {
            Toast.fire(
            'Error!',
            'Please Enter Product Name',
            'error'
            )
         this.focusElement('product_name' , false)
        }else if (this.forms.products.fields.product_name.length <= 19) {
          this.focusElement('product_name')
           Toast.fire(
            'Error!',
            'Please Enter Product Name at least 20 Characters',
            'error'
            )
        }else if (this.forms.products.fields.inct_hash == null) {
           this.focusElement('inct_hash')
          Toast.fire(
            'Error!',
            'Please Select Category',
            'error'
            )
        }else if (this.forms.products.fields.product_details == null || this.forms.products.fields.product_details == "") {
          this.focusElement('product_details')
         Toast.fire(
            'Error!',
            'Please Enter Product Details',
            'error'
            )
        }else if (this.forms.products.fields.product_details.length < 50) {
          this.focusElement('product_details')
          Toast.fire(
            'Error!',
            'Please Enter Product Details at least 50 Characters',
            'error'
            )
        }else if (this.forms.products.fields.onhand_qty == "" || this.forms.products.fields.onhand_qty == 0) {
          this.focusElement('onhand_qty')
          Toast.fire(
            'Error!',
            'Please Enter On hand Quantity',
            'error'
            )
        }else if (this.forms.products.fields.available_qty == "" || this.forms.products.fields.available_qty == 0) {
          this.focusElement('available_qty')
          Toast.fire(
              'Error!',
              'Please Enter Available Quantity',
              'error'
              )
        }else if (Number(this.forms.products.fields.onhand_qty) < Number(this.forms.products.fields.available_qty)) {
          this.focusElement('onhand_qty')
          Toast.fire(
              'Error!',
              'Your On Hand Stock should be greater than Your Available Stock',
              'error'
              )
          }else if (this.forms.products.fields.cost_amt == "" || this.forms.products.fields.cost_amt == 0) {
          this.focusElement('cost_amt')
          Toast.fire(
              'Error!',
              'Please Enter Price',
              'error'
              )
        }else if (this.forms.products.fields.weight == null || this.forms.products.fields.weight == 0) {
          this.focusElement('weight')
          Toast.fire(
              'Error!',
              'Please Enter Weight',
              'error'
              )
        }else{
          if (this.entryMode == "Add") {
            if (this.images !== null && this.images.length == 0) {
               this.focusElement('file')
               Toast.fire(
              'Error!',
              'Please Enter Image',
              'error'
              )
            }else{  
            this.forms.products.isSaving = true;
            this.resetFieldStates('products');

            this.$http
              .post("api/" + 'products', this.forms.products.fields, {
                headers: {
                  Authorization: "Bearer " + localStorage.getItem("token")
                }
              })
        .then(response => {
          this.upload();
          this.forms.products.isSaving = false;
          this.clearFields('products');
          this.loadProducts();
          this.images = [];
          this.files = [];
          this.showEntry = false;
          Toast.fire(
              'Success!',
              'The record has been successfully created.',
              'success'
              )
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
            }
          } else {
            //name of form
            //name of primary key
            //if from a modal?
            //row to be edited
            //is from tab
            if (this.ShowImages.length == 0 && this.images.length == 0) {
               this.focusElement('file')
               Toast.fire(
              'Error!',
              'Please Enter Image',
              'error'
              )
            }else{  
            this.forms.products.isSaving = true;
            this.$http
                .put(
                "api/products/" + this.forms.products.fields.inmr_hash,
                this.forms.products.fields,
                {
                    headers: {
                    Authorization: "Bearer " + localStorage.getItem("token")
                    }
                }
                )
                .then(response => {
                  if (this.images.length > 0) {
                    this.upload();
                }
                    Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Update successfuly.',
                    showConfirmButton: false,
                    timer: 2000
                    })
                    this.forms.products.isSaving = false;
                    this.showEntry = false;
                    this.loadProducts()
                    
                }) 
                .catch((error) => {
                    this.forms.products.isSaving = false;
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
                
                    
            }
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
     
      // setUpdate(data) {
      //   this.resetFieldStates("products");
      //   this.fillEntityForm("products", data.item.inmr_hash);
      //   this.showEntry = true;
      //   this.entryMode = "Edit";
      // },

      setUpdate(data) {
      this.$http
        .get("api/products/" + data.item.inmr_hash, {
          headers: {
            Authorization: "Bearer " + localStorage.getItem("token")
          }
        })
        .then(response => {
          this.forms.products.fields = response.data.products;
          this.ShowImages = response.data.images;
          // console.log(this.ShowImages.length > 0)
          this.showEntry = true;
          this.entryMode = "Edit";
        })
        .catch(err => {
          console.log(err);
        });
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
       
      return this.tables.products.items.filter(p => p.sumr_hash == this.forms.products.fields.getmerchantproducts && p.is_verified == this.forms.products.fields.getStatus);
    
    }else if (this.forms.products.fields.getmerchantproducts != null && this.forms.products.fields.getStatus == null){
       
      return this.tables.products.items.filter(p => p.sumr_hash == this.forms.products.fields.getmerchantproducts)
     
     }else if (this.forms.products.fields.getmerchantproducts == null && this.forms.products.fields.getStatus != null){
     
     return this.tables.products.items.filter(p => p.is_verified == this.forms.products.fields.getStatus)
    
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