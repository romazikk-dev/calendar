<x-dashboard-layout>
    
    <div class="home-page">
        
        <div class="row">
            <div class="coll col-sm-4">
                
                <div class="card">
                    <div class="card-header">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                            <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"></path>
                            <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"></path>
                        </svg>
                        Workers
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">
                            Total:
                            @if(empty($statistic['worker']) || empty($statistic['worker']['count']))
                                <span class="badge badge-warning">0</span>
                            @else
                                <span class="badge badge-success">
                                    {{$statistic['worker']['count']}}
                                </span>
                            @endif
                        </h5>
                        <div class="card-title">
                            Suspended:
                            @if(empty($statistic['worker']) || empty($statistic['worker']['suspended']))
                                <span class="badge badge-primary">0</span>
                            @else
                                <span class="badge badge-warning">
                                    {{$statistic['worker']['suspended']}}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="coll col-sm-4">
                
                <div class="card">
                    <div class="card-header">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-house-door-fill" viewBox="0 0 16 16">
                            <path d="M6.5 14.5v-3.505c0-.245.25-.495.5-.495h2c.25 0 .5.25.5.5v3.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5z"></path>
                        </svg>
                        Halls
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">
                            Total:
                            @if(empty($statistic['hall']) || empty($statistic['hall']['count']))
                                <span class="badge badge-warning">0</span>
                            @else
                                <span class="badge badge-success">
                                    {{$statistic['hall']['count']}}
                                </span>
                            @endif
                        </h5>
                        <div class="card-title">
                            Suspended:
                            @if(empty($statistic['hall']) || empty($statistic['hall']['suspended']))
                                <span class="badge badge-primary">0</span>
                            @else
                                <span class="badge badge-warning">
                                    {{$statistic['hall']['suspended']}}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="coll col-sm-4">
                
                <div class="card">
                    <div class="card-header">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-file-check" viewBox="0 0 16 16">
                            <path d="M10.854 6.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 8.793l2.646-2.647a.5.5 0 0 1 .708 0z"></path>
                            <path d="M4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H4zm0 1h8a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1z"></path>
                        </svg>
                        Templates
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">
                            Total:
                            @if(empty($statistic['template']) || empty($statistic['template']['count']))
                                <span class="badge badge-warning">0</span>
                            @else
                                <span class="badge badge-success">
                                    {{$statistic['template']['count']}}
                                </span>
                            @endif
                        </h5>
                        <div class="card-title">
                            Suspended:
                            @if(empty($statistic['template']) || empty($statistic['template']['suspended']))
                                <span class="badge badge-primary">0</span>
                            @else
                                <span class="badge badge-warning">
                                    {{$statistic['template']['suspended']}}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="coll col-sm-4">
                
                <div class="card">
                    <div class="card-header">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-people-fill" viewBox="0 0 16 16">
                            <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H7zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                            <path fill-rule="evenodd" d="M5.216 14A2.238 2.238 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.325 6.325 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1h4.216z"/>
                            <path d="M4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z"/>
                        </svg>
                        Clients
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">
                            Total:
                            @if(empty($statistic['client']) || empty($statistic['client']['count']))
                                <span class="badge badge-warning">0</span>
                            @else
                                <span class="badge badge-success">
                                    {{$statistic['client']['count']}}
                                </span>
                            @endif
                        </h5>
                    </div>
                </div>
                
            </div>
        </div>
        
    </div>
    
</x-dashboard-layout>
