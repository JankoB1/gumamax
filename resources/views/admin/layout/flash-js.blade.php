
    @if(session()->has('flash_message'))
        @push('scripts')
         <script>
             jQuery(document).ready(function(){
                swal({
                    title: "{{ session('flash_message.title') }}",
                    text:  "{{ session('flash_message.message') }}",
                    type:  "{{ session('flash_message.type') }}",
                    timer : 1700,
                    showConfirmButton: false
                });
             });
        </script>
        @endpush
    @endif
    @if(session()->has('flash_message_overlay'))
        @push('scripts')
        <script>
            jQuery(document).ready(function(){
                swal({
                    title: "{{ session('flash_message_overlay.title') }}",
                    text:  "{{ session('flash_message_overlay.message') }}",
                    type:  "{{ session('flash_message_overlay.type') }}",
                    confirmButtonText: "Ok"
                });
            });
        </script>
        @endpush
    @endif

