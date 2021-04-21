<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">
            Lists
            <b-button
              v-b-modal.modal-create-list
              size="sm"
              variant="primary"
              class="mb-2 float-right"
            >
              <b-icon icon="plus" aria-label="Help"></b-icon>
            </b-button>
          </div>

          <div class="card-body">
            <div v-if="listData.data && listData.data.length">
              <b-table striped hover :items="listData.data" :fields="fields">
                <template #cell(index)="data">
                    {{ data.index + 1 }}
                </template>
                <template #cell(action)="data">
                    <a href="javascript:void(0)" @click="getCards(data.item.id)">Cards</a>
                </template>
              </b-table>
            </div>
            <pagination
              :data="listData"
              @pagination-change-page="getResults"
            ></pagination>
          </div>
        </div>
      </div>
    </div>
    <b-modal
      id="modal-create-list"
      ref="modal"
      title="Create List"
      @show="resetModal"
      @hidden="resetModal"
      @ok="handleOk"
      ok-title="Save"
    >
      <ValidationObserver
        ref="observer"
        tag="form"
        @submit.prevent="createList()"
      >
        <b-form-group label="Title" label-for="title-input">
          <ValidationProvider
            vid="title"
            rules="required"
            v-slot="{ errors }"
            name="Title"
            tag="div"
          >
            <b-form-input id="title-input" v-model="title"></b-form-input>
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
        console.log('List Component mounted.')
    },
    data() {
        return {
            title:'',
            listData: {},
            fields: ['index','title', 'progress','action']
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
            axios.get('/lists?page=' + page)
                .then(response => {
                    this.listData = response.data;
                });
       },
       resetModal() {
        this.title = ''
       },
       async handleOk(bvModalEvt) {
          bvModalEvt.preventDefault();
          const valid = await this.$refs.observer.validate();
          if(valid){
             this.createList();
          }
       },
       createList(){
         axios.defaults.headers.common = {
           'X-Requested-With': 'XMLHttpRequest',
           'X-CSRF-TOKEN': window.csrf_token
          };
          var list = {title:this.title}
          axios.post('/lists', list)
          .then((res) => {
            this.$bvModal.hide('modal-create-list')
            this.getResults();
          })
          .catch((error) => {
            console.log(error.response.data.errors);
            this.$refs.observer.setErrors(error.response.data.errors)
          })
          .finally(() => {
            console.log('Closing....');
          })
       },
       getCards(list_id){
         window.location.href = '/cards?list_id='+list_id;
       }
    }
}
</script>
