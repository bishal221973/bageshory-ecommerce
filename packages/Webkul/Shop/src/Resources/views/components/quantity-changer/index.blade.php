@props([
    'name'     => '',
    'value'    => 1,
    'minValue' => 1,
])

<v-quantity-changer
    {{ $attributes->merge(['class' => 'flex items-center border border-navyBlue']) }}
    name="{{ $name }}"
    value="{{ $value }}"
    min-value="{{ $minValue }}"
></v-quantity-changer>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-quantity-changer-template"
    >
        <div class="flex items-center gap-2">
            <span
                class="icon-minus cursor-pointer text-2xl"
                role="button"
                tabindex="0"
                aria-label="@lang('shop::app.components.quantity-changer.decrease-quantity')"
                @click="decrease"
            ></span>

            <input
                type="number"
                :min="minValue"
                v-model.number="quantity"
                @blur="validateQuantity"
                @keyup.enter="validateQuantity"
                class="w-12 border-0 bg-transparent text-center outline-none max-sm:text-sm"
            />

            <span
                class="icon-plus cursor-pointer text-2xl"
                role="button"
                tabindex="0"
                aria-label="@lang('shop::app.components.quantity-changer.increase-quantity')"
                @click="increase"
            ></span>

            <v-field
                type="hidden"
                :name="name"
                v-model="quantity"
            ></v-field>
        </div>
    </script>

    <script type="module">
        app.component("v-quantity-changer", {
            template: "#v-quantity-changer-template",

            props: {
                name: {
                    type: String,
                    default: "",
                },

                value: {
                    type: Number,
                    default: 1,
                },

                minValue: {
                    type: Number,
                    default: 1,
                },
            },

            data() {
                return {
                    quantity: Number(this.value),
                };
            },

            watch: {
                value(newValue) {
                    this.quantity = Number(newValue);
                },
            },

            methods: {
                increase() {
                    this.quantity++;

                    this.$emit("change", this.quantity);
                },

                decrease() {
                    if (this.quantity > this.minValue) {
                        this.quantity--;

                        this.$emit("change", this.quantity);
                    }
                },

                validateQuantity() {
                    let qty = parseInt(this.quantity);

                    if (isNaN(qty) || qty < this.minValue) {
                        qty = this.minValue;
                    }

                    this.quantity = qty;

                    this.$emit("change", this.quantity);
                },
            },
        });
    </script>
@endPushOnce