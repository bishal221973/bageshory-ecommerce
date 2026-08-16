<div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
    <p class="text-base font-semibold leading-none text-gray-800 dark:text-white">
    Transactions
    <!-- @lang('admin::app.customers.customers.view.invoices.count', ['count' => count($customer->invoices)]) -->
    </p>

    <x-admin::datagrid
        :src="route('admin.customers.customers.view', [
            'id'   => $customer->id,
            'type' => 'transactions'
        ])"
    >
       
    </x-admin::datagrid>
</div>