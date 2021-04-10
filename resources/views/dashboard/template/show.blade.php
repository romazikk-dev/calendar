<x-dashboard-layout>
    <x-slot name="breadcrumbs">
        {{ Breadcrumbs::render('template', $template->title) }}
    </x-slot>
    
    <table class="show-table">
        <thead>
            <tr class="table-success">
                <th>Key</th>
                <th>Value</th>
            </tr>
        </thead>
        <tbody>
        @foreach($template->toLabelArray() as $k => $v)
            <tr>
                <td data-field="key" class="key">
                    {{$v['label']}}
                </td>
                <td data-field="value">
                    @php
                        if($k == 'created_at' || $k == 'updated_at'){
                            echo \Carbon\Carbon::createFromTimestamp(strtotime($v['value']))->format('d-m-Y');
                        }else{
                            echo $v['value'];
                        }
                    @endphp
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    
</x-dashboard-layout>