@props(['booking'])
<div class="dropdown float-end pe-lg-5 d-block d-lg-none fs-14">
    <button class="btn btn-sm btn-success bg-green border-0 dropdown-toggle" type="button" data-bs-toggle="dropdown"
        aria-expanded="false">
        In Progress</button>
    <ul class="dropdown-menu fs-14">
        <li><a class="dropdown-item" href="../seller/serviceDetails.php" title="Active Jobs">Srv
                Details</a></li>
        <li><a class="dropdown-item" href="../seller/serviceContract.php" title="Active Jobs">Contract</a></li>
        <li><a class="dropdown-item" href="../seller/serviceInvoice.php" title="Closed Jobs">Invoice</a></li>
        <li><a class="dropdown-item" href="../seller/serviceCompleted.php" title="Closed Jobs">Completed</a>
        </li>
    </ul>
</div> <!-- this drop down for small screens End-->