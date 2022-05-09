<template>
    <div class="dashboard-page bg-light" style="min-height: 100vh">
        <div class="container py-5">
            <div
                class="
                    d-flex
                    justify-content-between
                    flex-nowrap
                    align-content-center
                    mb-4
                "
            >
                <h4 class="mb-0" style="width: unset">Hello {{ authUser.name }}</h4>

                <a class="logout-action-container" style="cursor: pointer" @click="onLogout">Logout</a>
            </div>

            <div class="card">
                <div class="row">
                    <div class="col-3">
                        <div class="note-list-action mb-2 p-3">
                            <button
                                type="button"
                                class="btn btn-primary text-weight-medium"
                                style="width: 100%"
                                data-bs-toggle="modal"
                                data-bs-target="#updateNoteModal"
                                @click="onAddHandler"
                            >
                                Add Note
                            </button>
                        </div>

                        <div class="note-list-container" style="height: 100%">
                            <div class="text-muted" v-if="list == null">
                                Loading ...
                            </div>

                            <ul class="list-group" v-else-if="list.length > 0">
                                <li
                                    class="
                                        list-group-item
                                        d-flex
                                        justify-content-between
                                        align-items-center
                                    "
                                    :class="[
                                        item.id == selected ? 'active' : '',
                                    ]"
                                    v-for="(item, i) in list"
                                    :key="i"
                                    @click="onSelectHandler(item)"
                                    style="
                                        cursor: pointer;
                                        border: none;
                                        border-bottom: 1px solid #dedede;
                                        border-right: 1px solid #dedede;
                                        border-radius: 0;
                                        border-top: 1px solid #dedede;
                                    "
                                >
                                    <div
                                        class="
                                            d-flex
                                            flex-nowrap
                                            justify-content-between
                                        "
                                        style="width: 100%; align-items: center"
                                    >
                                        <div class="_list-item-info">
                                            <div class="_title">
                                                {{ item.title }}
                                            </div>
                                            <div class="text-ellipsis">
                                                <small class="_subtitle">{{
                                                    item.content
                                                }}</small>
                                            </div>
                                        </div>

                                        <div
                                            class="
                                                _list-item-actions
                                                d-flex
                                                flex-nowrap
                                            "
                                        >
                                            <button
                                                class="
                                                    btn btn-primary btn-sm
                                                    rounded-pill
                                                    me-1
                                                "
                                                @click="onUpdateHandler(item)"
                                            >
                                                <i class="fas fa-pen"></i>
                                            </button>

                                            <button
                                                class="
                                                    btn btn-danger btn-sm
                                                    rounded-pill
                                                "
                                                @click="onDelete(item)"
                                            >
                                                <i class="far fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </div>
                                </li>
                            </ul>

                            <div class="empty-note-list" v-else>Empty ...</div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="note-view-container">
                            <div class="note-content p-4" v-if="model.id">
                                <h4>{{ model.title }}</h4>

                                <div class="_content">
                                    {{ model.content }}
                                </div>
                            </div>

                            <div
                                v-else
                                class="
                                    text-muted
                                    d-flex
                                    justify-content-center
                                    align-items-center
                                "
                                style="min-height: 300px; height: 100%"
                            >
                                <h4>Select to view the note description ...</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div
            class="modal fade"
            id="updateNoteModal"
            tabindex="-1"
            aria-labelledby="updateNoteModal"
            aria-hidden="true"
        >
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">
                            {{ modalTitle }}
                        </h5>
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close"
                        ></button>
                    </div>

                    <form @submit.prevent="onSubmit">
                        <div class="modal-body">
                            <div class="alert alert-danger" role="alert" v-if="formErrorMessage">
                                {{ formErrorMessage }}
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Title</label>
                                <input
                                    type="text"
                                    v-model="form.title"
                                    class="form-control"
                                    placeholder="Hello World!"
                                />
                                <small
                                    class="text-danger"
                                    v-if="errors.title"
                                    >{{ errors.title[0] }}</small
                                >
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Content</label>
                                <textarea
                                    class="form-control"
                                    v-model="form.content"
                                    rows="3"
                                    placeholder="Aa ..."
                                ></textarea>
                                <small
                                    class="text-danger"
                                    v-if="errors.content"
                                    >{{ errors.content[0] }}</small
                                >
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button
                                type="button"
                                class="btn btn-secondary"
                                data-bs-dismiss="modal"
                            >
                                Close
                            </button>
                            <button
                                type="submit"
                                class="btn btn-primary fw-bold"
                            >
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import user from "../user";
export default {
    data() {
        return {
            list: null,
            form: {},
            formErrorMessage: null,
            model: {},
            errors: {},
            isLoading: false,
            api: "notes",
            selected: 0,
            modalTitle: 'Add Note',
            authUser: {},
        };
    },
    mounted() {
        this.getNotes();

        user.get('user')
            .then(res => {
                this.authUser = res.data;
                this.authUser.name = res.data.name ?? res.data.username
            })
            .catch(err => console.error(err));
    },
    methods: {
        getNotes() {
            user.get("notes")
                .then((res) => (this.list = res.data))
                .catch((err) => console.error(err));
        },

        onSubmit() {
            this.form.user_id = this.authUser.id;

            console.log(this.authUser);

            user.post(this.api, this.form)
                .then((res) => {
                    if (!res.data) {
                        if (!this.form.user_id) this.formErrorMessage = `Something went wrong, we can't store this note. Undefined user.`;
                        else this.formErrorMessage = `Something went wrong, we can't store this note.`;
                    }

                    if (res.data) {
                        $("#updateNoteModal").modal("hide");
                        this.form = {};
                        this.errors = {};
                        this.getNotes();
                        this.formErrorMessage = null;
                    }
                })
                .catch((err) => (this.errors = err.response.data.errors));
        },

        onSelectHandler(model) {
            this.selected = model.id;
            this.model = model;
        },

        onAddHandler() {
            this.modalTitle = 'Add Note';
        },

        onUpdateHandler(item) {
            this.form = item;
            this.modalTitle = 'Update Note';
            $('#updateNoteModal').modal('show');
        },

        onDelete(item) {
            if (confirm("Are you sure you want to delete this note?")) {
                user.delete(`${this.api}/${item.id}`)
                    .then((res) => {
                        if (res.data) {
                            this.list = this.list.filter(
                                (x) => x.id != item.id
                            );
                        }
                    })
                    .catch((err) => console.error(err));
            }
        },

        onLogout() {
            localStorage.removeItem('token');
            this.$router.push('/login');
        }
    },
};
</script>

<style>
</style>