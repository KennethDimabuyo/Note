<template>
    <div class="login-page">
        <div class="container">
            <div class="row justify-content-center mt-5">
                <div class="card p-2" style="width: 100%; max-width: 400px">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Login</h5>

                        <form @submit.prevent="onSubmit">
                            <div class="mb-3">
                                <label for="username" class="form-label"
                                    >Username</label
                                >
                                <input
                                    v-model="form.username"
                                    type="text"
                                    class="form-control"
                                    id="username"
                                />
                                <small class="text-danger" v-if="errors.username">{{ errors.username[0] }}</small>
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label"
                                    >Password</label
                                >
                                <input
                                    v-model="form.password"
                                    type="password"
                                    class="form-control"
                                    id="password"
                                />
                                <small class="text-danger" v-if="errors.password">{{ errors.password[0] }}</small>
                            </div>

                            <div class="d-grid">
                                <button class="btn btn-primary" :disabled="isLoading">Login</button>
                            </div>
                        </form>

                        <router-link to="/register" class="mt-4 d-block text-center">Register here</router-link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import user from '../../user';
export default {
    data() {
        return {
            form: {},
            errors: {},
            isLoading: false
        }
    },
    methods: {
        onSubmit() {
            this.isLoading = true;

            user.post('login', this.form)
                .then(res => {
                    if (!res.data.token) return alert('Nope');

                    localStorage.setItem('token', res.data.token);
                    this.$router.push('/dashboard');
                })
                .catch(err => this.errors = err.response.data.errors)
                .finally(() => this.isLoading = false);
        }
    }
};
</script>