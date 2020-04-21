<script src="{{ asset('assets/js/select2.min.js') }}"></script>
<script>
    $(document).ready(function () {
        $('#statutautorisation').select2({
            ajax: {
                url: '{{'/selectmorestatutautorisations'}}',
                data: function (params) {
                    return {
                        search: params.term,
                        page: params.page || 1
                    };
                },
                dataType: 'json',
                processResults: function (data) {
                    data.page = data.page || 1;
                    return {
                        results: data.items.map(function (item) {
                            return {
                                id: item.id,
                                text: item.name
                            };
                        }),
                        pagination: {
                            more: data.pagination
                        }
                    }
                },
                cache: true,
                delay: 250
            },
            placeholder: 'Statut Autorisation',
//                minimumInputLength: 2,
            multiple: true
        });
    });
</script>
