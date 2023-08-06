import { createRouter, createWebHistory } from "vue-router";

import index from '../component/index.vue';
import ListMember from '../../../Modules/Member/Resources/assets/js/component/ListMember.vue';

const routes = [
    {
		path: '/',
		name: 'index',
		component: index
	},
	{
		path: '/dashboard',
		name: 'dashboard',
		component: ListMember
	}
]

export default createRouter({
	history: createWebHistory(),
	linkActiveClass: "active",
	routes
})