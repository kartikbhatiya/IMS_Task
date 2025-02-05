<?php
// filepath: /h:/Desktop/IMS_Task/app/Rules/ValidMolecule.php
namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Molecule;

class ValidMolecule implements Rule
{
    protected $moleculeId;

    public function __construct($moleculeId)
    {
        $this->moleculeId = $moleculeId;
    }

    public function passes($attribute, $value)
    {
        return Molecule::where([['id', $value],['is_deleted', 0]])->exists();
    }

    public function message()
    {
        return "The molecule with ID {$this->moleculeId} is invalid.";
    }
}