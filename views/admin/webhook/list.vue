
<template>
    <table-render :path_param="['mpesa', 'webhook']" title="Mpesa Webhook" :table_fields="table_fields">

        <template #header>
            <th-render>Slug</th-render>
            <th-render>Validation Url</th-render>
            <th-render>Confirmation Url</th-render>
            <th-render>Paybill Till</th-render>
            <th-render>Shortcode</th-render>
            <th-render>Published</th-render>
        </template>

        <template #body="{ item }">
            <td>{{ item.slug }}</td>
            <td>{{ item.validation_url }}</td>
            <td>{{ item.confirmation_url }}</td>
            <td>{{ item.paybill_till }}</td>
            <td>{{ item.shortcode }}</td>
            <td class="text-center">
                <btn-status :status="item.published"></btn-status>
            </td>
        </template>

    </table-render>
</template>


<script>

export default {
    data() {
        return {
            table_fields: ['slug', 'validation_url', 'confirmation_url', 'paybill_till', 'shortcode', 'published'],
        };
    }
};
</script>