@extends("layouts.dashboard")
@section("content")
<div class="container py-4">
    <div class="text-center mb-4">
        <h1 class="h4">Investigation PDF</h1>
    </div>

    @if($investigation->pdf)
        <div class="card shadow-sm mx-auto" style="max-width: 90%; overflow: hidden;">
            <div class="card-body">
                <!-- Embed PDF for preview -->
                <div class="d-flex justify-content-center">
                    <iframe src="{{ asset('public/investigationspdf/' . $investigation->pdf) }}" 
                            style="width: 100%; height: 80vh; border: none;" 
                            class="rounded">
                    </iframe>
                </div>
            </div>
        </div>
        <!-- Download Button -->
        <div class="text-center mt-4">
            <a href="{{ asset('public/investigationspdf/' . $investigation->pdf) }}" 
               class="btn btn-primary" 
               download>
                Download PDF
            </a>
        </div>
    @else
        <div class="alert alert-danger text-center mt-4">
            PDF not available for this investigation.
        </div>
    @endif
</div>

<script>
    function executeRemove(id){
        document.getElementById("deleteForm").setAttribute("action", `{{ url('engineers/${id}') }}`);
    }
</script>
@endsection
