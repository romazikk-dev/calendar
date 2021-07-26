<template>
    <div v-else class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">
                <a @click.prevent="toggleShow" class="btn" :class="{'btn-success disabled': show == 'signup', 'btn-link': show == 'signin'}" href="#">Sign up</a>
                <a @click.prevent="toggleShow" class="btn" :class="{'btn-success disabled': show == 'signin', 'btn-link': show == 'signup'}" href="#">Sign in</a>
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div v-if="show == 'signup'">
                <form ref="signupForm" action="" method="post" id="signupForm">
                    <input name="user_id" type="hidden" id="signupUserId" :value="userId">
                    <div class="form-group">
                        <input name="email" type="email" class="form-control" id="signupEmail" placeholder="Email">
                        <small class="text-danger error" v-if="signupErrors && signupErrors.email">{{signupErrors.email[0]}}</small>
                    </div>
                    <div class="form-group">
                        <input name="phone" type="text" class="form-control" id="signupPhone" placeholder="Phone">
                        <small class="text-danger error" v-if="signupErrors && signupErrors.phone">{{signupErrors.phone[0]}}</small>
                    </div>
                    <div class="form-group">
                        <input name="first_name" type="text" class="form-control" id="signupFirstName" placeholder="First Name">
                        <small class="text-danger error" v-if="signupErrors && signupErrors.first_name">{{signupErrors.first_name[0]}}</small>
                    </div>
                    <div class="form-group">
                        <input name="last_name" type="text" class="form-control" id="signupLastName" placeholder="Last Name">
                        <small class="text-danger error" v-if="signupErrors && signupErrors.last_name">{{signupErrors.last_name[0]}}</small>
                    </div>
                    <div class="form-group">
                        <input name="password" type="password" class="form-control" id="signupPassword" placeholder="Password">
                        <small class="text-danger error" v-if="signupErrors && signupErrors.password">{{signupErrors.password[0]}}</small>
                    </div>
                    <div class="form-group">
                        <input name="password_confirm" type="password" class="form-control" id="signupPasswordConfirm" placeholder="Password confirmation">
                        <small class="text-danger error" v-if="signupErrors && signupErrors.password_confirm">{{signupErrors.password_confirm[0]}}</small>
                    </div>
                </form>
            </div>
            <div v-if="show == 'signin'">
                <form ref="signinForm" action="" method="post">
                    <div class="form-group">
                        <input name="email" type="email" class="form-control" placeholder="Email">
                        <small class="text-danger error" v-if="signinErrors && signinErrors.email">{{signinErrors.email[0]}}</small>
                    </div>
                    <div class="form-group">
                        <input name="password" type="password" class="form-control" placeholder="Password">
                        <small class="text-danger error" v-if="signinErrors && signinErrors.password">{{signinErrors.password[0]}}</small>
                    </div>
                </form>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" @click.prevent="send()" class="btn btn-primary">{{show == 'signup' ? 'Sign Up' : 'Sign In'}}</button>
        </div>
    </div>
</template>

<script>
    import MonthCell from "./MonthCell.vue";
    export default {
        name: 'modalAuthContent',
        mounted() {
            // console.log(JSON.parse(JSON.stringify('modalAuthContent')));
        },
        // props: ['userId'],
        data: function(){
            return {
                show: 'signin',
                signupErrors: null,
                signinErrors: null,
            };
        },
        computed: {
            userId: function(){
                return this.$store.getters['owner/ownerId'];
            },
        },
        methods: {
            send: function(){
                if(this.show == 'signin'){
                    var formData = new FormData(this.$refs['signinForm']);
                    var url = routes.calendar.booking.login;
                }else{
                    var formData = new FormData(this.$refs['signupForm']);
                    var url = routes.calendar.booking.register;
                }
                
                axios({
                    method: "post",
                    url: url,
                    data: formData,
                    headers: { "Content-Type": "multipart/form-data" },
                })
                .then((response) => {
                    let componentApp = this.getParentComponentByName(this, 'app');
                    if(componentApp){
                        this.signinErrors = null;
                        this.signupErrors = null;
                        componentApp.login(response.data.token);
                    }
                })
                .catch(error => {
                    this.signinErrors = null;
                    this.signupErrors = null;
                    console.log("ERRRR:: ", error.response.data);
                    
                    if(typeof error.response.data.errors !== 'undefined'){
                        if(this.show == 'signin'){
                            this.signinErrors = error.response.data.errors;
                        }else{
                            this.signupErrors = error.response.data.errors;
                        }
                    }
                    
                    if(error.response.data.toLowerCase().trim() == 'login invalid')
                        this.signinErrors = {email: ['Email or password invalid']};
                });
            },
            toggleShow: function(){
                if(this.show == 'signin'){
                    this.show = 'signup';
                }else{
                    this.show = 'signin';
                }
            },
        },
        components: {
            
        },
    }
</script>

<style scoped>
    small.error{
        position: absolute;
        margin-top: -2px;
    }
    .form-group{
        padding-bottom: 6px;
    }
</style>