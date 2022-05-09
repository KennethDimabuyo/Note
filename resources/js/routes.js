import NotFound from './vue/notFound.vue';
import Login from './vue/auth/login.vue';
import Register from './vue/auth/register.vue';
import Landing from './vue/landing.vue';
import Dashboard from './vue/dashboard.vue';
import user from './user';

export default {
    mode: 'history',
    routes: [
        {
            path: '*',
            component: NotFound
        },

        {
            path: '/',
            component: Login,
            beforeEnter: (to, from, next) => Unauthorize(next)
        },

        // auth
        {
            path: '/login',
            component: Login,
            beforeEnter: (to, from, next) => Unauthorize(next)
        },

        {
            path: '/register',
            component: Register,
            beforeEnter: (to, from, next) => Unauthorize(next)
        },

        {
            path: '/dashboard',
            component: Dashboard,
            beforeEnter: (to, from, next) => Authorized(next)
        },
    ]
}

function Authorized(next) {
    user.auth()
        .then(res => next())
        .catch(err => next('/login'));
}

function AuthorizedOrOffline(next) {
    user.authenticated()
        .then(res => {
            console.log(res);
        })  
        .catch(err => {
            console.log(err);
        });

        next();
}

function Unauthorize(next) {
    user.auth()
        .then(res => next('/dashboard'))
        .catch(err => next());
}