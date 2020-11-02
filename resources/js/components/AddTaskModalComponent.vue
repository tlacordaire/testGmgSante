<template>
    <!-- Modal to add a Task to an User -->
    <div class="modal fade" id="addTaskModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ajouter une tâche</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div v-if="errors" class="bg-red-500 text-white py-2 px-4 pr-0 rounded font-bold mb-4 shadow-lg">
                        <div v-for="(v, k) in errors" :key="k">
                            <p v-for="error in v" :key="error" class="text-sm">
                                {{ error }}
                            </p>
                        </div>
                    </div>
                    <form method="post" @submit.prevent="postNow">
                        <div class="form-group">
                            <label class="col-form-label">Nom:</label>
                            <input v-model="name" type="text" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="col-form-label">Description:</label>
                            <textarea v-model="description" type="text" class="form-control"></textarea>
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
        name: 'AddTaskModalComponent',

        props: {
            userTarget: {
                type: Number,
                default: null,
            },
        },

        data() {
            return {
                name: '',
                description: '',
                errors: null,
            };
        },

        methods: {
            /**
             * Submit the form to create the task
             */
            postNow() {
                axios
                    .post('http://testgmgsante.test/users/' + this.userTarget + '/tasks', {
                        name: this.name,
                        description: this.description,
                        user_id: this.userTarget,
                    })
                    .then((response) => {
                        this.$emit('on-refresh');
                        $('#addTaskModal').modal('toggle');

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
