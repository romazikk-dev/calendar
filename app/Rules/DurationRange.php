<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class DurationRange implements Rule
{
    // Range in minutes
    private $range;
    // Range in string representation XX:XX
    private $range_str;
    
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->range = \DurationRange::get();
        $this->range_str = [
            'start' => \Time::composeHourMinuteTimeFromMinutes($this->range['start']),
            'end' => \Time::composeHourMinuteTimeFromMinutes($this->range['end']),
        ];
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $duration_in_minutes = \Time::parseStringHourMinutesToMinutes($value);
        return $duration_in_minutes >= $this->range['start'] && $duration_in_minutes <= $this->range['end'];
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Not in range(' . $this->range_str['start'] . ' - ' . $this->range_str['end'] . ')';
    }
}
