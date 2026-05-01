<?php

use Carbon\Carbon;

test('it calculates total working hours correctly', function () {
    // 1. Setup raw timestamps
    $clockIn = Carbon::parse('08:00:00');
    $clockOut = Carbon::parse('17:00:00');

    // 2. The mathematical calculation
    $totalHours = $clockIn->diffInHours($clockOut);

    // 3. Verify the result is exactly 9 hours (using toEqual to ignore int/float differences)
    expect($totalHours)->toEqual(9);
});