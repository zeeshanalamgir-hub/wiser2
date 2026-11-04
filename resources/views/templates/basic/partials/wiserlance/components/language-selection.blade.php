<style>
    .ntn-lang-selection-dropdown-outer {}
    .ntn-lang-selection {
        display: flex;
        flex-direction: row;
    }
    ..ntn-lang-selection>button {
        border: none;
        background: transparent;
        margin-top: -8px;
        margin-left: -7px !important;
    }
    .ntn-drp-button-564362 {
        background: transparent;
        color: #fff;
        padding-left: 8px;
    }
</style>
<script src="{{ asset('assets/wiserlance/assets/js/jquery.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#ntn-lang-symbol').change(function() {
            var selectedOption = $(this).val();
            console.log('Selected option:', selectedOption);
        });
    });
</script>
<div class="ntn-lang-selection-dropdown-outer">
    <div class="ntn-lang-selection">
        <img src="{{ asset('assets/wiserlance/assets/img/icons/lang.svg') }}" />
        <div class="dropdown">
            <button
                style="padding: 10px;
            display: inline-block;
            font-weight: 400;
            font-size: 16px;
            color: #ffffff;"
                class="btn res dropdown-toggle ntn-drp-button-564362" type="button" data-toggle="dropdown">ENG
                <span class="caret"></span></button>
            <ul class="dropdown-menu" style="padding: 10px; border-redius:5px; back">
                <li style="border-bottom: 1px solid white">
                    <a href="#googtrans(en|en)" class=" lang-en lang-select mr-3" data-lang="en" data-res="ENG">
                        <span class="flag-icon flag-icon-us">
                        </span>
                        ENG
                    </a>
                </li>
                <li>
                    <a href="#googtrans(en|ar)" class=" lang-es lang-select" data-lang="ar" data-res="ENG">
                        <span class="flag-icon flag-icon-ar">
                        </span>
                        ARAB
                    </a>
                </li>
            </ul>
        </div>
    </div>


</div>
