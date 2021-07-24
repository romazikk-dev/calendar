<?php

namespace App\Classes\Setting;

use App\Classes\Setting\Enums\Keys;

class Nav{
    
    private $items;
    
    function __construct(){
        $this->setAllItems();
    }
    
    public function get($key = false){
        return !empty($key) && array_key_exists($key, $this->items) ? $this->items[$key] : $this->items;
    }
    
    private function setAllItems(){
        $this->items = [
            // 'all' => [
            //     'title' => 'All',
            //     'route' => route('dashboard.settings.index'),
            //     'route_name' => 'dashboard.settings.index',
            // ],
            "worker" => [
                "title" => 'Worker',
                "icon" => '
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                        <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                        <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
                    </svg>
                ',
                "includes" => true,
                'route' => route('dashboard.settings.worker.index'),
                'items' => [
                    Keys::WORKER_DEFAULT_BUSINESS_HOURS => [
                        'title' => 'Worker`s default bussiness hours',
                        'route' => route('dashboard.settings.worker.default_business_hours'),
                        'route_name' => 'dashboard.settings.worker.default_business_hours',
                    ],
                ]
            ],
            "hall" => [
                "title" => 'Hall',
                "icon" => '
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-house-door-fill" viewBox="0 0 16 16">
                        <path d="M6.5 14.5v-3.505c0-.245.25-.495.5-.495h2c.25 0 .5.25.5.5v3.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5z"/>
                    </svg>
                ',
                "includes" => true,
                'route' => route('dashboard.settings.hall.index'),
                'items' => [
                    Keys::HALL_DEFAULT_TIMEZONE => [
                        'title' => 'Default timezone',
                        'route' => route('dashboard.settings.hall.timezone'),
                        'route_name' => 'dashboard.settings.hall.timezone',
                    ],
                    Keys::HALL_DEFAULT_BUSINESS_HOURS => [
                        'title' => 'Default bussiness hours',
                        'route' => route('dashboard.settings.hall.default_business_hours'),
                        'route_name' => 'dashboard.settings.hall.default_business_hours',
                    ],
                    Keys::HALL_HOLIDAYS_FOR_ALL => [
                        'title' => 'Holidays for all halls',
                        'route' => route('dashboard.settings.hall.holidays'),
                        'route_name' => 'dashboard.settings.hall.holidays',
                    ],
                ]
            ],
            "template" => [
                "title" => 'Template',
                "icon" => '
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-file-check" viewBox="0 0 16 16">
                        <path d="M10.854 6.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 8.793l2.646-2.647a.5.5 0 0 1 .708 0z"/>
                        <path d="M4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H4zm0 1h8a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1z"/>
                    </svg>
                ',
                "includes" => true,
                'route' => route('dashboard.settings.template.index'),
                'items' => [
                    // Keys::TEMPLATE_MAIN => [
                    //     'title' => 'Main',
                    //     'route' => route('dashboard.settings.template.main'),
                    //     'route_name' => 'dashboard.settings.template.main',
                    // ],
                    Keys::TEMPLATE_SPECIFICS => [
                        'title' => 'Specifics',
                        'route' => route('dashboard.settings.template.specifics'),
                        'route_name' => 'dashboard.settings.template.specifics',
                    ],
                ]
            ],
            "clients_booking_calendar" => [
                "title" => 'Client`s booking calendar',
                "icon" => '
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar" viewBox="0 0 16 16">
                        <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                    </svg>
                ',
                "includes" => true,
                'route' => route('dashboard.settings.clients_booking_calendar.index'),
                'items' => [
                    Keys::CLIENTS_BOOKING_CALENDAR_MAIN => [
                        'title' => 'Main',
                        'route' => route('dashboard.settings.clients_booking_calendar.main'),
                        'route_name' => 'dashboard.settings.clients_booking_calendar.main',
                    ],
                    Keys::CLIENTS_BOOKING_CALENDAR_LANGUAGES => [
                        'title' => 'Languages',
                        'route' => route('dashboard.settings.clients_booking_calendar.languages'),
                        'route_name' => 'dashboard.settings.clients_booking_calendar.languages',
                    ],
                    Keys::CLIENTS_BOOKING_CALENDAR_CUSTOM_TITLES => [
                        'title' => 'Custom titles',
                        'route' => route('dashboard.settings.clients_booking_calendar.custom_titles'),
                        'route_name' => 'dashboard.settings.clients_booking_calendar.custom_titles',
                    ],
                ]
            ],
            "admins_booking_calendar" => [
                "title" => 'Admin`s booking calendar',
                "icon" => '
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar-event" viewBox="0 0 16 16">
                        <path d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1z"/>
                        <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                    </svg>
                ',
                "includes" => true,
                'route' => route('dashboard.settings.admins_booking_calendar.index'),
                'items' => [
                    Keys::ADMINS_BOOKING_CALENDAR_MAIN => [
                        'title' => 'Main',
                        'route' => route('dashboard.settings.admins_booking_calendar.main'),
                        'route_name' => 'dashboard.settings.admins_booking_calendar.main',
                    ],
                ]
            ],
        ];
    }
    
}