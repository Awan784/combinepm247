@if(Session::has('success'))
<br/>
    <div class="alert alert-success">{{session('success')}}</div>
@endif

@if(Session::has('error'))
    <br/>
    <div class="alert alert-danger">{{session('error')}}</div>

@endif



<script>

    setTimeout(function(){
        $(".alert").hide();
    },5000);

</script>
