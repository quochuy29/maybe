<template>
    <router-link class="nav-link" :to="to">
      <div
        class="text-center bg-white shadow icon icon-shape icon-sm border-radius-md d-flex align-items-center justify-content-center me-2">
        <slot name="icon"></slot>
      </div>
      <span class="nav-link-text ms-1">{{ navText }}</span
      >
    </router-link>
  </template>
  <script>
  export default {
    name: "SideNav",
    props: {
      to: {
        type: [Object, String],
        required: true,
      },
      navText: {
        type: String,
        required: true,
      },
    },
    data() {
      return {
        isExpanded: false,
      };
    },
  };
  </script>
  