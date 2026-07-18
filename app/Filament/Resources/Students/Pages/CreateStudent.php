<?php

namespace App\Filament\Resources\Students\Pages;

use App\Filament\Resources\Students\StudentResource;
use App\Models\Student;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CreateStudent extends CreateRecord
{
    protected static string $resource = StudentResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return DB::transaction(function () use ($data): Student {

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
                'role' => 'student',
            ]);

            unset(
                $data['name'],
                $data['email'],
                $data['password']
            );

            $data['user_id'] = $user->id;
            $data['device_token'] = null;

            return Student::create($data);
        });
    }
    protected function getRedirectUrl(): string
{
    return static::getResource()::getUrl('index');
}
}