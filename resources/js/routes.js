import Post from "../js/pages/Post/post.vue";
import Index from '../js/pages/Post/index.vue';
import View from '../js/pages/Post/view.vue';
import Edit from '../js/pages/Post/edit.vue';
import NotFoundPage from './pages/NotFoundPage.vue';

const routes = [
    {
        name:"Post",
        path:"/",
        component:Post,
    },
    {
        name:"Index",
        path:"/index",
        component:Index,
    },
    {
        name:"View",
        path:"/view/:id",
        component:View,
    },
    {
        name:"Edit",
        path:"/edit/:id",
        component:Edit,
    },
    {
        path: '/:catchAll(.*)',
        name: 'NotFound',
        component: NotFoundPage
      },

      
];
export default routes;
