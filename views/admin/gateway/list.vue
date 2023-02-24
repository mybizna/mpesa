
<template>
    <table-render :path_param="['mpesa', 'gateway']" title="Mpesa Gateway" :table_fields="table_fields">

        <template #header>
            <th-render>Name</th-render>
            <th-render>Slug</th-render>
            <th-render>Ledger</th-render>
            <th-render>Currency</th-render>
            <th-render>Shortcode</th-render>
            <th-render>Method</th-render>
            <th-render>Published</th-render>
            <th-render>Default</th-render>
        </template>

        <template #body="{ item }">
            <td>{{ item.title }}</td>
            <td>{{ item.slug }}</td>
            <td>{{ item.ledger_id }}</td>
            <td>{{ item.currency_id }}</td>
            <td>{{ item.business_shortcode }}</td>
            <td>{{ item.method }}</td>
            <td class="text-center">
                <btn-status :status="item.published"></btn-status>
            </td>
            <td class="text-center">
                <btn-status :status="item.default"></btn-status>
            </td>
        </template>


    </table-render>
</template>


<script>
export default {
    data() {
        return {
            table_fields: ['title', 'slug', 'ledger_id', 'currency_id', 'consumer_key',
                'consumer_secret', 'initiator_name', 'initiator_password', 'party_a', 'party_b', 'type',
                'passkey', 'business_shortcode', 'phone_number', 'method',
                'description', 'default', 'sandbox', 'published'],
        };
    }
};
</script>
