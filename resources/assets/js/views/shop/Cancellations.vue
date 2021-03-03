
<template>
    <div>
        <notifications group="notification" />
        <div class="animated fadeIn">
            <b-row>
        <!-- main row -->
        <b-col sm="12">
          <b-card>
            <!-- main card -->
            <h5 slot="header">
              <!-- table header -->
              <span class="text-primary">
                <i class="fa fa-exclamation-circle"></i>
                Cancellations
              </span>
            </h5>
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
                  :fields="tables.cancellations.fields"
                  :items.sync="tables.cancellations.items"
                  :filter="filters.cancellations.criteria"
                >
                   <template v-slot:cell(show_details)="row">
                      <b-button
                        class="button"
                        variant="primary"
                        size="sm"
                        @click="row.toggleDetails()"
                      >
                        <i class="fa fa-eye"></i>
                        {{ row.detailsShowing ? 'Hide' : 'Show'}} Details 
                      </b-button>
                    </template>
                    <template v-slot:row-details="row"> 
                          <b-form-group>
                          <label>Customer Name :</label>
                          <b-form-input disabled
                          v-model="this.fullname">
                          </b-form-input>
                          </b-form-group>
                          <b-form-group>
                          <label>Customer Address :</label>
                          <b-form-input disabled>
                          </b-form-input>
                          </b-form-group>
                         <b-table
                              style="overflow: hidden;"
                              fixed
                              responsive
                              striped
                              hover
                              small
                              bordered
                              show-empty
                              :fields="tables.soln.fields"
                              :items="solnfilter(row.item.sohr_hash)"
                              :filter="filters.soln.criteria"
                            >
                         </b-table>
                    </template>
                </b-table> 
                <!-- table -->
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
        // entryMode: "Add",
        // showModalProducts: false, 
        // showModalDelete: false,
        forms: { 
            cancellations: {
                    // isSaving: false,
                    // isDeleting: false,
                    // order_no: null
          },
        },
        tables: {
          cancellations: {
            fields: [ 
              {
                key: "order_no",
                label: "Order Number",
                tdClass: "align-middle",
                thStyle: { width: "100px" },
                sortable: true
              },
              {
                key: "order_date",
                label: "Order Date",
                tdClass: "align-middle",
                thStyle: { width: "100px" },
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
              key: "order_name",
              label: "Status",
              thStyle: { width: "80px" },
              tdClass: "align-middle",
              sortable: true
              },  
              {
                key: "order_subtotal",
                label: "Order Subtotal",
                tdClass: "align-middle text-right",
                thStyle: { width: "100px" },
                sortable: true,
                 formatter: (value) => {
                        return this.formatNumber(value)
                    }
              },
              {
                key: "show_details",
                label: "",
                thStyle: { width: "100px" },
                tdClass: "text-center"
              }
            ],
            items: []
            },
            soln: {
              fields: [
                {
                key: "soln_hash",
                label: "No.",
                tdClass: "align-middle",
                thStyle: { width: "40px" },
                sortable: false
                },
                {
                key: "product_name",
                label: "Product Name",
                tdClass: "align-middle",
                thStyle: { width: "40px" },
                sortable: false
                },
                {
                key: "qty",
                label: "Quantity",
                tdClass: "align-middle",
                thStyle: { width: "40px" },
                sortable: false
                },
                 {
                key: "unit_price",
                label: "Price",
                tdClass: "align-middle text-right",
                thStyle: { width: "100px" },
                sortable: true,
                 formatter: (value) => {
                        return this.formatNumber(value)
                    }
                },
              ],
            items: []
          }
        },
        filters: {
          cancellations: {
            criteria: null
          },
          soln: {
            criteria: null
          }
        },
             
        // options: {
        //   products: {
        //     items: []
        //   }
        // },
        paginations: {
          cancellations: {
            totalRows: 0,
            currentPage: 1,
            perPage: 10
          }
        },
        sohr_hash: null,
        order_no: null,
        row: []
      };
    },
    methods: {
      solnfilter(sohr_hash) {
      return this.tables.soln.items.filter(r => r.sohr_hash == sohr_hash);
    }
    },

    created() {
    this.$http
      .get("api/orders", {
        headers: {
          Authorization: "Bearer " + localStorage.getItem("token")
        }
      })
      .then(response => {
        this.tables.cancellations.items = response.data.cancellations;
        this.tables.soln.items = response.data.soln;
      })
      .catch(error => {
        console.log(error);
      });
  },
};
</script>