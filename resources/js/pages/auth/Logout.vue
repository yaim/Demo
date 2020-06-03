<template>
    <div></div>
</template>
<script>
    const axios = require('axios');

    export default {
        mounted: function() {
            this.$store.commit('logout');
            this.$router.push({name: 'login'});
       }
    }
</script>