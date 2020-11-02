<template>
    <!-- Modal to edit a Task -->
    <div class="modal fade" tabindex="-1" role="dialog" :id="'editTaskModal_' + task.id">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editer une tâche</h5>
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
                            <label class="col-form-label">Description:</label>
                            <textarea v-model="description" type="text" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label">Statut:</label>
                            <select v-model="status" class="form-control">
                                <option v-for="option in options" v-bind:value="option.value">
                                    {{ option.text }}
                                </option>
                            </select>
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
        name: 'AddTaskModalComponent',

        props: {
            userTarget: {
                type: Object,
                default: null,
            },
            task: {
                type: Object,
            },
        },

        data() {
            return {
                name: this.task.name,
                description: this.task.description,
                status: this.task.status,
                options: [
                    {
                        text: 'A faire',
                        value: '0',
                    },
                    {
                        text: 'Complétée',
                        value: '1',
                    },
                ],
            };
        },

        methods: {
            /**
             * Submit the form to update the task
             */
            postNow() {
                axios
                    .put('http://testgmgsante.test/users/' + this.userTarget.id + '/tasks/' + this.task.id, {
                        name: this.name,
                        description: this.description,
                        status: this.status,
                        user_id: this.userTarget,
                    })
                    .then((response) => {
                        this.$emit('on-refresh');
                        $('#editTaskModal_' + this.task.id).modal('toggle');

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
