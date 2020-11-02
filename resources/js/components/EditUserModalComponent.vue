<template>
    <!-- Modal to edit an User -->
    <div class="modal fade" tabindex="-1" role="dialog" :id="'editUserModal_' + user.id">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editer un utilisateur</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" @submit.prevent="postNow">
                        <div class="form-group">
                            <label class="col-form-label">Nom:</label>
                            <input v-model="name" type="text" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="col-form-label">Prénom:</label>
                            <input v-model="first_name" type="text" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="col-form-label">Email:</label>
                            <input v-model="email" type="email" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-warning">Modifier</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'EditUserModalComponent',

        props: {
            user: {
                type: Object,
            },
        },

        data() {
            return {
                name: this.user.name,
                first_name: this.user.first_name,
                email: this.user.email,
            };
        },

        methods: {
            /**
             * Submit the form to update the user
             */
            postNow() {
                axios
                    .put('http://testgmgsante.test/users/' + this.user.id, {
                        name: this.name,
                        first_name: this.first_name,
                        email: this.email,
                    })
                    .then((response) => {
                        this.$emit('on-refresh');
                        $('#editUserModal_' + this.user.id).modal('toggle');

                        this.$toasted.show(response.data.message, {
                            theme: 'toasted-primary',
                            type: 'success',
                            position: 'top-center',
                            duration: 4000,
                        });
                    })
                    .catch(error => {
                        let errors = error.response.data.errors;
                        for (let field of Object.keys(errors)) {
                            this.$toasted.show(errors[field][0], {
                                theme: 'toasted-primary',
                                type: 'error',
                                position: 'top-center',
                                duration: 4000,
                            });
                        }
                    });
            },
        },
    };
</script>

<style scoped>

</style>
