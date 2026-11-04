@push('script')
    <script type="text/javascript">
        $(document).on('change', '#currency-symbol', function() {
            var currency = $('#currency-symbol').val();
            if (currency == '') {
                currency = 'THB';
            }
            var url = "{{ route('currency.change', ['currency' => ':currency']) }}";
            url = url.replace(':currency', currency);
            location.assign(url);
        });
    </script>
@endpush
<?php
$currencies = $helpers->get_all_currencies();
?>

<style>
    .ntn-currency-selection {}

    .ntn-select-currency-footer {
        background-color: var(--primary-color);
        color: #fff;
        font-size: 17px;
    }
</style>

<div class="ntn-currency-selection">
    <img src="{{ asset('assets/wiserlance/assets/img/icons/dollar.svg') }}" style="width:20px" />
    <select id="currency-symbol" class="language-select langSel ntn-select-currency-footer" style="border:none;">


        @foreach ($currencies as $key => $item)
            <option {{ Session::get('currency') == $item->code ? 'selected' : '' }} value="{{ $item->code }}">
                {{ $item->code }}</option>
        @endforeach
    </select>
</div>
