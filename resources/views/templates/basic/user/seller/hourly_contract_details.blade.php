<div class="card shadow-sm mt-3">
    <div class="card-body p-4 pb-2">
        <a class="links-c2 collapsed" data-bs-toggle="collapse" href="#collapseContract" role="button" aria-expanded="false"
            aria-controls="collapseContract">
            <i class="fa-solid fa-circle-chevron-down float-end text-dgreen2 fs-4"></i>
            <h5 class="mb-3 text-dgreen">Contract Details</h5>
        </a>

        <div class="collapse" id="collapseContract" style="">
            <table class="table table-bordered fs-14">
                <thead>
                    <tr class="bg-dgreen2 text-white">
                        <th scope="col">Details</th>
                        <th scope="col">Information</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contract_details as $key=>$contract_detail)
                        <tr>
                            <td>{{ $key }}</td>
                            <td>{{ $contract_detail }}</td>
                        </tr>
                    @empty
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
