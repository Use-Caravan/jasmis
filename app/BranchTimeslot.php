<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BranchTimeslot extends CModel
{
    /**
	 * The database table used by the model.	 
	 * @var string
	 */
	protected $table = 'branch_timeslot';	
       
    /**
     * The attributes that primary key.     
     * @var string
     */
    protected $primaryKey = 'branch_timeslot_id';


    /**
     * The attributes that table unique key.
     *
     * @var string
     */
	protected $uniqueKey = 'branch_timeslot_key';

    /**
     * The attributes that enable table unique key.
     *
     * @var string
     */
    protected $keyGenerate = true;


	/**	 
	 * Protect the column to insert
	 * @var array
	 */
    protected $fillable = ['branch_timeslot_key','branch_id','timeslot_type','day_no','start_time','end_time','status'];
    

    public static function getByVendorID($vendorID)
    {
        //$query = self::select()        
    }


     /**
	 * Get Unique key to generate key
	 * @return string
	*/
    public static function uniqueKey()
    {
        $self = new self();
        return $self->uniqueKey;
    }  

    

    /**
     * @return object find by key
     */
    public static function findByKey($branchTimeSlotKey)
    {
        return self::where(self::uniqueKey(), $branchTimeSlotKey)->first();
    }

    public static function getDays($day = null)
    {
        $days = [                        
            1 => __('admincrud.Monday'),
            2 => __('admincrud.Tuesday'),
            3 => __('admincrud.Wednesday'),
            4 => __('admincrud.Thursday'),
            5 => __('admincrud.Friday'),
            6 => __('admincrud.Saturday'),
            7 => __('admincrud.Sunday'),
        ];
        return ($day === null) ? $days : $days[$day];
    }

    public static function getDaysNew()
    {    
        return [
            [
                'day_name' => __('admincrud.Monday'),
                'day_no' => 1,                
                'timeslots' => [
                    'delivery' => [],
                    'pickup' => []                    
                 ],
            ],
            [
                'day_name' => __('admincrud.Tuesday'),
                'day_no' => 2,                
                'timeslots' => [
                    'delivery' => [],
                    'pickup' => []                    
                 ],
            ],
            [
                'day_name' => __('admincrud.Wednesday'),
                'day_no' => 3,                
                'timeslots' => [
                    'delivery' => [],
                    'pickup' => []                    
                 ],
            ],
            
            [
                'day_name' => __('admincrud.Thursday'),
                'day_no' => 4,                
                'timeslots' => [
                    'delivery' => [],
                    'pickup' => []                    
                 ],
            ],
            [
                'day_name' => __('admincrud.Friday'),
                'day_no' => 5,                
                'timeslots' => [
                    'delivery' => [],
                    'pickup' => []                    
                 ],
            ],
            [
                'day_name' => __('admincrud.Saturday'),
                'day_no' => 6,                
                'timeslots' => [
                    'delivery' => [],
                    'pickup' => []                    
                ],
            ],
            [
                'day_name' => __('admincrud.Sunday'),
                'day_no' => 7,                
                'timeslots' => [
                    'delivery' => [],
                    'pickup' => []                    
                ],
            ]            
        ];
    }
}
