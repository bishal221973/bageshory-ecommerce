<div class="grid grid-cols-1 gap-4 lg:grid-cols-3">

    <!-- Left side -->
    <div class="lg:col-span-2">
        <!-- Your other content -->
    </div>

    <!-- Transactions -->
    <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
        <p class="mb-4 text-base font-semibold leading-none text-gray-800 dark:text-white">
            Transactions
        </p>

        <div class="overflow-x-auto">
            <x-admin::datagrid
                :src="route('admin.customers.customers.view', [
                    'id'   => $customer->id,
                    'type' => 'transactions',
                ])"
            >
                <!-- Datagrid Header -->

            </x-admin::datagrid>
        </div>
    </div>

</div>
