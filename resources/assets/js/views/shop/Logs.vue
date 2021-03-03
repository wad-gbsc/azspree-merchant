<style lang=scss scoped>

</style>
<template>
    <div>
      <notifications group="notification" />
        <div class="animated fadeIn">
        <div v-if="$store.state.user.type != 2">
            <not-found></not-found>
        </div>
        <div v-show="!showEntry" class="animated fadeIn">
        <b-row>
        <!-- main row -->
        <b-col sm="12">
           <b-card v-show="$store.state.user.type == 2">
            <!-- main card -->
            <h5 slot="header">
              <!-- table header -->
              <span class="text-primary">
                <i class="fa fa-database"></i>
                History Log
              </span>
            </h5>
         
        <b-row class="mb-2">
         
        <!-- row button and search input -->
        <b-col sm="3">
            <b-button
                  variant="primary"
                  @click="showEntry = true, entryMode='Create', tables.sohrItems.items = []"
                >
                  <i class="fa fa-plus-circle"></i> Create New Issuance
            </b-button>
        </b-col>
        <b-col sm="3"><span></span></b-col>
        <b-col sm="3"><span></span></b-col>
        <b-col sm="3">
          <b-form-input
            class="mt-4"
            v-model="filters.issuancemain.criteria"
            type="text"
            placeholder="Search"
          ></b-form-input>
        </b-col>
        </b-row>
              <!-- row table -->
              <b-col sm="12">
                <b-table
                  responsive
                  striped
                  hover
                  small
                  bordered
                  show-empty
                  :fields="tables.issuancemain.fields"
                  :items.sync="tables.issuancemain.items"
                  :filter="filters.issuancemain.criteria"
                  :total-rows="paginations.issuancemain.totalRows"
                  :per-page="paginations.issuancemain.perPage"
                  :current-page="paginations.issuancemain.currentPage"
                >

                <template v-slot:cell(action)="data">
                  <b-button-group>
                    <b-btn :size="'lg'" variant="primary" @click="setMarkDone(data)">
                      <i class="fa fa-check-square-o"></i>
                    </b-btn>
                   <b-btn :size="'lg'" variant="success" @click="previewReport(data)">
                      <i class="fa fa-print"></i>
                    </b-btn>
                    <b-btn :size="'lg'" variant="info" style="color:white" @click="setUpdate(data)">
                      <i class="fa fa-edit"></i>
                    </b-btn>
                    <b-btn
                      :size="'lg'"
                      :disabled="forms.logs.isDeleting"
                      variant="danger"
                      @click="setDelete(data)"
                    >
                      <icon v-if="forms.logs.isDeleting" name="sync" spin></icon>
                      <i v-else class="fa fa-trash"></i>
                    </b-btn>
                  </b-button-group>
                </template>
                </b-table> 
            <b-row>
            <b-col sm="12" class="my-1">
              <b-pagination
                size="sm"
                align="right"
                v-model="paginations.issuancemain.currentPage"
                :per-page="paginations.issuancemain.perPage"
                :total-rows="paginations.issuancemain.totalRows"
                class="my-0"
              ></b-pagination>
            </b-col>
          </b-row>
          </b-col>
          </b-card>
          <!-- ---------------------------------------------------------------------------------- -->
        </b-col>
        </b-row>
        </div>
        </div>
        <div v-show="showEntry" class="animated fadeIn"  @shown="focusElement('')">
        <b-row>
        <!-- sec row -->
        <b-col sm="12">
          <b-card>
            <!-- sec card -->
            <h5 slot="header">
              <!-- table header -->
              <span class="text-primary">{{entryMode}} Invoice</span>
              <!-- <span class="text-primary">Create Issuance</span> -->
            </h5> 
            <b-row>
              <b-col lg="4">
                <b-form-group>
                    <label><i class="icon-required fa fa-exclamation-circle"></i> Issuance No</label>
                    <b-form-input
                        readonly
                        type="text"
                        placeholder="Auto Generated"
                    ></b-form-input>
                </b-form-group>
                <b-form-group>
                        <label><i class="icon-required fa fa-exclamation-circle"></i> Issued to</label>
                      <select2
                        ref="issued_to"
                        id="issued_to"
                        v-model="forms.logs.fields.issued_to"
                        :allowClear="false"
                        :placeholder="'Select Merchant'">
                                <option v-for="right in options.sumr.items"
                                :key="right.sumr_hash" 
                                :value="right.sumr_hash">
                                {{right.seller_name}}
                                </option>
                        </select2>
                    </b-form-group>
              </b-col>
            </b-row>
              <div>
                <b-row class="mb-2">
                <b-col lg="4">
                  <b-button variant="primary" @click="showModalSohr = true, selectedRow=[]">
                            <i class="fa fa-plus-circle"></i> Add Sales
                  </b-button>
                </b-col>
                <b-col lg="4"></b-col>
                <b-col lg="4">
                 <b-form-input v-model="filters.sohrItems.criteria" placeholder="Search"></b-form-input>
                </b-col>
                </b-row>
                <b-row>
                    <b-col sm="12">
                         <b-table
                            responsive
                            fixed
                            striped
                            hover
                            small
                            bordered
                            show-empty
                            :filter="filters.sohrItems.criteria"
                            :fields="tables.sohrItems.fields"
                            :items.sync="tables.sohrItems.items"
                        >
                        <template v-slot:cell(remove)="data">
                                <b-btn
                                    :size="'sm'"
                                    variant="danger"
                                    @click="removeSOHR(data.index)"
                                >
                                    <i class="fa fa-trash"></i>
                                </b-btn>
                        </template>
                  <!-- table -->
                         </b-table>
                    </b-col>
                </b-row>
              </div>
              <hr>
              <b-row class="pull-right mt-2">
                  <b-col sm="12">
                      <b-button
                          :disabled="forms.logs.isSaving" 
                          variant="primary"
                          @click="onIssuanceEntry()">
                          <icon v-if="forms.logs.isSaving" name="sync" spin></icon>
                          <i class="fa fa-check"></i>
                          Save
                      </b-button>
                      <b-button variant="secondary" @click="showEntry=false">Close</b-button>
                  </b-col>
              </b-row>
                  <b-modal
                  size="lg"
                  v-model="showModalSohr"
                  :noCloseOnEsc="true"
                  :noCloseOnBackdrop="true"
              >
              <div slot="modal-title">
                  <!-- modal title -->
                  Sales Order Header List
              </div>
              <!-- modal title -->

              <b-row class="mb-2">
                  <!-- row button and search input -->
                  <b-col sm="4"></b-col>

                  <b-col sm="4">
                      <span></span>
                  </b-col>

                  <b-col sm="4">
                      <b-form-input
                          v-model="filters.sohr.criteria"
                          type="text"
                          placeholder="Search">
                      </b-form-input>
                  </b-col>
              </b-row>
              <!-- row button and search input -->
              <b-table
                  selectable select-mode="single"
                  responsive fixed hover small bordered show-empty
                  :filter="filters.sohr.criteria"
                  :fields="tables.sohr.fields"
                  :per-page="paginations.sohr.perPage"
                  :current-page="paginations.sohr.currentPage"
                  :items="filteredSohr"
                  @filttered="onFiltered($event, 'sohr')"
                  @row-selected="selectedRow = $event">
              <!-- table -->
              </b-table>
              <b-row>
                  <b-col sm="12" class="my-1">
                      <b-pagination
                          size="sm"
                          align="right"
                          v-model="paginations.sohr.currentPage"
                          :per-page="paginations.sohr.perPage"
                          :total-rows="paginations.sohr.totalRows"
                          class="my-0">
                      </b-pagination>
                  </b-col>
              </b-row>
                <!-- modal body -->

                <div slot="modal-footer">
                    <!-- modal footer buttons -->
                    <b-button
                        variant="primary"
                        @click="addSOHR()"
                    >
                        <i class="fa fa-check"></i>
                        Accept
                    </b-button>
                    <b-button variant="secondary" @click="showModalSohr=false">Close</b-button>
                </div>
                <!-- modal footer buttons -->
              </b-modal>
          </b-card>
        </b-col>
        </b-row>
           <b-modal
                  @shown="focusElement('transaction_no')"
                  centered
                  size="sm"
                  v-model="showModalMarkDone"
                  :noCloseOnEsc="true"
                  :noCloseOnBackdrop="true"
              >
              <input type="hidden" v-model="forms.logs.fields.issuance_hash">
              <b-form-group>
                  <label>Transaction No. :</label>
                  <b-form-input
                      ref="transaction_no"
                      id="transaction_no"
                      placeholder="Transaction No."
                      v-model="forms.logs.fields.transaction_no"
                      class='form-control'
              ></b-form-input>
              </b-form-group>
              <b-form-group>
                <date-picker
                  id="transaction_date"
                  format="MMMM/DD/YYYY"
                  type="date"
                  lang="en"
                  input-class="form-control mx-input"
                  v-model="forms.logs.fields.transaction_date"
                  ref="transaction_date"
                ></date-picker>
              </b-form-group>
             <div slot="modal-footer">
                  <b-button
                    class="button"
                    :disabled="forms.logs.isSaving"
                    variant="primary"
                    @click="markDone()"
                  >
                    <icon v-if="forms.logs.isSaving" name="sync" spin></icon>
                    <i class="fa fa-check"></i>
                    Submit
                  </b-button>
                  <b-button class="button" variant="secondary" @click="showModalMarkDone=false">Close</b-button>
                </div>
           </b-modal>
    </div>
