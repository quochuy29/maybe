import { createRouter, createWebHistory } from "vue-router";

import index from '../component/index.vue';
import IndexMember from '../../../Modules/Member/Resources/assets/js/component/Member.vue';

const routes = [
    {
		path: '/',
		name: 'index',
		component: index
	},
    {
		path: '/member',
		name: 'member',
		component: index
	},
    {
		path: '/product',
		name: 'product',
		component: index
	},
    {
		path: '/category',
		name: 'category',
		component: index
	},
    {
		path: '/permission',
		name: 'permission',
		component: index
	},
	{
		path: '/dashboard',
		name: 'dashboard',
		component: IndexMember
	}
]

export default createRouter({
	history: createWebHistory(),
	linkActiveClass: "active",
	routes
})