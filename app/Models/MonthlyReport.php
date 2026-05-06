<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyReport extends Model
{
    use HasFactory;

    /**
     * PHASE 1: AGGREGATE DATA STRUCTURE & PAYROLL METRICS
     * OBJECTIVE: Define the schema for storing monthly summarized attendance and labor statistics.
     * ATTRIBUTES:
     * - user_id: Reference to the employee whose data is being aggregated.
     * - month/year: Temporal markers for the reporting period.
     * - total_present/absent/late: Quantitative counts of monthly attendance behavior.
     * - total_overtime/working_hours: Calculated time-based metrics for performance and payroll processing.
     * - status: The final approval state of the monthly summary.
     */
    protected $fillable = [
        'user_id',
        'month',
        'year',
        'total_present',
        'total_absent',
        'total_late',
        'total_overtime',
        'total_working_hours',
        'status',
    ];

    /**
     * PHASE 2: RELATIONSHIP MAPPING
     * OBJECTIVE: Associate the aggregated metrics with a specific user profile.
     * TYPE: Inverse One-to-Many (belongsTo).
     * OUTCOME: Enables the generation of personalized payroll reports and historical performance tracking.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}