<style scoped>
.w-card:hover {
  cursor: pointer;
}
.t-card {
  width: 80%;
  margin-left: 10%;
}

@media screen and (max-width: 600px) {
  .a a{
   margin: 0%;
  }
}
</style>
<template>
  <div>
  <notifications group="notification" />
  <!-- <div v-if="errors" class="bg-red-500 text-white py-2 px-4 pr-0 rounded font-bold mb-4 shadow-lg">
    <div v-for="(v, k) in errors" :key="k">
      <p v-for="error in v" :key="error" class="text-sm">
        {{ error }}
      </p>
    </div>
  </div> -->
  <div class="animated fadeIn">
    
      <div class="row">
      <div class="col-sm-6 col-lg-3">
        <b-card class="bg-primary w-card" @click="newOrderEntry = true,IntransitEntry = false, DeliverEntry = false, CompletedEntry = false" style="height:75%;">
          <div class="card-body pb-0">
            <b-row>
            <b-col lg="6">
            <h2 class="mb-0">{{tables.sohr.items.filter(i => i.order_stat == 1).length}}</h2>
            <p style="font-size: 14px">New Order(s)</p>
            </b-col>
             <b-col lg="6">
               <a class="fa fa-shopping-cart float-right" style="font-size:60px;padding:0%;"></a>
             </b-col>
          </b-row>
          </div>
        </b-card>
      </div><!--/.col-->
      <div class="col-sm-6 col-lg-3">
        <b-card class="bg-success w-card" @click="IntransitEntry = true ,newOrderEntry = false, DeliverEntry = false, CompletedEntry = false" style="cursor:pointer !impotant; height:75%;">
          <div class="card-body pb-0">
            <b-row>
            <b-col lg="6">
            <h3 class="mb-0">{{tables.sohr.items.filter(i => i.order_stat == 2 || i.order_stat == 3 || i.order_stat == 4 || i.order_stat == 5).length}}</h3>
            <p class="mb-0" style="font-size: 12px">In - transit(s) &nbsp; <i class="fa fa-angle-double-down" aria-hidden="true"></i></p>
            <b-row>
            <p class="mb-0" style="font-size: 12px" > &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;For Drop-off(s) : &nbsp;</p>
            <h6 class="mb-0">{{tables.sohr.items.filter(i => i.order_stat == 2 ).length}}</h6>
            </b-row>
            <b-row>
            <p class="mb-0" style="font-size: 12px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;For Pick Up(s) : &nbsp;</p>
            <h6 class="mb-0">{{tables.sohr.items.filter(i => i.order_stat == 3 || i.order_stat == 4).length}}</h6>
            </b-row>
            <b-row>
            <p class="mb-0" style="font-size: 12px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;For Dispatch(s) : &nbsp;</p>
            <h6 class="mb-0">{{tables.sohr.items.filter(i => i.order_stat == 5).length}}</h6>
            <hr>
            </b-row>
            </b-col>
             <b-col lg="6">
               <a class="fa fa-truck float-right" style="font-size:60px;padding:0%;"></a>
             </b-col>
          </b-row>
          </div>
         </b-card>
      </div>
      <!--/.col-->
      <div class="col-sm-6 col-lg-3">
        <b-card class="bg-warning w-card" :no-block="true" @click="DeliverEntry = true, IntransitEntry = false ,newOrderEntry = false, CompletedEntry = false" style="height:75%;">
          <div class="card-body pb-0">
            <b-row>
            <b-col lg="6">
            <h3 class="mb-0">{{tables.sohr.items.filter(i => i.order_stat == 6  || i.order_stat == 7).length}}</h3>
            <p class="mb-0" style="font-size: 12px">Delivery(s) &nbsp; <i class="fa fa-angle-double-down" aria-hidden="true"></i></p>
           <b-row> 
            <p class="mb-0" style="font-size: 12px"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;To Deliver(s) : &nbsp;</p>
            <h6 class="mb-0">{{tables.sohr.items.filter(i => i.order_stat == 6 ).length}}</h6>
            </b-row>
             <b-row>
            <p class="mb-0" style="font-size: 12px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Delivered(s) : &nbsp;</p>
            <h6 class="mb-0">{{tables.sohr.items.filter(i => i.order_stat == 7).length}}</h6>
            </b-row>
            </b-col>
             <b-col lg="6">
               <a class="fa fa-arrow-circle-o-right float-right" style="font-size:60px;padding:0%;"></a>
             </b-col>
          </b-row>
          </div>
        </b-card>
      </div>
      <!--/.col-->
      <div class="col-sm-6 col-lg-3">
        <b-card class="bg-danger w-card" :no-block="true" @click="CompletedEntry = true,DeliverEntry = false, IntransitEntry = false ,newOrderEntry = false" style="height:75%;">
          <div class="card-body pb-0">
            <b-row>
            <b-col lg="6">
            <h2 class="mb-0">{{tables.sohr.items.filter(i => i.order_stat == 7 && i.status_user == "COMPLETED").length}}</h2>
            <p style="font-size: 14px">Completed(s)</p>
            </b-col>
             <b-col lg="6">
               <a class="fa fa-check-square float-right" style="font-size:60px;padding:0%;"></a>
               
             </b-col>
          </b-row>
          </div>
        </b-card>
      </div>
   
    <b-card class="t-card animated" v-show="newOrderEntry" 
        border-variant="primary"
        header-bg-variant="primary"
        header-text-variant="white" >
      <div slot="header" >
      <h5>
        <span class="text-primary">
     <span style="color: white;">
       <i class="fa fa-shopping-cart" aria-hidden="true"></i>
          New Order </span>
        </span>
      </h5>
      </div>
      <b-row>
      
       <b-col sm="3">
         
          <date-picker
            id="date"
            format="MMMM/DD/YYYY"
            type="date"
            lang="en"
            input-class="form-control mx-input"
            v-model="forms.dashboard.fields.neworderdate"
            ref="date"
          ></date-picker>
        </b-col>
        <b-col lg="3"><span></span></b-col>
        <b-col lg="3"><span></span></b-col>
        <b-col lg="3">
          <b-form-input
            v-model="filters.sohr.criteria"
            type="text"
            placeholder="Search"
          ></b-form-input></b-col>
        </b-row>
        <br>
      <b-table
        style="overflow: hidden"
        responsive
        hover
        small
        bordered
        show-empty
        :fields="tables.sohr.fields"
        :items="NeworderFilter"
        :filter="filters.sohr.criteria"
        :total-rows="paginations.sohr.totalRows"
        :per-page="paginations.sohr.perPage"
        :current-page="paginations.sohr.currentPage"
        :tbody-tr-class="rowClass"
      >     
            <template #cell(order_name)="data">
              <b class="text-info"> {{data.item.order_name}}</b> 
              <!-- <b-badge pill variant="primary">{{data.item.order_name}}</b-badge> -->
            </template>

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
              <div class="row">
                  <b-col lg="4">
                    <b-form-group>
                      <label>Customer Name :</label>
                  <b-form-input readonly
                  style="background-color: white;"
                    v-model="row.item.fullname">
                  </b-form-input>
                    </b-form-group>
                    <b-form-group>
                      <label>Customer Address :</label>
                  <b-form-input readonly
                  style="background-color: white;"
                   v-model="row.item.region">
                  </b-form-input>
                    </b-form-group>
                      <b-form-group>
                      <label>Customer Address :</label>
                  <b-form-input readonly
                  style="background-color: white;"
                   v-model="row.item.city">
                  </b-form-input>
                    </b-form-group>
                    </b-col>
                  <b-col lg="4">
                    <h1 v-show="row.item.is_cancel == 1">&nbsp; &nbsp; &nbsp;CANCELLED</h1>
                    <b-form-group class="m-3" v-show="radiobutton" v-model="radiobtn">
                    <label>Select type of delivery</label>
                    <b-form-radio-group
                    v-model="forms.dashboard.fields.selected"
                    :options="optionsNew"
                    button-variant="outline-primary"
                    size="lg"
                    stacked
                  ></b-form-radio-group>
                    </b-form-group>
                  </b-col>
                  <b-col lg="4">
                    <br>
                  <b-form-group v-show="row.item.decline_intransit_remarks != null" >
                  <b-row>
                    <b-col>
                  <label>Cancelled by : <u>{{row.item.cancel_by}}</u></label><br>
                  <label>Cancellation Remarks :</label>
                  <b-form-textarea
                  style="background-color: white;"
                  v-model="row.item.decline_intransit_remarks"
                  readonly
                  >
                 </b-form-textarea>
                 </b-col>
                 </b-row>
                 </b-form-group>
                   <br>
                    <b-form-group style="text-align:center;" v-show="btnneworder">
                    <b-button  @click="AcceptNewOrders(row)" variant="success"><i class="fa fa-check-square-o" aria-hidden="true"></i> Accept</b-button>
                    <b-button  @click="DeclineNewOrders(row)" variant="danger"><i class="fa fa-times" aria-hidden="true"></i> Decline</b-button>
                    </b-form-group>
                  </b-col>
                </div>
                   <b-row>
                <b-col sm="12">
                <b-table
                    responsive
                    hover
                    small
                    bordered
                    show-empty
                    :fields="tables.soln.fields"
                    :items="solnfilter(row.item.sohr_hash)"
                  >
                </b-table>
                </b-col>
                </b-row>
                <b-modal v-model="showModalAcceptNewOrderDeliver" :noCloseOnEsc="true" :noCloseOnBackdrop="true" 
                header-bg-variant="primary"
                header-text-variant="light">
                <div slot="modal-title">Accept New Order? <small>For Drop-off</small></div>

                <label>Are you sure you want to Accept this Order?</label><br>
                <b-form  style="color:#2196F3;">
                <span>Reminder : </span><br>
                <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;You will be charged tranfer fee base on your customers location.</span><br><br></b-form>
                    <b-row>
                    <b-col lg="6">
                    <span for="selectdhTodeliver" class="p-0">Select Hub :</span>
                    <select2
                    :placeholder="'Select Hub'"
                    class="form-control"
                    style="padding:0px; font-size:14px;" 
                    id="selectdhTodeliver" 
                    ref="selectdhTodeliver" 
                    v-model="forms.dashboard.fields.selectdhTodeliver" 
                    >
                    <option
                      v-for="right in options.default.items"
                      :key="right.where_dh"
                      :value="right.where_dh"
                    >{{right.seller_name}}</option>
                    </select2>
                    </b-col>
                    <b-col lg="6">
                      <!-- <b-form-group v-show="forms.dashboard.fields.selectdhTodeliver != $store.state.user.default_dh"> -->
                      <b-form-group v-model="note" v-show="noteshow">
                      <h6 style="color:red;">Note :</h6>
                      <span style="color:red;">You will be charged 
                       <vue-autonumeric
                        v-model="forms.dashboard.fields.tf_shipping"
                        style="border: none; border-color: transparent; background-color: white; width:60px; text-align: center; color:blue;font-weight: bold;" 
                        disabled></vue-autonumeric>
                        pesos in your Income for  transfer fee.</span>
                      </b-form-group>
                      </b-col>
                    </b-row>
                <div slot="modal-footer">
                  <b-button
                    class="button"
                    :disabled="forms.dashboard.isSaving"
                    variant="primary"
                    @click="onAcceptingNewOrder"
                  >
                    <icon v-if="forms.dashboard.isSaving" name="sync" spin></icon>
                    <i class="fa fa-check"></i>
                    I will Accept
                  </b-button>
                  <b-button class="button" variant="secondary" @click="showModalAcceptNewOrderDeliver=false">Close</b-button>
                </div>
                 </b-modal>
                  <b-modal v-model="showModalAcceptNewOrderPickUp" :noCloseOnEsc="true" :noCloseOnBackdrop="true"
                  header-bg-variant="primary"
                  header-text-variant="light">
                  
                <div slot="modal-title">Accept New Order? <small>For Pick Up</small></div>
                  <b-row>
                  <b-col lg="12">
                  <p>Are you sure you want to Accept this Order?</p>
                  <b-form @keydown="resetFieldStates('dashboard')" autocomplete="off">
                  <b-form-group>
                  <label>Pick Up Date :</label>
                  <date-picker
                    id="to_pick_datetime"
                    format="MMMM/DD/YYYY"
                    type="date"
                    lang="en"
                    input-class="form-control mx-input"
                    v-model="forms.dashboard.fields.to_pick_datetime"
                    ref="to_pick_datetime"
                    :clearable="false"
                  ></date-picker>
                  </b-form-group>
                    <!-- <label>Merchant Address :</label>
                    <b-form-input 
                    disabled
                    v-model="tables.sumr.items.city_hash"
                    >
                    </b-form-input> -->
                    <b-form-group >
                    <label for="shipping_fee">Select Hub :</label>
                    <select2
                    @input="getShippingFee"
                    class="form-control"
                    style="padding:0px; font-size:14px;"
                    v-model="forms.dashboard.fields.pickupSelectDH" 
                    :placeholder="'Select Hub'">
                    <option
                      v-for="right in tables.dhsf.items"
                      :key="right.sumr_hash"
                      :value="right.sumr_hash"
                    >{{right.seller_name}}</option>
                    </select2>
                    </b-form-group>
                    
                    <b-form-group>
                    <!-- <label id="shipping_fee" ref="shipping_fee">Shipping Fee : {{formatNumber(forms.dashboard.fields.shipping_fee)}} </label> -->
                    <label for="shipping_fee">Shipping Fee : </label>
                    <vue-autonumeric
                    style="width:50%;background-color: white;"
                    readonly
                    id="shipping_fee"
                    ref="shipping_fee"
                    :value="forms.dashboard.fields.shipping_fee"
                    class='form-control text-right'
                    :options="{
                              minimumValue: '0',  
                              emptyInputBehavior:'0',}"
                  ></vue-autonumeric>   
                   </b-form-group>
                   
                   <b-form-group v-show="row.item.excess_fee != 0 && row.item.total_excess_kg != 0" >
                    <label for="total_excess_fee">Excess Fee : </label>
                    <vue-autonumeric
                    style="width:50%;background-color: white;"
                    readonly
                    id="total_excess_fee"
                    ref="total_excess_fee"
                    v-model="row.item.total_excess_fee"
                    class='form-control text-right'
                    :options="{
                              minimumValue: '0',  
                              emptyInputBehavior:'0',}"
                  ></vue-autonumeric>
                    <label for="total_excess_kg">Excess Kg. : </label>
                    <b-form-input
                    style="width:50%;background-color: white;"
                    readonly
                    id="total_excess_kg"
                    ref="total_excess_kg"
                    v-model="row.item.total_excess_kg"
                    class='form-control text-right'
                  ></b-form-input>
                  </b-form-group>
                  <b-form-group v-model="note1" v-show="noteshow1">
                    <label for="tranfer_fee">Tranfer Fee : </label>
                    <b-form-input
                    style="width:50%;background-color: white;"
                    readonly
                    id="tranfer_fee"
                    ref="tranfer_fee"
                    v-model="transfer_fee.transfer_fee"
                    class='form-control text-right'
                  ></b-form-input>
                      <h6 style="color:red;">Note :</h6>
                      <span style="color:red;">You will be charged <label>{{formatNumber(Number(transfer_fee.transfer_fee) + Number(row.item.total_excess_fee) + Number(forms.dashboard.fields.shipping_fee))}}</label> pesos in your Income for  transfer fee.</span>
                      </b-form-group>
                  </b-form>
                </b-col>
                </b-row>
                <div slot="modal-footer">
                  <b-button
                    class="button"
                    :disabled="forms.dashboard.isSaving"
                    variant="primary"
                    @click="onAcceptingNewOrder"
                  >
                    <icon v-if="forms.dashboard.isSaving" name="sync" spin></icon>
                    <i class="fa fa-check"></i>
                    I will Accept
                  </b-button>
                  <b-button class="button" variant="secondary" @click="showModalAcceptNewOrderPickUp = false , clearFields('dashboard')">Close</b-button>
                </div>
                 </b-modal>
                 <b-modal v-model="showModalDeclineNewOrder" :noCloseOnEsc="true" :noCloseOnBackdrop="true" @shown="focusElement('decline_neworder_remarks')"
                  header-bg-variant="primary"
                  header-text-variant="light"> 
                 <div slot="modal-title">Decline Order?</div>
                  <h6 style="text-align:center;">Reason for declining</h6>
                  <b-form-textarea
                  id="decline_neworder_remarks"
                  ref="decline_neworder_remarks"
                  placeholder="Enter something..."
                  rows="3"
                  max-rows="6"
                  v-model="forms.dashboard.fields.decline_neworder_remarks"
                  ></b-form-textarea>
                  <div slot="modal-footer">
                  <b-button
                    class="button"
                    :disabled="forms.dashboard.isSaving"
                    variant="primary"
                    @click="onDeclineNewOrder"
                  >
                    <icon v-if="forms.dashboard.isSaving" name="sync" spin></icon>
                    <i class="fa fa-check"></i>
                    Submit
                  </b-button>
                  <b-button class="button" variant="secondary" @click="showModalDeclineNewOrder=false" >Close</b-button>
                </div>
                 </b-modal>
          </template>
      </b-table>
            <b-col sm="12" class="my-1">
              <b-pagination
                size="sm"
                align="right"
                v-model="paginations.sohr.currentPage"
                :per-page="paginations.sohr.perPage"
                :total-rows="paginations.sohr.totalRows"
                class="my-0"
              ></b-pagination>
            </b-col>
    </b-card>
    <b-card class="t-card animated" v-show="IntransitEntry" 
        border-variant="success"
        header-bg-variant="success"
        header-text-variant="white" >
     
      <div slot="header" >
      <h5>
        <span class="text-primary">
     <span style="color: white;">
          <i class="fa fa-truck"></i>
          In - Transit
        </span>
        </span>
        
      </h5>
      </div>
       <b-row class="mb-2">
        <!-- row button and search input -->
        
        <b-col sm="3">
       <div>
            <select class="form-control" style="padding:0px; font-size:14px;" id="sort_trans" ref="sort_trans" v-model="sort_trans" >
                <option value="">All</option>
                <option value="0" >To DROP-OFF</option>
                <option value="1" >For Pick-UP</option>
                <option value="2" >Ready For Pick-UP</option>
                <option value="3" >For Dispatch</option>
            </select>
        </div>
        </b-col>
        <b-col sm="3">
          <date-picker
            v-show="datehide"
            id="date"
            format="MMMM/DD/YYYY"
            type="date"
            lang="en"
            input-class="form-control mx-input"
            v-model="forms.dashboard.fields.date"
            ref="date"
          ></date-picker>
        </b-col>
      </b-row>
      <b-table
        style="overflow: hidden;"
        responsive
        hover
        small
        bordered
        show-empty
        :fields="tables.sohr.fields"
        :items="InTransitFilter"
      >
        <template #cell(order_name)="data">
          <b class="text-success"> {{data.item.order_name}}</b> 
          <!--  <b-badge pill variant="success">{{data.item.order_name}}</b-badge> -->
        </template>
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
              <div class="row">
                  <b-col lg="4">
                    <b-form-group>
                      <label>Customer Name :</label>
                  <b-form-input
                    readonly 
                    style="background-color: white;"
                    v-model="row.item.fullname">
                  </b-form-input>
                    </b-form-group>
                    <b-form-group>
                      <label>Customer Address :</label>
                  <b-form-input 
                    readonly
                    style="background-color: white;"
                    v-model="row.item.city">
                  </b-form-input>
                    </b-form-group>
                    </b-col>
                  <b-col lg="4">
                    <b-form-group>
                    <label>Merchant Name :</label>
                    <b-form-input 
                    style="background-color: white;"
                    readonly
                    v-model="row.item.seller_name"
                    >
                    </b-form-input>
                    </b-form-group>
                    <b-form-group>
                   <label>Merchant Address :</label>
                  <b-form-input readonly
                  style="background-color: white;"
                   v-model="row.item.m_city">
                  </b-form-input>
                    </b-form-group>
                  </b-col>
                  <b-col lg="4">
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <b-form-group style="text-align:center;" v-model="transitbtn" v-show="transitbutton">
                    <b-button  @click="AcceptIntransit(row)" variant="success"><i class="fa fa-check-square-o" aria-hidden="true"></i> Accept</b-button>
                    <b-button  @click="DeclineIntransit(row)  " variant="danger"><i class="fa fa-times" aria-hidden="true"></i> Decline</b-button>
                    </b-form-group>
                  </b-col>
                </div>
                   <b-row>
                <b-col sm="12">
                <b-table
                    responsive
                    hover
                    small
                    bordered
                    show-empty
                    :fields="tables.soln.fields"
                    :items="solnfilter(row.item.sohr_hash)"
                  >
                </b-table  >
                </b-col>
                </b-row>
                <b-modal v-model="showModalAcceptIntransit" :noCloseOnEsc="true" :noCloseOnBackdrop="true"
                header-bg-variant="success"
                header-text-variant="light">
                <div slot="modal-title">Accept New Order?</div>
                <b-col lg="12">Are you sure you want to Accept this Order?</b-col>
                <div slot="modal-footer">
                  <b-button
                    class="button"
                    :disabled="forms.dashboard.isSaving"
                    variant="primary"
                    @click="onAcceptingIntransit"
                  >
                    <icon v-if="forms.dashboard.isSaving" name="sync" spin></icon>
                    <i class="fa fa-check"></i>
                    I will Accept
                  </b-button>
                  <b-button class="button" variant="secondary" @click="showModalAcceptIntransit=false">Close</b-button>
                </div>
                 </b-modal>

                <b-modal v-model="showModalAcceptIntransitPickup" :noCloseOnEsc="true" :noCloseOnBackdrop="true"
                header-bg-variant="success"
                header-text-variant="light">
                <div slot="modal-title">Accept New Order? (For Pick Up)</div>
                <b-col lg="12">Are you sure you want to Accept this Order?</b-col>
                <div slot="modal-footer">
                  <b-button
                    class="button"
                    :disabled="forms.dashboard.isSaving"
                    variant="primary"
                    @click="onAcceptingIntransitPickUp"
                  >
                    <icon v-if="forms.dashboard.isSaving" name="sync" spin></icon>
                    <i class="fa fa-check"></i>
                    I will Pick Up
                  </b-button>
                  <b-button class="button" variant="secondary" @click="showModalAcceptIntransitPickup=false">Close</b-button>
                </div>
                 </b-modal>

                <b-modal v-model="showModalAcceptIntransitDispatch" :noCloseOnEsc="true" :noCloseOnBackdrop="true"
                header-bg-variant="success"
                header-text-variant="light">
                <div slot="modal-title">Ready for dispatch?</div>
                <b-col lg="12">Are you sure this Order ready for dispatch?</b-col>
                <div slot="modal-footer">
                  <b-button
                    class="button"
                    :disabled="forms.dashboard.isSaving"
                    variant="primary"
                    @click="onAcceptingIntransitDispatch"
                  >
                    <icon v-if="forms.dashboard.isSaving" name="sync" spin></icon>
                    <i class="fa fa-check"></i>
                   Yes
                  </b-button>
                  <b-button class="button" variant="secondary" @click="showModalAcceptIntransitDispatch=false">No</b-button>
                </div>
                 </b-modal>
                <b-modal v-model="showModalreadytoDeliver" :noCloseOnEsc="true" :noCloseOnBackdrop="true"
                header-bg-variant="success"
                header-text-variant="light">
                <div slot="modal-title">Ready to Deliver?</div>
                <b-col lg="12">Are you sure this Order already dispatched and ready to Deliver?</b-col>
                <div slot="modal-footer">
                  <b-button
                    class="button"
                    :disabled="forms.dashboard.isSaving"
                    variant="primary"
                    @click="onAcceptingDeliver"
                  >
                    <icon v-if="forms.dashboard.isSaving" name="sync" spin></icon>
                    <i class="fa fa-check"></i>
                   Yes
                  </b-button>
                  <b-button class="button" variant="secondary" @click="showModalreadytoDeliver=false">No</b-button>
                </div>
                 </b-modal>
                 <b-modal v-model="showModalDeclineIntransit" :noCloseOnEsc="true" :noCloseOnBackdrop="true" @shown="focusElement('decline_neworder_remarks')"
                 header-bg-variant="success"
                 header-text-variant="light"> 
                 <div slot="modal-title">Decline Order?</div>
                 <b-col lg="12">
                  <h6>Reason for declining</h6></b-col>
                <b-form-group
                  tab="0"
                  :invalid-feedback="invalidFeedbackDetail"
                  :valid-feedback="ValidFeedbackDetail"
                  :state="DetailState">
                  <b-form-textarea
                  id="decline_intransit_remarks"
                  ref="decline_intransit_remarks"
                  placeholder="Enter something..."
                  minlength=20
                  maxlength=50
                  rows="3"
                  max-rows="6"
                  v-model="forms.dashboard.fields.decline_intransit_remarks"
                  ></b-form-textarea>
                </b-form-group>
                  <div slot="modal-footer">
                  <b-button
                    class="button"
                    :disabled="forms.dashboard.isSaving"
                    variant="primary"
                    @click="onDeclineIntrasit"
                  >
                    <icon v-if="forms.dashboard.isSaving" name="sync" spin></icon>
                    <i class="fa fa-check"></i>
                    Submit
                  </b-button>
                  <b-button class="button" variant="secondary" @click="showModalDeclineIntransit=false">Close</b-button>
                </div>
                 </b-modal>
          </template>
      </b-table>
    </b-card>
      <b-card class="t-card animated" v-show="DeliverEntry"
        border-variant="warning"
        header-bg-variant="warning"
        header-text-variant="white" >
      <div slot="header" >
      <h5>
        <span class="text-primary">
     <span style="color: white;">
          <i class="fa fa-arrow-circle-o-right"></i>
          Delivery
          <!-- <small class="font-italic"></small> -->
     </span>
        </span>
      </h5>
      </div>
      <b-row class="mb-2">
        <!-- row button and search input -->
        <b-col sm="3">
          <div>
            <select class="form-control" style="padding:0px; font-size:14px;" id="sort_deliver" ref="sort_deliver" v-model="sort_deliver">
                <option value="">All</option>
                <option value="2" >To Deliver</option>
                <option value="1" >Delivered</option>
            </select>
        </div>
        </b-col>
        <b-col sm="3">
        </b-col>
      </b-row>
        <b-table
        style="overflow: hidden"
        responsive
        hover
        small
        bordered
        show-empty
        :fields="tables.sohr.fields"
        :items="DeliveryFilter"
      >
        <template #cell(order_name)="data">
          <b class="text-warning"> {{data.item.order_name}}</b> 
          <!-- <b-badge pill variant="primary">{{data.item.order_name}}</b-badge> -->
        </template>
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
              <div class="row">
                  <b-col lg="4">
                    <b-form-group>
                      <label>Customer Name :</label>
                  <b-form-input readonly
                  style="background-color: white;"
                    v-model="row.item.fullname">
                  </b-form-input>
                    </b-form-group>
                    <b-form-group>
                      <label>Customer Address :</label>
                  <b-form-input readonly
                  style="background-color: white;"
                   v-model="row.item.city">
                  </b-form-input>
                    </b-form-group>
                    </b-col>
                  <b-col lg="4">
                     <span>
                     </span>
                  </b-col>
                  <b-col lg="4">
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <b-form-group style="text-align:center;" v-show="row.item.order_stat == 6 && $store.state.user.type == 1">
                    <b-button @click="Delivered(row)" variant="success">
                      <i class="fa fa-check-square-o" aria-hidden="true"></i> Is Delivered?</b-button>
                    </b-form-group>
                  </b-col>
                </div>
                   <b-row>
                <b-col sm="12">
                <b-table
                    responsive
                    hover
                    small
                    bordered
                    show-empty
                    :fields="tables.soln.fields"
                    :items="solnfilter(row.item.sohr_hash)"
                  >
                </b-table  >
                </b-col>
                </b-row>
                 <b-modal v-model="showModalDelivered" :noCloseOnEsc="true" :noCloseOnBackdrop="true"
                 header-bg-variant="warning"
                 header-text-variant="light"
                 hide-footer>
                <div slot="modal-title">Delivered?</div>

                <b-col lg="12"> <h6>Are you sure the Order has been delivered?</h6></b-col>
                <div>
                  <b-button class="mt-3" variant="outline-success" block @click="onDelivered" :disabled="forms.dashboard.isSaving">
                    <icon v-if="forms.dashboard.isSaving" name="sync" spin></icon>
                    <i class="fa fa-check"></i> Yes</b-button>
                  <b-button class="mt-2" variant="outline-danger" block @click="showModalDeliveredNo = true">No</b-button>
                </div>
                 </b-modal>
                  <b-modal v-model="showModalDeliveredNo" :noCloseOnEsc="true" :noCloseOnBackdrop="true"
                 header-bg-variant="warning"
                 header-text-variant="light"
                 centered>
                <div slot="modal-title">Why oh why?</div>

                <h6>Reason</h6>
                <b-form-textarea
                  placeholder="Enter something..."
                  rows="3"
                  max-rows="6"
                  ></b-form-textarea>
                <div slot="modal-footer">
                  <b-button
                    class="button"
                    :disabled="forms.dashboard.isSaving"
                    variant="primary"
                    @click="onDelivered"
                  >
                    <icon v-if="forms.dashboard.isSaving" name="sync" spin></icon>
                    <i class="fa fa-check"></i>
                    Submit
                  </b-button>
                  <b-button class="button" variant="secondary" @click="showModalDeliveredNo=false">Cancel</b-button>
                </div>
                 </b-modal>
          </template>
          </b-table>
    </b-card>
     <b-card class="t-card animated" v-show="CompletedEntry"
        border-variant="danger"
        header-bg-variant="danger"
        header-text-variant="white" >
      <div slot="header" >
      <h5>
        <span class="text-primary">
     <span style="color: white;">
          <i class="fa fa-check-square" aria-hidden="true"></i>
          Completed
          <!-- <small class="font-italic"></small> -->
        </span>
        </span>
      </h5>
      </div>
      <b-row class="mb-2">
        <!-- row button and search input -->
        <b-col sm="3">
        </b-col>
      </b-row>
        <b-table
        style="overflow: hidden"
        responsive
        hover
        small
        bordered
        show-empty
        :fields="tables.sohr.fields"
        :items="CompletedFilter(tables.sohr.items.sohr_hash)"
      > 
        <template #cell(order_name)="data">
          <b class="text-danger"> {{data.item.order_name}}</b> 
          <!-- <b-badge pill variant="primary">{{data.item.order_name}}</b-badge> -->
        </template>
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
          <template v-slot:row-details="data">
                  <b-form-group>
                      <label>Customer Name :</label>
                  <b-form-input readonly
                  style="background-color: white;"
                    v-model="data.item.fullname">
                  </b-form-input>
                    </b-form-group>
                    <b-form-group>
                      <label>Customer Address :</label>
                  <b-form-input readonly
                  style="background-color: white;"
                   v-model="data.item.user_address">
                  </b-form-input>
                    </b-form-group>
                <b-table
                    responsive
                    hover
                    small
                    bordered
                    show-empty
                    :fields="tables.soln.fields"
                    :items="solnfilter(data.item.sohr_hash)">
                </b-table>
          </template>
          </b-table>
    </b-card>
    </div>
    </div>
      <!--/.col-->
  </div>
