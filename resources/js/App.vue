<template>
  	<Aside></Aside>
	<Main></Main>
</template>
  
<script>
	import Aside from './layout/aside.vue';
	import Main from './layout/main.vue';
	export default {
		name: "mainApp",
		components: {
			Aside,
			Main
		}
	};
</script>