<style lang=scss scoped>
input[type="number"]::-webkit-inner-spin-button,
input[type="number"]::-webkit-outer-spin-button {
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
  margin: 0;
}
</style>
<template>
    <div>
        <div v-if="$store.state.user.type != 2">
        <not-found></not-found>
        </div>
        <div v-show="!showEntry" class="animated fadeIn">
            <b-row>
                <b-col lg="12">
                    <b-card>
                        <!-- main card -->
                        <h5 slot="header">
                        <!-- table header -->
                        <span class="text-primary">
                            <i class="fa fa-users"></i>
                            List of all Merchants
                        </span>
                        </h5>
                                <b-row class="mb-2">
                        <!-- row button and search input -->
                                <b-col sm="4">
                                    <b-form-group>
                                    <b-button
                                    variant="primary"
                                    @click="showEntry = true, entryMode='Add', clearFields('merchants'), focusElement('shop_name', true)"
                                    >
                                    <i class="fa fa-plus-circle"></i> Add New Merchant
                                    </b-button>
                                    </b-form-group>
                                </b-col>
                                <b-col lg="4">
                                    <span></span>
                                </b-col>

                                <b-col sm="4">
                                    <b-form-input
                                    v-model="filters.merchants.criteria"
                                    type="text"
                                    placeholder="Search"
                                    ></b-form-input>
                                </b-col>
                                </b-row>
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
                                :fields="tables.merchants.fields"
                                :items.sync="tables.merchants.items"
                                :filter="filters.merchants.criteria"
                                >
                                <template v-slot:cell(action)="data">
                                        <b-btn :size="'sm'" variant="primary" @click="setUpdate(data)">
                                        <i class="fa fa-edit"></i>
                                        </b-btn>
                                        <b-btn :size="'sm'" :disabled="forms.merchants.isDeleting"
                                        variant="danger"
                                        @click="deleteMerchant(data)"
                                        >
                                        <icon v-if="forms.merchants.isDeleting" name="sync" spin></icon>
                                        <i v-else class="fa fa-trash"></i>
                                        </b-btn>
                                    </template>
                                </b-table> 
                                <!-- table -->
                            </b-col>
                        </b-row>
                    </b-card>
                </b-col>
            </b-row>
         </div>
            <div v-show="showEntry" class="animated fadeIn"  @shown="focusElement('shop_name')">
                <b-row>
                    <!-- sec row -->
                    <b-col sm="12">
                            <b-card>
                                <!-- sec card -->
                                <h5 slot="header">
                                <!-- table header -->
                                <span class="text-primary">{{entryMode}} Merchant</span>
                                </h5>
                                <b-col lg="12">
                                    <b-row>
                                        <b-col lg="4">
                                            <b-form @keydown="resetFieldStates('merchants')" autocomplete="off" @shown="focusElement('shop_name')">
                                                <b-form-group>
                                                    <label for="shop_name"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> Shop Name</label>
                                                            <b-form-input
                                                            id="shop_name"
                                                            ref="shop_name"
                                                            placeholder="Enter Shop Name"
                                                            v-model="forms.merchants.fields.shop_name">
                                                        </b-form-input>
                                                </b-form-group>
                                                <b-form-group>
                                                    <label for="seller_name"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> Seller Name</label>
                                                            <b-form-input
                                                            id="seller_name"
                                                            ref="seller_name"
                                                            placeholder="Enter Seller Name"
                                                            v-model="forms.merchants.fields.seller_name">
                                                        </b-form-input>
                                                </b-form-group>
                                                <b-form-group>
                                                    <label for="email"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> Email Address</label>
                                                            <b-form-input
                                                            type="email"
                                                            id="email"
                                                            ref="email"
                                                            placeholder="Enter Email Address"
                                                            v-model="forms.merchants.fields.email">
                                                        </b-form-input>
                                                </b-form-group>
                                                <b-form-group>
                                                    <label><i class="icon-required fa fa-exclamation-circle"></i> Contact No.</label>
                                                    <b-form-input
                                                        type="number"
                                                        ref="contact"
                                                        id="contact"
                                                        v-model="forms.merchants.fields.contact"
                                                        class='form-control'
                                                        placeholder="Contact No."
                                                    ></b-form-input>
                                                </b-form-group>
                                                <b-form-group>
                                                    <label><i class="icon-required fa fa-exclamation-circle"></i> Province</label>
                                                    <select2
                                                    id="prov_hash"
                                                    ref="prov_hash"
                                                    v-model="forms.merchants.fields.prov_hash"
                                                    :allowClear="false"
                                                    :placeholder="'Select Province'">
                                                            <option v-for="right in options.prov.items"
                                                            :key="right.prov_hash" 
                                                            :value="right.prov_hash">
                                                            {{right.province}}
                                                            </option>
                                                    </select2>
                                                </b-form-group>
                                                <b-form-group>
                                                    <label><i class="icon-required fa fa-exclamation-circle"></i> City</label>
                                                    <select2
                                                    id="city_hash"
                                                    ref="city_hash"
                                                    v-model="forms.merchants.fields.city_hash"
                                                    :allowClear="false"
                                                    :placeholder="'Select City'">
                                                            <option v-for="right in options.m_city.items"
                                                            :key="right.city_hash" 
                                                            :value="right.city_hash">
                                                            {{right.m_city}}
                                                            </option>
                                                    </select2>
                                                </b-form-group>
                                                <b-form-group>
                                                    <label><i class="icon-required fa fa-exclamation-circle"></i> Barangay</label>
                                                    <select2
                                                    id="brgy"
                                                    ref="brgy"
                                                    v-model="forms.merchants.fields.brgy"
                                                    :allowClear="false"
                                                    :placeholder="'Select Barangay'">
                                                            <option v-for="right in options.brgy.items"
                                                            :key="right.brgy_hash" 
                                                            :value="right.brgy_hash">
                                                            {{right.barangay}}
                                                            </option>
                                                    </select2>
                                                </b-form-group>
                                                <b-form-group>
                                                    <label for="sumr_address" autocomplete="off"> Address</label><span> House# / Bldg# / Subdivision </span>
                                                            <b-form-input
                                                            id="sumr_address"
                                                            ref="sumr_address"
                                                            placeholder="House# / Bldg# / Subdivision"
                                                            v-model="forms.merchants.fields.sumr_address">
                                                        </b-form-input>
                                                </b-form-group>
                                            </b-form>
                                        </b-col>
                                    </b-row>
                                </b-col>
                                <hr/>
                                    <b-row class="pull-right mt-2">
                                        <b-col sm="12">
                                            <b-button
                                            :disabled="forms.merchants.isSaving"
                                            variant="primary"
                                            @click="onMerchantEntry()"
                                            >
                                            <icon v-if="forms.merchants.isSaving" name="sync" spin></icon>
                                            <i class="fa fa-check"></i>
                                            Save
                                            </b-button>
                                            <b-button variant="secondary" @click="showEntry=false">Close</b-button>
                                        </b-col>
                                    </b-row>
                        </b-card>
                    </b-col>
                </b-row>
            </div>
    </div>
