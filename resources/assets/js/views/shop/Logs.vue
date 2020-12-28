<template>
    <div>
        <notifications group="notification" />
        <div class="animated fadeIn">
            <b-row>
        <!-- main row -->
        <b-col sm="12">
          <b-card v-show="$store.state.user.type == 2">
            <!-- main card -->
            <h5 slot="header">
              <!-- table header -->
              <span class="text-primary">
                <i class="fa fa-bars"></i>
                History Log
              </span>
            </h5>
        <b-row class="mb-2">
        <!-- row button and search input -->
        <b-col sm="3">
          <label>Select Merchant</label>
          <select2
            :placeholder="'Select Merchant'"
            class="form-control mt-4"
            id="selectMerchant" 
            ref="selectMerchant" 
            v-model="forms.logs.fields.selectMerchant" 
            >
            <option
              v-for="right in options.sumr.items"
              :key="right.sumr_hash"
              :value="right.sumr_hash"
            >{{right.seller_name}}</option>
            </select2>
        </b-col>
       
        <b-col sm="3">
          <label>From Date</label>
          <date-picker
            id="from_datetime"
            format="MMMM/DD/YYYY"
            type="date"
            lang="en"
            input-class="form-control mx-input"
            v-model="from_datetime"
            ref="from_datetime"
          ></date-picker>
        </b-col>
        <b-col sm="3">
          <label>To Date</label>
          <date-picker
            id="to_datetime"
            format="MMMM/DD/YYYY"
            type="date"
            lang="en"
            input-class="form-control mx-input"
            v-model="to_datetime"
            ref="to_datetime"
          ></date-picker>
        </b-col>
        <b-col sm="3">
          <b-form-input
            class="mt-4"
            v-model="filters.logs.criteria"
            type="text"
            placeholder="Search"
          ></b-form-input>
        </b-col>
      </b-row>
              <!-- row table -->
              <b-col sm="12">
                <b-table
                  selectable select-mode="multiple"
                  responsive
                  striped
                  hover
                  small
                  bordered
                  show-empty
                  :fields="tables.logs.fields"
                  :items="filterLogs"
                  :filter="filters.logs.criteria"
                  :total-rows="paginations.logs.totalRows"
                  :per-page="paginations.logs.perPage"
                  :current-page="paginations.logs.currentPage"
                >
                </b-table> 
                <b-row>
                  <b-col lg="3">
                  <b-button
                    @click="previewReport()"
                    class="btn-success"
                  >
                    <i class="fa fa-print"></i> Print
                  </b-button>
                  </b-col>
                  <b-col lg="4">
                    <span></span>
                  </b-col>
                  <b-col lg="2">
                <b-form-group style="float:right;" >
                <label for="totalquantity" >Total Quantity :</label>
                <b-form-input
                  style="background-color: white; width: 50%;"
                  readonly
                  ref="totalquantity"
                  id="totalquantity"
                  v-model="this.GetTotalQuantity"
                  class='form-control'
                  placeholder="0"
                ></b-form-input>
                  </b-form-group> 
                  </b-col>
                <b-col lg="3">
                <b-form-group>
                <label>Total Cost :</label>
                <vue-autonumeric
                    disabled
                    ref="totalcost"
                    id="totalcost"
                    v-model="this.GetTotalCost"
                    class='form-control text-right'
                      :options="{
                        minimumValue: '0', 
                        emptyInputBehavior:'0',}"
                ></vue-autonumeric>
                  </b-form-group> 
                  </b-col>
                  </b-row>
                   <b-row>
            <b-col sm="12" class="my-1">
              <b-pagination
                size="sm"
                align="right"
                v-model="paginations.logs.currentPage"
                :per-page="paginations.logs.perPage"
                :total-rows="paginations.logs.totalRows"
                class="my-0"
              ></b-pagination>
            </b-col>
          </b-row>
              </b-col>
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
        forms: { 
            logs: {
              fields: {
                 totalcost: 0,
                 totalqty: 0,
                 selectMerchant: 0,
              }
          },
        },
        options: {
              sumr: {
                items: []
              }
            },
        tables: {
         
          comr: {
            items: []
          },
          logs: {
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
                label: "Payment Method",
                tdClass: "align-middle",
                thStyle: { width: "100px" },
                sortable: true
              },
              {
                key: "shipping_fee",
                label: "Buyer Shipping Fee",
                tdClass: "align-middle text-right",
                thStyle: { width: "60px" },
                sortable: true,
                 formatter: (value) => {
                        return this.formatNumber(value)
                    }
              },
              {
                key: "m_shipping_fee",
                label: "Merchant Shipping Fee",
                tdClass: "align-middle text-right",
                thStyle: { width: "60px" },
                sortable: true,
                formatter: (value) => {
                        return this.formatNumber(value)
                    }
              },
              {
                key: "total_shipping",
                label: "Total Shipping Fee",
                tdClass: "align-middle text-right",
                thStyle: { width: "60px" },
                sortable: true,
               formatter: (value, key, item) => {
                item.total_shipping = Number(item.shipping_fee) + Number(item.m_shipping_fee);
                return this.formatNumber(item.total_shipping);
              }
              },
              {
                key: "order_name",
                label: "Status",
                thStyle: { width: "80px" },
                tdClass: "align-middle",
                sortable: true
              }, 
               
              {
                key: "total_qty",
                label: "Quantity",
                thStyle: { width: "80px" },
                tdClass: "align-middle",
                sortable: true
              }, 

              {
                key: "order_total",
                label: "Order Total Cost",
                tdClass: "align-middle text-right",
                thStyle: { width: "100px" },
                sortable: true,
                 formatter: (value) => {
                        return this.formatNumber(value)
                    }
              },
              {
              key: "percent",
              label: "Azspree",
              tdClass: "align-middle text-right",
              thStyle: { width: "60px" },
              sortable: true,
              formatter: (value, key, item) => {
              item.percent = Number(item.order_total) * Number(this.azspree.azspree) ;
              return this.formatNumber(item.percent);
            }
            },
            ],
            items: []
            },
            
          
            
        },
        filters: {
          logs: {
            criteria: null
          },
        },
        paginations: {
          logs: {
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
      previewReport() {
      var date_from = 0;
      var date_to = 0;
      if (this.from_datetime != null && this.to_datetime != null) {
        date_from = this.moment(this.from_datetime, "YYYY-MM-DD");
        date_to = this.moment(this.to_datetime, "YYYY-MM-DD");
      }
      window.open("api/logs/printreport/" + date_from + "/" + date_to);
    }
    //   getMerchant: function(value, data) {
    //   if (this.forms.logs.fields.selectMerchant != null && this.forms.logs.fields.selectMerchant.length > 0){
    //     // console.log(this.tables.logs.items.filter(r => r.sumr_hash == value))

    //     return this.tables.logs.items.filter(r => r.sumr_hash == value);
    //   }else{
    //       return this.tables.logs.items;
    //   }
    // },

    },
      computed: {
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
        // return this.tables.logs.items.filter(s => s.seller_name === this.forms.logs.fields.selectMerchant);
        
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
          
        // }
      // if (this.from_datetime != null && this.to_datetime != null) {
      //   return this.tables.logs.items.filter(
      //     d =>
      //       this.moment(d.order_date, "YYYY-MM-DD") >=
      //         this.moment(this.from_datetime, "YYYY-MM-DD") &&
      //       this.moment(d.order_date, "YYYY-MM-DD") <=
      //         this.moment(this.to_datetime, "YYYY-MM-DD")
      //   );
       }else{
        return this.tables.logs.items;
      }
    }
    },

    created() {
    this.$http
      .get("api/logs", {
        headers: {
          Authorization: "Bearer " + localStorage.getItem("token")
        }
      })
      .then(response => {
        this.options.sumr.items = response.data.sumr
        this.tables.logs.items = response.data.logs;
        this.azspree = response.data.comr[0];
        this.paginations.logs.totalRows = response.data.logs.length;
      })
      .catch(error => {
        console.log(error);
      });
  },
};
</script>