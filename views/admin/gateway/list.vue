
<template>
    <table-render :path_param="['mpesa', 'gateway']" title="Mpesa Gateway" :table_fields="table_fields">

        <template #header>
            <th-render>Name</th-render>
            <th-render>Slug</th-render>
            <th-render>Key</th-render>
            <th-render>Secret</th-render>
            <th-render>Type</th-render>
            <th-render>Short Code</th-render>
            <th-render>Paybill Till</th-render>
            <th-render>Published</th-render>
            <th-render>Default</th-render>
        </template>

        <template #body="{ item }">
            <td>{{ item.title }}</td>
            <td>{{ item.slug }}</td>
            <td>{{ item.key }}</td>
            <td>{{ item.secret }}</td>
            <td>{{ item.type }}</td>
            <td>{{ item.shortcode }}</td>
            <td>{{ item.paybill_till }}</td>
            <td>{{ item.published }}</td>
            <td>{{ item.default }}</td>
        </template>


    </table-render>
</template>


<script>

export default {
    data() {
        return {
            table_fields: ['title', 'slug', 'key', 'secret', 'type', 'shortcode', 'paybill_till', 'published', 'default'],
        };
    }
};
</script>