</template>
<script>
  export default {
    data() {
      return {
        entryMode: "Add",
        showEntry: false,
        // showModalDelete: false,
        forms: { 
            merchants: {
                    isSaving: false,
                    isDeleting: false,
                   fields: {
                       shop_name:  null,
                   }
          },
        },
        tables: {
          merchants: {
            fields: [ 
              {
                key: "shop_name",
                label: "Shop Name",
                tdClass: "align-middle",
                thStyle: { width: "100px" },
                sortable: true
              },
              {
                key: "seller_name",
                label: "Merchant Name",
                tdClass: "align-middle",
                thStyle: { width: "100px" },
                sortable: true
              },
              {
                key: "email",
                label: "Email",
                tdClass: "align-middle",
                thStyle: { width: "100px" },
                sortable: true
              },  
              {
                key: "contact",
                label: "Contact No.",
                tdClass: "align-middle text-left",
                thStyle: { width: "100px" },
                sortable: true,
              },
              {
                key: "action",
                label: "",
                thStyle: { width: "100px" },
                tdClass: "text-center"
              }
            ],
            items: []
            },
        },
        filters: {
          merchants: {
            criteria: null
          },
        },
             
       options: {
            prov: {
                items: []
            },
            m_city: {
                items: []
            },
                brgy: {
                items: []
            }
        },
        paginations: {
          merchants: {
            totalRows: 0,
            currentPage: 1,
            perPage: 10
          }
        },
        row: []
      };
    },
    methods: {
      deleteMerchant(data) {
        this.sumr_hash = data.item.sumr_hash;
           Swal.fire({
                    title: 'Are you sure?',
                    // text: "You won't be able to revert this!",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {

                        // Send request to the server
                         if (result.value) {
                                this.$http
                          .put(
                            "api/merchants/delete/" + this.sumr_hash,
                            this.forms.merchants.fields,
                            {
                              headers: {
                                Authorization: "Bearer " + localStorage.getItem("token")
                              }
                            }
                          ).then(()=>{
                                    Swal.fire({
                                        title: 'Deleted!',
                                        icon: 'success',
                                        text: 'The Product has been Deleted.',
                                        showConfirmButton: false,
                                        timer: 2000
                                    })
                                    this.loadMerchants();
                            }).catch(()=> {
                                    Swal.fire("Failed!", "There was something wronge.", "warning");
                            });
                         }
                    })
      },
        setUpdate(data) {
        this.resetFieldStates("merchants");
        this.fillEntityForm("merchants", data.item.sumr_hash);
        this.showEntry = true;
        this.entryMode = "Edit";
      },
        onMerchantEntry() {
            this.forms.merchants.isSaving = true;
            this.resetFieldStates('merchants');

            this.$http
              .post("api/" + 'merchants', this.forms.merchants.fields, {
                headers: {
                  Authorization: "Bearer " + localStorage.getItem("token")
                }
              })
        .then(response => {
          this.forms.merchants.isSaving = false;
          this.clearFields('merchants');
          this.showEntry = false;
          this.loadMerchants()
          Swal.fire({
                title: 'Success!',
                icon: 'success',
                text: 'The record has been successfully created.',
                showConfirmButton: false,
                timer: 2000
            })
        })
        .catch(error => {
          this.forms.merchants.isSaving = false;
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
        },
        loadMerchants() {
            this.$http
            .get("api/merchants", {
                headers: {
                Authorization: "Bearer " + localStorage.getItem("token")
                }
            })
            .then(response => {
                this.tables.merchants.items = response.data.merchants;
                this.options.prov.items = response.data.prov;
                this.options.m_city.items = response.data.m_city;
                this.options.brgy.items = response.data.brgy;
            })
            .catch(error => {
                console.log(error);
            });
        }
    },
    created() {
        this.loadMerchants()
  },
};
</script>