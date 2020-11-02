<template>
    <!-- Modal to add an User -->
    <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ajouter un utilisateur</h5>
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
                        <button type="submit" class="btn btn-primary">Ajouter</button>
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
        name: 'AddUserModalComponent',

        data() {
            return {
                name: '',
                first_name: '',
                email: '',
            };
        },

        methods: {
            /**
             * Submit the form to create the user
             */
            postNow() {
                axios
                    .post('http://testgmgsante.test/users', {
                        name: this.name,
                        first_name: this.first_name,
                        email: this.email,
                    })
                    .then((response) => {
                        this.$emit('on-refresh');
                        $('#addUserModal').modal('toggle');

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
