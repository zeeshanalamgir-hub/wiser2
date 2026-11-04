@extends('templates.basic.layouts.wiserlance.dashboard-layout-job')
@section('content')

    <div class="row">
        <div class="col-12">
            <h5 class="text-dgreen2 mt-2">Manage Services</h5>
            @if ($services->isEmpty())
                <div class="card shadow-sm mt-3 p-4" style="border: solid 1px #d2d2d2">
                    No services have been created yet.
                </div>
            @else
                <div class="card shadow-sm mt-3 p-4" style="border: solid 1px #d2d2d2">
                    @foreach ($services as $service)
                        <h6 class="mb-1 mt-{{ !$loop->first ? '3' : '2' }}">
                            <a href="{{ route('service.details', [encrypt($service->id)]) }}" class="links-c2">
                                {{ $service->title }}
                            </a>
                        </h6>

                        <div class="border-bottom row">
                            <div class="pb-3 fs-14 col-xl-8">
                                <ul class="nav">
                                    <div class="nav-item me-3">
                                        <i class="fa-solid fa-calendar-days text-dgreen2"></i>
                                        {{ showDateTime($service->created_at, 'd M Y') }}
                                    </div>
                                    <div class="nav-item me-3">
                                        <i class="fa-solid fa-clipboard-list text-dgreen2"></i>
                                        <span class="fw-bold">Status:</span> {{ $service->status_text }}
                                    </div>
                                </ul>
                            </div>

                            <div class="fs-14 mb-3 col-xl-4">
                                <div class="float-xl-end">
                                    <a href="{{ route('service.details', [encrypt($service->id)]) }}"
                                        class="links-c2 border p-1 rounded">
                                        <i class="fa-regular fa-eye text-green"></i> View
                                    </a>
                                    @if($service->status == 0)
                                        <a href="{{ route('user.service.edit', [$service->id, slug($service->title)]) }}"
                                            class="mx-2 links-c2 border p-1 rounded">
                                            <i class="fa-solid fa-pen-to-square text-dgreen2"></i> Edit
                                        </a>
                                    @endif
                                   
                                    @if($service->status == 0)
                                        <a href="#" class="links-c2 border p-1 rounded text-decoration-none"
                                            onclick="event.preventDefault(); confirmDelete({{ $service->id }});">
                                            <i class="fa-solid fa-xmark text-danger"></i> Delete
                                        </a>
                                    @endif

                                    <form id="delete-form-{{ $service->id }}"
                                        action="{{ route('user.service.delete', ['id' => $service->id]) }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
@section('script')
    <script>
        function confirmDelete(serviceId) {
            if (confirm('Are you sure you want to delete this service?')) {
                document.getElementById('delete-form-' + serviceId).submit();
            }
        }
    </script>
@endsection