</div>

</template>
<script>
  export default {
    data() {
      return {
        showModalMarkDone: false,
        entryMode: "Create",
        showModalSohr: false,
        showEntry: false,
        forms: { 
            logs: {
              fields: {
                 totalcost: 0,
                 totalqty: 0,
                 selectMerchant: 0,
                 issued_to: null,
                 issuance_hash: null,
              }
          },
        },
        options: {
              sumr: {
                items: []
              }
            },
        tables: {
        sohr: {
          fields: [ 
              {
                key: "order_no",
                label: "Order Number",
                tdClass: "align-middle",
                thStyle: { width: "80px" },
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
                key: "order_date",
                label: "Order Date",
                tdClass: "align-middle",
                thStyle: { width: "80px" },
                sortable: true
              },
              {
                key: "payment_method",
                label: "MOP",
                tdClass: "align-middle",
                thStyle: { width: "50px" },
                sortable: false
              },
              {
                key: "shipping_fee",
                label: "Buyer Shipping Fee",
                tdClass: "align-middle text-right",
                thStyle: { width: "60px" },
                sortable: false,
                 formatter: (value) => {
                        return this.formatNumber(value)
                    }
              },
              {
                key: "m_shipping_fee",
                label: "Merchant Shipping Fee",
                tdClass: "align-middle text-right",
                thStyle: { width: "60px" },
                sortable: false,
                formatter: (value) => {
                        return this.formatNumber(value)
                    }
              },
              {
                key: "total_shipping",
                label: "Total Shipping Fee",
                tdClass: "align-middle text-right",
                thStyle: { width: "60px" },
                sortable: false,
               formatter: (value, key, item) => {
                item.total_shipping = Number(item.shipping_fee) + Number(item.m_shipping_fee);
                return this.formatNumber(item.total_shipping);
              }
              }, 
               
              {
                key: "total_qty",
                label: "Qty",
                thStyle: { width: "50px" },
                tdClass: "align-middle",
                sortable: false
              }, 

              {
                key: "order_total",
                label: "Order Total Cost",
                tdClass: "align-middle text-right",
                thStyle: { width: "100px" },
                sortable: false,
                 formatter: (value) => {
                        return this.formatNumber(value)
                    }
              },
              {
              key: "percent",
              label: "Azspree",
              tdClass: "align-middle text-right",
              thStyle: { width: "60px" },
              sortable: false,
              formatter: (value, key, item) => {
              item.percent = Number(item.order_total) * Number(this.azspree.azspree) ;
              return this.formatNumber(item.percent);
            }
            },
            ],
          items: []
            },
        sohrItems: {
          fields: [ 
              {
                key: "order_no",
                label: "Order Number",
                tdClass: "align-middle",
                thStyle: { width: "80px" },
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
                key: "order_date",
                label: "Order Date",
                tdClass: "align-middle",
                thStyle: { width: "80px" },
                sortable: true
              },
              {
                key: "payment_method",
                label: "MOP",
                tdClass: "align-middle",
                thStyle: { width: "50px" },
                sortable: false
              },
              {
                key: "shipping_fee",
                label: "Buyer Shipping Fee",
                tdClass: "align-middle text-right",
                thStyle: { width: "60px" },
                sortable: false,
                 formatter: (value) => {
                        return this.formatNumber(value)
                    }
              },
              {
                key: "m_shipping_fee",
                label: "Merchant Shipping Fee",
                tdClass: "align-middle text-right",
                thStyle: { width: "60px" },
                sortable: false,
                formatter: (value) => {
                        return this.formatNumber(value)
                    }
              },
              {
                key: "total_qty",
                label: "Qty",
                thStyle: { width: "50px" },
                tdClass: "align-middle",
                sortable: false
              }, 

              {
                key: "order_total",
                label: "Order Total Cost",
                tdClass: "align-middle text-right",
                thStyle: { width: "100px" },
                sortable: false,
                 formatter: (value) => {
                        return this.formatNumber(value)
                    }
              },
               {
              key: "azspree",
              label: "Azspree",
              tdClass: "align-middle text-right",
              thStyle: { width: "60px" },
              sortable: true,
              formatter: (value, key, item) => {
              item.azspree = Number(item.order_total) * Number(this.azspree.azspree) ;
              return this.formatNumber(item.azspree);
            }
            },
             {
                key: "remove",
                label: "",
                thStyle: { width: "50px" },
                tdClass: "text-center"
              }
            ],
          items: []
            },
            issuancemain: {
              fields: [
            {
              key: "issuance_hash",
              label: "ID",
              thStyle: { width: "150px" },
              tdClass: "align-middle",
              sortable: true
            },
            {
              key: "issuance_no",
              label: "Issuance No.",
              tdClass: "align-middle",
              sortable: true
            },
            {
              key: "seller_name",
              label: "Issued to",
              tdClass: "align-middle",
              sortable: true
            },
            // {
            //   key: "issuance_datetime",
            //   label: "Datetime",
            //   tdClass: "align-middle",
            //   sortable: true,
            //   formatter: (value) => {
            //       return this.moment(value, 'MMMM DD, YYYY hh:mm a')
            //   }
            // },
            {
              key: "action",
              label: "",
              thStyle: { width: "120px" },
              tdClass: "text-center"
            }

              ],
              items: []
            },
            comr: {
              items: []
            },
        },
        filters: {
          sohr: {
            criteria: null
          },
          sohrItems: {
            criteria: null
          },
          issuancemain: {
            criteria: null
          },
        },
        paginations: {
          logs: {
            totalRows: 0,
            currentPage: 1,
            perPage: 10
          },
            sohr: {
            totalRows: 0,
            currentPage: 1,
            perPage: 10
          },
            issuancemain: {
            totalRows: 0,
            currentPage: 1,
            perPage: 10
          }
        },
      row: [],
      from_datetime: null,
      to_datetime: null
      };
    },
    methods: {
      onIssuanceEntry() {
      if(this.tables.sohrItems.items.length > 0) {
        this.forms.logs.fields.items = this.tables.sohrItems.items
        if (this.entryMode == "Create") {
          this.createEntity("logs", false, "issuancemain")
          this.loadIssuance();
        } else {
          this.updateEntity("logs", "issuance_hash", false, this.row)
        }
      }
      else {
        this.$notify({
          type: 'error',
          group: 'notification',
          title: 'Error!',
          text: 'Please Add a Sales'
        })
      }
    },

       addSOHR() {
        if(this.selectedRow.length > 0){

            if(this.tables.sohrItems.items.filter(i => i.sohr_hash == this.selectedRow[0].sohr_hash).length > 0){
            this.$notify({
                    type: "error",
                    group: "notification",
                    title: "Error!",
                    text: "Sales Order is already added."
                })
                return
            }
            this.tables.sohrItems.items.push({
                sohr_hash: this.selectedRow[0].sohr_hash,
                order_no: this.selectedRow[0].order_no,
                seller_name: this.selectedRow[0].seller_name,
                order_date: this.selectedRow[0].order_date,
                payment_method: this.selectedRow[0].payment_method,
                shipping_fee: this.selectedRow[0].shipping_fee,
                m_shipping_fee: this.selectedRow[0].m_shipping_fee,
                total_qty: this.selectedRow[0].total_qty,
                order_total: this.selectedRow[0].order_total,
                azspree: this.selectedRow[0].azspree,
            })
        }
    },
    removeSOHR(index){
        this.tables.sohrItems.items.splice(index, 1)
    },
    //   previewReport() {
    //   var date_from = 0;
    //   var date_to = 0;
    //   if (this.from_datetime != null && this.to_datetime != null) {
    //     date_from = this.moment(this.from_datetime, "YYYY-MM-DD");
    //     date_to = this.moment(this.to_datetime, "YYYY-MM-DD");
    //   }
    //   window.open("api/logs/printreport/" + date_from + "/" + date_to);
    // },
     previewReport(data) {
      this.issuance_hash = data.item.issuance_hash;
      window.open("api/logs/printreport/" + this.issuance_hash);
      },
      async setDelete(data) {
        this.issuance_hash = data.item.issuance_hash;
        Swal.fire({
                    title: 'Are you sure?',
                    // text: "You won't be able to revert this!",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Delete it!',
                    text: "You won't be able to revert this!",
                    }).then((result) => {

                        // Send request to the server
                         if (result.value) {
                                this.$http
                          .put(
                            "api/issuance/delete/" + this.issuance_hash,
                            this.forms.logs.fields,
                            {
                              headers: {
                                Authorization: "Bearer " + localStorage.getItem("token")
                              }
                            }
                          ).then(()=>{
                                    Swal.fire(
                                    'Deleted!',
                                    'The Issuance has been Banned.',
                                    'success'
                                    )
                                    this.loadIssuance();
                            }).catch(()=> {
                                    Swal.fire("Failed!", "There was something wronge.", "warning");
                            });
                         }
                    })
      },
      setUpdate(data) {
      this.row = data.item
      this.$http.get('api/issuance/'+ data.item.issuance_hash, {
          headers: {
                Authorization: 'Bearer ' + localStorage.getItem('token')
          }
      }).then(response => {
          this.forms.logs.fields = response.data.issuance
          this.tables.sohrItems.items = response.data.issuance_items
          this.showEntry = true
          this.entryMode = "Edit"
      }).catch(err => {
          console.log(err)
      })
      },
      setMarkDone(data) {
      this.row = data.item
      this.$http.get('api/issuance/'+ data.item.issuance_hash, {
          headers: {
                Authorization: 'Bearer ' + localStorage.getItem('token')
          }
      }).then(response => {
          this.forms.logs.fields.issuance_hash = response.data.issuance.issuance_hash
          this.showModalMarkDone = true;
      }).catch(err => {
          console.log(err)
      })
      },
      markDone() {

        if (this.forms.logs.fields.transaction_no == null || this.forms.logs.fields.transaction_no == "") {
                    this.focusElement('transaction_no')
                    Toast.fire({
                      icon: 'error',
                      title: 'Error!',
                      showConfirmButton: false,
                      timer: 2000,
                      text: 'Transaction No. field is required'
                      })
        }else if (this.forms.logs.fields.transaction_date == null || this.forms.logs.fields.transaction_date == ""){
                    this.focusElement('transaction_date')
                    Toast.fire({
                      icon: 'error',
                      title: 'Error!',
                      showConfirmButton: false,
                      timer: 2000,
                      text: 'Transaction Date field is required'
                      })
        }else{
      Swal.fire({
                  title: 'Are you sure?',
                  // text: "You won't be able to revert this!",
                  icon: 'question',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Yes, Mark as Paid!',
                  }).then((result) => {

                      // Send request to the server
                        if (result.value) {
                              this.$http
                        .put(
                          "api/markpaid/" + this.forms.logs.fields.issuance_hash,
                          this.forms.logs.fields,
                          {
                            headers: {
                              Authorization: "Bearer " + localStorage.getItem("token")
                            }
                          }
                        ).then(()=>{
                                  Swal.fire(
                                  'Paid!',
                                  'The Issuance has been Paid.',
                                  'success'
                                  )
                                  this.loadIssuance();
                                  this.showModalMarkDone = false;
                          }).catch(()=> {
                                  Swal.fire("Failed!", "There was something wronge.", "warning");
                          });
                        }
                  })
                  }
      },
      loadIssuance() {
      this.$http
      .get("api/logs", {
        headers: {
          Authorization: "Bearer " + localStorage.getItem("token")
        }
      })
      .then(response => {
        this.tables.issuancemain.items = response.data.issuancemain;
        this.options.sumr.items = response.data.sumr;
        this.tables.sohr.items = response.data.sohr;
        this.azspree = response.data.comr[0];
        this.paginations.sohr.totalRows = response.data.sohr.length;
      })
      .catch(error => {
        console.log(error);
      });
      
      },

    },
      computed: {

        filteredSohr() {
             if (this.forms.logs.fields.issued_to != null) {
                return this.tables.sohr.items.filter(r => r.sumr_hash == this.forms.logs.fields.issued_to);
             }
        },
        GetTotalCost() {
        if (this.from_datetime != null && this.to_datetime != null) {
        this.moment(this.from_datetime, "YYYY-MM-DD");
        this.moment(this.to_datetime, "YYYY-MM-DD");
          
        this.forms.logs.fields.totalcost = 0;
         this.tables.logs.items.forEach(item => {
          this.forms.logs.fields.totalcost +=
          Number(item.order_subtotal);
      });
          return this.forms.logs.fields.totalcost;
          }else{
             this.forms.logs.fields.totalcost = 0;
         this.tables.logs.items.forEach(item => {
          this.forms.logs.fields.totalcost +=
          Number(item.order_subtotal);
      });
            return this.forms.logs.fields.totalcost;
          }
          
      },
        GetTotalQuantity() {
        if (this.from_datetime != null && this.to_datetime != null) {
          this.moment(this.from_datetime, "YYYY-MM-DD");
          this.moment(this.to_datetime, "YYYY-MM-DD");

        this.forms.logs.fields.totalqty = 0;
         this.tables.logs.items.forEach(item => {
          this.forms.logs.fields.totalqty +=
          Number(item.total_qty);
      });
          return this.forms.logs.fields.totalqty;
          }else{
                 this.forms.logs.fields.totalqty = 0;
         this.tables.logs.items.forEach(item => {
          this.forms.logs.fields.totalqty +=
          Number(item.total_qty);
      });
            return this.forms.logs.fields.totalqty;
          }
        },  
      filterLogs() {
         if (this.forms.logs.fields.selectMerchant != null && this.forms.logs.fields.selectMerchant.length > 0) {
          if (this.from_datetime != null && this.to_datetime != null) {
          return this.tables.logs.items.filter(
            d =>
              this.moment(d.order_date, "YYYY-MM-DD") >=
                this.moment(this.from_datetime, "YYYY-MM-DD") &&
              this.moment(d.order_date, "YYYY-MM-DD") <=
                this.moment(this.to_datetime, "YYYY-MM-DD") && (d.seller_name === this.forms.logs.fields.selectMerchant)
          );
            return this.tables.logs.items;
          }else{
            return this.tables.logs.items.filter(s => s.seller_name === this.forms.logs.fields.selectMerchant);
          }
       }else{
        return this.tables.logs.items;
      }
    }
    },

    created() {
      this.loadIssuance();
   
  },
};
</script>