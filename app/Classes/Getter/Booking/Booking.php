<?php

namespace App\Classes\Getter\Booking;

// use App\Classes\Range\Range;
// use App\Classes\Suspension\Enums\Types;
// use App\Classes\Holiday\Enums\Fields;
// use App\Models\Holiday as HolidayModel;

// use App\Classes\Getter\Template\Template;
use App\Models\Booking as ModelBooking;
use App\Classes\Getter\Booking\Enums\GetParams;
use App\Models\User;
use App\Models\Hall;
use App\Models\Worker;
use App\Scopes\UserScope;
use App\Classes\Range\Range;
use App\Classes\Getter\Booking\Classes\MainBookingGetter;
use App\Classes\Getter\Booking\Classes\FreeSlots;
use App\Classes\Getter\Booking\Enums\Params;
use App\Exceptions\BookingGetterBadParametersException;

class Booking extends MainBooking{
    
    public function getValidationRules(array $params = [])
    {
        $rules_for_all = [
            'exclude_ids' => 'nullable|array',
            'exclude_ids.*' => 'nullable|numeric|exists:bookings,id',
        ];
        
        if(!empty($params) && !empty($params['type']) && $params['type'] == 'free')
            return array_unique(array_merge($rules_for_all, [
                'hall' => 'required|integer|exists:halls,id',
                'worker' => 'required|integer|exists:workers,id',
                'with' => 'nullable|array',
                'with.*' => 'nullable|string|max:255',
            ]));
        
        return array_unique(array_merge($rules_for_all, [
            'hall' => 'nullable|integer|exists:halls,id',
            'worker' => 'nullable|integer|exists:workers,id',
            'template' => 'nullable|integer|exists:templates,id',
            'client' => 'nullable|integer|exists:clients,id',
            'with' => 'nullable|array',
            'with.*' => 'nullable|string|max:255',
        ]));
    }
    
    public function all(Range $range, array $params = [])
    {
        return (new MainBookingGetter($range, $params))->get();
    }
    
    public function free(Range $range, array $params = [])
    {
        return (new FreeSlots($range, $params))->get();
    }
    
    public function freeWithBookings(User $owner, Range $range, int $hall_id, int $worker_id, array $params = [])
    {
        // $hall = Hall::find($hall_id);
        // $worker = Worker::find($worker_id);
        // if(empty($hall) || empty($worker))
        //     throw new BookingGetterBadParametersException('One of model(Hall|Worker) is empty in ' . self::class . '::' .  __FUNCTION__);
        // return (new FreeSlots($owner, $range, $hall, $worker, $params))->get();
    }
    
}