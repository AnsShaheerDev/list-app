<template>
<ValidationObserver v-slot="{ handleSubmit }">
  <form action="#" @submit.prevent="handleSubmit(login)">

    <div class="form-group row">
      <label for="email" class="col-md-4 col-form-label text-md-right"
        >Email</label>

      <div class="col-md-6">
       <ValidationProvider rules="required|email" v-slot="{ errors }" name="Email" tag="div">
        <input
          v-model="form.email"
          type="email"
          class="form-control"
        />
         <span class="text-danger">{{ errors[0] }}</span>
        </ValidationProvider>
      </div>
    </div>

    <div class="form-group row">
      <label for="password" class="col-md-4 col-form-label text-md-right"
        >Password</label>

      <div class="col-md-6">
       <ValidationProvider rules="required" v-slot="{ errors }" name="Password" tag="div">
        <input
          v-model="form.password"
          type="password"
          class="form-control"
        />
        <span class="text-danger">{{ errors[0] }}</span>
       </ValidationProvider>
      </div> 
    </div>

    <div class="form-group row mb-0">
      <div class="col-md-8 offset-md-4">
        <button type="submit" class="btn btn-primary">Login</button>
      </div>
    </div>
  </form>
 </ValidationObserver>
</template>
<script>
export default { data () { return { form: { email: '', password: ''} } }, 

methods: { 

 login () {
   axios.defaults.headers.common = {
    'X-Requested-With': 'XMLHttpRequest',
    'X-CSRF-TOKEN': window.csrf_token
   }; 
   axios.post('login', this.form)
         .then((res) => {
            console.log(res.data.redirect_url);
            window.location.href = res.data.redirect_url;
         })
         .catch((error) => {
            console.log(error);
         })
         .finally(() => {
            console.log('Closing....');
         })
 }, 

 } }
</script>