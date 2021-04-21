<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">
            Cards For List: {{listData.title}}
            <b-button
              v-b-modal.modal-create-update-card
              size="sm"
              variant="primary"
              class="mb-2 float-right"
            >
              <b-icon icon="plus" aria-label="Help"></b-icon>
            </b-button>
          </div>

          <div class="card-body">
            <div v-if="cardData.data && cardData.data.length">
              <b-table striped hover :items="cardData.data" :fields="fields">
                <template #cell(index)="data">
                    {{ data.index + 1 }}
                </template>
                <template #cell(action)="data">
                    <a href="javascript:void(0)" @click="editCard(data.item)">Edit</a>
                    <a href="javascript:void(0)" @click="deleteCard(data.item.id)">Delete</a>
                </template>
              </b-table>
            </div>
            <pagination
              :data="cardData"
              @pagination-change-page="getResults"
            ></pagination>
          </div>
        </div>
      </div>
    </div>
    <b-modal
  id="modal-create-update-card"
  ref="modal"
  title="Create/Update Card"
  @show="resetModal"
  @hidden="resetModal"
  @ok="handleOk"
  :ok-title="action">
      <ValidationObserver
        ref="observer"
        tag="form"
        @submit.prevent="createUpdateCard()">
        <b-form-group label="Title" label-for="title-input">
          <ValidationProvider
            vid="title"
            rules="required"
            v-slot="{ errors }"
            name="Title"
            tag="div">
            <b-form-input id="title-input" v-model="title"></b-form-input>
            <span class="text-danger">{{ errors[0] }}</span>
          </ValidationProvider>
        </b-form-group>
        <b-form-group label="Description" label-for="description-input">
          <ValidationProvider
            vid="description"
            rules="required"
            v-slot="{ errors }"
            name="description"
            tag="div">
        <b-form-textarea
        id="description-input"
        size="sm"
        v-model="description">

        </b-form-textarea>
        <span class="text-danger">{{ errors[0] }}</span>
      </ValidationProvider>
        </b-form-group>
        <b-form-group label="Completed?" v-slot="{ ariaDescribedby }">
          <ValidationProvider
            vid="checked"
            rules="required"
            v-slot="{ errors }"
            name="checked"
            tag="div">
           <b-form-radio v-model="completed" :aria-describedby="ariaDescribedby" name="completed" value="1">Yes</b-form-radio>
           <b-form-radio v-model="completed" :aria-describedby="ariaDescribedby" name="completed" value="0">No</b-form-radio> 
            <span class="text-danger">{{ errors[0] }}</span>
          </ValidationProvider>
        </b-form-group>
      </ValidationObserver>
    </b-modal>
  </div>
</template>
<script>
export default {
    mounted() {
        console.log('Card Component mounted.')
    },
    props: ['listInfo'],
    data() {
        return {
            listData:this.listInfo,
            list_id:this.listInfo.id,
            card_id:'',
            title:'',
            description:'',
            completed:'',
            action:'Save',
            cardData: {},
            fields: ['index','title', 'description','completed','action']
        }
    },
    created() {
        axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
        this.getResults();
    },
    methods: {
       getResults(page) {
            if (typeof page === 'undefined') {
                page = 1;
            }
            axios.get('/cards?page=' + page+'&list_id='+ this.listData.id)
                .then(response => {
                    this.cardData = response.data;
                });
       },
       resetModal() {
        this.title='';
        this.description='';
        this.completed='';
        this.action='Save';
       },
       async handleOk(bvModalEvt) {
          bvModalEvt.preventDefault();
          const valid = await this.$refs.observer.validate();
          if(valid){
             this.createUpdateCard();
          }
       },
       createUpdateCard(){
          axios.defaults.headers.common = {
           'X-Requested-With': 'XMLHttpRequest',
           'X-CSRF-TOKEN': window.csrf_token
          };
          var card = {list_id:this.list_id,title:this.title,description:this.description,completed:this.completed};
          var url = '/cards';
          if(this.action == 'Update'){
          url = '/cards/'+this.card_id;
          axios.put(url, card)
          .then((res) => {
            this.$bvModal.hide('modal-create-update-card')
            this.getResults();
          })
          .catch((error) => {
            this.$refs.observer.setErrors(error.response.data.errors)
          })
          .finally(() => {
            console.log('Closing....');
          })
          }else{
          axios.post(url, card)
          .then((res) => {
            this.$bvModal.hide('modal-create-update-card')
            this.getResults();
          })
          .catch((error) => {
            console.log(error.response.data.errors);
            this.$refs.observer.setErrors(error.response.data.errors)
          })
          .finally(() => {
            console.log('Closing....');
          })
          }
       },
       editCard(card){
         this.$bvModal.show('modal-create-update-card');

         this.card_id = card.id;
         this.title = card.title;
         this.description = card.description;
         this.completed = card.completed;
         this.action = 'Update';
       },
       deleteCard(card_id){
          this.$bvModal.msgBoxConfirm('Are you sure?')
               .then(value => {
                   axios.defaults.headers.common = {
                                                    'X-Requested-With': 'XMLHttpRequest',
                                                    'X-CSRF-TOKEN': window.csrf_token
                                                   };
                   axios.delete('/cards/'+card_id)
                        .then((res)=>{
                            this.getResults();
                        })
                        .catch((error)=>{
                            
                        })
                        .finally(() => {
                           console.log('Closing....');
                        })
               })
               .catch(err => {

               })

       }
    }
}
</script>