</template>

<script>
import CardLine1ChartExample from "./dashboard/CardLine1ChartExample";
import CardLine2ChartExample from "./dashboard/CardLine2ChartExample";
import CardLine3ChartExample from "./dashboard/CardLine3ChartExample";
import CardBarChartExample from "./dashboard/CardBarChartExample";
import MainChartExample from "./dashboard/MainChartExample";
import SocialBoxChartExample from "./dashboard/SocialBoxChartExample";
import { Callout } from "../components/";

export default {
  name: "dashboard",
  components: {
    Callout,
    CardLine1ChartExample,
    CardLine2ChartExample,
    CardLine3ChartExample,
    CardBarChartExample,
    MainChartExample,
    SocialBoxChartExample
  },
  data: function() {
    return { 
      noteshow1: false,
      noteshow: false,
      tf_fee: false,
      errors: null,
      datehide: false,
      newOrderEntry: true,
      IntransitEntry: false,
      radiobutton: false,
      transitbutton: false,
      deliveredbutton: false,
      btnneworder: false,
      DeliverEntry: false,
      CompletedEntry: false,
      sort_trans: "",
      sort_deliver: "",
      showModalAcceptNewOrderPickUp: false,
      showModalAcceptNewOrderDeliver: false,
      showModalDeclineNewOrder: false,
      showModalAcceptIntransit: false,
      showModalAcceptIntransitPickup: false,
      showModalDeclineIntransit: false,
      showModalDelivered: false,
      remarkshow: false,
      showModalAcceptIntransitDispatch: false,
      showModalreadytoDeliver: false,
      showModalDeliveredNo: false,
      optionsNew: [
          { text: 'DROP-OFF', value: 2 },
          { text: 'PICK-UP', value: 3 },
        ],
      // optionsPickup: [
      //     { text: 'Distribution Hub 1 (Dolores, CSFP)', value: 2},
      //     { text: 'Distribution Hub 2 (Angeles City)', value: 1 },
      //   ],
      forms: {
        dashboard: {
          isSaving: false,
          isDeleting: false,
          fields: {
            transfer_fee: 0,
            selectdhdh: 1,
            selectdhTodeliver: null,
            selected: 2,
            selected1: null,
            seller_address: null,
            decline_neworder_remarks: null,
            decline_intransit_remarks: '',
            to_pick_datetime: new Date(),
            date: null,
            neworderdate: null,
            total_fee: 0,
            shipping_fee: 0,
            total_cost: 0,
            pickupSelectDH: null,
          }
        }
      },
      tables: {
        sohr: {
          fields: [
            {
              key: "order_no",
              label: "Order No.",
              thStyle: { width: "80px" },
              tdClass: "align-middle",
              sortable: true
            },
            {
              key: "order_date",
              label: "Order Date",
              tdClass: "align-middle",
              thStyle: { width: "80px" },
              sortable: true,
              formatter: value => {
                return this.moment(value, "MMMM DD, YYYY");
              }
            },
            {
              label: "Quantity",
              thStyle: { width: "80px" },
              tdClass: "align-middle",
              sortable: true
            }, 
            {
              key: "order_name",
              label: "Status",
              thStyle: { width: "80px" },
              thClass: "text-center",
              tdClass: "align-middle text-center",
            },
            {
              key: "order_subtotal",
              label: "Order Subtotal",
              thClass: "text-right",
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
              thStyle: { width: "120px" },
              tdClass: "text-center"
            }
          ],
          items: []
        },
         soln: {
          fields: [
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
            thStyle: { width: "80px" },
            sortable: true,
            formatter: (value) => {
                    return this.formatNumber(value)
                }
            },
            {
            key: "order_total",
            label: "Total Price",
            thStyle: { width: "100px" },
            tdClass: "align-middle text-right",
            formatter: (value, key, item) => {
                item.total_cost = item.unit_price * item.qty;
                return this.formatNumber(item.total_cost);
              }  
            },
          ],
          items: []
        },
          dhsf: {
          items: []
        },
          sumr: {
          items: []
        },
         ship: {
          items: []
        },
         shipping: {
          items: []
        },
      },
   options: {
        default: {
          items: []
        },
     },
      filters: {
          sohr: {
            criteria: null
          }
        },
        paginations: {
          sohr: {
            totalRows: 0,
            currentPage: 1,
            perPage: 20
          }
        },
    }
  },
  methods: {
    getShippingFee: function(value,data)  {
      if (data.length > 0) {
        var sf = this.tables.dhsf.items[data[0].element.index]
        this.forms.dashboard.fields.shipping_fee = sf.shipping_fee
      }
    },

    rowClass(item, type) {
          if (!item || type !== "row") return;
          if (item.is_cancel == 1) return "table-danger"
          },
    onDeclineNewOrder() {
      if (this.forms.dashboard.fields.decline_neworder_remarks !== null && this.forms.dashboard.fields.decline_neworder_remarks.length == 0) {
          this.focusElement('decline_neworder_remarks')
          this.$notify({
          type: 'error',
          group: 'notification',
          title: 'Error!',
          text: 'Please Enter Reason'
        })
       }else if (this.forms.dashboard.fields.decline_neworder_remarks !== null && this.forms.dashboard.fields.decline_neworder_remarks.length <= 19) {
          this.focusElement('decline_neworder_remarks')
          this.$notify({
          type: 'error',
          group: 'notification',
          title: 'Error!',
          text: 'Please Enter Reason at least 20 Characters'
        })

       }else{

      this.forms.dashboard.isSaving = true;
      this.$http
        .put(
          "api/declineneworder/" + this.sohr_hash,
          this.forms.dashboard.fields,
          {
            headers: {
              Authorization: "Bearer " + localStorage.getItem("token")
            }
          }
        )
        .then(response => {
          this.forms.dashboard.isSaving = false;
          this.$notify({
            type: "success",
            group: "notification",
            title: "Success!",
            text: "The New Order has been Declined."
          });

          const index = this.tables.sohr.items.findIndex(
            item => item["sohr_hash"] === response.data.data["sohr_hash"]
          );

          this.$delete(this.tables.sohr.items, index);
          // this.paginations.queuereports.totalRows--;

          this.showModalDeclineNewOrder = false;
        })
        .catch(error => {
          this.forms.dashboard.isSaving = false;
          if (!error.response) return;
          const errors = error.response.data.errors;
          console.log(errors);
        });
       }
    },
    onAcceptingNewOrder() {
      if (this.forms.dashboard.fields.selected === 2){
        if (this.forms.dashboard.fields.selectdhTodeliver == null) {
         this.$notify({
            type: "error",
            group: "notification",
            title: "Error!",
            text: "Please select Distribution Hub."
          });
      }else{
      this.forms.dashboard.isSaving = true;
      this.$http
        .put(
          "api/acceptneworder/" + this.sohr_hash,
          this.forms.dashboard.fields,
          {
            headers: {
              Authorization: "Bearer " + localStorage.getItem("token")
            }
          }
        )
        .then(response => {
          this.forms.dashboard.isSaving = false;
          this.$notify({
            type: "success",
            group: "notification",
            title: "Success!",
            text: "The New Order has been Accepted."
          });
          const index = this.tables.sohr.items.findIndex(
            item => item["sohr_hash"] === response.data.data["sohr_hash"]
          );

          this.$delete(this.tables.sohr.items, index);
          // this.paginations.queuereports.totalRows--;

          this.showModalAcceptNewOrderDeliver = false;
          this.showModalAcceptNewOrderPickUp = false;
          window.location.reload();
        })
        .catch(error => {
          this.forms.dashboard.isSaving = false;
          if (!error.response) return;
          const errors = error.response.data.errors;
          this.$notify({
            type: "error",
            group: "notification",
            title: "Error!",
            text: "The Order has been already cancelled."
          });
          console.log(errors);
        });
      }
      }else{
        if (this.forms.dashboard.fields.shipping_fee == 0) {
         this.$notify({
            type: "error",
            group: "notification",
            title: "Error!",
            text: "Please select Distribution Hub."
          });
        }else{
        this.forms.dashboard.isSaving = true;
        this.$http
          .put(
            "api/acceptneworder/" + this.sohr_hash,
            this.forms.dashboard.fields,
            {
              headers: {
                Authorization: "Bearer " + localStorage.getItem("token")
              }
            }
          )
          .then(response => {
            this.forms.dashboard.isSaving = false;
            this.$notify({
              type: "success",
              group: "notification",
              title: "Success!",
              text: "The New Order has been Accepted."
            });
            const index = this.tables.sohr.items.findIndex(
              item => item["sohr_hash"] === response.data.data["sohr_hash"]
            );

            this.$delete(this.tables.sohr.items, index);
            // this.paginations.queuereports.totalRows--;

            this.showModalAcceptNewOrderDeliver = false;
            this.showModalAcceptNewOrderPickUp = false;
            window.location.reload();
          })
        .catch(error => {
          this.forms.dashboard.isSaving = false;
          if (!error.response) return;
          const errors = error.response.data.errors;
          this.$notify({
            type: "error",
            group: "notification",
            title: "Error!",
            text: "The Order has been already cancelled."
          });
          console.log(errors);
        });
      }
      }
        
    },
    
    onAcceptingIntransit() {
    
          this.forms.dashboard.isSaving = true
          this.resetFieldStates('dashboard');
          this.$http.put("api/acceptdropoff/" + this.row.sohr_hash,
          this.forms.dashboard.fields,
          {
            headers: {
                      Authorization: 'Bearer ' + localStorage.getItem('token')
                  }
            })
          .then(response => {
          this.forms.dashboard.isSaving = false;
          this.$notify({
            type: "success",
            group: "notification",
            title: "Success!",
            text: "The Order has been Accepted."
          });
          
          this.showModalAcceptIntransit = false
          window.location.reload();

          })
          // this.refresh()
    },
    //   refresh() {
    //         this.$http.post('/api/refresh', this.tables.sohr, {
    //             headers: {
    //                   Authorization: 'Bearer ' + localStorage.getItem('token'),
    //               }
    //         })
    //         .then((response) => {
    //             return response
    //         })
    //         .catch(error => {
    //           if (!error.response) return
    //           console.log(error)
    //         })
    // },
     onAcceptingIntransitPickUp() {
          this.forms.dashboard.isSaving = true
          this.$http.put("api/acceptintransitpickup/" + this.row.sohr_hash,
          this.forms.dashboard.fields,
          {
            headers: {
                      Authorization: 'Bearer ' + localStorage.getItem('token')
                  }
            })
          .then(response => {
          this.forms.dashboard.isSaving = false;
          this.$notify({
            type: "success",
            group: "notification",
            title: "Success!",
            text: "The New Order has been Accepted."
           
          });
          // for (var key in response.data.data) {
          //   row[key] = response.data.data[key];
          // }
          //     const index = this.tables.sohr.items.findIndex(
          //   item => item["sohr_hash"] === response.data.data["sohr_hash"]
          // );
            this.showModalAcceptIntransitPickup = false
            window.location.reload();
            })
    },
    onAcceptingIntransitDispatch() {
          this.forms.dashboard.isSaving = true
          this.resetFieldStates('dashboard');
          this.$http.put("api/readytodeliver/" + this.row.sohr_hash,
          this.forms.dashboard.fields,
          {
            headers: {
                      Authorization: 'Bearer ' + localStorage.getItem('token')
                  }
            })
          .then(response => {
          this.forms.dashboard.isSaving = false;
          this.$notify({
            type: "success",
            group: "notification",
            title: "Success!",
            text: "The Order has been Ready to Deliver."
            });
            
            this.showModalAcceptIntransitDispatch = false
            window.location.reload();
            })
            
    },
    onAcceptingDeliver() {
       
      this.forms.dashboard.isSaving = true;
      this.$http
        .put(
          "api/todeliver/" + this.sohr_hash,
          this.forms.dashboard.fields,
          {
            headers: {
              Authorization: "Bearer " + localStorage.getItem("token")
            }
          }
        )
        .then(response => {
          this.forms.dashboard.isSaving = false;
          this.$notify({
            type: "success",
            group: "notification",
            title: "Success!",
            text: "The Order is ready to Delivered."
          });

          const index = this.tables.sohr.items.findIndex(
            item => item["sohr_hash"] === response.data.data["sohr_hash"]
          );

          this.$delete(this.tables.sohr.items, index);
          // this.paginations.queuereports.totalRows--;

          this.showModalreadytoDeliver = false;
          window.location.reload();
        })
        .catch(error => {
          this.forms.dashboard.isSaving = false;
          if (!error.response) return;
          const errors = error.response.data.errors;
          console.log(errors);
        });
    },
    onDeclineIntrasit() {
       if (this.forms.dashboard.fields.decline_intransit_remarks !== null && this.forms.dashboard.fields.decline_intransit_remarks.length == 0) {
          this.$notify({
          type: 'error',
          group: 'notification',
          title: 'Error!',
          text: 'Please Enter Reason'
        })
       }else if (this.forms.dashboard.fields.decline_intransit_remarks !== null && this.forms.dashboard.fields.decline_intransit_remarks.length <= 19) {
          this.$notify({
          type: 'error',
          group: 'notification',
          title: 'Error!',
          text: 'Please Enter Reason at least 20 Characters'
        })

       }else{
      this.forms.dashboard.isSaving = true;
      this.$http
        .put(
          "api/declineintransit/delete/" + this.sohr_hash,
          this.forms.dashboard.fields,
          {
            headers: {
              Authorization: "Bearer " + localStorage.getItem("token")
            }
          }
        )
        .then(response => {
          this.forms.dashboard.isSaving = false;
          this.$notify({
            type: "success",
            group: "notification",
            title: "Success!",
            text: "The New Order has been Declined."
          });

          const index = this.tables.sohr.items.findIndex(
            item => item["sohr_hash"] === response.data.data["sohr_hash"]
          );

          this.$delete(this.tables.sohr.items, index);
          // this.paginations.queuereports.totalRows--;

          this.showModalDeclineIntransit = false;
          window.location.reload();
        })
        .catch(error => {
          this.forms.dashboard.isSaving = false;
          if (!error.response) return;
          const errors = error.response.data.errors;
          console.log(errors);
        });
       }
    },
    onDelivered() {
      this.forms.dashboard.isSaving = true;
      this.$http
        .put(
          "api/delivered/delete/" + this.sohr_hash,
          this.forms.dashboard.fields,
          {
            headers: {
              Authorization: "Bearer " + localStorage.getItem("token")
            }
          }
        )
        .then(response => {
          this.forms.dashboard.isSaving = false;
          this.$notify({
            type: "success",
            group: "notification",
            title: "Success!",
            text: "The Order has been Delivered."
          });

          const index = this.tables.sohr.items.findIndex(
            item => item["sohr_hash"] === response.data.data["sohr_hash"]
          );

          this.$delete(this.tables.sohr.items, index);
          // this.paginations.queuereports.totalRows--;

          this.showModalDelivered = false;
          window.location.reload();
        })
        .catch(error => {
          this.forms.dashboard.isSaving = false;
          if (!error.response) return;
          const errors = error.response.data.errors;
          console.log(errors);
        });
    },
     async AcceptNewOrders(row) {
      this.sohr_hash = row.item.sohr_hash;
      if (this.forms.dashboard.fields.selected == 3) {
        this.showModalAcceptNewOrderPickUp = true;
      }else{
        this.showModalAcceptNewOrderDeliver = true;
        this.forms.dashboard.fields.tf_shipping = Number(this.transfer_fee.transfer_fee) + Number(row.item.total_excess_fee)

      }
    },

    
    AcceptIntransit(row) {
      if (row.item.order_stat == 2) {
        
        this.row = row.item
        this.resetFieldStates('dashboard')
        this.fillEntityForm('show', this.row.sohr_hash)
        this.showModalAcceptIntransit=true
      }else if (row.item.order_stat == 3){
        this.row = row.item
        this.resetFieldStates('dashboard')
        this.fillEntityForm('show', this.row.sohr_hash)
        this.showModalAcceptIntransitPickup=true
      }else if (row.item.order_stat == 4){
        this.row = row.item
        this.resetFieldStates('dashboard')
        this.fillEntityForm('show', this.row.sohr_hash)
        this.showModalAcceptIntransit=true
      }else if (row.item.order_stat == 5){
        this.setDelete(row)
      }
     
    },
    async setDelete(row){
            this.sohr_hash = row.item.sohr_hash
            this.showModalreadytoDeliver = true
        },
    async DeclineNewOrders(row) {
      this.forms.dashboard.fields.decline_neworder_remarks = '';
      this.sohr_hash = row.item.sohr_hash;
      this.showModalDeclineNewOrder = true;
    },
    async DeclineIntransit(row) {
      this.forms.dashboard.fields.decline_intransit_remarks = '';
      this.sohr_hash = row.item.sohr_hash;
      this.showModalDeclineIntransit = true;
    },
     async Delivered(row) {
      this.sohr_hash = row.item.sohr_hash;
      this.showModalDelivered = true;
    
    },
    CompletedFilter(sohr_hash){
        return this.tables.sohr.items.filter(r => r.order_stat == 7 && r.status_user == "COMPLETED");
    },
    solnfilter(sohr_hash) {
      return this.tables.soln.items.filter(r => r.sohr_hash == sohr_hash);
    },

    LoadTable() {
      this.$http
      .get("api/sohr", {
        headers: {
          Authorization: "Bearer " + localStorage.getItem("token")
        }
      })
      .then(response => {
        
        this.transfer_fee = response.data.comr[0];
        this.tables.sohr.items = response.data.sohr;
        this.tables.soln.items = response.data.soln;
        this.tables.dhsf.items = response.data.dhsf;
        this.tables.ship.items = response.data.ship;
        this.tables.sumr.items = response.data.sumr;
        this.paginations.sohr.totalRows = response.data.sohr.length;
        this.options.default.items = response.data.default;
      })
      .catch(error => {
        console.log(error);
      });
    },

    getMerchantProducts: function(value, data) {
      if (data.length > 0) {
        this.options.filteredparts.items = this.options.parts.items.filter(
          p => p.department_id == value
        );
      }
    },
  
  },
  computed: {

    note() {
    if (this.forms.dashboard.fields.selectdhTodeliver == null ) {
       this.noteshow = false
    }
    else if (this.forms.dashboard.fields.selectdhTodeliver != this.$store.state.user.default_dh) {
       this.noteshow = true
     }else{
       this.noteshow = false
     }
   },

    note1() {
    if (this.forms.dashboard.fields.pickupSelectDH == null ) {
       this.noteshow1 = false
    }
    else if (this.forms.dashboard.fields.pickupSelectDH != this.$store.state.user.default_dh) {
       this.noteshow1 = true
    }else{
       this.noteshow1 = false
     }
   },
      
     invalidFeedbackDetail() {
      if (this.forms.dashboard.fields.decline_intransit_remarks !== null && this.forms.dashboard.fields.decline_intransit_remarks.length == 0) {
        return 'Please enter something.'
        } if (this.forms.dashboard.fields.decline_intransit_remarks !== null && this.forms.dashboard.fields.decline_intransit_remarks.length <= 19) {
          return 'Enter at least 20 characters.'
          }else{
            return
        }
      },
      ValidFeedbackDetail() {
       if (this.forms.dashboard.fields.decline_intransit_remarks !== null && this.forms.dashboard.fields.decline_intransit_remarks.length >= 20) {
          return 'Maximum of 50 characters.'
        }
      },
      DetailState() {
      return this.forms.dashboard.fields.decline_intransit_remarks !== null && this.forms.dashboard.fields.decline_intransit_remarks.length >= 20
      },

 
    GetTotalFee(){
        this.forms.dashboard.fields.total_fee = 0;
        this.forms.dashboard.fields.total_fee += ( Number(this.forms.dashboard.fields.shipping_fee) + Number(this.forms.dashboard.fields.transfer_fee));
        return this.forms.dashboard.fields.total_fee;
      },
    NeworderFilter(){
      if (this.forms.dashboard.fields.neworderdate != null){
         return this.tables.sohr.items.filter(r => r.order_stat == 1  && 
           this.moment(r.order_date, "YYYY-MM-DD") ==
            this.moment(this.forms.dashboard.fields.neworderdate, "YYYY-MM-DD"));
      }else{
            return this.tables.sohr.items.filter(r => r.order_stat == 1)
          }
    },
       InTransitFilter() {
       if (this.sort_trans == "0") {
          this.datehide = true
           if (this.forms.dashboard.fields.date != null){
           return this.tables.sohr.items.filter(r => r.order_stat == 2  && 
           this.moment(r.order_date, "YYYY-MM-DD") ==
            this.moment(this.forms.dashboard.fields.date, "YYYY-MM-DD"));
          }else{
            return this.tables.sohr.items.filter(r => r.order_stat == 2)
          }
      }else if (this.sort_trans == "1"){ 
        this.datehide = true
                if (this.forms.dashboard.fields.date != null) {
                  return this.tables.sohr.items.filter(r => r.order_stat == 3 &&
                  this.moment(r.order_date, "YYYY-MM-DD") ==
                  this.moment(this.forms.dashboard.fields.date, "YYYY-MM-DD"));
                }else{
                  return this.tables.sohr.items.filter(r => r.order_stat == 3)
                }
      }else if (this.sort_trans == "2"){
        
                this.datehide = true
                if (this.forms.dashboard.fields.date != null) {
                  return this.tables.sohr.items.filter(r => r.order_stat == 4 &&
                  this.moment(r.order_date, "YYYY-MM-DD") ==
                  this.moment(this.forms.dashboard.fields.date, "YYYY-MM-DD"));
                }else{
                  return this.tables.sohr.items.filter(r => r.order_stat == 4)
                }
      }else if (this.sort_trans == "3"){
                  this.datehide = true
                  if (this.forms.dashboard.fields.date != null) {
                  return this.tables.sohr.items.filter(r => r.order_stat == 5 &&
                  this.moment(r.order_date, "YYYY-MM-DD") ==
                  this.moment(this.forms.dashboard.fields.date, "YYYY-MM-DD"));
                }else{
                  return this.tables.sohr.items.filter(r => r.order_stat == 5)
                }

      }else{
              this.datehide = false
              return this.tables.sohr.items.filter(r => r.order_stat == 2 || r.order_stat == 3 || r.order_stat == 4  || r.order_stat == 5)
              
            }
    },
      DeliveryFilter() {
         this.tables.sohr.items.sohr_hash
         if (this.sort_deliver == "1") {
            return this.tables.sohr.items.filter(r => r.order_stat == 7);
         }else if (this.sort_deliver =="2"){
            return this.tables.sohr.items.filter(r => r.order_stat == 6);
         }else{
           return this.tables.sohr.items.filter(r => r.order_stat == 6 || r.order_stat == 7);
         }
    },
      radiobtn() {
        if (this.$store.state.user.type == 0) {
            this.radiobutton = true
            this.btnneworder = true
        }
    },
      transitbtn() {
        if (this.$store.state.user.type == 1) {
            this.transitbutton = true
        }
    },
        deliveredbtn() {
        if (this.$store.state.user.type == 1) {
            this.deliveredbutton = true
        }
        },
  },
  created() {
      this.LoadTable();
      // setInterval(() =>  this.LoadTable(), 3000)
  },

};
</script>
