<template>
    <div class="m-5">
        <div class="row">
            <div class="col-md-8">
                <h4>Liste des utilisateurs
                    <button type="button" class="btn btn-primary btn-sm ml-2 float-right" data-toggle="modal" data-target="#addUserModal">
                        <i class="fas fa-plus-circle"></i> Ajouter un utilisateur
                    </button>
                </h4>
                <table class="table table-sm table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">Nom</th>
                            <th scope="col">Prénom</th>
                            <th scope="col">Email</th>
                            <th scope="col" class="w-20">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="user in users" @click="selectUser(user)" :key="user.id" class="pointer">
                            <td>{{ user.name }}</td>
                            <td>{{ user.first_name }}</td>
                            <td>{{ user.email }}</td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <button type="button" class="btn btn-outline-warning" data-toggle="modal" :data-target="'#editUserModal_' + user.id"><i class="far fa-edit"></i> Modifier</button>
                                    <button type="button" class="btn btn-outline-danger" @click.stop="deleteUser(user.id)"><i class="far fa-trash-alt"></i> Supprimer</button>
                                </div>
                                <edit-user-modal-component :user="user" @on-refresh="getUsers"></edit-user-modal-component>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div v-if="selectedUser" class="col-md-4">
                <h4>Liste des tâches de {{ selectedUser.name }}
                    <div class="float-right">
                        <button type="button" class="btn btn-primary btn-sm ml-2" data-toggle="modal" data-target="#addTaskModal">
                            <i class="fas fa-plus-circle"></i> Ajouter une tâche
                        </button>
                        <button :disabled="!tasksToDelete.length" @click="deleteMultipleTasks" type="button" class="btn btn-warning btn-sm ml-2">
                            <i class="fas fa-trash"></i> Supprimer les tâches sélectionnées
                        </button>
                    </div>
                </h4>
                <table class="table table-striped table-sm">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col"></th>
                            <th scope="col">Nom</th>
                            <th scope="col">Description</th>
                            <th scope="col">Statut</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="task in tasks" :key="task.id">
                            <td><input v-model="tasksToDelete" :value="task.id" type="checkbox"></td>
                            <td>{{ task.name }}</td>
                            <td>{{ task.description }}</td>
                            <td v-if="task.status === '0'">A faire</td>
                            <td v-else>Complétée</td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <button type="button" class="btn btn-outline-warning" data-toggle="modal" :data-target="'#editTaskModal_' + task.id"><i class="far fa-edit"></i> Modifier</button>
                                    <button type="button" class="btn btn-outline-danger" @click="deleteTask(task.id)"><i class="far fa-trash-alt"></i> Supprimer</button>
                                </div>
                                <edit-task-modal-component :userTarget="selectedUser" :task="task" @on-refresh="getTasks"></edit-task-modal-component>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <add-user-modal-component @on-refresh="getUsers"></add-user-modal-component>
        <add-task-modal-component v-if="selectedUser" :userTarget="selectedUser.id" @on-refresh="getTasks"></add-task-modal-component>
    </div>
</template>

<script>
    import AddUserModalComponent from './AddUserModalComponent';
    import AddTaskModalComponent from './AddTaskModalComponent';
    import EditUserModalComponent from './EditUserModalComponent';
    import EditTaskModalComponent from './EditTaskModalComponent';

    import Toasted from 'vue-toasted';

    Vue.use(Toasted);

    export default {
        name: 'GlobalComponent',

        components: {
            AddUserModalComponent,
            AddTaskModalComponent,
            EditUserModalComponent,
            EditTaskModalComponent,
        },

        data() {
            return {
                users: null,
                tasks: null,
                tasksToDelete: [],
                selectedUser: null,
            };
        },

        methods: {
            /**
             * Updates the user table
             */
            getUsers() {
                this.selectedUser = null;
                this.tasksToDelete = [];

                axios
                    .get('http://testgmgsante.test/users')
                    .then(response => (this.users = response.data));
            },

            /**
             * Updates the task table
             */
            getTasks() {
                this.tasksToDelete = [];

                if (!this.selectedUser) {
                    return;
                }

                axios
                    .get('http://testgmgsante.test/users/' + this.selectedUser.id + '/tasks')
                    .then(response => (this.tasks = response.data));
            },

            /**
             * Delete an user
             *
             * @param id String
             */
            deleteUser(id) {
                axios
                    .delete('http://testgmgsante.test/users/' + id)
                    .then((response) => {
                        this.getUsers();

                        this.$toasted.show(response.data.message, {
                            theme: 'toasted-primary',
                            type: 'success',
                            position: 'top-center',
                            duration: 4000,
                        });
                    });
            },

            /**
             * Delete a task
             *
             * @param id String
             */
            deleteTask(id) {
                axios
                    .delete('http://testgmgsante.test/users/' + this.selectedUser.id + '/tasks/' + id)
                    .then((response) => {
                        this.getTasks();

                        this.$toasted.show(response.data.message, {
                            theme: 'toasted-primary',
                            type: 'success',
                            position: 'top-center',
                            duration: 4000,
                        });
                    });
            },

            /**
             * Delete multiple tasks
             */
            deleteMultipleTasks() {
                if (!!this.tasksToDelete.length) {
                    axios
                        .delete('http://testgmgsante.test/users/' + this.selectedUser.id + '/tasks', {
                            data: {
                                ids: this.tasksToDelete,
                            },
                        })
                        .then((response) => {
                            this.getTasks();

                            this.$toasted.show(response.data.message, {
                                theme: 'toasted-primary',
                                type: 'success',
                                position: 'top-center',
                                duration: 4000,
                            });
                        });
                }
            },

            /**
             * Updates the user selected
             *
             * @param user
             */
            selectUser(user) {
                this.selectedUser = user;

                this.getTasks();
            },
        },

        mounted() {
            this.getUsers();
        },
    };
</script>

<style scoped>
    .w-20 {
        width: 20%;
    }

    .pointer {
        cursor: pointer;
    }
</style>
