<template>
    <!--<b-animated fade-in>  main container -->
    <div class="container">
        <div v-if="$store.state.user.type != 0">
        <not-found></not-found>
        </div>
        <notifications group="notification" />
        <div class="animated fadeIn" v-show="$store.state.user.type == 1 || $store.state.user.type == 0">
            <b-row>
                <b-col sm="12">
                    <b-card >
                        <h5 slot="header">
                            <span class="text-primary">
                                <i class="fa fa-comments"></i> 
                                Comment(s)
                                <!-- <small class="font-italic">List of all registered usergroup groups.</small> -->
                                </span>
                        </h5>
                        <b-row>
                        <b-col sm="12">
                        <b-table
                            responsive
                            striped
                            hover
                            small
                            bordered
                            show-empty
                       
                            :fields="tables.comments.fields"
                            :items.sync="tables.comments.items"
                        >
                       
                        <template v-slot:cell(action)="data">
                            <div v-show="data.item.answer == null">
                                <b-btn
                                    :size="'sm'"
                                    variant="primary"
                                    @click="setComment(data)"
                                >
                                    <i class="fa fa-share"></i>
                                </b-btn>
                            </div>
                        </template>
                        </b-table>
                         <b-modal 
                            v-model="showModalComments"
                            :noCloseOnEsc="true"
                            :noCloseOnBackdrop="true"
                            @show="focusElement('answer')"
                        >
                            <!-- <div slot="modal-title"> 
                            Card type Entry - {{entryMode}}
                        </div> -->
                           <b-col lg=12> <!-- modal body -->
                        <b-form autocomplete="off">
                            <b-form-group>
                                <label>Comment</label>
                                <b-form-input
                                    ref="comment"
                                    id="comment"
                                    v-model="forms.comments.fields.comment"
                                    type="text"
                                    readonly
                                    placeholder="Customer Comment">
                                </b-form-input>
                            </b-form-group>
                            <b-form-group>
                                <label for="answer"><i class="icon-required fa fa-exclamation-circle"></i> Response</label>
                                <b-form-input
                                    id="answer"
                                    v-model="forms.comments.fields.answer"
                                    type="text"
                                    placeholder="Your Response"
                                    ref="answer">
                                </b-form-input>
                            </b-form-group>
                        </b-form>
                    </b-col> <!-- modal body -->

            <div slot="modal-footer"><!-- modal footer buttons -->
                <b-button :disabled="forms.comments.isSaving" variant="primary" @click="onComment">
                    <icon v-if="forms.comments.isSaving" name="sync" spin></icon>
                    <i class="fa fa-check"></i>
                    Save
                </b-button>
                <b-button variant="secondary" @click="showModalComments=false">Close</b-button>
            </div>
                         </b-modal>
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
    name: 'comments',
    data() {
        return { 
            showModalComments: false,
            isSaving: false,

            forms: {
                comments: { 
                    fields: {
                        answer: null,
                        comment: null,
                    }
                }
            },
            tables: {
                comments: { 
                    fields: [
                        {
                            key: "cmnt_hash",
                            label: "No.",
                            tdClass: "align-middle",
                            thStyle: { width: "20px" },
                            sortable: true
                        },
                        {
                            key: "product_name",
                            label: "Product Name",
                            tdClass: "align-middle",
                            thStyle: { width: "80px" },
                            sortable: true
                        },
                        {
                            key: "product_name",
                            label: "Product Name",
                            tdClass: "align-middle",
                            thStyle: { width: "80px" },
                            sortable: true
                        },
                        {
                            key: "fullname",
                            label: "Costumer Name",
                            tdClass: "align-middle",
                            thStyle: { width: "80px" },
                            sortable: true
                        },
                        {
                            key: "comment",
                            label: "Comment",
                            tdClass: "align-middle",
                            thStyle: { width: "80px" },
                            sortable: true
                        },
                        {
                            key: "created_datetime",
                            label: "Date/Time",
                            tdClass: "align-middle",
                            thStyle: { width: "80px" },
                            sortable: true
                        },
                        {
                            key: "answer",
                            label: "Response",
                            tdClass: "align-middle",
                            thStyle: { width: "80px" },
                            sortable: true
                        },
                        {
                            key: "action",
                            thStyle: { width: "50px" },
                            tdClass: "text-center"
                        },
                    ],
                    
                    items: []
                },
                

            }
        }
    },
    methods: {
    onComment() {
            // this.updateEntity('comments', 'cmnt_hash', true, this.row)
      
      this.forms.comments.isSaving = true;
      this.resetFieldStates('comments');
      this.$http
        .put(
          "api/comments/" + this.row.cmnt_hash,
          this.forms.comments.fields,
          {
            headers: {
              Authorization: "Bearer " + localStorage.getItem("token")
            }
          }
        )
        .then(response => {
          this.forms.comments.isSaving = false;
          this.$notify({
            type: "success",
            group: "notification",
            title: "Success!",
            text: "The Response has been successfully submitted."
          });

            this.showModalComments = false;
            setTimeout(function(){ window.location.reload(); }, 1000);
        })
        .catch(error => {
          this.forms.comments.isSaving = false;
          if (!error.response) return;
          const errors = error.response.data.errors;
          var a = 0;
          for (var key in errors) {
            // this.forms[entity].states[key] = false
            // this.forms[entity].errors[key] =  errors[key]
            if (a == 0) {
              this.focusElement(key, false);
              this.$notify({
                type: "error",
                group: "notification",
                title: "Error!",
                text: errors[key][0]
              });
            }
          }
          // this.forms[entity].isSaving = false
          // if (!error.response) return
          // const errors = error.response.data.errors
          // console.log(errors)
        });
        },
        setComment(data) {
        this.row = data.item;
        this.resetFieldStates("comments");
        this.fillEntityForm("comments", data.item.cmnt_hash);
        this.showModalComments = true;
        this.entryMode = "Edit";
        },
        loadComment() {
         this.$http
        .get("api/comments", {
            headers: {
            Authorization: "Bearer " + localStorage.getItem("token")
            }
        })
        .then(response => {
            this.tables.comments.items = response.data.comments;
            // this.options.sumr.items = response.data.sumr;
            // this.tables.sohr.items = response.data.sohr;
            // this.azspree = response.data.comr[0];
            // this.paginations.sohr.totalRows = response.data.sohr.length;
        })
        .catch(error => {
            console.log(error);
        });
        
        },
    },
    created() {
      this.loadComment();
   
  },
}
</script>